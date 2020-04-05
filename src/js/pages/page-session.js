import Vue from 'vue'
import Vuex from 'vuex'
import { Swiper, SwiperSlide, directive } from 'vue-awesome-swiper'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import OT from '@opentok/client'

const session = new Vue({
  ...baseConfig(store),
  data() {
    return {
      //Chat
      isActiveChat: false,
      messages: [],
      chatEditor: '',

      //Session
      session: null,  
      users: [],
      isShowedUsersList: true,      
      credentials: {
        apiKey: '',
        sessionId: '',
        token: ''
      },          
      publisher: null,
      publisher_screen: null,
      subscribers: [],
      mediaControls: {
        video: true,
        audio: true
      },
      
      //Swiper
      swiperOptions: {
        speed: 500,
        preventClicks: false,
        preventClicksPropagation: false,
        slidesPerView: 6,
        allowTouchMove: false,

        navigation:{
          prevEl: '.c-users__prev',
          nextEl: '.c-users__next'
        },

        breakpoints: {
          320: {
            slidesPerView: 3,
            spaceBetween: 20
          },
          480: {
            slidesPerView: 4,
            spaceBetween: 30
          },
          640: {
            slidesPerView: 5,
            spaceBetween: 40
          },
          1024: {
            slidesPerView: 6,
            spaceBetween: 40
          }
        }        
      }      
    }
  },
  components: {
    Swiper,
    SwiperSlide
  },
  computed: {
    ...baseState(),
    ...Vuex.mapState(['activedSession']),
    swiper() {
      return this.$refs.slider_users.$swiper
    }
  },
  directives: {
    swiper: directive
  },
  created(){
    if(!this.activedSession){
      window.location = this.SITE_URL;
    }
  },
  mounted(){
    this.users.push({
      stream: {
        id: 'publisher',
        name: this.activedSession.publisher
      },
    })

    this.openRoom()
  },
  methods: {
    ...baseActions(),
    endSession: function(){
      window.localStorage.removeItem('mab_session')
      window.location = `${this.SITE_URL}/mab-click`;
    },
    openRoom: function(){
      const SERVER_BASE_URL = 'https://pkclass.cleivervalera.com';

      fetch(SERVER_BASE_URL + '/session')
        .then(res => {
          return res.json()
        })
        .then(res => {
          this.apiKey = res.apiKey
          this.sessionId = res.sessionId
          this.token = res.token

          this.initSession();
        })
        .catch(this.handleError);
    },
    initSession: function(){
      this.session = OT.initSession(this.apiKey, this.sessionId)
      this.session.on('streamCreated', (event)=> {
        this.users.push({
          stream: event.stream
        })

        window.setTimeout(()=>{
          this.session.subscribe(event.stream, `user-${event.stream.id}`, {
            insertMode: 'append',
            width: '100%',
            height: '100%'
          }, this.handleError)

          this.session.subscribe(event.stream, 'monitor', {
            insertMode: 'replace',
            width: '100%',
            height: '100%'
          }, this.handleError);
        }, 100)
      });
      this.session.on('streamDestroyed', (event) => {
        this.users = this.users.filter(el => el.stream.id != event.stream.id)
      })
      this.session.on('signal:msg', (event) => {
        let message = JSON.parse(event.data)

        this.messages.push({
          author: message.author,
          content: message.content,
          time: message.time
        })

        window.setTimeout(()=>{
          this.$refs[`message-${ this.messages.length - 1 }`][0].scrollIntoView();
        },100)
      });   

      this.session.connect(this.token, (error)=> {
        if (error) {
          this.handleError(error);
        } else {
          this.shareVideo();
        }
      });
    },
    shareScreen: function(){
      OT.checkScreenSharingCapability((response) => {
        if(!response.supported || response.extensionRegistered === false) {
          alert('Este navegador no soporte compartir pantalla')
        } else if (response.extensionInstalled === false) {
          alert('Usted necesita una extension extra')
        } else {
          this.session.unpublish(this.publisher)

          this.users.push({
            stream: {
              id: 'publisher-screen',
              name: `${this.activedSession.publisher} - Pantalla`
            }
          })

          window.setTimeout(()=>{
            this.publisher_screen = OT.initPublisher('user-publisher-screen',{
              videoSource:'screen',
              name: `${this.activedSession.publisher} - Pantalla`
            },this.handleError)
  
            this.publisher_screen.on('mediaStopped', (event) => {
              this.users = this.users.filter(el => el.stream.id != 'publisher-screen')
              this.session.unpublish(this.publisher_screen)

              window.setTimeout(this.shareVideo, 100)
            });
            
            this.session.publish(this.publisher_screen, this.handleError);    
          }, 100)
        }
      });
    },
    shareVideo: function(){
      this.publisher = OT.initPublisher('user-publisher', {
        insertMode: 'append',
        width: '100%',
        height: '100%',
        name: this.activedSession.publisher
      }, this.handleError);

      this.session.publish(this.publisher, this.handleError); 
    },
    updateMediaStatus: function(media){
      if(media == 'video'){      
        this.publisher.publishVideo(this.mediaControls.video = !this.mediaControls.video);
      }else if(media == 'audio'){
        this.publisher.publishAudio(this.mediaControls.audio = !this.mediaControls.audio);
      }
    },
    setMonitor: function(user_stream_id){
      if(user_stream_id != 'publisher' && user_stream_id != 'publisher-screen'){
        this.session.subscribe(this.users.filter(el => el.stream.id == user_stream_id)[0].stream, 'monitor', {
          insertMode: 'replace',
          width: '100%',
          height: '100%'
        }, this.handleError);
      }
    },
    sendMessage: function(){
      this.session.signal({
        type: 'msg',
        data: JSON.stringify({
          author: this.activedSession.publisher,
          content: this.chatEditor,
          time: `${(new Date()).getHours()}:${(new Date()).getMinutes()}`
        })
      }, (error) => {
        if (error) {
          console.log('Error sending signal:', error.name, error.message);
        } else {
          this.chatEditor = '';
        }
      });      
    },
    handleError: function(error){
      if (error) {
        alert(error.message);
      }
    }
  }
})

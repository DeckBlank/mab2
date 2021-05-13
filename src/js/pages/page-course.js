import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {addCourseToShopCart} from '../libs/shop-cart'
import {store} from '../store'

const course = new Vue({
  ...baseConfig(store),
  data() {
    return {
      metas: new URLSearchParams(window.location.search),
      isActiveUnity: false,
      isAvaibleCourse: true,
      isActiveSignUp: false,
      accessGranted: false,

      area: '',
      unities: [],

      view: 1,
      foro: 1,
      commentbox: 0,
      questions: [
        { enable : true },
        { enable : false },
        { enable : false },
        { enable : false },
        { enable : false },
      ]
    }
  },
  computed: {
    ...baseState()
  },
  mounted: function(){
    // this.area = this.$refs.course.getAttribute('data-area');

    this.global();
    this.hideLoading();
    // this.isUserAuthOnCourse( this.$refs.course.getAttribute('data-id') )
    // this.saveCourseOnMetas()
    // this.verifyIsAvaibleCourse()
    this.getUnities( mab.course_id );
  },
  methods: {
    ...baseActions(),
    getUnities: function(course_id){
      fetch(`${ this.API }/course/${ course_id }/unities?user=${ this.logedUser.user_email }`)
        .then(res => { 
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          } else {
            throw res;
          }
        })
        .then(unities => {
          this.unities = unities;
          this.hideLoading();
        })
        .catch(err => {
          throw err;
        })      
    },
    isUserAuthOnCourse: function(course_id){
      if(this.metas.get('sector') != 'privado' && this.metas.get('sector') != 'publico'){
        window.location = `${this.SITE_URL}/emotional`
      }else{
        if (['creative', 'emotional'].includes(this.area)) {
          this.accessGranted = true;
        } else if(this.logedUser && this.logedUser.user_rol != 'foreign') {
          if (this.metas.get('sector') == 'privado') {
            fetch(`${this.API}/course/${course_id}/registration/checkout?user=${this.logedUser.user_email}`,{
              method: 'GET'
            })
            .then(res => {
              if (res.status >= 200 && res.status < 300) {
                return res.json();
              }else{
                throw res;
              }
            })
            .then(registration => {
              this.accessGranted = true;
            })
            .catch(err => {
              throw err;          
            })
          } else if (this.metas.get('sector') == 'publico') {
            this.accessGranted = true;
          }
        }
      }
    },
    saveCourseOnMetas: function(){
      window.localStorage.setItem('mab_metas', JSON.stringify({
        course: this.$refs.course.getAttribute('data-title')
      }))
    },
    addCourse: function(course_id, course_title, course_link){
      addCourseToShopCart(course_id, course_title, course_link, this.SITE_URL, this.metas)
    },
    verifyIsAvaibleCourse: function(){
      let shop_cart = window.localStorage.getItem('mab_shop_cart');
        shop_cart = JSON.parse(shop_cart);

      if(shop_cart){
        let courses = shop_cart.filter(course => course.id == this.$refs.course.getAttribute('data-id'))

        this.isAvaibleCourse = (courses.length > 0) ? false : true;
      }
    },
    playVideo: function(video, unity, topic){
      event.preventDefault();

      if (this.metas.get('sector') == 'privado') {
        /*
          TODO: if(this.accessGranted || (unity == 1 && topic == 1)){
        */
        if(this.accessGranted){
          window.location = video;
        } else {
          if (this.logedUser && this.logedUser.user_rol != 'foreign') {    
            if (unity == 1 && topic == 1) {
              window.location = video;
            } else {
              addCourseToShopCart(
                this.$refs.course.getAttribute('data-id'),
                this.$refs.course.getAttribute('data-title'),
                this.$refs.course.getAttribute('data-link'),
                this.SITE_URL,
                this.metas
              );
            }    
          } else {
            this.isActiveSignUp = true;
          }
        }    
      } else if(this.metas.get('sector') == 'publico') {
        if(this.accessGranted){
          window.location = video;
        }else{
          this.isActiveSignUp = true;
        }        
      }
    },
    downloadMaterial: function(unity, topic, topic_id, url, media){
      event.preventDefault();

      if (this.metas.get('sector') == 'privado') {
        /*
          TODO: if(this.accessGranted || (unity == 1 && topic == 1)){
        */
        if(this.accessGranted){
          this.saveMaterialLog(topic_id, url, media)
        }else{
          if (this.logedUser && this.logedUser.user_rol != 'foreign') {
            if (unity == 1 && topic == 1) {
              window.location = video;
            } else {
              addCourseToShopCart(
                this.$refs.course.getAttribute('data-id'),
                this.$refs.course.getAttribute('data-title'),
                this.$refs.course.getAttribute('data-link'),
                this.SITE_URL,
                this.metas
              );
            }         
          } else {
            this.isActiveSignUp = true;
          }
        }    
      } else if(this.metas.get('sector') == 'publico') {
        if(this.accessGranted){
          this.saveMaterialLog(topic_id, url, media)
        }else{
          this.isActiveSignUp = true;
        }        
      }
    },
    saveMaterialLog: function(topic_id, url, media){
      let course_id = this.$refs.course.getAttribute('data-id'),
        user = (this.logedUser) ? this.logedUser.user_email : 'anonimo';
      
      fetch(`${this.API}/topic/${topic_id}/material/log?user=${user}&course_id=${course_id}&media=${media}`,{
          method: 'PUT'
        })
        .then(res => { 
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(response => {
          window.open(url, '_blank');
        })
        .catch(err => {
          window.open(url, '_blank');
          throw err;
        })
    },

    resetAccordion: function(unity) {
      this.unities = this.unities.map(q => {
        return (q != unity) ? { ...q, enable : false } : q;
      })
    },
  }
})

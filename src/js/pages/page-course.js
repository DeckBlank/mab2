import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
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

      unities: []
    }
  },
  computed: {
    ...baseState()
  },
  beforeMount(){
    this.initSectors();
  },  
  mounted: function(){
    this.global();
    this.isUserAuthOnCourse( this.$refs.course.getAttribute('data-id') )
    this.saveCourseOnMetas()
    this.verifyIsAvaibleCourse()
    this.getUnities( this.$refs.course.getAttribute('data-id') );
  },
  methods: {
    ...baseActions(),
    getUnities: function(course_id){
      fetch(`${this.API}/course/${course_id}/unities?user=${this.logedUser.user_email}`)
        .then(res => { 
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
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
        if (this.logedUser) {                      
          item.setAttribute('href', `${this.SITE_URL}/solicitar-cursos`)
        } else {
          item.setAttribute('href', `${this.SITE_URL}#registro`)                      
        }
      }else{
        if(this.metas.get('sector') == 'privado'){
          fetch(`${this.API}/course/${course_id}/registration/checkout?user=${this.logedUser.user_email}`,{
              method: 'GET'
            })
            .then(res => {
              if (res.status >= 200 && res.status < 300) {
                return res.json()
              }else{
                throw res
              }
            })
            .then(registration => {
              this.accessGranted = true
            })
            .catch(err => {
              throw err;          
            })        
        }
      }
    },
    saveCourseOnMetas: function(){
      window.localStorage.setItem('mab_metas', JSON.stringify({
        course: this.$refs.course.getAttribute('data-title')
      }))
    },
    addCourseToShopCart: function(course_id, course_title, course_link){
      let shop_cart = window.localStorage.getItem('mab_shop_cart')

      if(!shop_cart){
        window.localStorage.setItem('mab_shop_cart', JSON.stringify([{
          id: course_id,
          title: course_title,
          link: course_link,
        }]))
      }else{
        shop_cart = JSON.parse(shop_cart);
        shop_cart.push({
          id: course_id,
          title: course_title,
          link: course_link,
        })

        window.localStorage.setItem('mab_shop_cart', JSON.stringify(shop_cart))
      }

      window.setTimeout(()=>{
        window.location = `${this.SITE_URL}/carrito`
      },100)
    },
    verifyIsAvaibleCourse: function(){
      let shop_cart = window.localStorage.getItem('mab_shop_cart');
        shop_cart = JSON.parse(shop_cart);

      if(shop_cart){
        this.isAvaibleCourse = !shop_cart.filter(course => course == this.$refs.course.getAttribute('data-id'))
      }
    },
    playVideo: function(video, unity){
      event.preventDefault();

      if (this.metas.get('sector') == 'privado') {
        if(this.accessGranted || unity == 1){
          window.location = video;
        }else{
          if (this.logedUser) {            
            window.location = `${this.SITE_URL}/solicitar-cursos`;
          } else {
            this.isActiveSignUp = true;
          }
        }    
      } else if(this.metas.get('sector') == 'publico') {
        if(this.logedUser || unity == 1){
          window.location = video;
        }else{
          this.isActiveSignUp = true;
        }        
      }
    },
    downloadMaterial: function(unity, topic_id, url, media){
      event.preventDefault();

      if (this.metas.get('sector') == 'privado') {
        if(this.accessGranted || unity == 1){
          this.saveMaterialLog(topic_id, url, media)
        }else{
          if (this.logedUser) {            
            window.location = `${this.SITE_URL}/solicitar-cursos`;
          } else {
            this.isActiveSignUp = true;
          }
        }    
      } else if(this.metas.get('sector') == 'publico') {
        if(this.logedUser || unity == 1){
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
    }
  }
})

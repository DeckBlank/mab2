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
        window.location = `${this.SITE_URL}/emotional`
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
        if(this.accessGranted || (unity == 1 && topic == 1)){
          window.location = video;
        }else{
          if (this.logedUser) {            
            addCourseToShopCart(
              this.$refs.course.getAttribute('data-id'),
              this.$refs.course.getAttribute('data-title'),
              this.$refs.course.getAttribute('data-link'),
              this.SITE_URL,
              this.metas
            );
          } else {
            this.isActiveSignUp = true;
          }
        }    
      } else if(this.metas.get('sector') == 'publico') {
        if(this.logedUser || (unity == 1 && topic == 1)){
          window.location = video;
        }else{
          this.isActiveSignUp = true;
        }        
      }
    },
    downloadMaterial: function(unity, topic, topic_id, url, media){
      event.preventDefault();

      if (this.metas.get('sector') == 'privado') {
        if(this.accessGranted || (unity == 1 && topic == 1)){
          this.saveMaterialLog(topic_id, url, media)
        }else{
          if (this.logedUser) {            
            addCourseToShopCart(
              this.$refs.course.getAttribute('data-id'),
              this.$refs.course.getAttribute('data-title'),
              this.$refs.course.getAttribute('data-link'),
              this.SITE_URL,
              this.metas
            );
          } else {
            this.isActiveSignUp = true;
          }
        }    
      } else if(this.metas.get('sector') == 'publico') {
        if(this.logedUser || (unity == 1 && topic == 1)){
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

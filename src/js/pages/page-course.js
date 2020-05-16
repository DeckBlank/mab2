import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const course = new Vue({
  ...baseConfig(store),
  data() {
    return {
      metas: new URLSearchParams(window.location.search),
      isActiveUnity: false,
      isAvaibleCourse: true
    }
  },
  computed: {
    ...baseState()
  },
  beforeMount(){
    this.initSectors();
  },  
  mounted: function(){
    this.isUserAuthOnCourse( this.$refs.course.getAttribute('data-id') )
    this.saveCourseOnMetas()
    this.verifyIsAvaibleCourse()
  },
  methods: {
    ...baseActions(),
    isUserAuthOnCourse: function(course_id){
      if(this.metas.get('sector') != 'privado' && this.metas.get('sector') != 'publico'){
        window.location = `${this.SITE_URL}/solicitar-cursos`;
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
              this.hideLoading();
            })
            .catch(err => {
              let topics = document.querySelectorAll('.c-topic')
  
              topics.forEach((topic, index) => {
                if(index != 0){
                  topic.querySelectorAll('.c-topic__item').forEach((item)=> {
                    item.setAttribute('href', `${this.SITE_URL}/solicitar-cursos`)
                  })                
                }
              })
  
              this.hideLoading();
              throw err;          
            })        
        }else {
          this.hideLoading();
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
    }
  }
})

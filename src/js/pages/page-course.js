import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const course = new Vue({
  ...baseConfig(store),
  data() {
    return {
      metas: new URLSearchParams(window.location.search),
      isActiveUnity: false
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
    this.hideLoading();
  },
  methods: {
    ...baseActions(),
    isUserAuthOnCourse: function(course_id){
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

            throw err;          
          })        
      }
    },
    saveCourseOnMetas: function(){
      window.localStorage.setItem('mab_metas', JSON.stringify({
        course: this.$refs.course.getAttribute('data-title')
      }))
    }
  }
})

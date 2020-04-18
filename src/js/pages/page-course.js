import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const course = new Vue({
  ...baseConfig(store),
  data() {
    return {
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
      let topics = document.querySelectorAll('.c-topic__video')

      if(!this.logedUser){
        topics.forEach(topic => {
          topic.setAttribute('href', `${this.SITE_URL}/solicitar-cursos`)
        })
      }else{
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
            topics.forEach(topic => {
              topic.setAttribute('href', `${this.SITE_URL}/solicitar-cursos`)
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

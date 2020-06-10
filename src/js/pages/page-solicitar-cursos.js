import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const solicitar_cursos = new Vue({
  ...baseConfig(store),
  data() {
    return {
    }
  },
  computed: {
    ...baseState()
  }, 
  beforeMount(){
    this.initSectors();
  },
  mounted(){
    this.global();
    this.fillForm();
    this.hideLoading();
  },
  methods: {
    ...baseActions(),
    fillForm: function(){
      if(this.logedUser){
        let fullname = document.querySelector("[name='fullname'"),
          ocupation = document.querySelector("[name='ocupation'"),
          email = document.querySelector("[name='email'"),
          mobile = document.querySelector("[name='phone'"),
          course = document.querySelector("[name='course'")
  
        fullname.value = this.logedUser.user_auth
        email.value = this.logedUser.user_email
        mobile.value = this.logedUser.user_mobile

        switch(this.logedUser.user_rol){
          case 'teacher':
              ocupation.value = 'Profesor'
            break;
          case 'student':
              ocupation.value = 'Alumno'
            break;
          case 'tutor':
              ocupation.value = 'Padre'
            break;
        }

        let mab_metas = window.localStorage.getItem('mab_metas')

        if(mab_metas){
          course.value = JSON.parse(mab_metas).course
        }        
      }
    }
  }
})

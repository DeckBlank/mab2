import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const solicitar_cursos = new Vue({
  ...baseConfig(store),
  data() {
    return {
      isLoading: false,
      form: {
        fullname: {
          value: ''
        },
        ocupation: {
          value: ''
        },
        email: {
          value: ''
        },
        mobile: {
          value: ''
        },
        course: {
          value: ''
        },
      },
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
        this.form.fullname.value = this.logedUser.user_auth
        this.form.email.value = this.logedUser.user_email
        this.form.mobile.value = this.logedUser.user_mobile

        switch(this.logedUser.user_rol){
          case 'teacher':
            this.form.ocupation.value = 'Profesor'
            break;
          case 'student':
            this.form.ocupation.value = 'Alumno'
            break;
          case 'tutor':
            this.form.ocupation.value = 'Padre'
            break;
        }

        let mab_metas = window.localStorage.getItem('mab_metas')

        if(mab_metas){
          this.form.course.value = JSON.parse(mab_metas).course
        }        
      }
    },
    sendRequest: function(){
      let form_data = new FormData()

      Object.keys(this.form).forEach(key => {
        form_data.append(key, this.form[key].value)
      })

      this.isLoading = true;

      fetch(`${this.API}/course/request`,{
          method: 'POST',
          body: form_data
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            this.isLoading = false
          }else{
            throw res
          }
        })
        .catch(err => {
          this.isLoading = false      
          throw err;          
        })      
    }
  }
})

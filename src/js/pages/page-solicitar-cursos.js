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
  created(){
    document.querySelector("[name='course'").innerHTML += `
      <option value="Otro" data-id="0">Otro</option>
    `
  },  
  beforeMount(){
    this.initSectors();
  },
  mounted(){
    this.fillForm();
    this.hideLoading();
  },
  methods: {
    ...baseActions(),
    fillForm: function(){
      if(this.logedUser){
        let fullname = document.querySelector("[name='fullname'"),
          email = document.querySelector("[name='email'"),
          phone = document.querySelector("[name='phone'"),
          course = document.querySelector("[name='course'")
  
        fullname.value = this.logedUser.user_auth
        email.value = this.logedUser.user_email
        phone.value = this.logedUser.user_phone

        let mab_metas = window.localStorage.getItem('mab_metas')

        if(mab_metas){
          course.value = JSON.parse(mab_metas).course
        }        
      }
    }
  }
})

import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {saveUserLoginSession} from '../libs/login'
import {store} from '../store'

const login = new Vue({
  ...baseConfig(store),
  data() {
    return {
      user: '',
      password: '',
      isLoading: false,
      errorMessage: '',
      isShowedErrorMessage: false
    }
  },
  computed: {
    ...baseState()
  },
  created(){
    if(this.logedUser){
      window.location = `${this.SITE_URL}/emotional`;
    }
  },
  mounted(){
    this.global();
    this.hideLoading();
  },  
  methods: {
    ...baseActions(),
    login: function(){
      if(this.user != '' && this.password != ''){
        this.isLoading = true
  
        fetch(`${this.API}/user/auth?user=${this.user}&password=${this.password}`,{
            method: 'GET'
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(user => {
            saveUserLoginSession(user)
            this.isLoading = false
            this.isShowedErrorMessage = false

            window.location = `${this.SITE_URL}/emotional`
          })
          .catch(err => {
            this.isLoading = false
            this.errorMessage = 'El usuario o contraseña son incorrectos'
            this.isShowedErrorMessage = true        
            throw err;          
          })
      }else{
        this.errorMessage = 'Debe llenar tus accesos'
        this.isShowedErrorMessage = true
      }
    }
  }
})

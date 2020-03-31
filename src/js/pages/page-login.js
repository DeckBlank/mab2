import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
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
      window.location = this.SITE_URL;
    }
  },
  methods: {
    ...baseActions(),
    saveSession: function(user){
      let mabSession = window.localStorage.getItem('mab_session')

      if(!mabSession){
        window.localStorage.setItem('mab_session',JSON.stringify({
          user_auth: user.data.user_login,
          session_token: user.data.user_pass
        }))
      }
    },
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
            this.saveSession(user)
            this.isLoading = false
            this.isShowedErrorMessage = false

            window.location = `${this.SITE_URL}`
          })
          .catch(err => {
            this.isLoading = false
            this.errorMessage = 'El usuario no existe'
            this.isShowedErrorMessage = true        
            throw err;          
          })
      }else{
        this.errorMessage = 'Debe llenar el ID y contraseña'
        this.isShowedErrorMessage = true
      }
    }
  }
})

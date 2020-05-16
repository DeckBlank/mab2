import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const recovery_password = new Vue({
  ...baseConfig(store),
  data() {
    return {
      /*Session Recovery */
      user: '',
      isLoading: false,
      errorMessage: 'El email es invalido',
      isShowedErrorMessage: false,
      isShowedSuccessMessage: false,

      /*Set new Password */
      stage: (new URLSearchParams(window.location.search)).get('stage'),
      sessionID: (new URLSearchParams(window.location.search)).get('session_id'),
      username: '',
      newPassword: '',
      newPasswordConfirm: ''
    }
  },
  computed: {
    ...baseState()
  },
  created(){
    if(this.stage && this.stage == 2 && !this.sessionID){
      window.location = `${this.SITE_URL}/login`;
    }
  },
  beforeMount(){
    this.initSectors();
  },
  mounted(){
    if(this.stage && this.stage == 2){
      this.getRecoverySession();
    }else{
      this.hideLoading();
    }
  },
  methods: {
    ...baseActions(),
    sendInstructions: function(){
      if(this.user != ''){
        this.isShowedErrorMessage = false
        this.isLoading = true

        let form_data = new FormData(); form_data.append('user', `${this.user}`)
  
        fetch(`${this.API}/user/recovery_session`,{
            method: 'POST',
            body: form_data
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(user => {
            this.isLoading = false
            this.isShowedSuccessMessage = true
          })
          .catch(err => {
            this.isLoading = false
            this.errorMessage = 'El usuario no existe'
            this.isShowedErrorMessage = true        
            throw err;          
          })
      }else{
        this.errorMessage = 'Debes llenar tu correo electrónico'
        this.isShowedErrorMessage = true
      }
    },
    updatePassword: function(){
      if (this.newPassword != '' && this.newPasswordConfirm != '') {
        if (this.newPassword == this.newPasswordConfirm ) {
          this.errorMessage = ''
          this.isShowedErrorMessage = false          
          this.isLoading = true
    
          fetch(`${this.API}/user/password?session_id=${this.sessionID}&new_pass=${this.newPassword}`,{
              method: 'PUT'
            })
            .then(res => {
              if (res.status >= 200 && res.status < 300) {
                return res.json()
              }else{
                throw res
              }
            })
            .then(user => {
              this.isLoading = false
              this.isShowedSuccessMessage = true

              window.setTimeout(()=>{
                window.location = `${this.SITE_URL}/login`
              },1000)
            })
            .catch(err => {
              this.isLoading = false
              this.errorMessage = 'El usuario no existe'
              this.isShowedErrorMessage = true        
              throw err;          
            })
                      
        } else {        
          this.errorMessage = 'No coinciden las contraseñas'
          this.isShowedErrorMessage = true
        }
      } else {
        this.errorMessage = 'Debes ingresa una nueva contraseña'
        this.isShowedErrorMessage = true
      }
    },
    getRecoverySession: function(){
      fetch(`${this.API}/user/recovery_session?session_id=${this.sessionID}`,{
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
          this.username = user.user_login; this.hideLoading();
        })
        .catch(err => { 
          window.location = `${this.SITE_URL}/recuperar-contrasena`;
          throw err;          
        })      
    }
  }
})

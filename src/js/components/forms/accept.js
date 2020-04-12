import Vue from 'vue'
import Vuex from 'vuex'
import {saveUserLoginSession} from '../../libs/login'

Vue.component('form-accept',{
  template: /*html*/
  `
    <div class="c-modal position-fixed width-100 height-100" :class="{ opened : switcher }">
      <div class="grid-container height-100">
        <div class="grid-x align-center-middle height-100">
          <div class="cell medium-8 large-6">
            <div class="c-modal__content flex-container align-center-middle">
              <div class="c-accept modal-content padding-2 br--medium">
                <p>Gracias por registrarte!
                </p>
                <div class="modal_btn_container">
                  <a @click="login">¡Bienvenido!</a>
                </div>
              </div>    
            </div>
          </div>      
        </div>      
      </div>      
    </div>  
  `,
  data() {
    return {
      isLoading: false
    }
  },
  computed: {
    ...Vuex.mapState(['API', 'SITE_URL'])
  },
  props: {
    switcher: Boolean,
    user: String,
    password: String
  },
  methods: {
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

            window.location = `${this.SITE_URL}/solicitar-cursos`
          })
          .catch(err => {
            this.isLoading = false
            throw err;          
          })
      }
    }    
  },
})

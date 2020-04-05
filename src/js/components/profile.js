import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('profile',{
  template: /*html*/`
    <div class="c-user position-relative" :class="{ active : isActiveMenuOptions }">
      <div class="c-user__profile rounded flex-container align-center-middle" @click="isActiveMenuOptions = !isActiveMenuOptions">        
      </div>
      <ul class="c-user__menu ul-reset position-absolute br--small bg-white">
        <li class="c-user__text black">Hola <span class="w-bold">Juanito!</span></li>
        <li class="c-user__option c-user__option--logout">
          <a href="" class="display-block" @click="logout">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  `,
  data() {
    return {
      isActiveMenuOptions: false
    }
  },
  computed: {
    ...Vuex.mapState(['SITE_URL'])
  },  
  methods: {
    logout: function(){
      window.localStorage.removeItem('mab_session')
      window.location = `${this.SITE_URL}/emotional`
    }
  }
})

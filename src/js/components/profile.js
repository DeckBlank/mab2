import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('profile',{
  template: /*html*/`
    <div class="c-user position-relative" :class="{ active : isActiveMenuOptions }">
      <div class="c-user__profile rounded flex-container align-center-middle" @click="isActiveMenuOptions = !isActiveMenuOptions">        
      </div>
      <ul class="c-user__menu f2 fs-18 ul-reset position-absolute br--small bg-white">
        <li class="c-user__text black w-medium">Hola <span class="f1 w-bold">{{logedUser.user_auth}}</span></li>
        <li class="c-user__option c-user__option--logout w-medium">
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
    ...Vuex.mapState(['SITE_URL', 'logedUser'])
  },  
  methods: {
    logout: function(){
      window.localStorage.removeItem('mab_loged_user')
      window.location = `${this.SITE_URL}/emotional`
    }
  }
})

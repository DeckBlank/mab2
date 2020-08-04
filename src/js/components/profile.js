import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('profile',{
  template: /*html*/`
    <div class="c-user position-relative" :class="{ active : isActiveMenuOptions }">
      <div class="flex-container align-middle">
        <p class="c-user__name margin-bottom-0 margin-right-1 f2 fs-18 w-medium white">{{logedUser.user_firstname}}</p>
        <div class="c-user__profile rounded flex-container align-center-middle" @click="isActiveMenuOptions = !isActiveMenuOptions">        
        </div>
      </div>
      <ul class="c-user__menu f2 fs-18 ul-reset position-absolute br--small bg-white">
        <li class="c-user__text black w-medium">Hola <span class="f1 w-bold">{{logedUser.user_firstname}}</span></li>
        <li class="c-user__option w-bold">
          <a :href="SITE_URL + '/test'" class="display-block">Mi test de estilos de aprendizaje</a>
        </li>
        <li class="c-user__option w-bold">
          <a :href="SITE_URL + '/progreso'" class="display-block">Mi progreso</a>
        </li>
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
    ...Vuex.mapState(['API', 'SITE_URL', 'logedUser'])
  },
  methods: {
    logout: function(){
      event.preventDefault()

      fetch(`${this.API}/user/logout/`)
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            window.localStorage.removeItem('mab_loged_user')
            window.location = `${this.SITE_URL}/emotional`

          }else{
            throw res
          }
        })
        .catch(err => {
          throw err;
        })       
    }
  }
})

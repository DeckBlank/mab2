import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('profile',{
  template: /*html*/`
    <div class="c-user position-relative" :class="{ active : isActiveMenuOptions }">
      <div class="c-user__profile rounded overflow-hidden flex-container align-center-middle" @click="isActiveMenuOptions = !isActiveMenuOptions">
        <img class="width-100 height-100 of--cover" src="https://scontent.ftru2-3.fna.fbcdn.net/v/t1.6435-9/90084625_100247424944921_6658651316983693312_n.png?_nc_cat=1&ccb=1-3&_nc_sid=09cbfe&_nc_eui2=AeH8SMN_QIP3IJ_ms2flzSGC7uTsun36oivu5Oy6ffqiK27aX0MPf5vz-Rwx_QoAkSzKJuE2Godzf3420d3fQiN5&_nc_ohc=ZbdM-IG-ZYgAX8bf5nW&_nc_ht=scontent.ftru2-3.fna&oh=d8cb89a3777077de2fa774e59e112a26&oe=609F42F2"></img>
      </div>
      <div class="c-menu-dropdown right br--medium position-absolute f2">
        <ul class="bg-white ul-reset br--medium overflow-hidden">
          <li class="c-menu-dropdown__item w-bold">
            <a :href="SITE_URL + '/perfil'" class="w-xbold text-center padding-horizontal-2">Mi perfil</a>
          </li>
          <li class="c-menu-dropdown__item w-bold">
            <a :href="SITE_URL + '/me-organizo'" class="w-xbold text-center padding-horizontal-2">Me organizo</a>
          </li>
          <li class="c-menu-dropdown__item w-bold">
            <a :href="SITE_URL + '/mis-cursos'" class="w-xbold text-center padding-horizontal-2">Mis cursos</a>
          </li>
          <li class="c-menu-dropdown__item inverse w-bold">
            <a href="" class="w-xbold text-center padding-horizontal-2" @click="logout($event)">Cerrar sesión</a>
          </li>
        </ul>
      </div>
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
    logout: function(e) {
      e.preventDefault()

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

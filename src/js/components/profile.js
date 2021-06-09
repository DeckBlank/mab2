import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('profile',{
  template: /*html*/`
    <div class="c-user position-relative" :class="{ active : switcher }">
      <input id="profile-avatar" class="c-user-cbox hide" type="checkbox" v-model="switcher">
      <label
        for="profile-avatar"
        @mouseover="blockMenu(true)"
        @mouseleave="blockMenu(false)"
        class="c-user__profile rounded overflow-hidden flex-container align-center-middle margin-right-0">
        <img class="width-100 height-100 of--cover" :src="avatar"></img>
      </label>
      <div @mouseover="blockMenu(true)" @mouseleave="blockMenu(false)" class="c-menu-dropdown right br--medium position-absolute f2">
        <ul class="bg-white ul-reset br--medium overflow-hidden">
          <li class="c-menu-dropdown__item w-bold">
            <a :href="SITE_URL + '/user/' + logedUser.user_nicename" class="w-xbold text-center padding-horizontal-2">Mi perfil</a>
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
      isActiveMenuOptions: false,
      switcher: false,
    }
  },
  computed: {
    ...Vuex.mapState(['API', 'SITE_URL', 'logedUser', 'THEME_URL']),
    avatar: function() {
      return (this.logedUser.user_avatar) ? this.logedUser.user_avatar : `${ this.THEME_URL }/static/images/user.png`;
    },
  },
  watch: {
    'switcher': function() {
      document.onclick = () => {
        this.unblockMenu();
      }
    },
  },
  methods: {
    logout: function(e) {
      e.preventDefault()

      fetch(`${this.API}/user/logout/`)
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            window.localStorage.removeItem('mab_loged_user')
            window.location = `${this.SITE_URL}`

          }else{
            throw res
          }
        })
        .catch(err => {
          throw err;
        })       
    },

    blockMenu: function(state) {
      if (state) {
        this.isActiveMenuOptions = true;
      } else {
        this.isActiveMenuOptions = false;
      }
    },
    unblockMenu: function() {
      if(!this.isActiveMenuOptions) {
        this.switcher = false;
      }
    },
  }
})

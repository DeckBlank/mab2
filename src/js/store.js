import Vuex from 'vuex'
import Vue from 'vue'
import { Swiper, SwiperSlide, directive } from 'vue-awesome-swiper'

Vue.use(Vuex)

export const store = new Vuex.Store({ 
  state: {
    //Site
    API: `${document.getElementById('app').getAttribute('data-site')}/wp-json/custom/v1`,
    SITE_URL: `${document.getElementById('app').getAttribute('data-site')}`,    

    //User
    logedUser: (window.localStorage.getItem('mab_loged_user')) ? JSON.parse(window.localStorage.getItem('mab_loged_user')) : false,

    //Session
    activedSession: (window.localStorage.getItem('mab_session')) ? JSON.parse(window.localStorage.getItem('mab_session')) : false,

    //Menu
    isActiveMenu: false,
    isActivePubSectorMenu: false,
    isActivePrivSectorMenu: false,
    isActivePubGradoMenu: false,
    isHeaderWithShadow: false,

    //Browser
    isActiveBrowserToggle: false
  },
  mutations: {
    setStatusMenu(state){
      state.isActiveMenu = !state.isActiveMenu
    },

    setStatusPubSectorMenu(state){
      state.isActivePubSectorMenu = !state.isActivePubSectorMenu
    },

    setStatusPrivSectorMenu(state){
      state.isActivePrivSectorMenu = !state.isActivePrivSectorMenu
    },

    setStatusPubGradoMenu(state){
      state.isActivePubGradoMenu = !state.isActivePubGradoMenu
    },

    setStatusHeaderShadow(state, status){
      state.isHeaderWithShadow = status
    },

    setStatusBrowserToggle(state){
      state.isActiveBrowserToggle = !state.isActiveBrowserToggle
    },
  },
  actions: {
    updateStatusMenu: ({commit})=>{
      commit('setStatusMenu')
    },

    updateStatusPubSectorMenu: ({commit})=>{
      commit('setStatusPubSectorMenu')
    },

    updateStatusPrivSectorMenu: ({commit})=>{
      commit('setStatusPrivSectorMenu')
    },

    updateStatusPubGradoMenu: ({commit})=>{
      commit('setStatusPubGradoMenu')
    },

    updateStatusHeaderShadow: ({commit}, status)=>{
      commit('setStatusHeaderShadow', status)
    },

    updateStatusBrowserToggle: ({commit})=>{
      commit('setStatusBrowserToggle')
    }
  }
})

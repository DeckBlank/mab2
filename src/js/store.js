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
    isActiveCursosMenuDesk: false,
    isHeaderWithShadow: false,

    //Browser
    isActiveBrowserToggle: false
  },
  mutations: {
    setStatusMenu(state){
      state.isActiveMenu = !state.isActiveMenu
    },

    setStatusCursosMenuMob(state){
      state.isActiveCursosMenuMob = !state.isActiveCursosMenuMob
    },

    setStatusCursosMenuDesk(state){
      state.isActiveCursosMenuDesk = !state.isActiveCursosMenuDesk
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

    updateStatusCursosMenuMob: ({commit})=>{
      commit('setStatusCursosMenuMob')
    },

    updateStatusCursosMenuDesk: ({commit})=>{
      commit('setStatusCursosMenuDesk')
    },

    updateStatusHeaderShadow: ({commit}, status)=>{
      commit('setStatusHeaderShadow', status)
    },

    updateStatusBrowserToggle: ({commit})=>{
      commit('setStatusBrowserToggle')
    }
  }
})

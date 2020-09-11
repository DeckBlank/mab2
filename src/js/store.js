import Vuex from 'vuex'
import Vue from 'vue'
import {getUserLoged} from './libs/login'

Vue.use(Vuex)

export const store = new Vuex.Store({ 
  state: {
    //Site
    API: `${document.getElementById('app').getAttribute('data-site')}/wp-json/custom/v1`,
    SITE_URL: `${document.getElementById('app').getAttribute('data-site')}`,
    THEME_URL: `${document.getElementById('app').getAttribute('data-theme')}`,

    //User
    logedUser: getUserLoged(),

    //Session
    activedSession: (window.localStorage.getItem('mab_session')) ? JSON.parse(window.localStorage.getItem('mab_session')) : false,

    //Menu
    sectorMenu: {
      private: false,
      public: false,
    },
    isActiveMenu: false,
    isHeaderWithShadow: false,

    //Browser
    isActiveBrowserToggle: false,

    //Page
    isLoadedPage: false,

    //Questionary + Poll
    isEnableQuestionary: false,
    isEnablePoll: false
  },
  mutations: {
    setStatusMenu(state){
      state.isActiveMenu = !state.isActiveMenu
    },

    setSectorMenu(state, sector){
      state.sectorMenu[sector] = !state.sectorMenu[sector]

      if(sector == 'public') state.sectorMenu.private = false;
      if(sector == 'private') state.sectorMenu.public = false;
    },

    setStatusHeaderShadow(state, status){
      state.isHeaderWithShadow = status
    },

    setStatusBrowserToggle(state){
      state.isActiveBrowserToggle = !state.isActiveBrowserToggle
    },

    disableLoading(state){
      state.isLoadedPage = true
    },

    setMetasBehaviour(state, behaviour){
      if (behaviour.type == 'questionary') {
        state.isEnableQuestionary = behaviour.value
      } else if(behaviour.type == 'poll') {
        state.isEnablePoll = behaviour.value
      }
    }
  },
  actions: {
    updateStatusMenu: ({commit})=>{
      commit('setStatusMenu')
    },

    updateStatusSectorMenu: function({commit}, sector){
      commit('setSectorMenu', sector);
    },

    updateStatusHeaderShadow: ({commit}, status)=>{
      commit('setStatusHeaderShadow', status)
    },

    updateStatusBrowserToggle: ({commit})=>{
      commit('setStatusBrowserToggle')
    },

    hideLoading: ({commit})=>{      
      window.setTimeout(()=>{
        commit('disableLoading')
      }, 1000)    
    },

    updateMetasBehaviour: ({commit}, behaviour)=>{
      commit('setMetasBehaviour', {type: behaviour.type, value: behaviour.value})
    }
  }
})

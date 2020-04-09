import Vuex from 'vuex'
import Vue from 'vue'

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
    sectors: [],
    pubGrade: null,
    privGrade: null,
    isActiveMenu: false,
    isActivePubSectorMenu: false,
    isActivePrivSectorMenu: false,
    isActivePubGradoMenu: false,
    isActivePrivGradoMenu: false,
    isHeaderWithShadow: false,

    //Browser
    isActiveBrowserToggle: false
  },
  mutations: {
    setStatusMenu(state){
      state.isActiveMenu = !state.isActiveMenu
    },

    setSectors(state, sectors){
      state.sectors = sectors;
    },

    setGrade(state, grade){
      if(grade.type == 'pub'){
        state.pubGrade = grade.value;

      }else if(grade.type == 'priv'){
        state.privGrade = grade.value;

      }
    },

    setStatusPubSectorMenu(state, status){
      state.isActivePubSectorMenu = (status == undefined) ? !state.isActivePubSectorMenu : status
    },

    setStatusPrivSectorMenu(state, status){
      state.isActivePrivSectorMenu =  (status == undefined) ? !state.isActivePrivSectorMenu : status
    },

    setStatusPubGradoMenu(state, status){
      state.isActivePubGradoMenu = (status == undefined) ? !state.isActivePubGradoMenu : status
    },

    setStatusPrivGradoMenu(state, status){
      state.isActivePrivGradoMenu = (status == undefined) ? !state.isActivePrivGradoMenu : status
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

    initSectors: function({commit}, sectors){
      fetch(`${this.state.API}/sectors`)
        .then(res => {
          if (res.status >= 200 && res.status < 300){
            return res.json()
          }else {
            throw res
          }
        })
        .then(sectors => {
          commit('setSectors', sectors)
        })
        .catch(err => {throw err})
    },

    defineGrade: function({commit}, grade){
      let gradeSelected

      if(grade.type == 'pub'){
        gradeSelected =  this.state.sectors[0].children.filter(el => el.name == grade.name)
      }else if(grade.type == 'priv'){
        gradeSelected =  this.state.sectors[1].children.filter(el => el.name == grade.name)
      }

      commit('setGrade', { type: grade.type, value: gradeSelected[0]}); this.dispatch(`updateStatus${ grade.type.replace('p', 'P') }GradoMenu`)
    },

    updateStatusPubSectorMenu: ({commit})=>{
      commit('setStatusPubSectorMenu')
      commit('setStatusPrivSectorMenu', false)
      commit('setStatusPrivGradoMenu', false)
    },

    updateStatusPrivSectorMenu: ({commit})=>{
      commit('setStatusPrivSectorMenu')
      commit('setStatusPubSectorMenu', false)
      commit('setStatusPubGradoMenu', false)
    },

    updateStatusPubGradoMenu: ({commit})=>{
      commit('setStatusPubGradoMenu')
    },

    updateStatusPrivGradoMenu: ({commit})=>{
      commit('setStatusPrivGradoMenu')
    },

    updateStatusHeaderShadow: ({commit}, status)=>{
      commit('setStatusHeaderShadow', status)
    },

    updateStatusBrowserToggle: ({commit})=>{
      commit('setStatusBrowserToggle')
    }
  }
})

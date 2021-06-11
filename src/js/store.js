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
    isActiveMenu: false,
    isHeaderWithShadow: false,

    //Browser
    isActiveBrowserToggle: false,

    //Page
    isLoadedPage: false,

    //Questionary + Poll
    isEnableQuestionary: false,
    isEnablePoll: false,

    shopCart: (window.localStorage.getItem('mab_shop_cart')) ? JSON.parse(window.localStorage.getItem('mab_shop_cart')) : false,
  },
  mutations: {
    setStatusMenu(state){
      state.isActiveMenu = !state.isActiveMenu
    },

    setStatusHeaderShadow(state, status){
      state.isHeaderWithShadow = status
    },

    setStatusBrowserToggle(state){
      state.isActiveBrowserToggle = !state.isActiveBrowserToggle
    },

    disableLoading(state){
      state.isLoadedPage = true;
    },

    setMetasBehaviour(state, behaviour){
      if (behaviour.type == 'questionary') {
        state.isEnableQuestionary = behaviour.value
      } else if(behaviour.type == 'poll') {
        state.isEnablePoll = behaviour.value
      }
    },

    updateShopCart(state, payload) {
      if (payload.operation == 'add') {
        let shop_cart = window.localStorage.getItem('mab_shop_cart')

        if(!shop_cart){
          shop_cart = [{
            id: payload.product.id,
            title: payload.product.title,
            link: payload.product.link,
          }];

          window.localStorage.setItem('mab_shop_cart', JSON.stringify(shop_cart));
        }else{
          shop_cart = JSON.parse(shop_cart);

          if (!shop_cart.find(co => co.id == payload.product.id)) {
            shop_cart.push({
              id: payload.product.id,
              title: payload.product.title,
              link: payload.product.link,
            })
            window.localStorage.setItem('mab_shop_cart', JSON.stringify(shop_cart))
          }
        }

        state.shopCart = shop_cart;
      } else {

      }
    },
  },
  actions: {
    updateStatusMenu: ({commit})=>{
      commit('setStatusMenu')
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
    },

    addCourseToShopCart: function({commit}, product) {
      commit('updateShopCart', { operation: 'add', product: product });
    },
    removeCourseFromShopCart: function({commit}, product) {

    },
  }
})

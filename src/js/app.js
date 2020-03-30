import Vue from 'vue'
import Vuex from 'vuex'
import './components/toggle';
import './components/browser';
import './components/profile';
import './components/video';

Vue.use(Vuex)

function baseConfig(store){
  return {
    el: '#app',
    store: store,
    delimiters: ['${', '}'],
    created () {
      window.addEventListener('scroll', this.handleScroll);
    },
    destroyed () {
      window.removeEventListener('scroll', this.handleScroll);
    }
  }
}

function baseState(){
  return Vuex.mapState([
    'API', 
    'SITE_URL', 
    'logedUser',
    'isActiveMenu', 
    'isActiveCursosMenuMob', 
    'isActiveCursosMenuDesk',
    'isHeaderWithShadow',
    'isActiveBrowserToggle'
  ])
}

function baseActions(){
  return {...Vuex.mapActions([
      'updateStatusCursosMenuMob', 
      'updateStatusCursosMenuDesk',
      'updateStatusHeaderShadow',
      'updateStatusBrowserToggle'
    ]),
    handleScroll: function(event){
      if(window.scrollY > 100){
        this.updateStatusHeaderShadow(true)
      }else{
        this.updateStatusHeaderShadow(false)
      }
    }    
  }
}

export {baseConfig, baseState, baseActions}

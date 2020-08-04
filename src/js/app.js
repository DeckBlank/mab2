import Vue from 'vue'
import Vuex from 'vuex'
import './components/sector';
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
    'sectorMenu',
    'isHeaderWithShadow',
    'isActiveBrowserToggle',
    'isLoadedPage',
    'isEnableQuestionary',
    'isEnablePoll'
  ])
}

function baseActions(){
  return {...Vuex.mapActions([
      'updateStatusSectorMenu',
      'updateStatusHeaderShadow',
      'updateStatusBrowserToggle',
      'hideLoading',
      'updateMetasBehaviour'
    ]),
    global: function(){
      this.saveLog();
    },
    saveLog: function(){
      if(!window.sessionStorage.getItem('mab_temp')){
        let user = (this.logedUser) ? this.logedUser.user_email : 'anonimo';

        fetch(`${this.API}/user/access/log?user=${user}`,{
            method: 'PUT'
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(response => {
            window.sessionStorage.setItem('mab_temp', JSON.stringify({
              user_active: true
            }))
          })
          .catch(err => {
            throw err;
          })      
      }
    },
  }
}

export {baseConfig, baseState, baseActions}

import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

import '../components/behaviour/questionaries/student';
import '../components/behaviour/questionaries/tutor';
import '../components/behaviour/poll';

const emotional = new Vue({
  ...baseConfig(store),
  data() {
    return {
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.global();
    this.hideLoading();

    if(this.logedUser && ['publico', 'privado'].includes(this.logedUser.user_sector))
      this.isEnableQP('poll');

    if(this.logedUser && this.logedUser.user_sector == 'publico'){
      this.isEnableQP('questionary');
    }
  },  
  methods: {
    ...baseActions(),
    isEnableQP: function(type){
      let isEnableQP__ = isEnableQP__ = (this.logedUser && !this.logedUser.user_metas[type]) ? true : false;

      if(isEnableQP__){
        fetch(`${this.API}/behaviour/${type}/enable?user=${this.logedUser.user_email}&sector=${this.logedUser.user_sector}`)
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(response => {
            this.updateMetasBehaviour({
              type: type,
              value: response
            });
          })
          .catch(err => {
            this.updateMetasBehaviour({
              type: type,
              value: false
            });
            throw err;
          })
      }
    },    
  }
})

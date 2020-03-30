import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const solicitar_id = new Vue({
  ...baseConfig(store),
  data() {
    return {
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    if(this.logedUser){
      window.location = this.SITE_URL;
    }
  },
  methods: {
    ...baseActions()
  }
})

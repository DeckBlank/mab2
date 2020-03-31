import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const course = new Vue({
  ...baseConfig(store),
  data() {
    return {
      isActiveUnity: false
    }
  },
  computed: {
    ...baseState()
  },
  created(){
    if(!this.logedUser){
      window.location = this.SITE_URL;
    }
  },
  mounted: function(){
    setTimeout(()=>{
      this.updateStatusCursosMenuDesk();
    }, 100)
  },
  methods: {
    ...baseActions()
  }
})

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
  mounted: function(){
    setTimeout(()=>{
      this.updateStatusCursosMenuDesk();
    }, 100)
  },
  methods: {
    ...baseActions()
  }
})

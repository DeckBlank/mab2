import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const _404 = new Vue({
  ...baseConfig(store),
  data() {
    return {
    }
  },
  computed: {
    ...baseState()
  },
  beforeMount(){
    this.initSectors();
  },
  mounted(){
    this.hideLoading();
  },
  methods: {
    ...baseActions()
  }
})

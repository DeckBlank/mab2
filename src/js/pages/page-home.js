import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const home = new Vue({
  ...baseConfig(store),
  data() {
    return {
      isActiveModal: true
    }
  },
  computed: {
    ...baseState()
  },
  methods: {
    ...baseActions()
  },
  mounted(){
    this.hideLoading();
  }
})

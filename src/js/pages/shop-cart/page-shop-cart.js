import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../../app'
import {store} from '../../store'

new Vue({
  ...baseConfig(store),
  data() {
    return {
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.hideLoading();
    this.global();
  },
  methods: {
    ...baseActions()
  }
})

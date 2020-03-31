import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const session = new Vue({
  ...baseConfig(store),
  data() {
    return {
      isActiveChat: false
    }
  },
  computed: {
    ...baseState()
  },
  methods: {
    ...baseActions()
  }
})

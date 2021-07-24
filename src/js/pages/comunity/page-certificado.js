import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../../app'
import {store} from '../../store'

const certificado = new Vue({
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
  },
  methods: {
    ...baseActions()
  }
})

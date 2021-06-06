import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const lideresSingle = new Vue({
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
    ...baseActions(),
    goBack: function(e) {
      e.preventDefault();

      window.history.back();
    },
  }
})

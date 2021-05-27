import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const donaciones = new Vue({
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

    setTimeout(function() {
      let video = document.getElementById("donac_video")
      video.play(); 
    },100)
  },
  methods: {
    ...baseActions()
  }
})

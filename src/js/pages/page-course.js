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
  beforeMount(){
    this.initSectors();
  },  
  mounted: function(){
    if(!this.logedUser){
      let topics = document.querySelectorAll('.c-topic__video')
  
      topics.forEach(topic => {
        topic.removeAttribute('href')
      })
    }
  },
  methods: {
    ...baseActions()
  }
})

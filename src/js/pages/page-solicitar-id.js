import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const solicitar_id = new Vue({
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
  created(){
    document.querySelector("[name='course'").innerHTML += `
      <option value="Otro" data-id="0">Otro</option>
    `
  },
  methods: {
    ...baseActions()
  }
})

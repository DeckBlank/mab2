import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import '../components/forms/student'
import '../components/forms/teacher'
import '../components/forms/tutor'

const forms = new Vue({
  ...baseConfig(store),
  data() {
    return {
      type: ''
    }
  },
  computed: {
    ...baseState()
  },
  methods: {
    ...baseActions()
  },
  beforeMount(){
    this.type = (new URLSearchParams(window.location.search)).get('type');
  }
})

import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import '../components/forms/student'
import '../components/forms/teacher'
import '../components/forms/tutor'
import '../components/forms/foreign'

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
  beforeMount(){
    this.type = (new URLSearchParams(window.location.search)).get('type');
  },
  mounted(){
    this.global();
    this.hideLoading();
  },
  methods: {
    ...baseActions()
  } 
})

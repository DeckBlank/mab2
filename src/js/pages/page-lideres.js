import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import '../components/lideres';


const lideres = new Vue({
  ...baseConfig(store),
  data() {
    return {
      lideres: [],
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.global();
    this.hideLoading();

    this.lideres = mab.leaders;
  },
  methods: {
    ...baseActions()
  }
})

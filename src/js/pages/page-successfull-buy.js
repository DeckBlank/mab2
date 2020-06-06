import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const successfull_buy = new Vue({
  ...baseConfig(store),
  data() {
    return {
      courses: []
    }
  },
  computed: {
    ...baseState()
  },
  created(){
    if (!this.logedUser) {
      window.location = `${this.SITE_URL}/login`
    }
  },
  beforeMount(){
    this.initSectors();
  },
  mounted(){
    this.global();
    this.isOk();
    this.hideLoading();
  },
  methods: {
    ...baseActions(),
    isOk: function(){
      let shop_cart = window.localStorage.getItem('mab_shop_cart');
        shop_cart = JSON.parse(shop_cart)
      
      this.courses = shop_cart
    }
  }
})

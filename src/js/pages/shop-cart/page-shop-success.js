import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../../app'
import {store} from '../../store'

new Vue({
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
      window.location = `${this.SITE_URL}/access`
    }
  },
  mounted(){
    this.global();
    this.isOk();
    this.hideLoading();
  },
  methods: {
    ...baseActions(),
    isOk: function(){
      let lap_response_code = (new URLSearchParams(window.location.search)).get('lapResponseCode'),
        reference_code = (new URLSearchParams(window.location.search)).get('referenceCode')

      if(lap_response_code == 'APPROVED'){
        fetch(`${this.API}/courses/buy/checkout?reference_code=${reference_code}&user=${this.logedUser.user_email}`)
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              this.listCourses(); this.hideLoading();
            }else{
              throw res
            }
          })
          .catch(err => {
            // window.location = `${this.SITE_URL}/carrito`; throw err;
          })
      }else{
        // window.location = `${this.SITE_URL}/carrito`
      }
    },
    listCourses: function(){
      let shop_cart = window.localStorage.getItem('mab_shop_cart');
        shop_cart = JSON.parse(shop_cart)

      this.courses = shop_cart; window.localStorage.removeItem('mab_shop_cart')
    }
  }
})

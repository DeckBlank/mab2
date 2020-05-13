import Vue from 'vue'
import {md5} from 'pure-md5';
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const shop_cart = new Vue({
  ...baseConfig(store),
  data() {
    return {
      courses: [],
      total: 0,
      total_discount: 0,
      amount: 0,

      //Webcheckout
      merchanId: '',
      accountId: '',
      signature: '',
      referenceCode: 'CompraMABPayU'
    }
  },
  computed: {
    ...baseState()
  },
  created(){
    if (this.logedUser) {
      this.listCourses();
    } else {
      window.location = `${this.SITE_URL}/login`
    }
  },
  beforeMount(){
    this.initSectors();
  },
  methods: {
    ...baseActions(),
    listCourses: function(){
      let shop_cart = window.localStorage.getItem('mab_shop_cart');
        shop_cart = JSON.parse(shop_cart)

      if(shop_cart){
        fetch(`${this.API}/courses?ids=${[...(shop_cart.map(course => course.id))].join(',')}`,{
            method: 'GET'
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(courses => {
            this.courses = courses
  
            courses.list.forEach(course => {
              this.total += course.price
              this.total_discount += course.price*course.discount/100
            })
  
            this.amount = Math.round(this.total - this.total_discount)
            this.signature = md5(`${this.courses.pasarell.api_key}~${this.courses.pasarell.merchan_id}~${this.referenceCode}~${this.amount}~PEN`);

            this.hideLoading();
          })
          .catch(err => {
            window.location = `${this.SITE_URL}/emotional`
            throw err;
          })
      }else{
        this.hideLoading();
      }
    }
  }
})

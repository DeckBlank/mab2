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

      //Webcheckout
      merchanId: '',
      accountId: '',
      signature: '',
      referenceCode: ''
    }
  },
  computed: {
    ...baseState(),
    amount: {
      get: function(){
        return this.total - this.total_discount;
      }
    }  
  },
  watch: {
    'total': function(val) {
      if(val == 0){
        this.total_discount = 0;  window.location = `${this.SITE_URL}/emotional`

      }else {
        if(this.courses.list.length > 1){
          this.total_discount = this.courses.list.map(course => course._discount).reduce((a, b) => a + b)
  
        }else {
          this.total_discount = this.courses.list[0]._discount;
        }

        this.signature = md5(`${this.courses.pasarell.api_key}~${this.courses.pasarell.merchan_id}~${this.referenceCode}~${this.total - this.total_discount}~PEN`);
      } 
    }
  },
  created(){
    if (this.logedUser) {
      this.listCourses();
    } else {
      window.location = `${this.SITE_URL}/login`
    }
  },
  mounted(){
    this.initSectors();
    this.global();
  },
  methods: {
    ...baseActions(),
    listCourses: function(){
      let shop_cart = window.localStorage.getItem('mab_shop_cart');
        shop_cart = JSON.parse(shop_cart)

      if(shop_cart && shop_cart.length > 0){
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
  
            courses.list.forEach((course, index) => {
              let discount = course.price*course.discount/100 + (course.price - course.price*course.discount/100)*courses.discount.global/100;

              this.total += course.price
              this.courses.list[index]._discount = discount;
            })

            this.referenceCode = `ComprasMAB-${this.logedUser.user_email}-${new Date().valueOf()}`
            this.hideLoading();
          })
          .catch(err => {
            window.location = `${this.SITE_URL}/emotional`
            throw err;
          })
      }else{
        this.hideLoading();
      }
    },
    buyCourses: function(){
      event.preventDefault();

      let shop_cart = window.localStorage.getItem('mab_shop_cart'),
        form_data = new FormData();
      
      shop_cart = JSON.parse(shop_cart)

      form_data.append('reference_code', this.referenceCode)
      form_data.append('user', this.logedUser.user_email)

      fetch(`${this.API}/courses/buy/log?ids=${[...(shop_cart.map(course => course.id))].join(',')}&user=${this.logedUser.user_email}`, {
          method: 'POST',
          body: form_data
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            this.$refs.summary_form.submit()
          }else{
            throw res
          }
        })
        .catch(err => {
          throw err;
        })
    },
    removeCourseFromShopCart: function(course_id, price, discount, index){
      let shop_cart = window.localStorage.getItem('mab_shop_cart');
      
      /**
       * 1. Update ShopCart
       */
      shop_cart = JSON.parse(shop_cart)
      shop_cart = shop_cart.filter(course => course.id != course_id);

      window.localStorage.setItem('mab_shop_cart', JSON.stringify(shop_cart));
      
      /**
       * 2. Update Courses List + Prices
       */
      if(index == 0 && this.courses.list.length > 1){
        this.courses.list[1]._discount = this.courses.list[0]._discount;
        this.courses.list = this.courses.list.filter(course => course.id != course_id);
      }else{
        this.courses.list = this.courses.list.filter(course => course.id != course_id);
      }

      this.total -= price;
    }
  }
})

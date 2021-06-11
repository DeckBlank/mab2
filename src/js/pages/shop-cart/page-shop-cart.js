import Vue from 'vue'
import {md5} from 'pure-md5';
import {baseConfig, baseState, baseActions} from '../../app'
import {store} from '../../store'

const shop_cart = new Vue({
  ...baseConfig(store),
  data() {
    return {
      courses: {
        list: [],
      },
      total: 0,
      total_discount: 0,

      //Webcheckout
      merchanId: '',
      accountId: '',
      signature: '',
      referenceCode: '',

      isOpenedAlert: false,
      deleteShopCartGranted: false,
    }
  },
  computed: {
    ...baseState(),
    amount: function(){
      return (this.total - this.total_discount).toFixed(2);
    },
    courseBasicThumbnail: function() {
      return `${ this.THEME_URL }/static/images/og_image.png`;
    },
  },
  watch: {
    'total': function(val) {
      if(val == 0){
        this.total_discount = 0;  window.location = `${this.SITE_URL}/cursos`

      }else {
        if(this.courses.list.length > 1){
          this.total_discount = this.courses.list.map(course => course._discount).reduce((a, b) => a + b)
        }else {
          this.total_discount = this.courses.list[0]._discount;
        }

        this.total_discount = this.total_discount.toFixed(2);

        this.signature = md5(`${this.courses.pasarell.api_key}~${this.courses.pasarell.merchan_id}~${this.referenceCode}~${this.total - this.total_discount}~PEN`);
      }
    }
  },
  mounted(){
    if (this.logedUser) {
      this.listCourses();
    } else {
      window.location = `${this.SITE_URL}/access`
    }

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
            window.localStorage.removeItem('mab_shop_cart');
            window.location = `${this.SITE_URL}/cursos`

            throw err;
          })
      }else{
        this.hideLoading();
      }
    },
    buyCourses: function(e) {
      e.preventDefault();

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
      let shopCart = window.localStorage.getItem('mab_shop_cart');

      shopCart = JSON.parse(shopCart);

      if (shopCart.length == 1) {
        this.isOpenedAlert = true;
      } else {
        shopCart = shopCart.filter(course => course.id != course_id);

        window.localStorage.setItem('mab_shop_cart', JSON.stringify(shopCart));

        if(index == 0 && this.courses.list.length > 1){
          this.courses.list[1]._discount = this.courses.list[1].price*this.courses.discount.global/100;
          this.courses.list = this.courses.list.filter(course => course.id != course_id);
        }else{
          this.courses.list = this.courses.list.filter(course => course.id != course_id);
        }

        this.total -= price;
      }
    },

    emptyShopCart: function() {
      this.deleteShopCartGranted = true;

      window.localStorage.removeItem('mab_shop_cart');

      window.location.href = `${this.SITE_URL}/cursos`;
    },
    closeAlert: function(e) {
      e.preventDefault();

      this.isOpenedAlert = false;
    },
  }
})

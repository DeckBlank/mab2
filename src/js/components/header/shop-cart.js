import Vue from 'vue'
import Vuex from 'vuex'
import {md5} from 'pure-md5';

Vue.component('shopcart',{
  template: /*html*/`
    <section class="c-section padding-top-0">
      <div class="c-mab-carrito padding-2">
        <div class="grid-x grid-margin-x grid-margin-y align-center">
          <div v-if="courses.list && courses.list.length" class="cell">
            <h2 class="c-mab-carrito__title fs-25 text-uppercase dark">TU CARRITO DE COMPRAS</h2>
            <h4 class="c-mab-carrito__subtitle f2 fs-18 dark">Tienes <strong>{{ courses.list.length }}</strong> item(s) en tu carrito </h4>
          </div>
          <div v-if="courses.list && courses.list.length" class="cell large-8">
            <div class="c-mab-carrito__scroll">
              <div>
                <div v-for="(course, cindex) of courses.list" :key="course.id" class="c-mab-carrito-item c-mab-carrito-item--primary margin-bottom-1">
                  <div class="grid-x grid-margin-x align-middle"> 
                    <div class="cell small-11 medium-11 large-11">
                      <div class="c-mab-carrito-product mab-shadow-soft flex-container align-center-middles bg-warning br--large">
                        <figure class="c-mab-carrito-product__figure">
                          <img :src="(course.thumbnail) ? course.thumbnail : courseBasicThumbnail" class="width-100 height-100 object-cover br-left--large" alt="">
                        </figure>
                        <div class="c-mab-carrito-product__content br-right--large white f2">
                          <h4 class="c-mab-carrito-product__name fs-18 f2 w-xbold"> {{ course.title }} </h4>
                          <h6 class="c-mab-carrito-product__teacher f2 fs-14">  Con {{ course.teacher }}</h6>
                          <p> {{ course.description }} </p>

                            <div class="text-right">
                              <div class="c-mab-carrito-product__price w-xbold">
                                S/ {{ course.price }}.00
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="cell small-1 medium-1 large-1">
                      <button @click="removeCourseFromShopCart(course.id, course.price, -1, cindex)" class="dark fs-25 c-link--ho-primary">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-if="courses.list && courses.list.length" class="cell large-4">
            <div class="c-mab-carrito-list fs-18">
              <div v-for="course of courses.list" :key="course.id" class="c-mab-carrito-list-item flex-container align-justify align-middle dark f2">
                <span class="c-mab-carrito-list-item__name"> {{ course.title }} </span>
                <span class="c-mab-carrito-list-item__price w-xbold">S/ {{ course.price }}</span>
              </div>
            </div>
            <div class="c-mab-carrito-total margin-top-1">
              <div class="flex-container align-justify align-middle dark f2 fs-21">
                <span>TOTAL</span>
                <span class="w-xbold">S/ {{ amount }}</span>
              </div>
            </div>

            <div class="margin-top-2 margin-bottom-1">
              <form v-if="courses.list" ref="summary_form" :action="pasarell.action" method="POST" class="text-center">
                <input name="merchantId"    type="hidden"  :value="pasarell.merchan_id">
                <input name="accountId"     type="hidden"  :value="pasarell.account_id">
                <input name="description"   type="hidden"  :value="'Compra de ' + [...(courses.list.map(course => course.title))].join(', ')"  >
                <input name="referenceCode" type="hidden"  :value="referenceCode" >
                <input name="amount"        type="hidden"  :value="amount"   >
                <input name="tax"           type="hidden"  value="0" >
                <input name="taxReturnBase" type="hidden"  value="0" >
                <input name="currency"      type="hidden"  value="PEN" >
                <input name="signature"     type="hidden"  :value="signature"  >
                <input name="test"          type="hidden"  :value="pasarell.enviroment == 'dev' ? 1 : 0" >
                <input name="buyerEmail"    type="hidden"  :value="logedUser.user_email" >
                <input name="responseUrl"    type="hidden"  :value="SITE_URL + '/carrito/success'" >
                <input name="confirmationUrl"    type="hidden"  :value="'http://shipping.mabclick.com/' + pasarell.enviroment + '_confirm.php'" >
                <input type="submit" class="c-button c-button--dark-white f2 button-reset width-100 display-block fs-21" @click="buyCourses($event)" value="Comprar" >
              </form>
            </div>
            <div class="text-center">
              <a href="/cursos" class="c-link c-link--ho-dark dark f2 fs-16 margin-top-1 w-xbold">Seguir comprando</a>
            </div>
          </div>

          <div v-if="courses.list && !courses.list.length" class="cell large-10">
            <div class="c-mab-carrito__vacio flex-container dark f2 text-center">
              <div class="c-mab-carrito__vacio-content-xx">
                <div class="c-mab-carrito__vacio-figure fs-35 margin-bottom-2">
                  <img :src="THEME_URL + '/static/images/carrito/cart.png'" alt="">
                </div>
                <h2 class="w-xbold fs-25">¡Tú carrito de compras esta vacío!</h2>
                <p class="fs-16">
                  Luego de varios años dando clases particulares a niños y niñas de diferentes
                  colegios privados de Lima, Macarena expande sus servicios a todo el Perú con el
                  objetivo de disminuir brechas sociales, educativas y tecnológicas en el país.
                </p>
                <div class="margin-top-2 margin-bottom-1 text-center">
                  <a href="/cursos" class="c-button c-button--primary f2">
                    Ver productos
                  </a>
                </div>
              </div>
              
            </div>
          </div>

          <div class="cell">
            <div class="f2 dark margin-top-2" :class="{'text-center' : courses.list && !courses.list.length}">
              Compra con todos los medios de pago disponibles: 
              <div class="c-medios-pagos flex-container align-middle" :class="{'align-center' : courses.list && !courses.list.length}">
                <img :src="THEME_URL + '/static/images/vectors/icon-cards/icon-visa.png'" alt="">
                <img :src="THEME_URL + '/static/images/vectors/icon-cards/icon-mc.png'" alt="">
                <img :src="THEME_URL + '/static/images/vectors/icon-cards/icon-americanex.png'" alt="">
                <img :src="THEME_URL + '/static/images/vectors/icon-cards/icon-paypal.png'" alt="">
                <img :src="THEME_URL + '/static/images/vectors/icon-cards/icon-pagoefectivo.png'" alt="">
                <img :src="THEME_URL + '/static/images/vectors/icon-cards/icon-diners.png'" alt="">
                <img :src="THEME_URL + '/static/images/vectors/icon-cards/icon-tambo.png'" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="c-mab-carrito-message c-mab-carrito-message--header width-100 height-100 align-center-middle" :class="{'visible' : isOpenedAlert}">
        <div class="c-mab-carrito-message__content br--large bg-white text-center flex-container flex-dir-column align-center-middle">
          <img class="c-mab-carrito-message__img" :src="THEME_URL + '/static/images/carrito/red-alert.png'" alt="">
          <h3 class="c-mab-carrito-message__title dark text-uppercase fs-25">
            vaciar CARRITO
          </h3>
          <p class="dark fs-18 f2">
            ¿Estás seguro que deseas vaciar tu carrito? 
          </p>
          <div class="margin-top-2 margin-bottom-1">
            <button @click="emptyShopCart()" class="c-button c-button--primary f2">
              Vaciar carrito
            </button>
          </div>
          <div class="text-center">
            <a href="#" @click="closeAlert($event)" class="c-link c-link--ho-dark dark  f2 fs-16 margin-top-1 w-xbold">Volver</a>
          </div>
        </div>
        
      </div>
    </section>
  `,
  data() {
    return {
      courses: {
        list: [],
      },
      total: 0,
      total_discount: 0,

      pasarell: {},

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
    ...Vuex.mapState(['API', 'SITE_URL', 'THEME_URL', 'logedUser', 'shopCart']),
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
    },
    'shopCart': function() {
      this.total = 0;
      this.total_discount = 0;

      this.listCourses();
    },
  },
  mounted(){
    if (this.logedUser) {
      this.listCourses();

      this.pasarell = (mab.pasarell) ? mab.pasarell : {};
    } else {
      window.location = `${this.SITE_URL}/access`
    }
  },
  methods: {
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
          })
          .catch(err => {
            window.localStorage.removeItem('mab_shop_cart');
            window.location = `${this.SITE_URL}/cursos`

            throw err;
          })
      }else{
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
});

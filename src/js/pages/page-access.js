import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import Swiper from 'swiper'

const access = new Vue({
  ...baseConfig(store),
  data() {
    return {
      view: 1
    }
  },
  computed: {
    ...baseState()
  },
  watch: {
    'view': function() {
      if(this.view == 3) {
        setTimeout(function() {
          new Swiper('.swiper-container', {
            autoplay: {
              delay: 3000,
            },
            pagination: {
              el: '.c-image-slider .swiper-pagination',
            },
            navigation: {
              nextEl: '.swiper-button-next',
              prevEl: '.swiper-button-prev',
            },
          });
        },1000)
      }
    }
  },
  mounted(){
    this.global();
    this.hideLoading();
  },
  methods: {
    ...baseActions(),
    next: function () {
      if(this.view != 3) {
        this.view = this.view + 1
      }
    },
    prev: function () {
      if(this.view != 1) {
        this.view = this.view - 1
      }
    }
  }
})

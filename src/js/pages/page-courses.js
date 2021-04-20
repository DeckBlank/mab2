import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import Swiper from 'swiper'

const courses = new Vue({
  ...baseConfig(store),
  data() {
    return {
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.global();
    this.hideLoading();

    setTimeout(function() {
      let courseContinue = new Swiper('.c-mab-recommended .swiper-container', {
        slidesPerView: 3,
        spaceBetween: 0,
        navigation: {
          nextEl: '.c-mab-recommended .swiper-button-next',
          prevEl: '.c-mab-recommended .swiper-button-prev',
        },
        breakpoints: {
          // when window width is >= 320px
          200: {
            slidesPerView: 1,
            spaceBetween: 10
          },
          640: {
            slidesPerView: 2,
            spaceBetween: 0,
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 0
          },
          1024: {
            slidesPerView: 4,
            spaceBetween: 0
          }
        }
      });
      let category = new Swiper('.c-category-list .swiper-container', {
        slidesPerView: 3,
        spaceBetween: 0,
        navigation: {
          nextEl: '.c-category-list .swiper-button-next',
          prevEl: '.c-category-list .swiper-button-prev',
        },
        breakpoints: {
          // when window width is >= 320px
          200: {
            slidesPerView: 2,
            spaceBetween: 10
          },
          640: {
            slidesPerView: 3,
            spaceBetween: 0,
          },
          768: {
            slidesPerView: 4,
            spaceBetween: 0
          },
          1024: {
            slidesPerView: 5,
            spaceBetween: 0
          }
        }
      });

    },1000)
  },
  methods: {
    ...baseActions(),
  }
})

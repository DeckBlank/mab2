import Vue from 'vue'
import { mapState } from 'vuex';
import {store} from '../../store';
import Swiper from 'swiper';

import {baseConfig, baseState, baseActions} from '../../app';
import {addCourseToShopCart} from '../../libs/shop-cart'

import '../../components/lideres';

const myCourses = new Vue({
  ...baseConfig(store),
  data() {
    return {
      enrolledCourses: [],
      recommendCourses: [],

      isLoadingEnroll: true,
      isLoadingRecommended: true,

      lideres: [
        {
          name: 'Maca Wellness',
          avatar: 'maca',
          job: 'Nutrición',
          profile: 'maca-wellness',
          padding: 3,
          width: 250,
        },
        {
          name: 'Ernesto Reaño',
          avatar: 'ernesto',
          job: 'Autismo',
          profile: 'ernesto-reano',
          padding: 1,
          width: 320,
        },
        {
          name: 'Viviana de Ferrari',
          avatar: 'viviana',
          job: 'Amor Propio',
          profile: 'viviana-de-ferrari',
          padding: 2,
          width: 280,
        },
        {
          name: 'Vanessa Vasquez',
          avatar: 'vanessa',
          job: 'El Poder de la Empatía',
          profile: 'vanessa-vasquez',
          padding: 1,
          width: 280,
        },
        {
          name: 'Menta Days',
          avatar: 'menta',
          job: 'Arte para la Vida',
          profile: 'menta-days',
          padding: 2,
          width: 260,
        },
        {
          name: 'Verónica Álvarez',
          avatar: 'veronica',
          job: 'Danza para la Vida',
          profile: 'veronica-alvarez',
          padding: 3,
          width: 240,
        },
      ],
    }
  },
  computed: {
    ...baseState(),
    ...mapState(['THEME_URL']),
    userName: function() {
      let firstname = this.logedUser.user_firstname.split(' ');

      return (firstname.length) ? firstname[0] : this.logedUser.user_firstname;
    },
    courseBasicThumbnail: function() {
      return `${ this.THEME_URL }/static/images/og_image.png`;
    },
  },
  mounted(){
    this.global();
    this.hideLoading();
    this.getEnrolledCourses();
    this.getRecommendedCoures();

    setTimeout(function() {
      new Swiper('.c-other-services .swiper-container', {
        slidesPerView: 1,
        spaceBetween: 30,
        pagination: {
          el: '.c-other-services .swiper-pagination',
          type: 'bullets',
          clickable: true,
        },
      });
    },1000)
  },
  methods: {
    ...baseActions(),
    initSliderEnrolledCourses: function() {
      window.setTimeout(() => {
        new Swiper('.c-mab-continue .swiper-container', {
          slidesPerView: 3,
          spaceBetween: 0,
          navigation: {
            nextEl: '.c-mab-continue .swiper-button-next',
            prevEl: '.c-mab-continue .swiper-button-prev',
          },
          breakpoints: {
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
          },
          on: {
            init: () => {
              this.isLoadingEnroll = false;
            },
          },
        });
      }, 1000)
    },
    initSliderRecommendedCourses: function() {
      window.setTimeout(() => {
        new Swiper('.c-mab-recommended .swiper-container', {
          slidesPerView: 3,
          spaceBetween: 0,
          navigation: {
            nextEl: '.c-mab-recommended .swiper-button-next',
            prevEl: '.c-mab-recommended .swiper-button-prev',
          },
          breakpoints: {
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
          },
          on: {
            init: () => {
              this.isLoadingRecommended = false;
            },
          },
        });
      }, 1000);
    },
    getEnrolledCourses: function() {
      fetch(`${ this.API }/users/${ this.logedUser.user_id }/courses?user_email=${ this.logedUser.user_email }&_wpnonce=${ mab.nonce }`)
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(response => {
        if (response.status) {
          this.enrolledCourses = response.data;

          this.initSliderEnrolledCourses();
        } else {
          window.setTimeout(() => {
            this.isLoadingEnroll = false;
          }, 1000);
        }
      })
      .catch(err => {
        window.setTimeout(() => {
          this.isLoadingEnroll = false;
        }, 1000);

        throw err;
      })
    },
    getRecommendedCoures: function() {
      fetch(`${ this.API }/users/${ this.logedUser.user_id }/courses/recommended?user_email=${ this.logedUser.user_email }&_wpnonce=${ mab.nonce }`)
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(response => {
        if (response.status) {
          this.recommendCourses = response.data;

          this.initSliderRecommendedCourses();
        } else {
          window.setTimeout(() => {
            this.isLoadingRecommended = false;
          }, 1000);
        }
      })
      .catch(err => {
        window.setTimeout(() => {
          this.isLoadingRecommended = false;
        }, 1000);

        throw err;
      })
    },

    addCourse: function(course_id, course_title, course_link){
      addCourseToShopCart(course_id, course_title, course_link, this.SITE_URL)
    },
  }
})

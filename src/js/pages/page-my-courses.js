import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app';
import {store} from '../store';
import Swiper from 'swiper';

import '../components/lideres';

const myCourses = new Vue({
  ...baseConfig(store),
  data() {
    return {
      enrolledCourses: [],
      recommendCourses: [],

      lideres: [
        {
          name: 'Maca Wellness',
          avatar: 'maca',
          job: 'Nutrición',
          profile: '',
          padding: 3,
          width: 250,
        },
        {
          name: 'Ernesto Reaño',
          avatar: 'ernesto',
          job: 'Autismo',
          profile: '',
          padding: 1,
          width: 320,
        },
        {
          name: 'Viviana de Ferrari',
          avatar: 'viviana',
          job: 'Amor Propio',
          profile: '',
          padding: 2,
          width: 280,
        },
        {
          name: 'Vanessa Vasquez',
          avatar: 'vanessa',
          job: 'El Poder de la Empatía',
          profile: '',
          padding: 1,
          width: 280,
        },
        {
          name: 'Menta Days',
          avatar: 'menta',
          job: 'Arte para la Vida',
          profile: '',
          padding: 2,
          width: 260,
        },
        {
          name: 'Verónica Álvarez',
          avatar: 'veronica',
          job: 'Danza para la Vida',
          profile: '',
          padding: 3,
          width: 240,
        },
      ],
    }
  },
  computed: {
    ...baseState(),
    userName: function() {
      let firstname = this.logedUser.user_firstname.split(' ');

      return (firstname.length) ? firstname[0] : this.logedUser.user_firstname;
    },
  },
  mounted(){
    this.global();
    this.hideLoading();
    this.getEnrolledCourses();
    this.getRecommendedCoures();

    setTimeout(function() {
      new Swiper('.c-mab-recommended .swiper-container', {
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
          }
        });
      }, 1000)
    },
    getEnrolledCourses: function() {
      fetch(`${ this.API }/users/courses?user_email=${ this.logedUser.user_email }&_wpnonce=${ mab.nonce }`)
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
        }
      })
      .catch(err => {
        throw err;
      })
    },
    getRecommendedCoures: function() {
      fetch(`${ this.API }/users/${ this.logedUser.user_id }/courses?user_email=${ this.logedUser.user_email }&_wpnonce=${ mab.nonce }`)
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(response => {
        if (response.status)
          this.recommendCourses = response.data;
      })
      .catch(err => {
        throw err;
      })
    },
  }
})

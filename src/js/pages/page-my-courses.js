import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import Swiper from 'swiper'

const myCourses = new Vue({
  ...baseConfig(store),
  data() {
    return {
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
          padding: 2,
          width: 300,
        },
        {
          name: 'Viviana de Ferrari',
          avatar: 'viviana',
          job: 'Amor Propio',
          profile: '',
          padding: 2,
          width: 250,
        },
        {
          name: 'Vanessa Vasquez',
          avatar: 'vanessa',
          job: 'El Poder de la Empatía',
          profile: '',
          padding: 1,
          width: 250,
        },
        {
          name: 'Menta Days',
          avatar: 'menta',
          job: 'Arte para la Vida',
          profile: '',
          padding: 2,
          width: 250,
        },
        {
          name: 'Verónica Álvarez',
          avatar: 'veronica',
          job: 'Danza para la Vida',
          profile: '',
          padding: 2,
          width: 250,
        },
      ]
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.global();
    this.hideLoading();
    this.initLideresBrands();

    setTimeout(function() {
      let courseContinue = new Swiper('.c-mab-continue .swiper-container', {
        slidesPerView: 3,
        spaceBetween: 30,
        navigation: {
          nextEl: '.c-mab-continue .swiper-button-next',
          prevEl: '.c-mab-continue .swiper-button-prev',
        },
        breakpoints: {
          // when window width is >= 320px
          200: {
            slidesPerView: 1,
            spaceBetween: 20
          },
          640: {
            slidesPerView: 2,
            spaceBetween: 40,
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 40
          },
          1024: {
            slidesPerView: 4,
            spaceBetween: 40
          }
        }
      });
      let courseRecommended = new Swiper('.c-mab-recommended .swiper-container', {
        slidesPerView: 3,
        spaceBetween: 30,
        navigation: {
          nextEl: '.c-mab-recommended .swiper-button-next',
          prevEl: '.c-mab-recommended .swiper-button-prev',
        },
        breakpoints: {
          // when window width is >= 320px
          200: {
            slidesPerView: 1,
            spaceBetween: 20
          },
          640: {
            slidesPerView: 2,
            spaceBetween: 40,
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 40
          },
          1024: {
            slidesPerView: 4,
            spaceBetween: 40
          }
        }
      });

      let otherServices = new Swiper('.c-other-services .swiper-container', {
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
    initLideresBrands: function() {
      new Swiper('.c-lideres .swiper-container', {
        speed: 900,
        loop: false,
        allowTouchMove: false,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },

        pagination: {
          el: '.c-lideres .swiper-pagination',
          type: 'bullets',
          clickable: true,
        },

        breakpoints: {
          320: {
            slidesPerView: 1,
            spaceBetween: 10,
          },
          480: {
            slidesPerView: 1,
            spaceBetween: 10,
          },
          640: {
            slidesPerView: 2,
            spaceBetween: 10,
          },
          1024: {
            slidesPerView: 2,
            spaceBetween: 30,
          },
          1140: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
        },
      })
    }
  }
})

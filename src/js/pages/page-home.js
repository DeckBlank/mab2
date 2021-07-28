import Vue from 'vue'
import Swiper from 'swiper'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store';

import '../components/lideres';

const home = new Vue({
  ...baseConfig(store),
  data() {
    return {
      advantages: [
        {
          vector: 'person',
          title: 'clases 100% personalizadas'
        },
        {
          vector: 'book',
          title: 'cursos del currículo nacional'
        },
        {
          vector: 'certificate',
          title: 'capacitaciones a docentes de todo el perú'
        },
        {
          vector: 'balance',
          title: 'balance emocional y académico'
        },
        {
          vector: 'file',
          title: 'recursos para los distintos estilos de aprendizaje'
        },
        {
          vector: 'brain',
          title: 'pruebas psicológicas'
        },
      ],

      questions: [],
      testimonies: [],

      lideres: [],
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.hideLoading();
    this.global();
    this.initTestimoniesSlider();
    this.initBrandsSlider();

    this.questions    = mab.questions;
    this.testimonies  = mab.testimonies;
    this.lideres      = mab.leaders;

    this.questions = this.questions.map(question => {
      return {
        ...question,
        enable: false
      }
    });

    this.questions[0].enable = true;
  },
  methods: {
    ...baseActions(),
    initTestimoniesSlider: function() {
      window.setTimeout(() => {
        new Swiper('#slider-testimonies', {
          speed: 3000,
          loop: true,
          autoplay: true,
  
          pagination: {
            el: '#slider-testimonies + .swiper-pagination',
            type: 'bullets',
            clickable: true,
          },
  
          breakpoints: {
            320: {
              slidesPerView: 1,
              spaceBetween: 10,
            },
            480: {
              slidesPerView: 2,
              spaceBetween: 10,
            },
            640: {
              slidesPerView: 2,
              spaceBetween: 10,
            },
            1024: {
              slidesPerView: 3,
              spaceBetween: 30,
            },
            1140: {
              slidesPerView: 3,
              spaceBetween: 30,
            },
          },
        })
      }, 100);
    },
    initBrandsSlider: function() {
      new Swiper('#slider-brands', {
        speed: 3000,
        loop: true,
        freeMode: true,
        freeModeMomentum: false,
        autoplay: {
          enabled: true,
          delay: 0,
          disableOnInteraction: true,
        },

        breakpoints: {
          320: {
            slidesPerView: 2,
            spaceBetween: 10,
          },
          480: {
            slidesPerView: 2,
            spaceBetween: 10,
          },
          640: {
            slidesPerView: 3,
            spaceBetween: 10,
          },
          1024: {
            slidesPerView: 4,
            spaceBetween: 30,
          },
          1140: {
            slidesPerView: 5,
            spaceBetween: 30,
          },
        },
      })
    },
    resetAccordion: function(question) {
      this.questions = this.questions.map(q => {
        return (q != question) ? { ...q, enable : false } : q;
      })
    },
  }
})

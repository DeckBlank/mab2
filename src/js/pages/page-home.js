import Vue from 'vue'
import Swiper from 'swiper'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store';

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
          width: 300,
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

      questions: [
        { enable : true },
        { enable : false },
        { enable : false },
        { enable : false },
        { enable : false },
      ]
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){   
    this.hideLoading();
    this.global();
    this.initTestimoniesSlider();
    this.initLideresSlider();
    this.initBrandsSlider();
  },
  methods: {
    ...baseActions(),
    initTestimoniesSlider: function() {
      new Swiper('#slider-testimonies', {
        speed: 900,
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
    },
    initLideresSlider: function() {
      new Swiper('#slider-lideres', {
        speed: 900,
        loop: true,
        allowTouchMove: false,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },

        pagination: {
          el: '#slider-lideres + .swiper-pagination',
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
    },
    initBrandsSlider: function() {
      new Swiper('#slider-brands', {
        speed: 1200,
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

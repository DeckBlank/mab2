import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import tippy from 'tippy.js';
import Swiper from 'swiper';

new Vue({
  ...baseConfig(store),
  data() {
    return {
      sliderGradient: false,

      slider: null,
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.global();
    this.hideLoading();

    let breakpoint = window.matchMedia('(min-width: 1024px)');

    this.checkDevice(breakpoint); breakpoint.addEventListener('change', this.checkDevice);
    this.initSlider();
  },
  methods: {
    ...baseActions(),
    checkDevice: function(breakpoint) {
      if (breakpoint.matches) {
        this.initTooltips('desktop');
      } else {
        this.initTooltips('mobile');
      }
    },

    initTooltips: function(device) {
      const BASE_CONFIG = {
        allowHTML: true,
        theme: 'mab-secondary',
        inertia: true,
        animation: 'scale-subtle',
        trigger: 'click',
        placement: (device == 'desktop') ? 'left' : 'bottom',
      }
      const totalHitos = document.querySelector('#about').getAttribute('data-hitos');

      for (let index = 0; index < totalHitos; index++) {
        tippy(`#tooltip-${ index + 1 }`, {
          ...BASE_CONFIG,
          content: document.querySelector(`#template-${ index + 1 }`).innerHTML,
          onShow: () => {
            this.sliderGradient = true;
          },
          onHide: () => {
            this.sliderGradient = false;
          },
        });
      }
    },

    initSlider: function() {
      setTimeout(() => {
        this.slider = new Swiper(".mySwiper", {
          speed: 10000,
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
              slidesPerView: 1,
              spaceBetween: 0
            },
            768: {
              slidesPerView: 2,
              spaceBetween: 0
            },
            1024: {
              slidesPerView: 2.8,
              spaceBetween: 0
            }
          }
        });
      }, 100);
    },
    pauseSlider: function(mode) {
      if (mode) {
        this.slider.autoplay.stop();
      } else {
        this.slider.autoplay.start();
      }
    },
  }
})

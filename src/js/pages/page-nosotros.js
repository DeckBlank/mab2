import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import tippy from 'tippy.js';
import Swiper from 'swiper';



const nosotros = new Vue({
  ...baseConfig(store),
  data() {
    return {
      sliderGradient: false,
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.global();
    this.hideLoading();

    setTimeout(function(){
      var swiper = new Swiper(".mySwiper", {
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
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
    },100)

    this.initTooltips();
    // this.initTooltipsMobile();
  },
  methods: {
    ...baseActions(),
    toggleSlidergradient: function() {
      this.sliderGradient = true
    },
    hideSliderGradient: function() {
      this.sliderGradient = false;
    },
    initTooltips: function(){
      const BASE_CONFIG = {
        allowHTML: true,
        theme: 'mab-secondary',
        inertia: true,
        animation: 'scale-subtle',
        trigger: 'click',
        placement: 'left',
      }

      tippy('#test', {
        content: document.querySelector('#test-template').innerHTML,
        ...BASE_CONFIG
      });
      tippy('#test-2', {
        content: document.querySelector('#test-template-2').innerHTML,
        ...BASE_CONFIG
      });
      tippy('#test-3', {
        content: document.querySelector('#test-template-3').innerHTML,
        ...BASE_CONFIG
      });
    },

    initTooltipsMobile: function(){
      const BASE_CONFIG = {
        allowHTML: true,
        theme: 'mab-secondary',
        inertia: true,
        animation: 'scale-subtle',
        trigger: 'click',
        placement: 'bottom',
      }

      tippy('#test', {
        content: document.querySelector('#test-template').innerHTML,
        ...BASE_CONFIG
      });
      tippy('#test-2', {
        content: document.querySelector('#test-template-2').innerHTML,
        ...BASE_CONFIG
      });
      tippy('#test-3', {
        content: document.querySelector('#test-template-3').innerHTML,
        ...BASE_CONFIG
      });
    },
  }
})

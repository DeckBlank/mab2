import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import Swiper from 'swiper';

const perfil = new Vue({
  ...baseConfig(store),
  data() {
    return {
      modal: false,
      skilss: 1,
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.global();
    this.hideLoading();

    setTimeout(function() {
      let cursos = new Swiper('.c-my-courses .swiper-container', {
        slidesPerView: 3,
        spaceBetween: 0,
        navigation: {
          nextEl: '.c-my-courses .swiper-button-next',
          prevEl: '.c-my-courses .swiper-button-prev',
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
    })


  },
  methods: {
    ...baseActions()
  }
})

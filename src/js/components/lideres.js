import Vue from 'vue';
import Vuex from 'vuex';
import Swiper from 'swiper';

Vue.component('lideres',{
  template: /*html*/`
    <div class="c-slider">
      <div id="slider-lideres" class="c-slider swiper-container margin-bottom-3">
        <div class="swiper-wrapper">
          <div v-for="lider of body" :key="lider.id" class="swiper-slide white">
            <div class="flex-container flex-dir-column align-middle text-center height-100">
              <div class="c-lider-avatar margin-bottom-1 margin-top-1">
                <div class="c-profile-card c-profile-card--primary position-relative height-100 flex-container align-center align-bottom">
                  <figure class="c-profile-card__content position-relative" :class="'padding-horizontal-' + lider.padding">
                    <img class="of--contain" :style="'width: ' + lider.width + 'px;'" :src="lider.avatar" alt="">
                  </figure>
                </div>
              </div>
              <div>
                <h3 class="fs-25 w-xbold dark margin-bottom-0">{{ lider.name }}</h3>
                <p class="fs-18 dark f2 margin-bottom-0">{{ lider.job }}</p>
                <a class="c-button c-button--white-dark f2 w-bold margin-top-1 d-inline-block c-link--ho-dark" :href="SITE_URL + '/lider/' + lider.profile">Leer más</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="c-slider__pagination swiper-pagination"></div>
    </div>
  `,
  data() {
    return { }
  },
  props: ['body'],
  computed: {
    ...Vuex.mapState(['SITE_URL', 'THEME_URL'])
  },
  mounted() {
    this.initLideresSlider();
  },
  methods: {
    initLideresSlider: function() {
      window.setTimeout(() => {
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
      }, 100)
    },
  },
});

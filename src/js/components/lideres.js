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
                <div class="c-profile-card c-profile-card--warning position-relative height-100 flex-container align-center align-bottom">
                  <figure class="c-profile-card__content position-relative" :class="'padding-horizontal-' + lider.padding">
                    <img class="of--contain" :style="'width: ' + lider.width + 'px;'" :src="THEME_URL + '/static/images/home/leaders/' + lider.avatar + '.png'" alt="">
                  </figure>
                </div>
              </div>
              <div>
                <h3 class="fs-25 f2 w-xbold white margin-bottom-0">{{ lider.name }}</h3>
                <p class="fs-18 white f2 margin-bottom-0">{{ lider.job }}</p>
                <a class="c-link c-link--warning f2 w-bold" :href="lider.profile">Leer más</a>
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
    ...Vuex.mapState(['THEME_URL'])
  },
  mounted() {
    this.initLideresSlider();
  },
  methods: {
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
  },
});

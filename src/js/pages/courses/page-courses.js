import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../../app'
import {store} from '../../store'
import Swiper from 'swiper'

const courses = new Vue({
  ...baseConfig(store),
  data() {
    return {
      levels: [
        {
          name: 'Bebés',
          slug: 'bebes',
          grades: [
            {
              name: '0 - 12 Meses',
              slug: '0_12_months',
            },
            {
              name: '1 - 2 Años',
              slug: '1_2_years',
            },
          ],
        },
        {
          name: 'Inicial',
          slug: 'inicial',
          grades: [
            {
              name: '3 Años',
              slug: '3_years',
            },
            {
              name: '4 Años',
              slug: '4_years',
            },
            {
              name: '5 Años',
              slug: '5_years',
            },
          ],
        },
        {
          name: 'Primaria',
          slug: 'primaria',
          grades: [
            {
              name: '1ero',
              slug: '1',
            },
            {
              name: '2do',
              slug: '2',
            },
            {
              name: '3ro',
              slug: '3',
            },
            {
              name: '4to',
              slug: '4',
            },
            {
              name: '5to',
              slug: '5',
            },
            {
              name: '6to',
              slug: '6',
            },
          ],
        },
        {
          name: 'Secundaria',
          slug: 'secundaria',
          grades: [
            {
              name: '1ero',
              slug: '1',
            },
            {
              name: '2do',
              slug: '2',
            },
            {
              name: '3ro',
              slug: '3',
            },
            {
              name: '4to',
              slug: '4',
            },
            {
              name: '5to',
              slug: '5',
            },
          ],
        },
        {
          name: 'Adultos',
          slug: 'adultos',
          grades: [
            {
              name: 'Arte y creatividad',
              slug: 'arte_y_creatividad',
            },
            {
              name: 'Movimiento',
              slug: 'movimiento',
            },
            {
              name: 'Cocina',
              slug: 'cocina',
            },
            {
              name: 'Política',
              slug: 'politica',
            },
            {
              name: 'Emociones y más',
              slug: 'emociones_y_mas',
            },
          ],
        },
      ],

      filter: {
        level: -1,
        grade: -1,
        word: -1,
      },
    }
  },
  computed: {
    ...baseState(),
    grades: function() {
      return (this.filter.level != -1) ? this.levels.find(lev => lev.slug == this.filter.level).grades : [];
    },
  },
  watch: {
    'filter.level': function() {
      this.filter.grade = -1;
    },
  },
  mounted(){
    this.global();
    this.hideLoading();

    setTimeout(function() {
      let courseContinue = new Swiper('.c-mab-recommended .swiper-container', {
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
      let category = new Swiper('.c-category-list .swiper-container', {
        slidesPerView: 3,
        spaceBetween: 0,
        navigation: {
          nextEl: '.c-category-list .swiper-button-next',
          prevEl: '.c-category-list .swiper-button-prev',
        },
        breakpoints: {
          // when window width is >= 320px
          200: {
            slidesPerView: 2,
            spaceBetween: 10
          },
          640: {
            slidesPerView: 3,
            spaceBetween: 0,
          },
          768: {
            slidesPerView: 4,
            spaceBetween: 0
          },
          1024: {
            slidesPerView: 5,
            spaceBetween: 0
          }
        }
      });

    },1000)
  },
  methods: {
    ...baseActions(),
  }
})

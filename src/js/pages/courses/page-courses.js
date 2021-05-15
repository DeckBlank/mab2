import Vue from 'vue'
import Swiper from 'swiper'
import { mapState } from 'vuex'

import {baseConfig, baseState, baseActions} from '../../app';
import {store} from '../../store';
import {addCourseToShopCart} from '../../libs/shop-cart';

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
          slug: 'Secundaria',
          grades: [
            {
              name: '1ero',
              slug: 'I',
            },
            {
              name: '2do',
              slug: 'II',
            },
            {
              name: '3ro',
              slug: 'III',
            },
            {
              name: '4to',
              slug: 'IV',
            },
            {
              name: '5to',
              slug: 'V',
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
        search: '',
      },

      recommendCourses: [],
      courses: [],
      page: 1,
      hasPagination: false,

      isLoadingCategories: true,
      isLoadingRecommended: true,
      isLoadingCourses: false,
    }
  },
  computed: {
    ...baseState(),
    ...mapState(['THEME_URL']),
    grades: function() {
      return (this.filter.level != -1) ? this.levels.find(lev => lev.slug == this.filter.level).grades : [];
    },
    courseBasicThumbnail: function() {
      return `${ this.THEME_URL }/static/images/og_image.png`;
    },
    coursesQuery: function() {
      let query = '';

      if (this.filter.level && this.filter.level != -1) query += `&level=${ this.filter.level }`;
      if (this.filter.grade && this.filter.grade != -1) query += `&grade=${ this.filter.grade }`;
      if (this.filter.search) query += `&search=${ this.filter.search }`;

      return query;
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

    this.getRecommendedCoures();
    this.getCourses();

    setTimeout(function() {
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
    initSliderRecommendedCourses: function() {
      window.setTimeout(() => {
        new Swiper('.c-mab-recommended .swiper-container', {
          slidesPerView: 3,
          spaceBetween: 0,
          navigation: {
            nextEl: '.c-mab-recommended .swiper-button-next',
            prevEl: '.c-mab-recommended .swiper-button-prev',
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
          },
          on: {
            init: () => {
              this.isLoadingRecommended = false;
            },
          },
        });
      }, 1000);
    },
    getRecommendedCoures: function() {
      fetch(`${ this.API }/users/${ this.logedUser.user_id }/courses/recommended?user_email=${ this.logedUser.user_email }&_wpnonce=${ mab.nonce }`)
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

          this.initSliderRecommendedCourses();
      })
      .catch(err => {
        throw err;
      })
    },
    getCourses: function(from = false) {
      this.isLoadingCourses = true;

      if (from == 'search') {
        this.page     = 1;
        this.courses  = [];

      } else if (from == 'level' || from == 'grade') {
        this.hasPagination  = false;
        this.page           = 1;
        this.courses        = [];
      }

      fetch(`${ this.API }/courses/all?_wpnonce=${ mab.nonce }${ this.coursesQuery }${ (this.hasPagination) ? '&paged=' + this.page : '' }`)
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(response => {
        if (response.status) {
          this.courses = this.courses.concat(response.data);

          if (response.pagination) {
            this.hasPagination = true;
            this.page++;
          } else {
            this.hasPagination = false;
            this.page = 0;
          }
        } else {
          this.hasPagination = false;
          this.page = 0;
        }

        window.setTimeout(() => {
          this.isLoadingCourses = false;
        }, 1000);
      })
      .catch(err => {
        window.setTimeout(() => {
          this.isLoadingCourses = false;
        }, 1000);

        throw err;
      })
    },

    addCourse: function(course_id, course_title, course_link){
      addCourseToShopCart(course_id, course_title, course_link, this.SITE_URL)
    },
  }
})

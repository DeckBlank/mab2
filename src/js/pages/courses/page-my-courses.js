import Vue from 'vue'
import { mapState } from 'vuex';
import {store} from '../../store';
import Swiper from 'swiper'

import {baseConfig, baseState, baseActions} from '../../app';

import '../../components/courses/course-enroll';
import '../../components/courses/course';
import '../../components/lideres';

new Vue({
  ...baseConfig(store),
  data() {
    return {
      enrolledCourses: [],
      enrolledGroupCourses: {},
      enrolledCoursesPaged: 1,
      isLoadingEnroll: true,

      recommendCourses: [],
      recommendGroupCourses: {},
      recommendCoursesPaged: 1,
      isLoadingRecommend: true,

      lideres: [],
    }
  },
  computed: {
    ...baseState(),
    ...mapState(['THEME_URL']),
    userName: function() {
      let firstname = this.logedUser.user_firstname.split(' ');

      return (firstname.length) ? firstname[0] : this.logedUser.user_firstname;
    },
  },
  mounted(){
    this.global();
    this.hideLoading();
    this.getEnrolledCourses();
    this.getRecommendCourses();

    this.lideres = mab.leaders;

    setTimeout(function() {
      new Swiper('.c-other-services .swiper-container', {
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
    getEnrolledCourses: function() {
      this.isLoadingEnroll = true;

      fetch(`${ this.API }/users/${ this.logedUser.user_id }/courses?user_email=${ this.logedUser.user_email }&_wpnonce=${ mab.nonce }&paged=${ this.enrolledCoursesPaged }`)
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(response => {
        if (response.status) {
          this.enrolledGroupCourses[ this.enrolledCoursesPaged ] = response.data;
          this.enrolledCourses = response.data;

          this.isLoadingEnroll = false;
        } else {
          window.setTimeout(() => {
            this.isLoadingEnroll = false;
          }, 1000);
        }
      })
      .catch(err => {
        window.setTimeout(() => {
          this.isLoadingEnroll = false;
        }, 1000);

        throw err;
      })
    },
    navigateEnroll: function(direction) {
      let paged = ( direction == 'right' )
        ? this.enrolledCoursesPaged + 1
        : this.enrolledCoursesPaged - 1;

      if ( !this.enrolledGroupCourses[paged] ) {
        this.enrolledCoursesPaged = paged;
        this.isLoadingEnroll      = true;

        this.getEnrolledCourses();
      } else {
        this.isLoadingEnroll      = true;
        this.enrolledCoursesPaged = paged;
        this.enrolledCourses      = this.enrolledGroupCourses[paged];

        window.setTimeout(() => {
          this.isLoadingEnroll = false;
        }, 1000)
      }
    },

    getRecommendCourses: function() {
      this.isLoadingRecommend = true;

      fetch(`${ this.API }/users/${ this.logedUser.user_id }/courses/recommended?user_email=${ this.logedUser.user_email }&_wpnonce=${ mab.nonce }&paged=${ this.recommendCoursesPaged }`)
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(response => {
        if (response.status) {
          this.recommendGroupCourses[ this.recommendCoursesPaged ] = response.data;
          this.recommendCourses = response.data;

          this.isLoadingRecommend = false;
        } else {
          window.setTimeout(() => {
            this.isLoadingRecommend = false;
          }, 1000);
        }
      })
      .catch(err => {
        window.setTimeout(() => {
          this.isLoadingRecommend = false;
        }, 1000);

        throw err;
      })
    },
    navigateRecommend: function(direction) {
      let paged = ( direction == 'right' )
        ? this.recommendCoursesPaged + 1
        : this.recommendCoursesPaged - 1;

      if ( !this.recommendGroupCourses[paged] ) {
        this.recommendCoursesPaged = paged;
        this.isLoadingRecommend      = true;

        this.getRecommendCourses();
      } else {
        this.isLoadingRecommend      = true;
        this.recommendCoursesPaged = paged;
        this.recommendCourses      = this.recommendGroupCourses[paged];

        window.setTimeout(() => {
          this.isLoadingRecommend = false;
        }, 1000)
      }
    },
  }
})

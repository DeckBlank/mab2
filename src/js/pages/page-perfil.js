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

      tests: {
        behaviour: false,
        learning: false,
      },

      isLoadingEnroll: true,
      enrolledCourses: [],
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.global();
    this.hideLoading();

    this.getEnrolledCourses();
    this.getTestLearning();
    this.getTestBehaviour();
  },
  methods: {
    ...baseActions(),
    initSliderEnrolledCourses: function() {
      window.setTimeout(() => {
        new Swiper('.c-my-courses .swiper-container', {
          slidesPerView: 3,
          spaceBetween: 0,
          navigation: {
            nextEl: '.c-my-courses .swiper-button-next',
            prevEl: '.c-my-courses .swiper-button-prev',
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
              this.isLoadingEnroll = false;
            },
          },
        });
      }, 1000)
    },

    getTestLearning: function(){
      fetch(`${this.API}/test?user=${this.logedUser.user_email}`,{
        method: 'GET'
      })
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json()
        }else{
          throw res
        }
      })
      .then(result => {
        this.tests.learning = true;
      })
      .catch(err => {
        throw err;
      })      
    },
    getTestBehaviour: function(){
      fetch(`${this.API}/test/behaviour?user=${this.logedUser.user_email}`,{
        method: 'GET'
      })
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json()
        }else{
          throw res
        }
      })
      .then(result => {
        this.tests.behaviour = true;
      })
      .catch(err => {
        throw err;
      })      
    },

    getEnrolledCourses: function() {
      fetch(`${ this.API }/users/${ this.logedUser.user_id }/courses?user_email=${ this.logedUser.user_email }&_wpnonce=${ mab.nonce }`)
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(response => {
        if (response.status) {
          this.enrolledCourses = response.data;

          this.initSliderEnrolledCourses();
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
  }
})

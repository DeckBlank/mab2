import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../../app'
import {store} from '../../store'
import Swiper from 'swiper';
import { mapState } from 'vuex';

import '../../components/courses/course-enroll';

new Vue({
  ...baseConfig(store),
  data() {
    return {
      userId: -1,
      showShareBox: false,

      user: {
        try: false,
        profile: {},

        isLoading: false,

        firstname: {
          value: '',
          pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{1,70}$",
          isValid: false,
        },
        fatherName: {
          value: '',
          pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{1,70}$",
          isValid: false,
        },
        motherName: {
          value: '',
          pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{1,70}$",
          isValid: false,
        },
        phrase: {
          value: '',
          isValid: false,
        },

        isModalOpened: false,
      },
      avatar: {
        source: '',
        isLoadingAvatar: false,
      },

      skills: {
        view: 1,
        try: false,
        isLoading: false,

        soft: {
          options: [
            'Optimización del tiempo',
            'Pensamiento crítico',
            'Relaciones Interpersonales',
            'Perseverancia',
            'Auto-conocimiento',
            'Solución de problemas y conflictos',
            'Actitud positiva',
            'Manejo de emociones',
            'Toma de decisiones',
            'Manejo de tensión y estrés',
            'Empatía',
            'Seguridad',
            'Comunicación asertiva',
            'Motivación',
            'Pensamiento creativo',
          ],
        },
        hard: {
          options: [
            'Deporte',
            'Ciencias',
            'Historia y geografía',
            'Música',
            'Arte',
            'Agricultura',
            'Idiomas',
            'Gastronomía',
            'Lectura y escritura',
            'Tecnología',
            'Habilidad numérica',
            'Baile y movimiento',
            'Textilería',
            'Oratoria y exposición',
            'Creación y construcción'
          ],
        },

        values: {
          soft: {
            value: [],
            isValid: false,
          },
          hard: {
            value: [],
            isValid: false,
          },
        }
      },

      tests: {
        behaviour: false,
        learning: false,
      },

      enrolledCourses: [],
      enrolledGroupCourses: {},
      enrolledCoursesPaged: 1,
      isLoadingEnroll: true,

      isOpenedModalCongrats: false,

      device: '',
    }
  },
  computed: {
    ...baseState(),
    ...mapState(['THEME_URL']),
    courseBasicThumbnail: function() {
      return `${ this.THEME_URL }/static/images/og_image.png`;
    },

    userFullName: function() {
      return `${ this.user.firstname.value } ${ this.user.fatherName.value } ${ this.user.motherName.value }`;
    },
    userRole: function() {
      const role = '';

      switch (this.logedUser.user_rol) {
        case 'student':
          role = 'Estudiante';
          break;

        case 'teacher':
        case 'mab-teacher':
          role = 'Profesor';
          break;

        case 'tutor':
          role = 'Padre/Madre/Apoderad@';
          break;

        case 'mab-leader':
          role = 'Lider';
          break;

        case 'mab-speaker':
          role = 'Speaker';
          break;

        default:
          role = 'Estudiante';
          break;
      }

      return role;
    },
    userBasicAvatar: function() {
      return `${ this.THEME_URL }/static/images/user.png`;
    },

    isProfileOwner: function() {
      return (this.logedUser && this.logedUser.user_id == this.userId) ? true : false;
    },

    userProfile: function() {
      return `${ this.SITE_URL }/user/${ this.logedUser.user_nicename }`;
    },
  },
  watch: {
    'user.firstname.value': function() {
      this.user.firstname.isValid = this.validateText(this.user.firstname);
    },
    'user.fatherName.value': function() {
      this.user.fatherName.isValid = this.validateText(this.user.fatherName);
    },
    'user.motherName.value': function() {
      this.user.motherName.isValid = this.validateText(this.user.motherName);
    },
    'user.phrase.value': function() {
      this.user.phrase.isValid = (this.user.phrase.value.length > 5) ? true : false;
    },

    'skills.values.soft.value': function() {
      this.skills.values.soft.isValid = (this.skills.values.soft.value.length == 5) ? true : false;
    },
    'skills.values.hard.value': function() {
      this.skills.values.hard.isValid = (this.skills.values.hard.value.length == 5) ? true : false;
    },
  },
  mounted(){
    this.global();

    this.userId = document.querySelector('#profile').getAttribute('data-user');

    this.getProfile();

    if (this.logedUser) {
      this.getEnrolledCourses();
      this.getTestLearning();
      this.getTestBehaviour();
    }

    let breakpoint = window.matchMedia('(min-width: 1024px)');

    this.initShare();
    this.checkDevice(breakpoint); breakpoint.addEventListener('change', this.checkDevice);
  },
  methods: {
    ...baseActions(),
    validateText: function(parameter) {
      let pattern = new RegExp( parameter.pattern );
      let value   = parameter.value.trim()

      if(pattern.test(value)){
        return true;
      }else{
        return false
      }
    },
    isWrongField: function(field) {
      return this.user.try && !this.user[field].isValid;
    },
    isWrongFieldHability: function(field) {
      return this.skills.try && !this.skills.values[field].isValid;
    },
    checkDevice: function(breakpoint) {
      if (breakpoint.matches) {
        this.device = 'desktop';
      } else {
        this.device = 'mobile';
      }
    },

    observePhraseInput: function(evt) {
      if (this.user.phrase.value.length > 42) {
        if (evt.keyCode >= 48 && evt.keyCode <= 90) {
          evt.preventDefault()
          return
        }
      }
    },

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

    getProfile: function() {
      fetch(`${this.API}/users/${ this.userId }/profile?_wpnonce=${ mab.nonce }`)
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(response => {
        if (response.status) {
          this.user.profile = response.data;

          this.user.firstname.value = response.data.user_firstname;

          const lastname = response.data.user_lastname.split('-panda-');

          if (lastname.length == 2) {
            this.user.fatherName.value  = lastname[0];
            this.user.motherName.value  = lastname[1];
          } else if (lastname.length) {
            this.user.fatherName.value  = response.data.user_lastname.substr(0, response.data.user_lastname.indexOf(' '));
            this.user.motherName.value  = response.data.user_lastname.substr(response.data.user_lastname.indexOf(' ') + 1);
          }

          this.skills.values.soft.value = response.data.habilites.soft;
          this.skills.values.hard.value = response.data.habilites.hard;

          if (response.data.phrase) {
            this.user.phrase.value = response.data.phrase;
          }

          this.hideLoading();
        }
      })
      .catch(err => {
        throw err;
      }) 
    },
    updateProfileData: function() {
      this.user.try = true;

      if (
        this.user.firstname.isValid &&
        this.user.fatherName.isValid &&
        this.user.motherName.isValid &&
        this.user.phrase.isValid
      ) {
        this.user.isLoading = true;

        const formData = new FormData();

        formData.append('_wpnonce', mab.nonce);

        fetch(`${this.API}/users/${ this.logedUser.user_id }/profile?_wpnonce=${ mab.nonce }`,{
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            firstname: this.user.firstname.value,
            father_name: this.user.fatherName.value,
            mother_name: this.user.motherName.value,
            phrase: this.user.phrase.value,
          }),
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json();
          } else {
            throw res;
          }
        })
        .then(data => {
          if (data.status) {
            this.user.profile = {
              ...this.user.profile,
              firstname: this.user.firstname.value,
              father_name: this.user.fatherName.value,
              mother_name: this.user.motherName.value,
              phrase: this.user.phrase.value
            };

            this.user.isModalOpened = false;
          }
        })
        .catch(err => {
          throw err;          
        })
        .finally(() => {
          this.user.isLoading = false;
        })
      }
    },
    updateProfileHabilities: function(e) {
      this.skills.try = true;

      if (
        this.skills.values.soft.isValid &&
        this.skills.values.soft.isValid
      ) {
        this.skills.isLoading = true;

        fetch(`${this.API}/users/${ this.logedUser.user_id }/profile/habilities?_wpnonce=${ mab.nonce }`,{
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            soft: this.skills.values.soft.value.join(','),
            hard: this.skills.values.hard.value.join(','),
          }),
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json();
          } else {
            throw res;
          }
        })
        .then(data => {
          if (data.status) {
            this.skills.view = 1;
            this.isOpenedModalCongrats = true;
          }
        })
        .catch(err => {
          throw err;          
        })
        .finally(() => {
          this.skills.isLoading = false;
        })
      }
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

    changeAvatarImage: function(event) {
      this.avatar.source = event.target.files[0];

      if (this.avatar.source) {
        this.updateAvatar();
      } else {
        this.avatar.source = '';
      }
    },
    updateAvatar: function() {
      this.avatar.isLoadingAvatar = true;

      const formData = new FormData();

      formData.append('avatar', this.avatar.source);
      formData.append('_wpnonce', mab.nonce);

      fetch(`${this.API}/users/${ this.logedUser.user_id }/profile/avatar`,{
        method: 'POST',
        body: formData,
      })
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(data => {
        if (data.status && data.avatar) {
          this.user.profile.avatar = {
            url: data.avatar
          };
        }
      })
      .catch(err => {
        throw err;          
      })
      .finally(() => {
        this.avatar.isLoadingAvatar = false;
      })
    },

    openShare: function() {
      this.showShareBox = true;
      this.isOpenedModalCongrats = true;
    },
    initShare: function() {
      this.social = {
        whatsapp: `whatsapp://send?text=Hola, te comparto mis habilidades ${ this.userProfile }`,
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${ this.userProfile }`,
        linkedin: `http://www.linkedin.com/shareArticle?mini=true&url=${ this.userProfile }`,
        twitter: `https://twitter.com/share?text=Hola, te comparto mis habilidades&url=${ this.userProfile }`,
      }
    },
    copyProfileLink: function(e) {
      e.preventDefault();

      let linkToCopy = document.querySelector('#user-profile-link');

      linkToCopy.setAttribute('type', 'text');
      linkToCopy.select();

      document.execCommand('copy');

      linkToCopy.setAttribute('type', 'hidden');
    },
  }
})

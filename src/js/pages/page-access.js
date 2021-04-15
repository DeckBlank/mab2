import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import Swiper from 'swiper'

new Vue({
  ...baseConfig(store),
  data() {
    return {
      metas: new URLSearchParams(window.location.search),
      view: 'login',
      gallery: {
        login: {
          img: 'access/auth/login.png',
          title: '<span>¡Hola!</span><br><p>Qué gusto verte por aquí de nuevo</p>',
          author: {
            name: 'Macarena Arribas',
            job: 'Fundadora de MAB'
          },
        },
        register: {
          img: 'access/auth/login.png',
          title: '<span>¡Hola, soy Maca!</span><p>¿Estás listo para girar la educación?</p>',
          author: {
            name: 'Macarena Arribas',
            job: 'Fundadora de MAB'
          },
        },
        steps: [
          {
            img: 'access/steps/0.png',
            sticker: 'access/sticker.png',
            title: '<p>ganadora de los <span>globant awards 2020</span> categoría <span>game changer</span></p>',
          },
          {
            img: 'access/steps/1.png',
            author: {
              name: 'Viviana de Ferrari',
              job: 'Amor Propio'
            },
            title: '<p>aprende con <span>especialistas</span> reconocidos a nivel <span>nacional e internacional</span></p>',
          },
          {
            img: 'access/steps/2.png',
            title: '<p>tutores que han <span>pasado por lo mismo</span> que tú</p>',
          },
          {
            img: 'access/steps/3.png',
            title: '<p>¡ya casi estás listo/a para comenzar a aprender distinto!</p>',
          }
        ],
        slider: [
          {
            img: 'access/slider/1.png',
            author: {
              name: 'Proyecto “De Tambo a Tambo”',
            },
            title: '<span>mucho más que una plataforma de cursos</span> <p>conoce todos nuestros proyectos y servicios que tenemos para ti</p>',
          },
          {
            img: 'access/slider/2.png',
            author: {
              name: 'Tutorías personalizadas',
            },
            title: '<span>mucho más que una plataforma de cursos</span> <p>conoce todos nuestros proyectos y servicios que tenemos para ti</p>',
          },
          {
            img: 'access/slider/3.png',
            author: {
              name: 'Talleres, programa Kids',
            },
            title: '<span>mucho más que una plataforma de cursos</span> <p>conoce todos nuestros proyectos y servicios que tenemos para ti</p>',
          },
        ]
      },

      countries: [],
      cities: [],

      categories: [],
      subcategories: [],

      user: {
        loading: false,
        try: false,
        error: '',

        email: {
          value: 'hola@g.com',
          pattern: "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$",
          // isValid: false,
          isValid: true,
        },
        password: {
          value: '12345',
          // isValid: false,
          isValid: true,
        },
        terms: true,
        // terms: false,

        sector: {
          value: 'publico',
          isValid: true,
        },

        role: {
          type: '',
          isValid: false,
          extension: '',
        },

        categories: {
          value: [],
          isValid: false,
        },
        subcategories: {
          value: [],
          isValid: false,
        },
      },

      profile : {
        name: {
          value: '',
          pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{1,70}$",
          isValid: false
        },
        father_name: {
          value: '',
          pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{1,70}$",
          isValid: false
        },
        mother_name: {
          value: '',
          pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{1,70}$",
          isValid: false
        },
        date_birth: {
          value: '',
          isValid: false
        },
        date_birth: {
          value: '',
          isValid: false
        },
        country: {
          value: '',
          isValid: false,
          isLoading: false,
        },
        city: {
          value: '',
          isValid: false,
          isLoading: false,
        },
        phone: {
          value: '',
          pattern: "^[0-9]{5,15}$",
          isValid: false
        },
      },

      steps: [
        { isWrong : false },
      ],

      modals: {
        teacher : {
          isOpened : false,
          students: [ '1', '2', '3', '4', '5+'],
          data: {
            try : false,
            students: {
              value: '',
              isValid: false
            }
          }
        },
        tambero : {
          isOpened : false,
          data: {
            try : false,
            students: {
              value: '',
              isValid: false
            }
          }
        },
      },
    }
  },
  computed: {
    ...baseState(),
    callingCode: function() {
      return (this.profile.country.value) ? `+${ this.profile.country.value.callingCodes[0] }` : '+XXX';
    },
  },
  watch: {
    'view': function() {
      if(this.view == 'step-4') {
        setTimeout(() => {
          new Swiper('#slider-mab', {
            autoplay: {
              delay: 3000,
            },
            pagination: {
              el: '.c-image-slider .swiper-pagination',
            },
          });
        },1000)
      }
    },

    'user.email.value' : function() {
      this.user.email.isValid = this.validateText(this.user.email);
    },
    'user.password.value' : function() {
      if (this.view == 'login') {
        this.user.password.isValid = (this.user.password.value.length && this.user.password.value.length >= 1) ? true : false;
      } else {
        this.user.password.isValid = (this.user.password.value.length && this.user.password.value.length >= 5) ? true : false;
      }
    },

    'user.sector.value' : function() {
      this.validateSelect(this.user.sector);
    },

    'profile.name.value' : function() {
      this.profile.name.isValid = this.validateText(this.profile.name);
    },
    'profile.father_name.value' : function() {
      this.profile.father_name.isValid = this.validateText(this.profile.father_name);
    },
    'profile.mother_name.value' : function() {
      this.profile.mother_name.isValid = this.validateText(this.profile.mother_name);
    },
    'profile.date_birth.value' : function() {
      this.validateSelect(this.profile.date_birth);
    },
    'profile.country.value' : function(country) {
      this.validateSelect(this.profile.country);

      if (country.alpha2Code == 'PE') this.getCities();
    },
    'profile.city.value' : function() {
      this.validateSelect(this.profile.city);
    },
    'profile.phone.value' : function() {
      this.validateSelect(this.profile.phone);
    },

    'user.role.type' : function(role) {
      this.validateSelect(this.user.role, 'type');
    },

    'user.categories' : {
      handler: function() {
        this.user.categories.isValid = (this.user.categories.value.length && this.user.categories.value.length >= 3) ? true : false;

        this.getSubcategories();
      },
      deep : true,
    },
    'user.subcategories' : {
      handler: function() {
        this.user.subcategories.isValid = (this.user.subcategories.value.length && this.user.subcategories.value.length >= 3) ? true : false;
      },
      deep : true,
    },

    'modals.teacher.data.students.value' : function() {
      this.validateSelect(this.modals.teacher.data.students);
    },
  },
  mounted(){
    this.global();
    this.hideLoading();
    this.getCountries();
    this.getCategories();

    if (this.metas.get('auth') && this.metas.get('auth') == 'register')
      this.view = 'step-0';
      // this.view = 'register';

    this.initFacebook();
  },
  methods: {
    ...baseActions(),
    getCountries: function(){
      fetch(`https://restcountries.eu/rest/v2/all`)
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json();
          }else{
            throw res;
          }
        })
        .then(countries => {
          this.countries = countries;
        })
        .catch(err => {
          throw err;          
        })      
    },
    getCities: function() {
      fetch(`${ this.API }/geo?_wpnonce=${ mab.nonce }`)
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        }else{
          throw res
        }
      })
      .then(response => {
        if (response.status) {
          this.cities = response.data;
        }
      })
      .catch(err => {
        throw err;
      })
    },
    getCategories: function() {
      fetch(`${ this.API }/courses/mab_categories?_wpnonce=${ mab.nonce }`)
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        }else{
          throw res
        }
      })
      .then(response => {
        if (response.status) {
          this.categories = response.data;
        }
      })
      .catch(err => {
        throw err;
      })
    },
    getSubcategories: function() {
      fetch(`${ this.API }/courses/mab_subcategories?categories=${ this.user.categories.value.join(',') }&_wpnonce=${ mab.nonce }`)
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        }else{
          throw res
        }
      })
      .then(response => {
        if (response.status) {
          this.subcategories = response.data;
        }
      })
      .catch(err => {
        throw err;
      })
    },

    validateText: function(parameter) {
      let pattern = new RegExp( parameter.pattern );
      let value   = parameter.value.trim()

      if(pattern.test(value)){
        return true;
      }else{
        return false
      }
    },
    validateSelect(parameter, field = 'value') {
      if(parameter[field] != '' && parameter.isValid == false){
        parameter.isValid = true
      }
    },
    isWrongField: function(field, mutiple = true, register = 'user') {
      if (mutiple) {
        return this.user.try && !this[register][field].isValid;
      } else {
        return this.user.try && !this[register][field];
      }
    },
    isWrongModalField: function(field, register) {
      return this.modals[register].data.try && !this.modals[register].data[field].isValid;
    },

    validateStep: function(step) {
      let isValid = false;

      this.user.try = true;

      switch (step) {
        case 0: {
            isValid = this.user.sector.isValid;
          }
          break;

        case 1: {
            isValid = this.profile.name.isValid &&
              this.profile.father_name.isValid &&
              this.profile.mother_name.isValid &&
              this.profile.date_birth.isValid &&
              this.profile.country.isValid &&
              ( (this.profile.country.value && this.profile.country.value.alpha2Code == 'PE')
                ? this.profile.city.isValid
                : true 
              ) &&
              this.profile.phone.isValid;
          }
          break;

        case 2: {
            isValid = this.user.role.isValid;
          }
          break;

        case 3: {
            isValid = this.user.categories.isValid;
          }
          break;

        case 4: {
            isValid = this.user.subcategories.isValid;
          }
          break;
      }

      return isValid;
    },
    nextStep: function(step) {
      switch (step) {
        case 1:
          if ( !this.validateStep(0) ) return true;

          this.view     = `step-${ step }`;
          this.user.try = false;
          break;

        case 2:
          if ( !this.validateStep(1) ) return true;

          this.view     = `step-${ step }`;
          this.user.try = false;
          break;

        case 3:
          if ( !this.validateStep(2) ) return true;

          this.view     = `step-${ step }`;
          this.user.try = false;
          break;

        case 4:
          if ( !this.validateStep(3) ) return true;

          this.view     = `step-${ step }`;
          this.user.try = false;
          break;
      }
    },
    prevStep: function (step) {
      if (step == -1) {
        window.location.href = `${ this.SITE_URL }/access?auth=register`;
      } else {
        this.view = `step-${ step }`;
      }
    },
    validateModal: function(role) {
      if (role == 'teacher') {
        this.modals.teacher.data.try = true;

        if (this.modals.teacher.data.students.isValid) {
          this.modals.teacher.isOpened = false;
          this.view = 'step-3';
        }
      } else {

      }
    },

    login: function(e) {
      e.preventDefault();

      this.user.try = true;

      if (this.user.email.isValid && this.user.password.isValid) {
        this.user.isLoading = true;

        fetch(`${ this.API }/auth/login?email=${ this.user.email.value }&password=${ this.user.password.value }&_wpnonce=${ mab.nonce }`,{
            method: 'GET'
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json();
            }else{
              throw res;
            }
          })
          .then(user => {
            // saveUserLoginSession(user)
            window.location = `${this.SITE_URL}/mis-cursos`;
          })
          .catch(err => {
            this.user.error     = 'El usuario o contraseña son incorrectos';
            this.user.isLoading = false; throw err;          
          })
      }
    },
    register: function(e, step = 'init') {
      e.preventDefault();

      if (step == 'init') {
        this.user.try = true;

        if (this.user.email.isValid && this.user.password.isValid && this.user.terms) {
          this.view = 'step-0';
        }
      } else {
        if ( this.validateStep(4) ) {
          this.user.isLoading = true;

          const formData = new FormData();

          formData.append('email', this.user.email.value);
          formData.append('password', this.user.password.value);
          formData.append('sector', this.user.sector.value);
          formData.append('profile', JSON.stringify({
            name : this.profile.name.value,
            father_name : this.profile.father_name.value,
            mother_name : this.profile.mother_name.value,
            date_birth : this.profile.date_birth.value,
            country : this.profile.country.value.alpha2Code,
            city : this.profile.city.value,
            phone: {
              number: this.profile.phone.value,
              code: this.profile.country.value.callingCodes[0],
            },
          }));
          formData.append('role', JSON.stringify({
            type : this.role.type,
            extension : 0,
          }));
          formData.append('category', this.user.categories.value.join(','));
          formData.append('subcategory', this.user.subcategories.value.join(','));
          formData.append('_wpnonce', mab.nonce);

          fetch(`${this.API}/course/request`, {
              method: 'POST',
              body: formData
            })
            .then(res => {
              if (res.status >= 200 && res.status < 300) {
                this.user.isLoading = false; this.isSentForm = true;
              }else{
                throw res;
              }
            })
            .catch(err => {
              this.user.isLoading = false; throw err;          
            })
        }
      }
    },
    initFacebook: function() {
      window.fbAsyncInit = function() {
        FB.init({
          appId   : mab.facebook_id,
          cookie  : true,
          xfbml   : true,
          version : 'v2.11'
        });

        FB.AppEvents.logPageView();   
      };

      (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    },
    authWithGoogle: function() {
      this.user.isLoading = true;

      gapi.load('auth2', () => {
        const auth2 = gapi.auth2.init({
          client_id: mab.google_id,
          fetch_basic_profile: true,
          scope: 'profile',
        });

        auth2.signIn().then(user => {
          const profile   = user.getBasicProfile();
          const formData  = new FormData();

          formData.append('email', profile.getEmail());
          formData.append('_wpnonce', mab.nonce );

          fetch(`${ this.API }/auth/social`, {
            method: 'POST',
            body: formData,
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json();
            }else{
              throw res;
            }
          })
          .then(response => {
            if (response.status) {
              // saveUserLoginSession(user)
              // window.location = `${this.SITE_URL}/mis-cursos`;
            } else {
              this.view = 'step-0';
            }
          })
          .catch(err => {
            this.user.error     = 'El usuario o contraseña son incorrectos';
            this.user.isLoading = false; throw err;          
          })
          // console.log('Full Name: '   + profile.getName());
          // console.log('Given Name: '  + profile.getGivenName());
          // console.log('Family Name: ' + profile.getFamilyName());
        }, (err) => {
          console.log(err);
        })
      })
    },
    authWithFacebook: function() {
      FB.getLoginStatus((response) => {
        if (response.status == 'connected') {
          const token = response.authResponse.accessToken;

          this.handleFacebookAuth('login', token);
        } else if(response.status == 'authorization_expired') {
          FB.login((response) => {
            if (response.authResponse) {
              const token = response.authResponse.accessToken;

              this.handleFacebookAuth('login', token);
            } else {
              // this.auth.isLoading     = false;
              // this.auth.error.active  = true;
            }
          }, {scope: 'public_profile,email', auth_type: 'reauthorize'});
        } else {
          FB.login((response) => {
            if (response.authResponse) {
              this.handleFacebookAuth('login', response.authResponse.accessToken);
            } else {
              // this.auth.isLoading     = false;
              // this.auth.error.active  = true;
              // this.auth.error.message = 'No se ha podido completar el proceso.';
            }
          }, {scope: 'public_profile,email'});
        }
      });
    },
    handleFacebookAuth: function(type, token) {
      console.log(type)
      console.log(token)
    },
  }
})

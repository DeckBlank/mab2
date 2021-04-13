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

      user: {
        try: false,
        email: {
          value: '',
          pattern: "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$",
          isValid: false
        },
        password: {
          value: '',
          isValid: false,
        }
      }
    }
  },
  computed: {
    ...baseState()
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
      this.user.password.isValid = (this.user.password.value.length && this.user.password.value.length >= 5) ? true : false;
    }
  },
  mounted(){
    this.global();
    this.hideLoading();

    if (this.metas.get('auth') && this.metas.get('auth') == 'register')
      this.view = 'register';

    this.initFacebook();
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
    nextStep: function (step) {
      this.view = `step-${ step }`;
    },
    prevStep: function (step) {
      if (step == -1) {
        window.location.href = `${ this.SITE_URL }/access?auth=register`;
      } else {
        this.view = `step-${ step }`;
      }
    },
    login: function(e) {
      e.preventDefault();
      
      this.user.try = true;
      console.log(333);
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
      gapi.load('auth2', function() {
        const auth2 = gapi.auth2.init({
          client_id: mab.google_id,
          fetch_basic_profile: true,
          scope: 'profile',
        })

        auth2.signIn().then(user => {
          const profile = user.getBasicProfile()

          console.log('Full Name: '   + profile.getName());
          console.log('Given Name: '  + profile.getGivenName());
          console.log('Family Name: ' + profile.getFamilyName());
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

import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import Swiper from 'swiper'

const access = new Vue({
  ...baseConfig(store),
  data() {
    return {
      view: 1
    }
  },
  computed: {
    ...baseState()
  },
  watch: {
    'view': function() {
      if(this.view == 3) {
        setTimeout(function() {
          new Swiper('.swiper-container', {
            autoplay: {
              delay: 3000,
            },
            pagination: {
              el: '.c-image-slider .swiper-pagination',
            },
            navigation: {
              nextEl: '.swiper-button-next',
              prevEl: '.swiper-button-prev',
            },
          });
        },1000)
      }
    }
  },
  mounted(){
    this.global();
    this.hideLoading();
  },
  methods: {
    ...baseActions(),
    next: function () {
      if(this.view != 3) {
        this.view = this.view + 1
      }
    },
    prev: function () {
      if(this.view != 1) {
        this.view = this.view - 1
      }
    },
    authWithGoogle: function() {
      gapi.load('auth2', function() {
        const auth2 = gapi.auth2.init({
          client_id: '999086702146-87q1a9kleriolc0ro8g7ob1qvp3k09d3.apps.googleusercontent.com',
          fetch_basic_profile: true,
          scope: 'profile',
        })

        auth2.signIn().then(user => {
          const profile = user.getBasicProfile()

          console.log('Full Name: ' + profile.getName());
          console.log('Given Name: ' + profile.getGivenName());
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

          this.authWithFB('login', token);
        } else if(response.status == 'authorization_expired') {
          FB.login((response) => {
            if (response.authResponse) {
              const token = response.authResponse.accessToken;

              this.authWithFB('login', token);
            } else {
              // this.auth.isLoading     = false;
              // this.auth.error.active  = true;
            }
          }, {scope: 'public_profile,email', auth_type: 'reauthorize'});
        } else {
          FB.login((response) => {
            if (response.authResponse) {
              this.authWithFB('login', response.authResponse.accessToken);
            } else {
              // this.auth.isLoading     = false;
              // this.auth.error.active  = true;
              // this.auth.error.message = 'No se ha podido completar el proceso.';
            }
          }, {scope: 'public_profile,email'});
        }
      });
    },
    authWithFB: function(type, token) {
      console.log(type)
      console.log(token)
    },
  }
})

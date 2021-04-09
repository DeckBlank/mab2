import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import Swiper from 'swiper'

new Vue({
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

    this.initFacebook();
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

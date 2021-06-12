import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

new Vue({
  ...baseConfig(store),
  data() {
    return {
      metas: new URLSearchParams(window.location.search),
      step: 1,

      donation: {
        try: false,

        amount: {
          value: '',
          isValid: false,
        },
        method: {
          value: '',
          isValid: false,
        },
        email: {
          value: '',
          pattern: "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$",
          isValid: false,
        },
      },

      signature: '',
      isOpenedModal: false,
      isCreatingSignature: false,

      isOpenedShareModal: false,
      device: '',
    }
  },
  computed: {
    ...baseState(),
    referenceCode: function() {
      return `DonacionesMAB-${ this.donation.email.value }-${ new Date().valueOf() }`;
    },
    amount: function() {
      return this.donation.amount.value;
    },

    donationLink: function() {
      return `${ this.SITE_URL }/donaciones`;
    },
  },
  watch: {
    'donation.amount.value': function() {
      this.donation.amount.isValid = ( Number(this.donation.amount.value) > 0 ) ? true : false;
    },
    'donation.method.value': function() {
      this.validateSelect(this.donation.method);
    },
    'donation.email.value': function() {
      this.donation.email.isValid = this.validateText(this.donation.email);
    },
  },
  mounted(){
    this.global();
    this.hideLoading();

    if (this.metas.get('step') == 3) {
      this.step = 3;
    }

    setTimeout(() => {
      const video = document.getElementById("donac_video"); video.play(); 
    }, 1000)

    let breakpoint = window.matchMedia('(min-width: 1024px)');

    this.initShare();
    this.checkDevice(breakpoint); breakpoint.addEventListener('change', this.checkDevice);
  },
  methods: {
    ...baseActions(),
    validateText: function(parameter){
      let pattern = new RegExp( parameter.pattern );
      let value   = parameter.value.trim()
    
      if(pattern.test(value)){
        return true;
      }else{
        return false
      }
    },
    validateSelect: function(parameter) {
      if (parameter.value != '' && parameter.isValid == false) {
        parameter.isValid = true;
      }
    },
    isWrongField: function(field) {
      return (this.donation.try && !this.donation[field].isValid) ? true : false;
    },
    checkDevice: function(breakpoint) {
      if (breakpoint.matches) {
        this.device = 'desktop';
      } else {
        this.device = 'mobile';
      }
    },

    createSignature: function() {
      this.isCreatingSignature = true;

      const formData = new FormData();

      formData.append('referenceCode', this.referenceCode);
      formData.append('amount', this.amount);
      formData.append('_wpnonce', mab.nonce);

      fetch(`${ this.API }/donations/signature`,{
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
      .then(response => {
        if (response.status) {
          this.signature = response.data;

          window.setTimeout(() => {
            this.$refs.donation_form.submit()
          }, 1000);
        } else {
          this.isCreatingSignature = false;
        }
      })
      .catch(err => {
        this.isCreatingSignature = false;

        throw err;
      })
    },

    nextStep: function(step) {
      switch (step) {
        case 2: {
            if (
              this.donation.amount.isValid &&
              this.donation.method.isValid &&
              this.donation.email.isValid
            ) {
              
              if (this.donation.method.value == 'transferencia') {
                this.step = 2;
              } else {
                this.createSignature();
              }
            }
          }
          break;

        case 3: {
            this.step = 3;
            this.isOpenedModal = false;
          }
          break;
      }
    },
    handleDonation: function(e) {
      e.preventDefault();

      this.donation.try = true;

      this.nextStep(2);
    },

    initShare: function() {
      this.social = {
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${ this.donationLink }`,
        linkedin: `http://www.linkedin.com/shareArticle?mini=true&url=${ this.donationLink }`,
        twitter: `https://twitter.com/share?text=Hola, animate a donar por la educación en MAB&url=${ this.donationLink }`,
      }
    },
    copyDonationLink: function(e) {
      e.preventDefault();

      let linkToCopy = document.querySelector('#user-donation-link');

      linkToCopy.setAttribute('type', 'text');
      linkToCopy.select();

      document.execCommand('copy');

      linkToCopy.setAttribute('type', 'hidden');
    },
  }
})

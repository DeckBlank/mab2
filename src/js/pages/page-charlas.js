import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const charlas = new Vue({
  ...baseConfig(store),
  data() {
    return {
      isOpenedModal: false,

      eventSelected: '',

      user: {
        try: false,
        sent: false,
        isLoading: false,

        fullname: {
          value: '',
          pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{1,70}$",
          isValid: false,
        },
        email: {
          value: '',
          pattern: "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$",
          isValid: false,
        },
      }
    }
  },
  computed: {
    ...baseState()
  },
  watch: {
    'user.fullname.value' : function() {
      this.user.fullname.isValid = this.validateText(this.user.fullname);
    },
    'user.email.value' : function() {
      this.user.email.isValid = this.validateText(this.user.email);
    },
  },
  mounted(){
    this.global();
    this.hideLoading();
  },
  methods: {
    ...baseActions(),
    validateText: function(parameter) {
      let pattern = new RegExp( parameter.pattern );
      let value   = parameter.value.trim()

      if(pattern.test(value)){
        return true;
      }else{
        return false;
      }
    },
    isWrongField: function(field) {
      return this.user.try && !this.user[field].isValid;
    },

    openInscriptionModal: function(eventId) {
      this.eventSelected = eventId;
      this.isOpenedModal = true;
    },
    sendInscription: function() {
      this.user.try = true;

      if (this.user.fullname.isValid && this.user.email.isValid) {
        this.user.isLoading = true;

        const formData = new FormData();

        formData.append('fullname', this.user.fullname.value);
        formData.append('email', this.user.email.value);
        formData.append('_wpnonce', mab.nonce);

        fetch(`${ this.API }/events/${ this.eventSelected }/participants`,{
          method: 'POST',
          body: formData
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json();
          }else{
            throw res;
          }
        })
        .then(response => {
          if (response.status) window.location.reload();

          this.user.isLoading = false;
        })
        .catch(err => {
          this.user.isLoading = false;

          throw err;
        })
      }
    },
  }
})

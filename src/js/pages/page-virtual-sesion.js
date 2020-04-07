import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import { DatePicker, TimeSelect, Upload } from 'element-ui'
import lang from 'element-ui/lib/locale/lang/es'
import locale from 'element-ui/lib/locale'

// configure language
locale.use(lang)


Vue.component(DatePicker.name, DatePicker)
Vue.component(TimeSelect.name, TimeSelect)
Vue.component(Upload.name, Upload)

const virtual_sesion = new Vue({
  ...baseConfig(store),
  data() {
    return {
      isOpenedRequestModal: false,
      isOpenedValidateCodeModal: false,

      //Session Request
      isSentSessionRequest: false,
      sessionRequest: {
        counter: 0,
        fullname: {
          value: '',
          pattern: '^([a-zA-Z ]+)$',
          isValid: false
        },
        email: {
          value: '',
          pattern: "[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$",
          isValid: false
        },
        date1: '',
        date2: '',
        time1: '',
        time2: '',
        course: '',
        resources: []
      },

      //Session
      session: null,
      sessionKey: 'quRIjSV7',
      sessionUser: '',
      isRightCode: null
    }
  },
  computed: {
    ...baseState()
  },
  watch: {
    'sessionRequest.fullname.value': function(){
      this.validateText('fullname')
    },
    'sessionRequest.email.value': function(){
      this.validateText('email')
    },
    'sessionRequest.date1': function(val){
      this.validateDateTime(val)
    },
    'sessionRequest.time1': function(val){
      this.validateDateTime(val)
    },
    'sessionRequest.date2': function(val){
      this.validateDateTime(val)
    },
    'sessionRequest.time2': function(val){
      this.validateDateTime(val)
    },
    'sessionRequest.course': function(val){
      this.validateDateTime(val)
    },
    'sessionRequest.resources': function(val){
      if(val.length > 0){
        this.sessionRequest.counter++;
      }else{
        this.sessionRequest.counter--;
      }
    },
  },
  beforeMount(){
    this.initSectors();
  },  
  methods: {
    ...baseActions(),
    handleRemove: function(file){
      this.sessionRequest.resources = this.sessionRequest.resources.filter(res => res.file.name != file.name)
    },
    handleBeforeUpload: function(file){      
      if(file.type.split('/')[1] == 'jpg' ||
        file.type.split('/')[1] == 'jpeg' ||
        file.type.split('/')[1] == 'pdf'
        ){
        return file;
      }else{
        return false;
      }
    },
    sendSessionRequest: function(){
      let session_request_form = new FormData();

      session_request_form.append('fullname', this.sessionRequest.fullname.value)
      session_request_form.append('email', this.sessionRequest.email.value)
      session_request_form.append('date1', this.sessionRequest.date1)
      session_request_form.append('time1', this.sessionRequest.time1)
      session_request_form.append('date2', this.sessionRequest.date2)
      session_request_form.append('time2', this.sessionRequest.time2)
      session_request_form.append('course', this.sessionRequest.course)

      this.sessionRequest.resources.forEach(el => {
        session_request_form.append('resources[]', el.file)
      })

      fetch(`${this.API}/session_request`,{
          method: 'POST',
          body: session_request_form
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(request_session => {
          this.isSentSessionRequest = true;
        })
        .catch(err => {
          throw err;          
        })
    },
    validateText: function(field){
      let input_pattern = new RegExp( this.sessionRequest[field].pattern ),
        input_value = this.sessionRequest[field].value.trim()

      if(input_pattern.test(input_value)){
        this.sessionRequest[field].isValid = true
        this.sessionRequest.counter++;
      }else{
        this.sessionRequest[field].isValid = false
        this.sessionRequest.counter--;
      }     
    },
    validateDateTime: function(val){
      if(val != ''){
        this.sessionRequest.counter++;
      }else{
        this.sessionRequest.counter--;
      }
    },
    addAtachments: function(file){
      this.sessionRequest.resources.push(file)
    },
    getSession: function(){
      fetch(`${this.API}/session?key=${this.sessionKey}`,{
          method: 'GET'
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(session => {
          this.session = session;
          this.isRightCode = true
        })
        .catch(err => {
          this.isRightCode = false
          throw err;          
        })       
    },
    joinSession: function(){
      window.localStorage.setItem('mab_session',JSON.stringify({
        id: this.session.id,
        name: this.session.name,
        key: this.session.key,
        teacher: this.session.teacher,
        publisher: this.sessionUser
      }))   
      window.location = `${this.SITE_URL}/sesion/${this.session.slug}?key=${this.sessionKey}`
    }
  }
})

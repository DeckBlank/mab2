import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import { DatePicker, TimeSelect, Upload } from 'element-ui'
import lang from 'element-ui/lib/locale/lang/es'
import locale from 'element-ui/lib/locale'

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
        date1: {
          value: '',
          isValid: false
        },
        date2: {
          value: '',
          isValid: false
        },
        time1: {
          value: '',
          isValid: false
        },
        time2: {
          value: '',
          isValid: false
        },
        course: {
          value: '',
          isValid: false
        },
        resources: {
          value: [],
          isValid: false
        }
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
      this.sessionRequest.fullname.isValid = this.validateText(this.sessionRequest.fullname)
    },
    'sessionRequest.email.value': function(){
      this.sessionRequest.email.isValid = this.validateText(this.sessionRequest.email)
    },
    'sessionRequest.date1.value': function(val){
      this.validateDateTime(this.sessionRequest.date1)
    },
    'sessionRequest.time1.value': function(val){
      this.validateDateTime(this.sessionRequest.time1)
    },
    'sessionRequest.date2.value': function(val){
      this.validateDateTime(this.sessionRequest.date2)
    },
    'sessionRequest.time2.value': function(val){
      this.validateDateTime(this.sessionRequest.time2)
    },
    'sessionRequest.course.value': function(val){
      this.validateSelect(this.sessionRequest.course)
    },
    'sessionRequest.resources.value': function(val){
      if(val.length > 0 && this.sessionRequest.resources.isValid == false){
        this.sessionRequest.resources.isValid = true
        this.sessionRequest.counter++;
      }else if(val.length == 0){
        this.sessionRequest.resources.isValid = false
        this.sessionRequest.counter--;
      }
    },
    'isSentSessionRequest': function(val){
      if(val == true){
        window.setTimeout(()=>{
          this.isOpenedRequestModal = false;
        },5000)
      }
    }
  },
  beforeMount(){
    this.initSectors();
  },
  mounted(){
    this.hideLoading();
  },
  updated(){
    [...document.querySelectorAll('.el-input__inner')].forEach(el => {
      el.readOnly = true
    })
  },
  methods: {
    ...baseActions(),
    handleRemove: function(file){
      this.sessionRequest.resources.value = this.sessionRequest.resources.value.filter(res => res.file.name != file.name)
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
      session_request_form.append('date1', this.sessionRequest.date1.value)
      session_request_form.append('time1', this.sessionRequest.time1.value)
      session_request_form.append('date2', this.sessionRequest.date2.value)
      session_request_form.append('time2', this.sessionRequest.time2.value)
      session_request_form.append('course', this.sessionRequest.course.value)

      this.sessionRequest.resources.value.forEach(el => {
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
    validateText: function(parameter){
      let input_pattern = new RegExp( parameter.pattern ),
        input_value = parameter.value.trim()

      if(input_pattern.test(input_value)){
        if(parameter.isValid == null || parameter.isValid == false){
          this.sessionRequest.counter++;
        }
        return true;
      }else{
        if(parameter.isValid == null || parameter.isValid == true){
          this.sessionRequest.counter--;
        }
        return false
      }
    },
    validateSelect: function(parameter){
      if(parameter.value != '' && parameter.isValid == false){
        parameter.isValid = true
        this.sessionRequest.counter++;
      }
    },    
    validateDateTime: function(parameter){
      if( (parameter.value != '' || parameter.value != null ) && parameter.isValid == false){
        parameter.isValid = true
        this.sessionRequest.counter++;
      }else if(parameter.value == '' || parameter.value == null){
        parameter.isValid = false
        this.sessionRequest.counter--;
      }
    },
    addAtachments: function(file){
      this.sessionRequest.resources.value.push(file)
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

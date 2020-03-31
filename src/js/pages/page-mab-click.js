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

const mab_click = new Vue({
  ...baseConfig(store),
  data() {
    return {
      isOpenedRequestModal: true,
      isOpenedValidateCodeModal: false,

      //Request
      isSentRequest: false,
      request: {
        fullname: '',
        email: '',
        date1: '',
        date2: '',
        time1: '',
        time2: '',
        course: '',
        resources: []
      },
      form: '',

      //Validate
      isRightCode: false,
      value: '',
      value2: '',
      attachments: [],
      fullname: '',
      email: ''
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){    
  },
  methods: {
    ...baseActions(),
    handleRemove: function(){

    },
    sendRequest: function(){
      console.log(this.attachments)
    },
    addAtachments: function(file){
      this.attachments.push(file) 
    }
  }
})

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
      isOpenedRequestModal: false,
      isOpenedValidateCodeModal: false,
      isRightCode: false,
      value: '',
      value2: '',
      fileList: [],
      fullname: '',
      email: ''
    }
  },
  computed: {
    ...baseState()
  },
  methods: {
    ...baseActions(),
    handlePreview: function(){

    },
    handleRemove: function(){

    }
  }
})

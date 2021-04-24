import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const curso = new Vue({
  ...baseConfig(store),
  data() {
    return {
      view: 1,
      foro: 1,
      commentbox: 0,
      questions: [
        { enable : true },
        { enable : false },
        { enable : false },
        { enable : false },
        { enable : false },
      ]
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.global();
    this.hideLoading();
  },
  methods: {
    ...baseActions(),
    resetAccordion: function(question) {
      this.questions = this.questions.map(q => {
        return (q != question) ? { ...q, enable : false } : q;
      })
    },
  }
})

import Vue from 'vue'
import Swiper from 'swiper'
import {baseConfig, baseState, baseActions} from '../../app'
import {store} from '../../store'

import tippy from 'tippy.js';

const test = new Vue({
  ...baseConfig(store),
  data() {
    return {
      //Questions
      isEnableChange: false,
      currentQuestion: 1,
      testExists: false,
      testDone: false,
      options: [
        {
          value: 'Casi siempre',
          credit: 5
        },
        {
          value: 'Frecuentemente',
          credit: 4
        },
        {
          value: 'A veces',
          credit: 3
        },
        {
          value: 'Rara vez',
          credit: 2
        },
        {
          value: 'Casi nunca',
          credit: 1
        }
      ],
      questions: {
        count: 0,
        list: []
      },
      testResult: {
        style: {},
        list: []
      },
      sliderQuestions: null    
    }
  },
  computed: {
    ...baseState()
  },
  created(){
    if(!this.logedUser){
      window.location = `${this.SITE_URL}/login`;
    }
  },
  mounted(){
    this.global();
    this.initTooltips();
    this.getTest();
  },
  methods: {
    ...baseActions(),
    changeQuestion: function(direction){
      if (direction == 'next') {
        if(this.sliderQuestions.slideNext()){
          this.isEnableChange = false

          if(this.currentQuestion < this.questions.count){
            this.currentQuestion += 1;
          }
        }else{
          let visualResult = this.testResult.list.filter(el => el.type == 'visual'),
          auditiveResult = this.testResult.list.filter(el => el.type == 'auditivo'),
          kinestheticResult = this.testResult.list.filter(el => el.type == 'kinestesico'),
          result = this.testResult.list.map(el => el.value).reduce((total, current)=>{ return total + current});
  
          if(visualResult.length > 0){
            let visualSum = visualResult.map(el => el.value).reduce((total, current)=>{ return total + current})
            this.testResult.visual = ((visualSum/result)*100).toFixed(0);
          }
  
          if(kinestheticResult.length > 0){
            let kinestheticSum = kinestheticResult.map(el => el.value).reduce((total, current)=>{ return total + current});
            this.testResult.kinesthetic = ((kinestheticSum/result)*100).toFixed(0);
          }

          if(auditiveResult.length > 0){
            let auditiveSum = auditiveResult.map(el => el.value).reduce((total, current)=>{ return total + current});
            this.testResult.auditive = 100 - this.testResult.visual - this.testResult.kinesthetic;
          }

          this.testDone = true
          this.saveTest();
        }
      }else if(direction == 'previous'){
        if(this.sliderQuestions.slidePrev()){
          this.isEnableChange = true
          this.currentQuestion -= 1;
        }
      }
    },
    enableNext: function(){
      this.isEnableChange = true
    },
    getQuestions: function(){
      fetch(`${this.API}/test/questions`,{
          method: 'GET'
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(questions => {
          questions.list.forEach(q => {
            this.testResult.list.push({
              value: 0,
              type: q.question.type
            })
          })

          this.questions = questions;
          window.setTimeout(()=>{
            this.sliderQuestions = new Swiper('#slider-questions', {
              allowTouchMove: false,
              slidesPerView: 1,
              spaceBetween: 10,
              speed: 500,
              loop: false
            })
          }, 100)
        })
        .catch(err => {
          throw err;          
        })      
    },
    getTest: function(){
      fetch(`${this.API}/test?user=${this.logedUser.user_email}`,{
          method: 'GET'
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(result => {
          this.testExists = true;
          this.testDone = true;

          this.testResult.visual = parseFloat(JSON.parse(result).visual)
          this.testResult.auditive = parseFloat(JSON.parse(result).auditive)
          this.testResult.kinesthetic = parseFloat(JSON.parse(result).kinesthetic)
          
          this.hideLoading();
        })
        .catch(err => {
          this.getQuestions();
          this.hideLoading();
          throw err;
        })      
    },
    saveTest: function(){
      let form_data = new FormData();

      form_data.append('user', this.logedUser.user_email)
      form_data.append('result', JSON.stringify({
        visual: this.testResult.visual,
        auditive: this.testResult.auditive,
        kinesthetic: this.testResult.kinesthetic
      }))

      fetch(`${this.API}/test`,{
          method: 'POST',
          body: form_data
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(response => {

        })
        .catch(err => {
          throw err;
        }) 
    },
    initTooltips: function(){
      const BASE_CONFIG = {
        allowHTML: true,
        theme: 'light',
        inertia: true,
        animation: 'scale-subtle'
      }

      tippy('#kinesthetic', {
        content: document.querySelector('#kinesthetic-template').innerHTML,
        ...BASE_CONFIG
      });
  
      tippy('#visual', {
        content: document.querySelector('#visual-template').innerHTML,
        ...BASE_CONFIG
      });
  
      tippy('#auditive', {
        content: document.querySelector('#auditive-template').innerHTML,
        ...BASE_CONFIG
      });
    }
  }
})

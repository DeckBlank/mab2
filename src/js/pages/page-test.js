import Vue from 'vue'
import { Swiper, SwiperSlide, directive } from 'vue-awesome-swiper'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const _404 = new Vue({
  ...baseConfig(store),
  data() {
    return {
      //Questions
      isEnableChange: false,
      currentQuestion: 1,
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
      questions: [],
      testResult: {
        visual: 0,   
        auditive: 0,
        kinesthetic: 0,        
        list: []
      },

      //Swiper
      swiperOptions: {
        allowTouchMove: false,
        speed: 500,
        loop: false,
        preventClicks: false,
        preventClicksPropagation: false
      }      
    }
  },
  components: {
    Swiper,
    SwiperSlide
  },
  directives: {
    swiper: directive
  },  
  computed: {
    ...baseState(),
    swiper() {
      return this.$refs.slider_questions.$swiper
    }
  },
  watch: {
    'testResult.list': {
      handler: function(value){
        let visualResult = value.filter(el => el.type == 'visual'),
        auditiveResult = value.filter(el => el.type == 'auditivo'),
        kinestheticResult = value.filter(el => el.type == 'kinestesico'),
        result = value.map(el => el.value).reduce((total, current)=>{ return total + current});

        if(visualResult.length > 0){
          let visualSum = visualResult.map(el => el.value).reduce((total, current)=>{ return total + current})
          this.testResult.visual = ((visualSum/result)*100).toFixed(2);
        }

        if(auditiveResult.length > 0){
          let auditiveSum = auditiveResult.map(el => el.value).reduce((total, current)=>{ return total + current});
          this.testResult.auditive = ((auditiveSum/result)*100).toFixed(2);
        }

        if(kinestheticResult.length > 0){
          let kinestheticSum = kinestheticResult.map(el => el.value).reduce((total, current)=>{ return total + current});
          this.testResult.kinesthetic = ((kinestheticSum/result)*100).toFixed(2);
        }
      },
      deep: true
    }
  },
  created(){
    if(!this.logedUser){
      window.location = `${this.SITE_URL}/login`;
    }
  },
  beforeMount(){
    this.initSectors();
  },
  mounted(){
    this.getQuestions();
    this.hideLoading();
  },
  methods: {
    ...baseActions(),
    changeQuestion: function(direction){
      if (direction == 'next') {
        if(this.swiper.slideNext()){
          this.isEnableChange = false
  
          if(this.currentQuestion < this.questions.length){
            this.currentQuestion += 1;
          }
        }else{
          this.testDone = true
          this.saveTest();
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
          questions.forEach(q => {
            this.testResult.list.push({
              value: 0,
              type: q.question.type
            })
          })

          console.log(this.testResult)

          this.questions = questions;
          this.getTest();
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
          this.testDone = true;

          this.testResult.visual = parseFloat(JSON.parse(result).visual)
          this.testResult.auditive = parseFloat(JSON.parse(result).auditive)
          this.testResult.kinesthetic = parseFloat(JSON.parse(result).kinesthetic)
        })
        .catch(err => {
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
    }
  }
})

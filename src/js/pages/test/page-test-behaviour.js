import Vue from 'vue'
import Swiper from 'swiper'
import {baseConfig, baseState, baseActions} from '../../app'
import {store} from '../../store'
import {mapState} from 'vuex'

const test = new Vue({
  ...baseConfig(store),
  data() {
    return {
      isEnableTest: true,
      isEnableChange: false,
      isValidTest: true,
      currentQuestion: 1,
      testExists: false,      
      testDone: false,
      questions: {
        count: 0,
        list: [],
        result: {},
      },
      sliderQuestions: null,
    }
  },
  computed: {
    ...baseState(),
    ...mapState(['THEME_URL']),
    testType: function() {
      return (this.getUserType() == 'primary') ? 'JEPI' : 'MBTI';
    },
    home: function() {
      return `${this.SITE_URL}/emotional/`;
    }
  },
  created(){
    if(!this.logedUser && this.logedUser.user_sector != 'privado'){
      window.location = `${this.SITE_URL}/login`;
    }
  },
  mounted(){
    this.global();
    this.hideLoading();
    this.getTest();
    this.checkoutEnableTest();
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
          this.saveTest();
        }
      }else if(direction == 'previous'){
        if(this.sliderQuestions.slidePrev()){
          this.isEnableChange = true
          this.currentQuestion -= 1;
        }
      }
    },    
    enableNext: function(questionIndex, questionValue){
      this.isEnableChange = true;
    },
    initTest: function() {
      if(this.getUserType() == 'primary') {
        this.questions.list   = require('../../extras/tests/jepi/questions.json');
        this.questions.count  = this.questions.list.length;
        this.questions.list   = this.questions.list.map((question) => {
          return {
            ...question,
            options: [
              {title: 'Si', credit: 1},
              {title: 'No', credit: 0},
            ]
          }
        });
        
      } else {
        this.questions.list   = require('../../extras/tests/mbti/questions.json');
        this.questions.count  = this.questions.list.length;
      }

      window.setTimeout(()=>{
        this.sliderQuestions = new Swiper('#slider-questions', {
          allowTouchMove: false,
          slidesPerView: 1,
          spaceBetween: 10,
          speed: 500,
          loop: false
        })
      }, 100)
    },
    getUserType: function() {
      if (this.logedUser.user_role = 'student') {
        switch (this.logedUser.user_grade) {
          case '1RO PRIMARIA':
          case '2DO PRIMARIA':
          case '3RO PRIMARIA':
          case '4TO PRIMARIA':
          case '5TO PRIMARIA':
          case '6TO PRIMARIA':
          case '1RO SECUNDARIA':
            return 'primary';
            break;
  
          case '2DO SECUNDARIA':
          case '3RO SECUNDARIA':
          case '4TO SECUNDARIA':
          case '5TO SECUNDARIA':
          case '6TO SECUNDARIA':
            return 'secondary';
            break;
        }
      } else if(['tutor', 'teacher'].includes(this.logedUser.user_role)){
        return 'secondary';
      }
    },
    getTest: function(){
      fetch(`${this.API}/test/behaviour?user=${this.logedUser.user_email}`,{
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
          let testResult  = JSON.parse(result);
          let testResults = '';

          if (this.getUserType() == 'primary') {
            testResults = require('../../extras/tests/jepi/result.json');

          } else {
            testResults = require('../../extras/tests/mbti/result.json');

          }

          this.questions.result = testResults[testResult.id];
          this.testExists       = true;
          this.testDone         = true;

          console.log(testResults[testResult.id])

          this.hideLoading();
        })
        .catch(err => {
          this.initTest();
          this.hideLoading();
          throw err;
        })      
    },    
    saveTest: function() {
      let enable = false;

      if (this.getUserType() == 'primary') {
        if (this.isTellingTrue()) {
          this.calculateBehaviour();
          enable = true;
        } else {
          this.isValidTest = false;
        }
      } else {
        this.calculateBehaviour();
        enable = true;
      }

      if (enable) {
        let formData = new FormData();

        formData.append('user', this.logedUser.user_email)
        formData.append('result', JSON.stringify({
          id: this.questions.result.id,
          title: this.questions.result.title
        }))

        fetch(`${this.API}/test/behaviour`,{
            method: 'POST',
            body: formData
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              this.testDone = true;
            }else{
              throw res
            }
          })
          .catch(err => {
            throw err;
          })
      }
    },
    blockTest: function() {
      window.localStorage.setItem('mab_temp', JSON.stringify({
        behaviour_timer: new Date().getTime() / 1000
      }))

      window.location = `${this.SITE_URL}/emotional/`;
    },
    calculateBehaviour: function() {
      if (this.getUserType() == 'primary') {
        let axis        = {
          x: this.questions.list.filter(q => q.dimention == 'I' || q.dimention == 'E'),
          y: this.questions.list.filter(q => q.dimention == 'N' || q.dimention == 'EE'),
        }
        let axisResult  = {
          x: axis.x.map(q => q.answer).reduce((q1, q2) => q1 + q2, 0),
          y: axis.y.map(q => q.answer).reduce((q1, q2) => q1 + q2, 0),
        }
        let jepiResults = require('../../extras/tests/jepi/result.json');

        if (axisResult.x < 11 && axisResult.y < 11) {
          this.questions.result = jepiResults[0];

        } else if (axisResult.x < 11 && axisResult.y > 11) {
          this.questions.result = jepiResults[1];

        } else if (axisResult.x > 11 && axisResult.y > 11) {
          this.questions.result = jepiResults[2];

        } else if (axisResult.x > 11 && axisResult.y < 11) {
          this.questions.result = jepiResults[3];

        }

      } else {
        let mbtiResults = require('../../extras/tests/mbti/result.json');
        let dimentions = require('../../extras/tests/mbti/dimentions.json');
        let dimentionsSummary = {
          E: this.calculateMbtiDimention(dimentions.E),
          I: this.calculateMbtiDimention(dimentions.I),
          S: this.calculateMbtiDimention(dimentions.S),
          N: this.calculateMbtiDimention(dimentions.N),
          T: this.calculateMbtiDimention(dimentions.T),
          F: this.calculateMbtiDimention(dimentions.F),
          J: this.calculateMbtiDimention(dimentions.J),
          P: this.calculateMbtiDimention(dimentions.P)
        };
        let dimentionsResult = {
          col1: (dimentionsSummary.E > dimentionsSummary.I) ? 'E' : 'I',
          col2: (dimentionsSummary.S > dimentionsSummary.N) ? 'S' : 'N',
          col3: (dimentionsSummary.T > dimentionsSummary.F) ? 'T' : 'F',
          col4: (dimentionsSummary.J > dimentionsSummary.P) ? 'J' : 'P'
        };

        this.questions.result = mbtiResults[Object.values(dimentionsResult).join('')];
      }
    },
    calculateMbtiDimention: function(dimention) {
      let result = 0;

      dimention.forEach(cell => {
        cell.result = (this.questions.list[cell.index - 1].answer == cell.formule[0]) ? cell.formule[1] : cell.formule[2];

        result += cell.result;
      });

      return result;
    },
    isTellingTrue: function() {
      let dimesionL = this.questions.list.filter(q => q.dimention == 'L').map(q => q.answer);

      return (dimesionL.reduce((q1, q2) => q1 + q2, 0) <= 4) ? true : false;
    },
    checkoutEnableTest: function() {
      let current = new Date().getTime() / 1000;
      let mab_temp = window.localStorage.getItem('mab_temp');
      mab_temp = JSON.parse(mab_temp)

      this.isEnableTest = ((mab_temp.behaviour_timer + 15*60) <= current) ? true : false;
    }
  }
})

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

      isSaving: false,

      grades: [
        "1RO PRIMARIA",
        "2DO PRIMARIA",
        "3RO PRIMARIA",
        "4TO PRIMARIA",
        "5TO PRIMARIA",
        "6TO PRIMARIA",
        "1RO SECUNDARIA",
        "2DO SECUNDARIA",
        "3RO SECUNDARIA",
        "4TO SECUNDARIA",
        "5TO SECUNDARIA",
        "6TO SECUNDARIA"
      ],
      grade: {
        try: false,
        completed: false,

        value: '',
        isValid: false
      },
    }
  },
  computed: {
    ...baseState(),
    ...mapState(['THEME_URL']),
    testType: function() {
      return (this.getUserType() == 'primary') ? 'JEPI' : 'MBTI';
    },
    profile: function() {
      return `${this.SITE_URL}/perfil`;
    },
    filled: function() {
      return (this.questions.list.filter(q => q.answer != '-1').length == this.questions.count) ? true : false;
    },
    userProfile: function() {
      return `${ this.SITE_URL}/user/${ this.logedUser.user_nicename }`;
    },
  },
  watch: {
    'grade.value': function() {
      this.validateSelect(this.grade);
    },
  },
  created(){
    if(!this.logedUser){
      window.location = `${this.SITE_URL}/access`;
    } else if (this.logedUser.user_rol == 'foreign') {
      window.location = `${this.SITE_URL}`;
    }
  },
  mounted(){
    this.global();
    this.hideLoading();
    this.getTest();
    this.checkoutEnableTest();

    if (this.logedUser && this.logedUser.user_grade) {
      this.grade.value      = this.logedUser.user_grade;
      this.grade.completed  = true;
      this.grade.isValid    = true;

      this.startTest();
    }
  },
  methods: {
    ...baseActions(),
    validateSelect(parameter, field = 'value') {
      if(parameter[field] != '' && parameter.isValid == false){
        parameter.isValid = true;
      }
    },
    startTest: function() {
      this.grade.try = true;

      if (this.grade.isValid) {
        this.grade.completed = true;
        this.initTest();
      }
    },

    changeQuestion: function(direction){
      if (direction == 'next') {
        if(this.sliderQuestions.slideNext()){
          this.isEnableChange = false

          if(this.currentQuestion < this.questions.count){
            this.currentQuestion += 1;
          }
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
              {title: 'Sí', credit: 1},
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
      }, 1000)
    },
    getUserType: function() {
      if (this.logedUser.user_role = 'student') {
        switch (this.grade.value) {
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

          this.hideLoading();
        })
        .catch(err => {
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

        formData.append('user_id', this.logedUser.user_id)
        formData.append('user', this.logedUser.user_email)
        formData.append('result', JSON.stringify({
          id: this.questions.result.id,
          title: this.questions.result.title
        }))

        if (!this.logedUser.user_grade)
          formData.append('user_grade', this.grade.value)

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

      window.location = this.userProfile;
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

      if (mab_temp) {
        this.isEnableTest = ((mab_temp.behaviour_timer + 15*60) <= current) ? true : false;
      }
    }
  }
})

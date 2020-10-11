import Vue from 'vue'
import { Swiper, SwiperSlide, directive } from 'vue-awesome-swiper'
import {baseConfig, baseState, baseActions} from '../app'
import {addCourseToShopCart} from '../libs/shop-cart'
import {store} from '../store'

import '../components/likes';
import '../components/editor';
import '../components/thread/comment';

const topic = new Vue({
  ...baseConfig(store),
  data() {
    return {
      metas: new URLSearchParams(window.location.search),
      course_link: '',
      topicID: null,

      //Likes
      likes: 0,

      //Comments
      comments: {
        number: 0,
        list: []
      },
      commentsPaged: 0,
      isLoadingComments: false,      

      //Questions
      isOpenedQuestionsModal: false,
      isEnableChange: false,
      currentQuestion: 1,
      currentOptionSelected: 0,
      testDone: false,
      questions: [],
      testResult: [],
      totalRightAnswers: 0,      
      totalWrongAnswers: 0,
      
      //Material
      isActiveMaterial: false,

      //Swiper
      swiperOptions: {
        allowTouchMove: false,
        speed: 500,
        loop: false,
        autoHeight: true,
        preventClicks: false,
        preventClicksPropagation: false
      },

      //Teacher
      isOpenedTeacherModal: false,
      isSentFormTeacher: false,
      teacher: {
        valid: true,
        sent: false,
        sending: false,
        email: {
          value: '',
          pattern: "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$",
          isValid: false
        },
        phone: {
          value: '',
          pattern: '^([0-9]+)$',
          isValid: false
        },
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
    'teacher.email.value': function(){
      this.teacher.email.isValid = this.validateText(this.teacher.email)
    },
    'teacher.phone.value': function(){
      this.teacher.phone.isValid = this.validateText(this.teacher.phone)
    },
  },
  mounted(){
    this.global();
    
    this.topicID      = this.$refs.topic.getAttribute('data-id');
    this.area         = this.$refs.topic.getAttribute('data-area');
    this.course_link  = `${this.SITE_URL}/curso/${this.metas.get('course_slug')}?sector=${this.metas.get('sector')}`;
    
    this.isUserAuthOnTopic(this.metas.get('course_id'))
    this.getLikes();
    this.getComments();
    
    if(this.logedUser){
      this.getQuestions();
      this.saveViewLog(this.metas.get('course_id'));
    }
  },
  methods: {
    ...baseActions(),
    getLikes: function(){
      fetch(`${this.API}/topic/${this.topicID}/likes`,{
          method: 'GET'
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(likes => {
          this.likes = parseInt(likes[0]);
        })
        .catch(err => {    
          throw err;          
        })      
    },   
    getQuestions: function(){
      fetch(`${this.API}/topic/${this.topicID}/questions`,{
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
            this.testResult.push({
              value: '',
              isRight: null
            })
          })

          this.questions = questions;
          this.getTestScore()
        })
        .catch(err => {
          throw err;          
        })      
    },     
    changeQuestion: function(direction){
      if (direction == 'next') {
        if(this.swiper.slideNext()){
          this.isEnableChange = false
  
          if(this.currentQuestion < this.questions.length){
            this.currentQuestion += 1;
          }
          else{
            this.testDone = true
            this.addNewTestScore()
          }
        }
      }
    },
    verifyOptionSelected: function(qindex){
      if(this.testResult[qindex].value == this.questions[qindex].question.right){
        this.testResult[qindex].isRight = true
        this.totalRightAnswers += 1
      }else{
        this.testResult[qindex].isRight = false
        this.totalWrongAnswers += 1
      }

      this.isEnableChange = true
    },
    restartTest: function(){
      this.testDone = false
      this.currentQuestion = 1
      this.totalRightAnswers = 0
      this.totalWrongAnswers = 0

      this.testResult = []
      this.questions.forEach(q => {
        this.testResult.push({
          value: '',
          isRight: null
        })
      })

      this.swiper.slideTo(0)
    },
    addNewTestScore: function(){
      let test_result = JSON.stringify({
        wrongs: this.totalWrongAnswers,
        rights: this.totalRightAnswers
      })

      fetch(`${ this.API }/topic/${ this.topicID }/test_score?
          result=${ test_result }&
          user=${ this.logedUser.user_email }&
          course_id=${ this.metas.get('course_id') }&
          course_security=${ (this.metas.get('sector') == 'publico') ? 0 : 1 }&
          unity=${ this.metas.get('unity') }`,{
          method: 'PUT'
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(score => {
          
        })
        .catch(err => {
          throw err;          
        })      
    },
    getTestScore: function(){
      fetch(`${this.API}/topic/${this.topicID}/test_score?user=${this.logedUser.user_email}`,{
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
          this.currentQuestion = this.questions.length;
          this.testDone = true;
          this.swiper.slideTo(this.questions.length)

          this.totalWrongAnswers = JSON.parse(result.score).wrongs
          this.totalRightAnswers = JSON.parse(result.score).rights
        })
        .catch(err => {
          throw err;          
        })       
    },
    getComments: function(){
      if(this.commentsPaged != -1){
        fetch(`${this.API}/topic/${this.topicID}/comments?paged=${this.commentsPaged + 1}`,{
            method: 'GET'
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(comments => {
            this.comments.number = comments.number;
            this.comments.list.push(...comments.list);
            this.commentsPaged += 1
          })
          .catch(err => {
            this.commentsPaged = -1
            throw err;          
          })          
      }
    },
    isUserAuthOnTopic: function(course_id){
      if(!this.metas.get('course_name') || !this.metas.get('course_slug') || !this.metas.get('unity') || (this.metas.get('sector') != 'privado' && this.metas.get('sector') != 'publico')){
        window.location = `${this.course_link}`;
      }else{
        if (['creative', 'emotional'].includes(this.area)) {
          this.hideLoading();
        } else if(this.logedUser && this.logedUser.user_rol != 'foreign') {
          if (this.metas.get('sector') == "privado") {
            fetch(`${this.API}/course/${course_id}/registration/checkout?user=${this.logedUser.user_email}&topic=${this.topicID}`)
            .then(res => {
              if (res.status >= 200 && res.status < 300) {
                return res.json()
              }else{
                throw res
              }
            })
            .then(registration => {
              this.hideLoading();
            })
            .catch(err => {
              addCourseToShopCart(
                course_id,
                this.metas.get('course_name'),
                `${this.SITE_URL}/curso/${this.metas.get('course_name')}`,
                this.SITE_URL,
                this.metas
              );

              throw err;
            })
          } else if (this.metas.get('sector') == "publico") {
            this.hideLoading();
          }
        } else {
          window.location = `${this.course_link}`;
        }
      }
    },
    downloadMaterial: function(url, media){
      event.preventDefault();

      let user = (this.logedUser) ? this.logedUser.user_email : 'anonimo';

      fetch(`${this.API}/topic/${this.topicID}/material/log?
        user=${user}&
        course_id=${ this.metas.get('course_id') }&
        media=${media}`,{
          method: 'PUT'
        })
        .then(res => { 
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(response => {
          window.open(url, '_blank');
        })
        .catch(err => {
          window.open(url, '_blank');
          throw err;
        })     
    },
    saveViewLog: function(course_id){
      let user = (this.logedUser) ? this.logedUser.user_email : 'anonimo';

      fetch(`${this.API}/topic/${this.topicID}/video/log?user=${user}&course_id=${course_id}`,{
          method: 'PUT'
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .catch(err => {
          throw err;          
        })
    },
    validateText: function(parameter){
      let input_pattern = new RegExp( parameter.pattern ),
        input_value = parameter.value.trim()

      if(input_pattern.test(input_value)){
        return true;
      }else{
        return false
      }
    },
    sendTeacher: function() {
      event.preventDefault();

      this.teacher.valid = this.teacher.email.isValid &&
        this.teacher.phone.isValid

      if(this.teacher.valid){
        let formData = new FormData();
  
        formData.append('email', this.teacher.email.value)
        formData.append('phone', this.teacher.phone.value)

        this.teacher.sending = true;
  
        fetch(`${this.API}/user/teacher/network`,{
            method: 'POST',
            body: formData
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(response => {
            this.teacher.sending      = false;
            this.teacher.sent         = true;
            this.isOpenedTeacherModal = false;
          })
          .catch(err => {
            this.teacher.sending = false;

            throw err;          
          })      
      }
    }
  }
})

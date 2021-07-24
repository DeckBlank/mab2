import Vue from 'vue'
import { Swiper, SwiperSlide, directive } from 'vue-awesome-swiper'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

import '../components/likes';
import '../components/editor';
import '../components/thread/comment';
import { mapActions } from 'vuex'

new Vue({
  ...baseConfig(store),
  data() {
    return {
      showTooltipCerticado: false,
      view: 1,
      foro: 1,
      commentbox: 0,

      metas: new URLSearchParams(window.location.search),
      unityData: '',
      topicID: null,

      likes: 0,

      comments: {
        number: 0,
        list: [],
        sticky: null,

        is_user_owner: false,
      },
      commentsPaged: 0,
      isLoadingComments: false,      

      isOpenedQuestionsModal: false,
      isEnableChange: false,
      currentQuestion: 1,
      currentOptionSelected: 0,
      testDone: false,
      questions: [],
      testResult: [],
      totalRightAnswers: 0,      
      totalWrongAnswers: 0,

      isActiveMaterial: false,

      swiperOptions: {
        allowTouchMove: false,
        speed: 500,
        loop: false,
        autoHeight: true,
        preventClicks: false,
        preventClicksPropagation: false
      },

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
      },

      unities: [],

      courseProgress: 0,
      courseCertificate: null,
      courseCertificateSocial: null,
      isOpenedCertificateModal: false,

      device: '',
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
    },
    courseLink: function() {
      return (this.unityData) ? `${ this.SITE_URL }/curso/${ this.unityData.course.slug }` : '#';
    },

    hasCertificate: function() {
      return (this.courseProgress == 100) ? true : false;
    },
    certificate: function() {
      return (this.hasCertificate)
        ? `${ this.SITE_URL }/wp-json/custom/v1/users/${ this.logedUser.user_id }/certificate?course=${ this.metas.get('course_id') }`
        : '#';
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
    
    this.topicID  = mab.topic_id;
    this.area     = this.$refs.topic.getAttribute('data-area');

    this.getCourseUnity();
    
    this.isUserAuthOnTopic(this.metas.get('course_id'))
    this.getLikes();
    this.getUnities(  this.metas.get('course_id') );
    this.getComments();
    this.getCourseProgress( this.metas.get('course_id') );
    
    if (this.logedUser) {
      this.getQuestions();
      this.saveViewLog( this.metas.get('course_id') );
    }

    let breakpoint = window.matchMedia('(min-width: 1024px)');

    this.checkDevice(breakpoint); breakpoint.addEventListener('change', this.checkDevice);
  },
  methods: {
    ...baseActions(),
    ...mapActions(['addCourseToShopCart']),
    checkDevice: function(breakpoint) {
      if (breakpoint.matches) {
        this.device = 'desktop';
      } else {
        this.device = 'mobile';
      }
    },

    getUnities: function(course_id){
      fetch(`${ this.API }/course/${ course_id }/unities?user=${ this.logedUser.user_email }`)
        .then(res => { 
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          } else {
            throw res;
          }
        })
        .then(response => {
          this.unities = response.data;
        })
        .catch(err => {
          throw err;
        })      
    },
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
    getCourseProgress: function(course_id) {
      fetch(`${ this.API }/courses/${ course_id }/progress?user_email=${ this.logedUser.user_email }`)
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json()
        } else {
          throw res;
        }
      })
      .then(response => {
        if (response.status) {
          this.courseProgress     = Number(response.data.percentage);
          this.courseCertificate  = response.data.certificate;

          if (Number(response.data.percentage) == 100) {
            this.isOpenedCertificateModal = (!response.data.notification) ? true : false;

            window.setTimeout(() => {
              document.querySelector('#certificate_download').download = `${ this.logedUser.user_nicename }.pdf`;
            }, 100);

            if (this.courseCertificate) {
              const certificateLink = `${ this.SITE_URL }/certificado/${ this.courseCertificate.data.id }`;

              this.courseCertificateSocial = {
                whatsapp: `whatsapp://send?text=Termine mi curso de ${ this.courseCertificate.course } en Aprende MAB, ${ certificateLink }`,
                facebook: `https://www.facebook.com/sharer/sharer.php?u=${ certificateLink }`,
                linkedin: `http://www.linkedin.com/shareArticle?mini=true&url=${ certificateLink }`,
                twitter: `https://twitter.com/share?text=Hola, Termine mi curso de ${ this.courseCertificate.course } en Aprende MAB&url=${ certificateLink }`,
              }
            }
          }
        }
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
        .then(response => {
          if (response.status) {
            if (response.data.course_completed) {
              this.isOpenedQuestionsModal = false;
              this.courseProgress         = 100;
              this.courseCertificate      = response.data.certificate;

              if (this.courseCertificate) {
                const certificateLink = `${ this.SITE_URL }/certificado/${ this.courseCertificate.data.id }`;
  
                this.courseCertificateSocial = {
                  whatsapp: `whatsapp://send?text=Termine mi curso de ${ this.courseCertificate.course } en Aprende MAB, ${ certificateLink }`,
                  facebook: `https://www.facebook.com/sharer/sharer.php?u=${ certificateLink }`,
                  linkedin: `http://www.linkedin.com/shareArticle?mini=true&url=${ certificateLink }`,
                  twitter: `https://twitter.com/share?text=Hola, Termine mi curso de ${ this.courseCertificate.course } en Aprende MAB&url=${ certificateLink }`,
                }
              }

              if (!response.data.notification) this.isOpenedCertificateModal = true;
            }
          }
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
    getComments: function(reset = false){
      if (reset) this.commentsPaged = 0;

      if(this.commentsPaged != -1){
        fetch(`${this.API}/topic/${this.topicID}/comments?paged=${ this.commentsPaged + 1 }&user_id=${ this.logedUser ? this.logedUser.user_id : '' }&course_id=${ this.metas.get('course_id') }`,{
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
            this.comments.number  = comments.number;
            this.comments.sticky  = comments.sticky;

            this.comments.is_user_owner = comments.is_user_owner;

            if (!reset) this.comments.list.push(...comments.list);
            else this.comments.list = comments.list;

            this.commentsPaged += 1
          })
          .catch(err => {
            this.commentsPaged = -1
            throw err;          
          })          
      }
    },
    isUserAuthOnTopic: function(course_id){
      if(!this.metas.get('course_id') && !this.metas.get('unity')){
        window.location = this.courseLink;
      }else{
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
          this.addCourseToShopCart({
            id: course_id,
            title: this.unityData.course.slug,
            link: `${ this.SITE_URL }/curso/${ this.unityData.course.slug }`,
            url: this.SITE_URL
          });

          throw err;
        })
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
    },

    getCourseUnity: function(){
      fetch(`${ this.API }/courses/${ this.metas.get('course_id') }/unities/${ this.metas.get('unity') }?_wpnonce=${ mab.nonce }`,{
          method: 'GET'
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json();
          } else {
            throw res;
          }
        })
        .then(response => {
          if (response.status)
            this.unityData = response.data;
        })
        .catch(err => {
          throw err;          
        })      
    },
    getTopicLink: function(topicLink, topicIndex, unityIndex) {
      const previousUnities = this.unities.slice(0, unityIndex);
      let topicNumber = 0;

      if (unityIndex == 0) {
        topicNumber = topicIndex + (unityIndex + 1);
      } else {
        let unitiesLength = 0;

        previousUnities.forEach(element => {
          unitiesLength += element.topics.length;
        });

        topicNumber = topicIndex + (unityIndex + 1) + (unitiesLength - unityIndex);
      }

      return `${ topicLink }?course_id=${ this.metas.get('course_id') }&topic_number=${ topicNumber }&unity=${ unityIndex + 1 }`;
    },

    resetAccordion: function(question) {
      this.questionsAlter = this.questionsAlter.map(q => {
        return (q != question) ? { ...q, enable : false } : q;
      })
    },

    closeCertificateModal: function() {
      const courseId = this.metas.get('course_id');

      const formData = new FormData();

      formData.append('user_email', this.logedUser.user_email);
      formData.append('_wpnonce', mab.nonce);

      fetch(`${ this.API }/courses/${ courseId }/progress/notification`, {
        method: 'POST',
        body: formData,
      })
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(response => {
        console.log('Exit');
      })
      .catch(err => {
        throw err;
      })

      this.isOpenedCertificateModal = false;
    },
  }
})

import Vue from 'vue'
import Vuex from 'vuex'
import Swiper from 'swiper'
import {updateUserLoginSession} from '../../../libs/login'

Vue.component('questionary-student',{
  template: /*html*/`
    <section class="c-modal position-fixed width-100 height-100 padding-vertical-2" :class="{ opened : isActiveModal }">
      <div class="grid-container height-100">
        <div class="grid-x align-center-middle height-100">
          <div class="cell medium-10 large-9">
            <div class="c-modal__content position-relative flex-container flex-dir-column questions br--medium bg-white">
              <div class="flex-container align-right margin-bottom-1">
                <button @click="isActiveModal = false">
                  <span class="c-icon fs-30"><i class="far fa-times"></i></span>
                </button>
              </div>
              <section class="c-modal__body padding-vertical-1 padding-right-1">
                <h2 class="c-questions__title f1 text-uppercase dark-gray fs-25 w-bold margin-bottom-2 text-center fs-60">
                  Cuestionario de seguimiento
                </h2>
                <div id="slider-questionary-questions" class="c-questions__list swiper-container margin-bottom-1">
                  <div class="swiper-wrapper">
                    <div class="swiper-slide">
                      <div class="text-center">
                        <p class="fs-18 f2 w-medium text-center">
                          A continuación, encontraras unas preguntas para conocerte un poco más. En esta parte no hay respuestas correctas o incorrectas, ¡no tiene nota! Apreciamos mucho la honestidad. Por favor, lee las frases siguientes y marca el nivel de acuerdo o desacuerdo que tienes con cada una de ellas.
                        </p>
                        <button 
                          class="c-button c-button--secondary w-medium f2 fs-18"
                          @click="changeQuestion('next', true)">Iniciar</button>
                      </div>
                    </div>
                    <div v-for="(q_, qindex) of questionaryStudent.base.list" :key="q_.id" class="swiper-slide">
                      <h3 class="fs-21 f2 dark-gray w-bold  margin-bottom-2">{{q_.question.value}}</h3>                  
                      <ul class="c-question f2 w-medium ul-reset overflow-hidden">
                        <li
                          class="c-question__item dark" 
                          v-for="(option, oindex) of questionaryStudent.base.options[qindex]" :key="option.id">
                          <input
                            :id="'option-base-' + qindex + oindex"
                            :name="'question-base-' + qindex"
                            :value="option"
                            type="radio" 
                            class="hide"
                            v-model="questionaryResult[qindex].value"
                            @change="enableNext(qindex)"
                          >
                          <label 
                            :for="'option-base-' + qindex + oindex" 
                            class="c-option fs-18 align-middle width-100"
                          >
                            <div class="c-option__checkbox position-relative margin-right-1">
                              <span class="display-block rounded position-relative"></span>
                            </div>
                            <p class="margin-bottom-0">                      
                              {{option}}
                            </p>
                          </label>
                        </li>
                      </ul>
                    </div>
                    <div class="swiper-slide">
                      <div class="text-center">
                        <p class="fs-18 f2 w-medium text-center">
                          Porfavor marca con que frecuencia realizas las siguientes acciones cada vez que tienes que tomar una decision
                        </p>
                        <button 
                          class="c-button c-button--secondary w-medium f2 fs-18"
                          @click="changeQuestion('next', true)">Iniciar</button>
                      </div>
                    </div>
                    <div v-for="(q_, qindex) of questionaryStudent.decisions.list" :key="q_.id" class="swiper-slide">
                      <h3 class="fs-21 f2 dark-gray w-bold  margin-bottom-2">{{q_.question.value}}</h3>                  
                      <ul class="c-question f2 w-medium ul-reset overflow-hidden">
                        <li
                          class="c-question__item dark" 
                          v-for="(option, oindex) of questionaryStudent.decisions.options[qindex]" :key="option.id">
                          <input
                            :id="'option-decision-' + qindex + oindex"
                            :name="'question-decision-' + qindex"
                            :value="option"
                            type="radio" 
                            class="hide"
                            v-model="questionaryResult[questionaryStudent.base.count + qindex].value"
                            @change="enableNext(qindex)"
                          >
                          <label 
                            :for="'option-decision-' + qindex + oindex" 
                            class="c-option fs-18 align-middle width-100"
                          >
                            <div class="c-option__checkbox position-relative margin-right-1">
                              <span class="display-block rounded position-relative"></span>
                            </div>
                            <p class="margin-bottom-0">                      
                              {{option}}
                            </p>
                          </label>
                        </li>
                      </ul>
                    </div>                      
                  </div>
                </div>
                <div v-if="questionaryResult.length > 0" class="flex-container align-middle align-right">
                  <div class="flex-container align-middle" :class="{ enable: isEnableChange, hide: questionaryDone }">
                    <button 
                      class="flex-container f2 fs-18 w-medium align-center-middle"
                      :class="{ 'sec-color' : currentQuestion != 1 }"
                      @click="changeQuestion('previous')" 
                      :disabled="currentQuestion == 1">
                      <span class="c-icon margin-left-1"><i class="far fa-arrow-left"></i></span>
                    </button>                 
                    <p class="c-questions__progress margin-bottom-0 fs-18 f2 w-medium dark-gray text-center margin-horizontal-1">
                      <span>{{currentQuestion}}</span> de <span>{{limitQuestion}}</span>
                    </p>
                    <button 
                      class="flex-container f2 fs-18 w-medium align-center-middle"
                      :class="{ 'sec-color' : (isEnableChange || ( (currentMode=='base') ? questionaryResult[currentQuestion - 1].value != '' : questionaryResult[questionaryStudent.base.count].value != '')) }"
                      @click="changeQuestion('next')" 
                      :disabled="!isEnableChange && ( (currentMode=='base') ? questionaryResult[currentQuestion - 1].value=='' : questionaryResult[questionaryStudent.base.count].value=='') ">
                      Siguiente
                      <span class="c-icon margin-left-1"><i class="far fa-arrow-right"></i></span>
                    </button>                                   
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </section>
  `,
  data() {
    return {
      isActiveModal: true,

      //Questionary
      isEnableChange: false,

      currentMode: 'base',
      currentQuestion: 1,
      limitQuestion: 1,

      questionaryDone: false,
      questionaryStudent: {
        base: {
          count: 0,
          list: [],
          options: []
        },
        decisions: {
          count: 0,
          list: [],
          options: []
        }
      },
      questionaryResult: [],
      sliderQuestions: null
    }
  },
  computed: {
    ...Vuex.mapState(['API', 'SITE_URL', 'logedUser'])
  },
  mounted(){
    this.getQuestionary();
  },
  methods: {
    changeQuestion: function(direction, single){
      if (direction == 'next') {
        if (single) {
          this.sliderQuestions.slideNext()

        } else {
          if(this.sliderQuestions.slideNext()){
            this.isEnableChange = false
  
            if (this.currentMode == 'base') {
              if(this.currentQuestion < this.questionaryStudent.base.count){
                this.currentQuestion += 1;
              }else{
                this.currentQuestion = 1;
                this.limitQuestion = this.questionaryStudent.decisions.count;
                this.currentMode = 'decisions'
              }
            } else if(this.currentMode == 'decisions'){
              if(this.currentQuestion < this.questionaryStudent.decisions.count){
                this.currentQuestion += 1;
              }                
            }
          }else{
            this.questionaryDone = true;
            this.isActiveModal = false;
            this.saveQuestionary()
          }
        }
      }else if(direction == 'previous'){
        if(this.sliderQuestions.slidePrev()){
          this.isEnableChange = true
          this.currentQuestion -= 1;
        }
      }      
    },
    enableNext: function(questionIndex){
      if(this.questionaryResult[questionIndex].value != '')
        this.isEnableChange = true
    },
    getQuestionary: function(){
      fetch(`${this.API}/behaviour/questionary?rol=student`,{
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
          questions.base.list.forEach(q => {
            this.questionaryResult.push({
              key: q.question.key,
              value: ''
            })
            switch (q.question.option_type) {
              case 'autoestima':
                  this.questionaryStudent.base.options.push([
                    "Muy de acuerdo",
                    "De acuerdo",
                    "En desacuerdo",
                    "Muy en desacuerdo"
                  ])                
                break;

              case 'empatia':
                  this.questionaryStudent.base.options.push([
                    "Totalmente de acuerdo",
                    "De acuerdo",
                    "Ni de acuerdo ni en desacuerdo ",
                    "En desacuerdo",
                    "Totalmente en desacuerdo "
                  ])                
                break;

              case 'SDQ':
                  this.questionaryStudent.base.options.push([
                    "De acuerdo",
                    "Ni de acuerdo ni en desacuerdo ",
                    "En desacuerdo"
                  ])                
                break;
            }
          })

          questions.decisions.list.forEach(q => {
            this.questionaryResult.push({
              key: q.question.key,
              value: ''
            })
            this.questionaryStudent.decisions.options.push([
              "Siempre",
              "Usualmente",
              "A veces",
              "Nunca"
            ])
          })

          this.questionaryStudent.base.list = questions.base.list;
          this.questionaryStudent.base.count = questions.base.count;

          this.questionaryStudent.decisions.list = questions.decisions.list;
          this.questionaryStudent.decisions.count = questions.decisions.count;

          this.limitQuestion = questions.base.count;

          window.setTimeout(()=>{
            this.sliderQuestions = new Swiper('#slider-questionary-questions', {
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
    saveQuestionary: function(){
      let form_data = new FormData();

      form_data.append('user_email', this.logedUser.user_email)
      form_data.append('result', JSON.stringify(this.questionaryResult))

      fetch(`${this.API}/behaviour/questionary?rol=student`,{
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
          updateUserLoginSession('user_metas.questionary', {
            questionary: true
          })
        })
        .catch(err => {
          throw err;
        }) 
    }      
  },
})

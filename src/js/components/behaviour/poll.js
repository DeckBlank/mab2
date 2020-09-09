import Vue from 'vue'
import Vuex from 'vuex'
import Swiper from 'swiper'
import {updateUserLoginSession} from '../../libs/login'

Vue.component('poll',{
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
                  Encuesta de satisfacción
                </h2>
                <div id="slider-poll-questions" class="c-questions__list swiper-container margin-bottom-1">
                  <div class="swiper-wrapper">
                    <div v-for="(q_, qindex) of poll.list" :key="q_.id" class="swiper-slide">
                      <h3 class="fs-21 f2 dark-gray w-bold  margin-bottom-2">{{q_.question.value}}</h3>                  
                      <ul class="c-question f2 w-medium ul-reset overflow-hidden">
                        <li
                          class="c-question__item dark" 
                          v-for="(option, oindex) of poll.options[qindex]" :key="option.id">
                          <input
                            :id="'option-' + qindex + oindex"
                            :name="'question-' + qindex"
                            :value="option"
                            type="radio" 
                            class="hide"
                            v-model="pollResult[qindex].value"
                            @change="enableNext(qindex)"
                          >
                          <label 
                            :for="'option-' + qindex + oindex" 
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
                <div v-if="pollResult.length > 0" class="flex-container align-middle align-right">
                  <div class="flex-container align-middle" :class="{ enable: isEnableChange, hide: pollDone }">                       
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
                      :class="{ 'sec-color' : (isEnableChange || pollResult[currentQuestion - 1].value != '') }"
                      @click="changeQuestion('next')"
                      :disabled="!isEnableChange && pollResult[currentQuestion - 1].value == ''">
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

      //Poll
      isEnableChange: false,

      currentQuestion: 1,
      limitQuestion: 1,

      pollDone: false,
      poll: {
        count: 0,
        list: [],
        options: []
      },
      pollResult: [],
      sliderQuestions: null
    }
  },
  computed: {
    ...Vuex.mapState(['API', 'SITE_URL', 'logedUser'])
  },
  mounted(){
    this.getPoll();
  },
  methods: {
    changeQuestion: function(direction){
      if (direction == 'next') {
        if(this.sliderQuestions.slideNext()){
          this.isEnableChange = false

          if(this.currentQuestion < this.poll.count){
            this.currentQuestion += 1;
          }
        }else{
          this.pollDone = true;
          this.isActiveModal = false;
          this.savePoll()
        }
      }else if(direction == 'previous'){
        if(this.sliderQuestions.slidePrev()){
          this.isEnableChange = true
          this.currentQuestion -= 1;
        }
      }
    },
    enableNext: function(questionIndex){
      if(this.pollResult[questionIndex].value != '')
        this.isEnableChange = true
    },
    getPoll: function(){
      fetch(`${this.API}/behaviour/poll`,{
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
            this.pollResult.push({
              key: q.question.key,
              value: ''
            })

            this.poll.options.push( q.question.options.map(op => op.option ) )
          })

          this.poll.list = questions.list;
          this.poll.count = questions.count;

          this.limitQuestion = questions.count;

          window.setTimeout(()=>{
            this.sliderQuestions = new Swiper('#slider-poll-questions', {
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
    savePoll: function(){
      let form_data = new FormData();

      form_data.append('user_email', this.logedUser.user_email)
      form_data.append('result', JSON.stringify(this.pollResult))

      fetch(`${this.API}/behaviour/poll?rol=${this.logedUser.user_rol}`,{
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
          updateUserLoginSession('user_metas.poll', true)
        })
        .catch(err => {
          throw err;
        }) 
    }      
  },
})

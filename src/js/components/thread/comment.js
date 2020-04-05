import Vue from 'vue'
import Vuex from 'vuex'
import '../likes';
import '../editor';
import './answer';

Vue.component('comment',{
  template: /*html*/`
    <div class="c-comment fs-18 margin-bottom-2">
      <div class="flex-container align-middle margin-bottom-1">
        <div class="margin-right-1">
          <figure class="c-avatar overflow-hidden rounded">
            <img class="width-100 height-100 of--cover" :src="pic" alt="">
          </figure>                  
        </div>
        <div class="flex-container align-middle">
          <p class="margin-bottom-0 fs-18 margin-right-1">{{body.author}}</p>
          <span class="c-comment__date gray-gray fs-16">{{(new Date(body.date)).toLocaleDateString('es', { weekday: 'long', month: 'long', day: 'numeric' })}}</span>
        </div>
      </div>
      <div class="c-comment__body">
        <div class="c-comment__content margin-bottom-1">
          {{body.content}}
        </div>
        <div class="flex-container align-middle margin-bottom-1">
          <button v-if="logedUser" class="flex-container align-middle" @click="isShowedAnswerEditor = true">
            <span class="margin-right-1"><i class="far fa-reply"></i></span>
            Responder
          </button>
        </div>
        <div class="flex-container margin-bottom-2" :class="{ hide : !isShowedAnswerEditor}">
          <div class="margin-right-1">
            <figure class="c-avatar c-avatar--small overflow-hidden rounded">
              <img :src="pic" alt="">
            </figure>
          </div>
          <div class="width-100">
            <editor
              :target="{ type: 'answer', id: body.id }"
              :post="post"
              :thread.sync="answers"
              :flag.sync="isShowedAnswerEditor">
            </editor>
          </div>      
        </div>                  
        <div v-if="answers.list.length != 0" class="margin-bottom-1">
          <button 
            class="c-show-answers sec-alt flex-container align-middle" 
            :class="{ showed : isShowedAnswers }" 
            @click="isShowedAnswers = !isShowedAnswers"
          >
            <span class="c-icon margin-right-1"><i class="far fa-chevron-down"></i></span> 
            <p v-if="answers.list.length < 5 || answersPaged == -1  " class="margin-bottom-0">Ver {{answers.list.length}} respuesta(s)</p>
            <p v-else class="margin-bottom-0">Ver {{answers.list.length}}+ respuesta(s)</p>
          </button>
        </div>                   
        <div class="c-comment__answers" :class="{ hide : !isShowedAnswers }">
          <answer v-for="answer of answers.list" :key="answer.id" :body="answer" :pic="pic"></answer>
          <button 
            v-if="answersPaged != -1 && answers.list.length != 0 && answers.list.length >= 5 " 
            class="sec-alt flex-container align-middle"
            @click="getAnswers"
          >
            <span class="c-icon margin-right-1"><i class="far fa-ellipsis-h"></i></span> 
            <p class="margin-bottom-0">Mostrar más respuestas</p>
          </button>
        </div>
      </div>
    </div>
  `,
  props: {
    pic: String,
    body: Object,
    post: Object
  },
  data() {
    return {
      isShowedAnswers: false,
      isShowedAnswerEditor: false,
      isLoadingAnswers: false,
      answersPaged: 0,
      answers: {
        list: this.body.answers
      }
    }
  },
  computed: {
    ...Vuex.mapState(['API', 'logedUser'])
  },
  methods: {
    getAnswers: function(){
      if(this.answersPaged != -1){
        fetch(`${this.API}/comment/${this.body.id}/answers?paged=${this.answersPaged + 1}`,{
            method: 'GET'
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(answers => {
            this.answers.list.push(...answers);
            this.answersPaged += 1
          })
          .catch(err => {
            this.answersPaged = -1
            throw err;          
          })          
      }
    }
  },
})
              
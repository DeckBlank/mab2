import Vue from 'vue'
import Vuex from 'vuex'
import '../likes';
import '../editor';
import './answer';

Vue.component('comment',{
  template: /*html*/`
    <div class="c-comment-container">
      <div class="c-comment c-comment--small br-top--large br-bottom--large margin-bottom-1">
        <div class="grid-x">
          <div class="small-2 medium-2 large-2">
            <figure class="c-comment__figure">
              <img v-if="body.authorAvatar" :src="body.authorAvatar" alt="" class="width-100 height-100 of--cover"> 
              <img v-else :src="THEME_URL + '/static/images/user.png'" alt="" class="width-100 height-100 of--cover"> 
            </figure>
          </div>
          <div class="small-10 medium-8 large-8">
            <div class="c-comment-content">
              <div class="c-comment__autor f2 w-xbold dark fs-21">
                {{ body.author }} <span v-if="false" class="padding-left-1 fs-16"><i class="far fa-thumbtack"></i></span>
              </div>
              <div v-if="body.authorField" class="c-comment__rol f2 margin-bottom-1 fs-14 lh-14">
                {{ body.authorField }}
              </div>
              <p class="c-comment__text f2 dark fs-16 lh-18">
                {{ body.content }}
              </p>
              <div class="c-comment__images flex-container margin-bottom-1">
                <figure>
                  <img class="width-100 height-100 of--cover" src="https://mab.site/wp-content/themes/mab-theme/app/static/images/curso/certificate.jpg">
                </figure>
                <figure>
                  <img class="width-100 height-100 of--cover" src="https://mab.site/wp-content/themes/mab-theme/app/static/images/curso/certificate.jpg">
                </figure>
                <figure>
                  <img class="width-100 height-100 of--cover" src="https://mab.site/wp-content/themes/mab-theme/app/static/images/curso/certificate.jpg">
                </figure>
              </div>
            </div>
          </div>
          <div class="cell medium-2 large-2">
            <div class="flex-container align-bottom align-right text-right f2 height-100 padding-right-1 fs-14">
              <button @click="isShowedAnswerEditor = !isShowedAnswerEditor"><span>Responder</span></button>
              <button v-if="false"><span class="padding-left-1 flex-container align-middle"><i class="far fa-heart"></i><span class="c-comment__like">12</span></span></button>
            </div>
          </div>
        </div>
      </div>
      <div v-if="isShowedAnswerEditor" class="c-answer-editor">
        <div class="grid-x align-right">
          <div class="small-11 medium-11 large-11">
            <editor
              :target="{ type: 'answer', id: body.id }"
              :post="post"
              :thread.sync="answers"
              :flag.sync="isShowedAnswerEditor">
            </editor>
          </div>
        </div>
      </div>
      <div v-if="answers.list.length" class="c-answers-container">
        <div class="grid-x align-right">
          <div class="small-11 medium-11 large-11">
            <answer v-for="answer of answers.list" :key="answer.id" :body="answer" :pic="pic"></answer>
            <button 
              v-if="answersPaged != -1 && answers.list.length != 0 && answers.list.length >= 5 " 
              class="sec-alt flex-container align-middle f2"
              @click="getAnswers"
            >
              <span class="c-icon margin-right-1"><i class="far fa-ellipsis-h"></i></span> 
              <p class="margin-bottom-0">Mostrar más respuestas</p>
            </button>
          </div>
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
    ...Vuex.mapState(['API', 'THEME_URL', 'logedUser'])
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
              
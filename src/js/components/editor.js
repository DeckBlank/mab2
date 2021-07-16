import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('editor',{
  template: /*html*/`
    <div class="c-mab-editor flex-container align-center-middle margin-bottom-1">
      <div v-if="false" class="c-mab-editor__reactions flex-container align-center-middle">
        <button class="fs-25">
          <i class="far fa-smile"></i>
        </button>
        <button class="fs-25">
          <i class="far fa-paperclip"></i>
        </button>
      </div>
      <input v-model="textContent" class="input-reset margin-0 f2" type="text" placeholder="Escribe un comentario">
      <button :disabled="isLoading" @click="add()" class="fs-25">
        <i class="far fa-paper-plane"></i>
      </button>
    </div>
  `,
  data() {
    return {
      isActiveEditor: false,
      textContent: '',

      isLoading: false,
    }
  },
  computed: {
    ...Vuex.mapState(['API', 'SITE_URL', 'logedUser'])
  },
  props: {
    flag: Boolean,
    target: Object,
    post: Object,
    thread: Object
  },
  methods: {
    activeEditor: function(){
      if(this.logedUser){
        this.isActiveEditor = true
      }else{
        window.location = `${this.SITE_URL}/access`
      }
    },
    addNewComment: function(){
      let form_data = new FormData();

      this.isLoading = true;

      form_data.append('user', this.logedUser.user_auth)
      form_data.append('user_email', this.logedUser.user_email)
      form_data.append('content', this.textContent)

      fetch(`${this.API}/${this.post.type}/${this.target.id}/comment`,{
          method: 'POST',
          body: form_data,
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(comment => {
          this.updateThread(comment);

          this.isLoading = false;
        })
        .catch(err => {
          throw err;          
        })      
    },
    addNewAnswer: function(){
      let form_data = new FormData();

      this.isLoading = true;

      form_data.append('user', this.logedUser.user_auth)
      form_data.append('user_email', this.logedUser.user_email)
      form_data.append('content', this.textContent)

      fetch(`${this.API}/${this.post.type}/${this.post.id}/comment/${this.target.id}/answer`,{
          method: 'POST',
          body: form_data,
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(answer => {
          this.updateThread();

          this.isLoading = false;
        })
        .catch(err => {
          throw err;          
        })      
    },
    add: function() {
      if(this.textContent != ''){
        if(this.target.type == "post"){
          this.addNewComment()
        }else if(this.target.type == "answer"){
          this.addNewAnswer()
        }
      }
    },
    updateThread: function(commentId = null) {
      if (this.target.type == 'post') {
        this.$emit('update:thread', {
          number: this.thread.number + 1,
          list: [{
            id: commentId,
            author: this.logedUser.user_auth,
            authorField: '',
            authorAvatar: this.logedUser.user_avatar,
            date: new Date(),
            content: this.textContent,
            answers: []
          }, ...this.thread.list]
        });
      } else {
        this.$emit('update:thread', {
          list: [{
              comment_author: this.logedUser.user_auth,
              comment_date: new Date(),
              comment_content: this.textContent
            }, 
            ...this.thread.list
          ]
        });
      }

      this.textContent = '';
    },
  },
})

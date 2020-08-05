import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('editor',{
  template: /*html*/`
    <div class="c-editor">
      <textarea class="c-editor__textarea input-reset bg-light-gray br--small margin-bottom-1" rows="1" v-model="textContent" @focus="activeEditor"></textarea>
      <div class="flex-container align-right" :class="{ hide : !isActiveEditor }">
        <button v-if="target.type == 'post'" class="c-button margin-right-1" @click="isActiveEditor = false">Cancelar</button>
        <button v-else class="c-button margin-right-1" @click="$emit('update:flag', false);">Cancelar</button>
        
        <button 
          v-if="target.type == 'post'" 
          class="c-button c-button--secondary-alt" 
          @click="$emit('update:thread', {
            number: thread.number + 1,
            list: [{
              author: logedUser.user_auth,
              date: new Date(),
              content: textContent,
              answers: []
            }, ...thread.list]
          });"
        >
          Comentar
        </button>
        <button 
          v-else 
          class="c-button c-button--secondary-alt" 
          @click="$emit('update:thread', {
            list: [{
                comment_author: logedUser.user_auth,
                comment_date: new Date(),
                comment_content: textContent
              }, 
              ...thread.list
            ]
          });"
        >
          Responder
        </button>
      </div>  
    </div>
  `,
  data() {
    return {
      isActiveEditor: false,
      textContent: ''
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
  watch: {
    thread: function(){
      if(this.textContent != ''){
        if(this.target.type == "post"){
          this.addNewComment()
        
        }else if(this.target.type == "answer"){
          this.addNewAnswer()
        
        }
      }
    }
  },
  methods: {
    activeEditor: function(){
      if(this.logedUser){
        this.isActiveEditor = true
      }else{
        window.location = `${this.SITE_URL}/login`
      }
    },
    addNewComment: function(){
      let form_data = new FormData();

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
          this.textContent = ''
        })
        .catch(err => {
          throw err;          
        })      
    },
    addNewAnswer: function(){
      let form_data = new FormData();

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
          this.textContent = ''
        })
        .catch(err => {
          throw err;          
        })      
    }
  },
})

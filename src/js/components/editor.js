import Vue from 'vue'
import Vuex from 'vuex'

import 'dm-file-uploader';
import $ from 'jquery';

Vue.component('editor',{
  template: /*html*/`
    <div class="c-mab-editor flex-container align-center-middle margin-bottom-1">
      <div v-if="true" class="c-mab-editor__reactions flex-container align-center-middle">
        <button class="">
          <i class="far fa-smile"></i>
        </button>
        <div :id="'drop-comment-area-' + ( (this.target) ? this.target.id : '' )">
          <label class="">
            <i class="far fa-paperclip"></i>
            <input type="file" class="hide" accept=".jpg, .png, .jpeg, .PNG"/>
          </label>
        </div>
      </div>
      <input v-model="textContent" class="input-reset margin-0 f2" type="text" placeholder="Escribe un comentario">
      <button v-if="false" :disabled="isLoading" @click="add()" class="fs-25">
        <i class="far fa-paper-plane"></i>
      </button>
      <div class="c-mab-editor__list-figure width-100 flex-container">
        <!-- to trash  -->
          <figure class="c-mab-editor__figure position-relative">
            <img class="width-100 height-100 of--cover" src="https://mab.site/wp-content/themes/mab-theme/app/static/images/curso/certificate.jpg">
            <figcaption class="bg-transparent position-absolute width-100 height-100 flex-container">
              <div class="width-100 flex-container align-right">
              </div>
              <div class="c-img-icon white margin-bottom-0">
                <span class="bg-pri-color trash">
                  <i class="fas fa-trash"></i>
                </span>
              </div>
              <div></div>
            </figcaption>
          </figure>
          <!-- error  -->
          <figure class="c-mab-editor__figure position-relative">
            <img class="width-100 height-100 of--cover" src="https://mab.site/wp-content/themes/mab-theme/app/static/images/curso/certificate.jpg">
            <figcaption class="position-absolute width-100 height-100 flex-container">
              <div class="width-100 flex-container align-right">
                <button class="c-img-cross flex-container align-center-middle white bg-pri-color"><i class="fas fa-times white"></i></button>
              </div>
              <div class="c-img-icon white">
                <span class="">
                  <i class="fas fa-sad-tear"></i>
                </span>
                <span>Error</span>
              </div>
              <div></div>
              <div class="c-img-loader width-100">
                <div style="width: 50%" class="height-100 bg-pri-color"></div>
              </div>
            </figcaption>
          </figure>
          <!-- cargando -->
          <figure class="c-mab-editor__figure position-relative">
            <img class="width-100 height-100 of--cover" src="https://mab.site/wp-content/themes/mab-theme/app/static/images/curso/certificate.jpg">
            <figcaption class="position-absolute width-100 height-100 flex-container">
              <div class="width-100 flex-container align-right">
                <button class="c-img-cross flex-container align-center-middle white bg-pri-color"><i class="fas fa-times white"></i></button>
              </div>
              <div class="c-img-icon white">
                <span>
                </span>
                <span></span>
              </div>
              <div></div>
              <div class="c-img-loader width-100">
                <div style="width: 50%" class="height-100 bg-limon"></div>
              </div>
            </figcaption>
          </figure>
      </div>
      <div class="c-mab-editor__list-btn width-100 flex-container align-end">
        <button class="c-button c-button--white-black">Cancelar</button>
        <button class="c-button c-button--secondary i-white">Publicar</button>
      </div>
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
  mounted() {
    this.init();
  },
  methods: {
    init: function() {
      $(`#drop-comment-area-${ (this.target) ? this.target.id : '' }`).dmUploader({
        auto: true,
        queue: true,
        url: `${this.API}/topics/comments/attachments`,
        method: 'POST',
        maxFileSize: 300000000,
        fieldName: 'image',
        dataType: 'json',
        extFilter: ['png', 'jpg', 'jpeg'],
        // onNewFile: this.addNewFile,
        // onUploadProgress: this.updateProgressFile,
        // onUploadCanceled: this.handleCancel,
        // onBeforeUpload: this.handleBeforeUpload,
        // onUploadError: this.handleError,
        // onUploadSuccess: this.handleUploadSuccess,
        // onUploadComplete: this.handleUploadComplete,
        // onFileSizeError: this.handleFileSizeError,
        // onFileExtError: this.handleFileExtError,
        // onComplete: this.handleAllFilesCompleted,
      });
    },

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

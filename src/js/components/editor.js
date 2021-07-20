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
        <div
          v-for="(attachment, aindex) of attachments.data"
          :key="attachment.id"
        >
          <figure
            v-if="attachment.status != -2"
            class="c-mab-editor__figure position-relative"
            :class="[{'c-atachment--error' : attachment.status == 0}, {'loaded' : attachment.status == 2}]"
          >
            <img class="width-100 height-100 of--cover" :src="attachment.src">
            <figcaption class="bg-transparent position-absolute width-100 height-100 flex-container">
              <div class="width-100 flex-container align-right">
                <button
                  v-if="attachment.status == -1"
                  @click="cancelUploadFile(attachment.id, aindex)"
                  class="c-img-cross flex-container align-center-middle white bg-pri-color"
                >
                  <i class="fas fa-times white"></i>
                </button>
              </div>

              <button
                @click="handleDeleteFile(attachment.idRemote, aindex)"
                v-if="attachment.status == 2"
                class="c-img-icon white margin-bottom-0"
                :disabled="attachment.isDeleting"
              >
                <span class="bg-pri-color trash">
                  <i class="fas fa-trash white"></i>
                </span>
              </button>

              <div v-if="attachment.status == 0" class="c-img-icon white">
                <span class="">
                  <i class="fas fa-sad-tear"></i>
                </span>
                <span>Error</span>
              </div>

              <div></div>

              <div v-if="attachment.status != 2" class="c-img-loader width-100">
                <div :style="'width:' + attachment.progress + '%'" class="height-100 bg-limon"></div>
              </div>
            </figcaption>
          </figure>
        </div>
      </div>
      <div class="c-mab-editor__list-btn width-100 flex-container align-end">
        <button v-if="target.type == 'answer'" @click="cancel()" class="c-button c-button--white-black f2">Cancelar</button>
        <button @click="add()" :disabled="isLoading || !isReadyComment" class="c-button c-button--secondary i-white f2">Publicar</button>
      </div>
    </div>
  `,
  data() {
    return {
      isActiveEditor: false,
      textContent: '',

      isLoading: false,

      attachments: {
        isReady: true,
        ids: {},
        data: [],
        temp: 0,
      },
    }
  },
  computed: {
    ...Vuex.mapState(['API', 'SITE_URL', 'logedUser']),

    isReadyComment: function() {
      return (this.attachments.isReady && this.textContent.length) ? true : false;
    },
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
        onNewFile: this.addNewFile,
        onUploadProgress: this.updateProgressFile,
        onUploadCanceled: this.handleCancel,
        onUploadError: this.handleError,
        onUploadSuccess: this.handleUploadSuccess,
        onUploadComplete: this.handleUploadComplete,
        onFileSizeError: this.handleFileSizeError,
        onFileExtError: this.handleFileExtError,
        onComplete: this.handleAllFilesCompleted,
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

      this.attachments.data = this.attachments.data.filter(atch => atch.status != -2);
      this.isLoading        = true;

      form_data.append('user', this.logedUser.user_auth)
      form_data.append('user_email', this.logedUser.user_email)
      form_data.append('content', this.textContent)
      form_data.append('attachment', this.attachments.data.map(atch => atch.idRemote).join())

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

      this.attachments.data = this.attachments.data.filter(atch => atch.status != -2);
      this.isLoading        = true;

      form_data.append('user', this.logedUser.user_auth)
      form_data.append('user_email', this.logedUser.user_email)
      form_data.append('content', this.textContent)
      form_data.append('attachment', this.attachments.data.map(atch => atch.idRemote).join())

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
          ...this.thread,
          number: this.thread.number + 1,
          list: [{
            id: commentId,
            author: this.logedUser.user_auth,
            authorField: '',
            authorAvatar: this.logedUser.user_avatar,
            date: new Date(),
            content: this.textContent,
            likes: 0,
            likedUser: false,
            attachments: this.attachments.data,
            answers: []
          }, ...this.thread.list]
        });
      } else {
        this.$emit('update:thread', {
          ...this.thread,
          list: [
            {
              authorAvatar: this.logedUser.user_avatar,
              comment_author: this.logedUser.user_auth,
              comment_date: new Date(),
              comment_content: this.textContent,
              attachments: this.attachments.data,
            }, 
            ...this.thread.list
          ]
        });
      }

      this.attachments.data = [];
      this.attachments.ids  = {};
      this.attachments.temp = 0;

      this.textContent = '';
    },

    cancel: function() {
      this.$emit('update:flag', false);
    },

    getFileIndex: function(id) {
      return this.attachments.ids[id].index;
    },
    addNewFile: function(id, file) {
      if (this.attachments.temp < 3) {
        this.attachments.isReady = false;

        const reader = new FileReader();

        reader.onload = (e) => {
          this.attachments.data.push({
            id: id,
            idRemote: -1,
            src: e.target.result,
            url: '',
            status: -1,
            progress: 0,
            isDeleting: false,
          })

          this.attachments.ids[id] = {
            id: id,
            index: this.attachments.data.length - 1,
          }
        }

        reader.readAsDataURL(file);

        this.attachments.temp++;
      } else {
        return false;
      }
    },
    cancelUploadFile: function(id, index) {
      $(`#drop-comment-area-${ (this.target) ? this.target.id : '' }`).dmUploader('cancel', id);
    },
    updateProgressFile: function(id, percent) {
      this.attachments.data[this.getFileIndex(id)].progress = percent;
    },
    handleError: function(id) {
      this.attachments.data[this.getFileIndex(id)].status = 0;
    },
    handleCancel: function(id) {
      this.attachments.data[this.getFileIndex(id)].progress = 0;
      this.attachments.data[this.getFileIndex(id)].status   = -2;
    },
    handleDeleteFile: function(idRemote, index) {
      this.attachments.data[index].isDeleting = true;

      fetch(`${this.API}/topics/comments/attachments/${ idRemote }`,{
        method: 'DELETE',
      })
      .then(res => {
        if (res.status == 201 || res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(response => {
        window.setTimeout(() => {
          this.attachments.data[index].progress   = 0;
          this.attachments.data[index].status     = -2;
          this.attachments.data[index].isDeleting = false;
        }, 1000)
      })
      .catch(err => {
        throw err;
      })
    },
    handleUploadSuccess: function(id, response) {
      if (response.status) {
        this.attachments.data[this.getFileIndex(id)].status   = 1;
        this.attachments.data[this.getFileIndex(id)].url      = response.data.path;
        this.attachments.data[this.getFileIndex(id)].idRemote = response.data.id;
      } else {
        this.attachments.data[this.getFileIndex(id)].status = 0;
      }
    },
    handleUploadComplete: function(id) {
      this.attachments.data[this.getFileIndex(id)].status = 2;
    },
    handleAllFilesCompleted: function() {
      this.attachments.isReady = true;
    },
    handleFileSizeError: function(file) {
      alert('Archivo muy pesado')
    },
    handleFileExtError: function(file) {
      alert('Archivo muy no permitido')
    },
  },
})

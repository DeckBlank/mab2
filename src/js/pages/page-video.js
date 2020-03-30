import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import '../components/likes';
import '../components/editor';
import '../components/thread/comment';

const video = new Vue({
  ...baseConfig(store),
  data() {
    return {
      videoID: null,
      likesAverage: 0,
      comments: {
        number: 0,
        list: []
      },
      commentsPaged: 0,
      isLoadingComments: false
    }
  },
  computed: {
    ...baseState(),
  },
  mounted(){
    this.videoID = this.$refs.video.getAttribute('data-id');

    this.getLikesAverage()
    this.getComments()
  },
  methods: {
    ...baseActions(),
    getLikesAverage: function(){
      fetch(`${this.API}/video/${this.videoID}/likes`,{
          method: 'GET'
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(average => {
          this.likesAverage = parseFloat(average[0]);
        })
        .catch(err => {    
          throw err;          
        })      
    },
    getComments: function(){
      if(this.commentsPaged != -1){
        fetch(`${this.API}/video/${this.videoID}/comments?paged=${this.commentsPaged + 1}`,{
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
    }
  }
})

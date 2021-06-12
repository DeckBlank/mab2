import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('likes',{
  template: /*html*/`
    <div class="c-likes f2 position-relative" :class="{ done : isLiked }">
      <button 
        class="c-likes__toggle flex-container align-middle" 
        @click="addNewLike(5)"
        :disabled="isLiked"
      >
        <span class="c-icon fs-21 margin-right-1"><i class="fas fa-heart"></i></span>
        <p class="margin-bottom-0 black w-medium">{{count}}</p>
      </button>
    </div>
  `,
  props: {
    count: Number,
    target: Object
  },
  data() {
    return {
      isLiked: false
    }
  },
  computed: {
    ...Vuex.mapState(['API', 'SITE_URL', 'logedUser'])
  },
  beforeMount() {
    if(this.logedUser) this.isUserLiked()
  },
  methods: {
    addNewLike: function(level){
      if(!this.logedUser){
        window.location = `${this.SITE_URL}/access`;
      }else{
        fetch(`${this.API}/${this.target.type}/${this.target.id}/likes?level=${level}&user=${this.logedUser.user_email}`,{
            method: 'PUT'
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              this.$emit('update:count', this.count + 1); this.isLiked = true
            }else{
              throw res
            }
          })
          .catch(err => {
            this.isLiked = false;
            throw err;          
          }) 
      }  
    },
    isUserLiked: function(){
      fetch(`${this.API}/${this.target.type}/${this.target.id}/likes/checkout?user=${this.logedUser.user_email}`,{
          method: 'GET'
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(score => {
          this.isLiked = true
        })
        .catch(err => {
          throw err;          
        })       
    }
  },
})

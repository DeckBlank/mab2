import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('likes',{
  template: /*html*/`
    <div class="c-likes f2 position-relative" :class="[{ done : isLiked }, { active : isActiveLikes}]">
      <button 
        class="c-likes__toggle flex-container align-middle" 
        @click="isActiveLikes = !isActiveLikes"
        :disabled="isLiked"
      >
        <span class="c-icon fs-21 margin-right-1"><i class="far fa-heart"></i></span>
        <p class="margin-bottom-0 gray-gray">{{likesAverage}}</p>
      </button>
      <div class="c-likes__list br--small bg-medium-gray position-absolute">
        <ul class="ul-reset">
          <li v-for="item of levels" :key="item.id" class="c-likes__item">
            <button class="flex-container align-center-middle" @click="addNewLike(item)">
              <span class="c-icon"><i class="far fa-heart"></i></span>
              {{item}}
            </button>
          </li>  
        </ul>
      </div>
    </div>
  `,
  props: {
    average: {
      type: Number,
      default: 0
    },
    target: Object
  },  
  data() {
    return {
      isActiveLikes: false,
      isLiked: false,
      levels: [5,4,3,2,1],
      likesAverage: this.average
    }
  },
  computed: {
    ...Vuex.mapState(['API', 'SITE_URL', 'logedUser'])
  },
  watch: {
    average: function(){
      this.likesAverage = this.average
    }
  },
  beforeMount() {
    this.isUserLiked()
  },
  methods: {
    addNewLike: function(level){
      if(!this.logedUser){
        window.location = `${this.SITE_URL}/login`;
      }else{
        fetch(`${this.API}/${this.target.type}/${this.target.id}/likes?level=${level}&now_average=${this.average}&user=${this.logedUser.user_email}`,{
            method: 'PUT'
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(saved_level => {
            this.likesAverage = saved_level
            this.isActiveLikes = false;
            this.isLiked = true
          })
          .catch(err => {
            this.likesAverage = 0;
            this.isActiveLikes = false;
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

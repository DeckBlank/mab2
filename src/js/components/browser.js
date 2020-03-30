import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('browser',{
  template: /*html*/`
    <div class="c-browser-container position-relative">
      <div class="c-browser flex-container" :class="{ active: isActiveBrowser }">
        <button class="c-browser__icon bg-light-gray height-100 flex-container align-middle" @click="search">
          <span class="c-icon"><i class="far fa-search"></i></span>
        </button>
        <input 
          type="text"
          class="c-browser__input input-reset height-100"
          v-model="query"
          @focus="isActiveBrowser = true" 
          @blur="isActiveBrowser = false"
          @keyup.enter="search"
        >
      </div>
      <div class="c-browser-result padding-horizontal-1 padding-top-1 position-absolute width-100 bg-white" :class="{ showed : (isActiveBrowser && (isLoadingBrowser || courses.length > 0 || videos.length > 0)) }">
        <div class="c-browser-result__loading text-center padding-bottom-1" :class="{ hide : !isLoadingBrowser }">Loading...</div>
        <ul class="c-browser-result__list ul-reset">
          <li class="c-browser-result__item padding-bottom-1" v-for="course of courses" :key="course.id">
            <a :href="SITE_URL + '/curso/' + course.post_name" class="flex-container align-justify">
              <p class="margin-bottom-0 dark margin-right-1">{{course.post_title}}</p>
              <span class="gray-gray">Curso</span>
            </a>
          </li>
          <li class="c-browser-result__item padding-bottom-1" v-for="video of videos" :key="video.id">
            <a :href="SITE_URL + '/video/' + video.post_name" class="flex-container align-justify">
              <p class="margin-bottom-0 dark margin-right-1">{{video.post_title}}</p>
              <span class="gray-gray">Video</span>
            </a>
          </li>
        </ul>
      </div>
    </div>    
  `,
  data() {
    return {
      isActiveBrowser: false,
      isLoadingBrowser: false,
      query: '',
      courses: [],
      videos: []
    }
  },
  computed: {
    ...Vuex.mapState(['API', 'SITE_URL'])
  },
  methods: {
    search: function(){
      if(this.query != ''){
        this.isLoadingBrowser = true;
  
        fetch(`${this.API}/videos?query=${this.query}`,{
            method: 'GET'
          })
          .then(res => { 
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(videos => {
            this.videos = videos
            this.isLoadingBrowser = false        
          })
          .catch(err => {
            this.videos = []
            this.isLoadingBrowser = false                    
            throw err;
          })

        fetch(`${this.API}/courses?query=${this.query}`,{
            method: 'GET'
          })
          .then(res => { 
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(courses => {
            this.courses = courses
            this.isLoadingBrowser = false        
          })
          .catch(err => {
            this.courses = []
            this.isLoadingBrowser = false
            throw err;
          })
      }else {
        this.videos = [],
        this.courses = []
      }
    }    
  },
})

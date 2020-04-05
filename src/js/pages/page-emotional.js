import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const emotional = new Vue({
  ...baseConfig(store),
  data() {
    return {
      //Filter
      isUsedFilter: false,
      isActiveFilter: false,
      filterOptions: [],

      //Feed
      isFeedLoading: false,
      videos: []
    }
  },
  computed: {
    ...baseState()
  },
  watch: {
    filterOptions: function(){
      this.getVideosByCategories(this.filterOptions)
    }
  },    
  beforeMount(){
    const category = (new URLSearchParams(window.location.search)).get('category'); 

    if(category){
      this.getVideosByCategories([category])
    }else{
      this.getVideos()
    }
  },
  methods: {
    ...baseActions(),
    cleanFilter: function(){
      this.filterOptions = [];
      this.isFeedLoading = false;
    },
    getVideos: function(){
      this.isFeedLoading = true;

      fetch(`${this.API}/videos`,{
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

          window.setTimeout(()=>{
            this.isFeedLoading = false
          }, 500)
        })
        .catch(err => {
          this.videos = []

          window.setTimeout(()=>{
            this.isFeedLoading = false
          }, 500)
                  
          throw err;          
        })
    },
    getVideosByCategories: function(categories){
      this.isFeedLoading = true;
      this.isUsedFilter = true;

      fetch(`${this.API}/videos?categories=${categories}`,{
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

          window.setTimeout(()=>{
            this.isFeedLoading = false
          }, 500)          
        })
        .catch(err => {
          this.videos = []
          
          window.setTimeout(()=>{
            this.isFeedLoading = false
          }, 500)
                  
          throw err;
        })
    }    
  }
})

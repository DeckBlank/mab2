import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

import '../components/behaviour/questionaries/student';
import '../components/behaviour/questionaries/tutor';
import '../components/behaviour/poll';

const emotional = new Vue({
  ...baseConfig(store),
  data() {
    return {
      //Filter
      isUsedFilter: false,
      filter: '',

      //Feed
      isFeedLoading: false,
      videos: [],
      exercises: []
    }
  },
  computed: {
    ...baseState()
  },
  watch: {
    filter: function(value){
      if(value !== 'exercises'){
        this.getVideosByCategory(value)
      }else{
        this.getExcercies()
      }
    }
  },    
  beforeMount(){
    const category = (new URLSearchParams(window.location.search)).get('category'); 

    if(category){
      this.filter = category;
      this.getVideosByCategory(category)
    }else{
      this.getExcercies()
    }
  },
  mounted(){
    this.global();
    this.ecualizeFilters();
    this.hideLoading();

    this.isEnableQP('poll');

    if(this.logedUser && this.logedUser.user_sector == 'publico'){
      this.isEnableQP('questionary');
    }
  },  
  methods: {
    ...baseActions(),
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
    getVideosByCategory: function(category){
      this.isFeedLoading = true;
      this.exercises = []
      this.isUsedFilter = true;

      fetch(`${this.API}/videos?category=${category}`,{
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
    ecualizeFilters: function(){
      let height_big = 0;

      document.querySelectorAll('.c-filter__option').forEach(el => {
        if (el.clientHeight > height_big) {
          height_big = el.clientHeight
        }
      })
  
      document.querySelectorAll('.c-filter__option').forEach(el => {
        el.style = `height: ${height_big}px`;
      })
    },
    getExcercies: function(){
      this.isFeedLoading = true;
      this.videos = [];
      this.isUsedFilter = true;

      fetch(`${this.API}/exercises`,{
          method: 'GET'
        })
        .then(res => { 
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(exercises => {
          this.exercises = exercises

          window.setTimeout(()=>{
            this.isFeedLoading = false
          }, 500)          
        })
        .catch(err => {
          window.setTimeout(()=>{
            this.isFeedLoading = false
          }, 500)
                  
          throw err;
        })      
    },
    isEnableQP: function(type){
      let mabTemp = window.sessionStorage.getItem('mab_temp'); mabTemp = JSON.parse(mabTemp)
      let isEnableQP__ = (this.logedUser && mabTemp && !mabTemp.behaviour) ? true : (this.logedUser && mabTemp && mabTemp.behaviour[type])

      if(isEnableQP__){
        fetch(`${this.API}/behaviour/${type}/enable?user=${this.logedUser.user_email}`)
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(response => {
            this.updateMetasBehaviour({
              type: type,
              value: response
            });
          })
          .catch(err => {
            this.updateMetasBehaviour({
              type: type,
              value: false
            });
            throw err;
          })
      }
    },    
  }
})

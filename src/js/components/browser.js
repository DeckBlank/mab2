import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('browser',{
  template: /*html*/`
    <div class="c-browser-container position-absolute" :class="{'enable' : isActiveBrowserToggle}">
      <div class="bg-pri-color padding-vertical-1">
        <div class="grid-container">
          <div class="c-browser flex-container">
            <input 
              type="text"
              class="c-browser__input input-reset height-100 w-medium"
              v-model="query"
              @keyup.enter="search"
              placeholder="Buscar curso"
            >
            <button class="c-browser__icon bg-light-gray height-100 flex-container align-middle" @click="search">
              <span class="c-icon"><i class="far fa-search"></i></span>
            </button>
          </div>
        </div>
      </div>
    </div>    
  `,
  data() {
    return {
      query: '',
    }
  },
  computed: {
    ...Vuex.mapState(['SITE_URL', 'isActiveBrowserToggle'])
  },
  methods: {
    search: function() {
      if(this.query != ''){
        window.location.href = `${this.SITE_URL}/cursos?query=${ this.query }`;
      }
    }    
  },
})

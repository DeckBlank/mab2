import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('sector',{
  template: /*html*/`
    <div class="c-sector position-fixed" :class="{ 'active' : active }">
      <div 
        class="c-cursos bg-sec-color position-absolute" :class="{ 'active' : (step == 0 && active) }">
        <h2 v-if="logedUser" class="c-cursos__title margin-bottom-1 w-black white">MAB ACADÉMICO</h2>
        <h2 v-else class="c-cursos__title margin-bottom-1 w-black white">{{ (type == 'public') ? 'PÚBLICO' : 'PRIVADO'}}</h2>
        <ul class="c-cursos__list ul-reset">
          <li  v-for="level of levels" class="c-cursos__item fs-18 f2">
            <a class="display-block" @click="getGrades(level.name)">{{ level.name }}</a>
          </li>
        </ul>
      </div>
      <div class="c-cursos bg-sec-color position-fixed" :class="{ active : (step == 1 && active) }">
        <h2 class="c-cursos__title c-cursos__title--grade margin-bottom-1 w-black white">{{ selected.level.name }}</h2>
        <ul class="c-cursos__list ul-reset">
          <li class="c-cursos__item fs-18 f2">
            <a class="flex-container align-middle" @click="step = 0">
              <span class="c-icon margin-right-1"><i class="far fa-arrow-left"></i></span>
              Volver
            </a>
          </li>

          <li v-for="grade of selected.level.data" class="c-cursos__item fs-18 f2">
            <a class="display-block" @click="getCourses(grade.name)">{{grade.name}}</a>
          </li>
        </ul>
      </div>      
      <div class="c-cursos bg-sec-color position-fixed" :class="{ active : (step == 2 && active) }">
        <h2 class="c-cursos__title c-cursos__title--grade margin-bottom-1 w-black white">{{ selected.grade.name }}</h2>
        <ul class="c-cursos__list ul-reset">
          <li class="c-cursos__item fs-18 f2">
            <a class="flex-container align-middle" @click="step = 1">
              <span class="c-icon margin-right-1"><i class="far fa-arrow-left"></i></span>
              Volver
            </a>
          </li>

          <li v-for="course of selected.grade.data" class="c-cursos__item fs-18 f2">
            <a :href="course.url + '?sector=' + ((type == 'public') ? 'publico' : 'privado')" class="display-block">{{course.name}}</a>
          </li>
        </ul>
      </div>      
    </div>
  `,
  data: function(){
    return {
      step: 0,
      active: false,
      levels: [],
      selected: {
        level: {
          name: '',
          data: []
        },
        grade: {
          name: '',
          data: []          
        },
      }
    }
  },
  props: ['type', 'name'],
  computed: {
    ...Vuex.mapState(['API', 'SITE_URL', 'logedUser', 'sectorMenu']),
  },
  watch: {
    'sectorMenu': {
      handler: function(){
        this.active = this.sectorMenu[this.type]
      },
      deep: true,
    }
  },
  mounted(){
    this.getSector();
  },
  methods: {
    getSector: function(){
      fetch(`${this.API}/sectors?type=${this.type}`)
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(sector => {
          this.levels = sector.children;
        })
        .catch(err => {
          throw err;
        })      
    },
    getGrades: function(level){
      this.step = 1;

      this.selected.level.name = level;
      this.selected.level.data = this.levels.filter((_level)=> _level.name == level)[0].children;
    },
    getCourses: function(grade){
      let grades = this.levels.filter((_level)=> _level.name == this.selected.level.name)[0].children;

      this.step = 2;
      this.selected.grade.name = grade;
      this.selected.grade.data = grades.filter((_grade)=> _grade.name == grade)[0].children;
    }
  }
})

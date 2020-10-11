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
            <a class="display-block" @click="getGrades(level)">{{ level.name }}</a>
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
            <a class="display-block" @click="getCourses(grade)">{{grade.name}}</a>
          </li>
        </ul>
      </div>      
      <div class="c-cursos bg-sec-color position-fixed" :class="{ active : (step == 2 && active) }">
        <h2 class="c-cursos__title c-cursos__title--grade margin-bottom-1 w-black white">{{ selected.grade.name }}</h2>
        <ul v-if="!selected.grade.data.isAreas" class="c-cursos__list ul-reset">
          <li class="c-cursos__item fs-18 f2">
            <a class="flex-container align-middle" @click="step = 1">
              <span class="c-icon margin-right-1"><i class="far fa-arrow-left"></i></span>
              Volver
            </a>
          </li>

          <li v-for="course of selected.grade.data.courses" class="c-cursos__item fs-18 f2">
            <a :href="getCourseLink(course)" class="display-block">{{course.name}}</a>
          </li>
        </ul>
        <ul v-else class="c-cursos__list ul-reset">
          <li class="c-cursos__item fs-18 f2">
            <a class="flex-container align-middle" @click="step = 1">
              <span class="c-icon margin-right-1"><i class="far fa-arrow-left"></i></span>
              Volver
            </a>
          </li>

          <li class="c-dropdown fs-18 f2">
            <input id="area-academic" type="checkbox" class="hide"></input>
            <label class="c-dropdown__title text-uppercase align-justify align-mddle" for="area-academic">
              {{selected.grade.data.areas.academic.name}}
              <span class="c-icon">
                <i class="far fa-chevron-down"></i>
              </span>
            </label>
            <ul class="c-dropdown__list ul-reset">
              <li v-for="course of selected.grade.data.areas.academic.courses">
                <a :href="getCourseLink(course)">{{course.name}}</a>
              </li>
            </ul>
          </li>

          <li class="c-dropdown fs-18 f2">
            <input id="area-emotional" type="checkbox" class="hide"></input>
            <label class="c-dropdown__title text-uppercase align-justify align-mddle" for="area-emotional">
              {{selected.grade.data.areas.emotional.name}}
              <span class="c-icon">
                <i class="far fa-chevron-down"></i>
              </span>
            </label>
            <ul class="c-dropdown__list ul-reset">
              <li v-for="course of selected.grade.data.areas.emotional.courses">
                <a :href="getCourseLink(course)">{{course.name}}</a>
              </li>
            </ul>
          </li>

          <li class="c-dropdown fs-18 f2">
            <input id="area-creative" type="checkbox" class="hide"></input>
            <label class="c-dropdown__title text-uppercase align-justify align-mddle" for="area-creative">
              {{selected.grade.data.areas.creative.name}}
              <span class="c-icon">
                <i class="far fa-chevron-down"></i>
              </span>
            </label>
            <ul class="c-dropdown__list ul-reset">
              <li v-for="course of selected.grade.data.areas.creative.courses">
                <a :href="getCourseLink(course)">{{course.name}}</a>
              </li>
            </ul>
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
          id: '',
          name: '',
          data: []
        },
        grade: {
          id: '',
          name: '',
          isAreas: false,
          data: []          
        },
      }
    }
  },
  props: ['type', 'name'],
  computed: {
    ...Vuex.mapState(['API', 'SITE_URL', 'logedUser', 'sectorMenu', 'sectorMenuData']),
  },
  watch: {
    'sectorMenu': {
      handler: function(){
        this.active = this.sectorMenu[this.type]
      },
      deep: true,
    },
    'sectorMenuData': {
      handler: function(value) {
        this.levels = (this.sectorMenuData[this.type]) ? this.sectorMenuData[this.type].levels : [];
      },
      deep: true
    }
  },
  methods: {
    getCourseLink: function(course) {
      return `${this.SITE_URL}/curso/${course.slug}?sector=${((this.type == 'public') ? 'publico' : 'privado')}`;
    },
    getGrades: function(level){
      this.step = 1;

      this.selected.level.id    = level.id;
      this.selected.level.name  = level.name;
      this.selected.level.data  = this.levels.filter((_level)=> _level.id == level.id)[0].grades;
    },
    getCourses: function(grade){
      this.step = 2;

      this.selected.grade.name = grade.name;
      this.selected.grade.data = this.selected.level.data.filter((_grade)=> _grade.id == grade.id)[0].courses;
    },
  }
})

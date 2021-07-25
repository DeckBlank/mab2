import Vue from 'vue'
import {mapState, mapActions } from 'vuex'

Vue.component('header-mobile', {
  template: /*html*/`
    <header class="c-header c-header-mobile width-100 padding-bottom-0" :class="{ 'c-header-mobile--visible' : isActiveMenu}">
      <div class="grid-container">
        <nav class="c-nav width-100">
          <div class="c-nav__left width-100 flex-container align-center-middle margin-bottom-1">
            <figure class="c-brand c-brand--normal flex-container align-center-middle">
              <a class="c-brand__link" :href="SITE_URL">
              </a>
            </figure>
          </div>
          <div class="c-nav__right width-100">
            <ul class="c-menu flex-container flex-dir-column ul-reset">
              <li class="flex-container flex-dir-column">
                <label for="mob-cbx-cursos" class="c-menu__item flex-container align-justify align-middle padding-1">
                  <a class="c-link c-link--sec position-relative f2 fs-18 w-sbold">Cursos</a>
                  <span class="c-icon"><i class="far fa-chevron-down"></i></span>
                </label>
                <input id="mob-cbx-cursos" type="checkbox" class="hide">

                <ul class="c-submenu ul-reset bg-sec-color br--medium width-100 overflow-hidden">
                  <li v-for="(category, icategory) of categories" :key="category.id">
                    <label :for="'mob-cbx-category-' + icategory" class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                      <a class="position-relative">{{ category.name }}</a>

                      <span class="c-icon white"><i class="far fa-chevron-down"></i></span>                 
                    </label>
                    <input :id="'mob-cbx-category-' + icategory" type="checkbox" class="hide">

                    <ul class="c-submenu ul-reset bg-sec-color width-100 overflow-hidden">
                      <div v-for="subcategory of category.subcategories" :key="subcategory.id" class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                        <a :href="getSubcategoryLink(subcategory)" class="position-relative">{{ subcategory.name }}</a>
                      </div>
                    </ul>
                  </li>
                  <li>
                    <label class="c-submenu__item padding-horizontal-1">
                      <a :href="SITE_URL + '/cursos'">Ver todos</a> 
                    </label>
                  </li>
                </ul>
              </li>

              <li class="flex-container flex-dir-column">
                <label for="mob-cbx-comunidad" class="c-menu__item flex-container align-justify align-middle padding-1">
                  <a class="c-link c-link--sec position-relative f2 fs-18 w-sbold">Comunidad</a>
                  <span class="c-icon"><i class="far fa-chevron-down"></i></span>
                </label>              
                <input id="mob-cbx-comunidad" type="checkbox" class="hide">

                <ul class="c-submenu ul-reset bg-sec-color br--medium width-100 overflow-hidden">
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a :href="SITE_URL + '/charlas'" class="position-relative">Charlas</a>
                  </div>
                  <div v-if="false" class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a :href="SITE_URL + '/lideres'" class="position-relative">Líderes</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a :href="SITE_URL + '/blog'" class="position-relative">Blog</a>
                  </div>
                </ul>
              </li>

              <li class="flex-container flex-dir-column">
                <label for="mob-cbx-world" class="c-menu__item flex-container align-justify align-middle padding-1">
                  <a class="c-link c-link--sec position-relative f2 fs-18 w-sbold">Mundo MAB</a>
                  <span class="c-icon"><i class="far fa-chevron-down"></i></span>
                </label>              
                <input id="mob-cbx-world" type="checkbox" class="hide">

                <ul class="c-submenu ul-reset bg-sec-color br--medium width-100 overflow-hidden">
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a :href="SITE_URL + '/nosotros'" class="position-relative">Nosotros</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a :href="SITE_URL + '/educacion'" class="position-relative">Educación</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a :href="SITE_URL + '/empresas'" class="position-relative">MAB Empresas</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a :href="SITE_URL + '/proyectos'" class="position-relative">Proyectos</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a :href="SITE_URL + '/llegamos'" class="position-relative">¿Cómo llegamos a ti?</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a :href="SITE_URL + '/servicios'" class="position-relative">Servicios</a>
                  </div>
                </ul>
              </li>

              <li class="flex-container flex-dir-column">
                <div class="c-menu__item flex-container align-justify padding-1">
                  <a :href="SITE_URL + '/donaciones'" class="c-link c-link--sec position-relative f2 fs-18 w-sbold">Donaciones</a>
                </div>
              </li>

              <li v-if="logedUser" class="flex-container flex-dir-column">
                <div class="c-menu__item flex-container align-justify padding-1">
                  <a :href="SITE_URL + '/mis-cursos'" class="c-link c-link--sec position-relative f2 fs-18 w-sbold">Mis cursos</a>
                </div>
              </li>

              <li class="flex-container flex-dir-column">
                <div class="c-menu__item flex-container align-justify padding-1">
                  <a :href="SITE_URL + '/cursos'" class="c-link c-link--sec position-relative f2 fs-18 w-sbold">Cursos</a>
                </div>
              </li>
            </ul>   
          </div>      
        </nav>
      </div>
    </header>
  `,
  data() {
    return {
      categories: [],
      subcategories: [],

      isLoadingSubcategories: false,
    }
  },
  computed: {
    ...mapState(['API', 'SITE_URL', 'THEME_URL', 'logedUser', 'isActiveMenu'])
  },
  mounted() {
    this.getCategories();
  },
  methods: {
    getCategories: function() {
      fetch(`${ this.API }/courses/mab_categories?deep=true&_wpnonce=${ mab.nonce }`)
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        }else{
          throw res
        }
      })
      .then(response => {
        if (response.status) {
          this.categories = response.data;
        }
      })
      .catch(err => {
        throw err;
      })
    },

    getSubcategoryLink: function(subcategory) {
      return (subcategory.type == 1) ? `${ this.SITE_URL }/cursos?subcategory=${ subcategory.id }` : subcategory.course;
    }
  },
})

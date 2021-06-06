import Vue from 'vue'
import {mapState, mapActions } from 'vuex'
import '../browser';
import '../profile';

Vue.component('header-main',{
  template: /*html*/`
  <header class="c-header width-100">
    <div class="grid-container">
      <nav class="c-nav flex-container align-justify">
        <div class="c-nav__left flex-container align-middle">
          <div class="c-brand">
            <a :href="SITE_URL" class="height-100">
              <img class="height-100 of--contain" :src="THEME_URL + '/static/images/logo-white.png'" alt="Logo - MAB Yout Learnin Coach">
            </a>
          </div>
          <div class="c-item position-relative margin-left-2">
            <input v-model="menus.courses.switcher" id="cbx-course" class="hide" type="checkbox">
            <label @mouseover="blockMenu('courses', true)" @mouseleave="blockMenu('courses', false)" for="cbx-course" class="white f2 fs-18 w-medium">Cursos <i class="far fa-chevron-down fs-16 ml-05"></i></label>
            <div @mouseover="blockMenu('courses', true)" @mouseleave="blockMenu('courses', false)" class="c-menu-dropdown c-menu-dropdown--medium left br--medium position-absolute f2">
              <div class="grid-x bg-white br--medium overflow-hidden">
                <div class="cell small-6">
                  <div class="c-menu-dropdown__left padding-vertical-2">
                    <ul class="bg-white ul-reset overflow-hidden">
                      <li v-for="category of categories" :key="category.id" class="c-menu-dropdown__item">
                        <a @click="getSubcategories($event, category)" href="#" class="w-xbold padding-horizontal-2">
                          {{ category.name }}
                        </a>
                      </li>
                      <li class="padding-horizontal-2 padding-top-2">
                        <a :href="SITE_URL + '/cursos'" class="c-button c-button--secondary display-block text-center w-sbold">
                          Ver todos <i class="far fa-arrow-right ml-05"></i>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="cell small-6">
                  <div class="padding-2">
                    <p v-if="isLoadingSubcategories" class="w-bold dark text-center">Cargando...</p>
                    <ul v-else-if="subcategories.length" class="ul-reset">
                      <li v-for="subcategory of subcategories" :key="subcategory.id" class="mb-05">
                        <a :href="getSubcategoryLink(subcategory)" class="c-link c-link--black c-link--ho-secondary display-block w-medium fs-18 f2">{{ subcategory.name }}</a>
                      </li>
                    </ul>
                    <p v-else class="w-bold dark text-center">Sin subcategorias...</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="c-item position-relative margin-left-1">
            <input v-model="menus.comunity.switcher" id="cbx-comunity" class="hide" type="checkbox">
            <label @mouseover="blockMenu('comunity', true)" @mouseleave="blockMenu('comunity', false)" for="cbx-comunity" class="white f2 fs-18 w-medium">Comunidad <i class="far fa-chevron-down fs-16 ml-05"></i></label>
            <div @mouseover="blockMenu('comunity', true)" @mouseleave="blockMenu('comunity', false)" class="c-menu-dropdown left br--medium position-absolute f2">
              <ul class="bg-white ul-reset br--medium overflow-hidden">
                <li class="c-menu-dropdown__item inverse">
                  <a :href="SITE_URL + '/charlas'" class="w-xbold text-center padding-horizontal-2">
                    Charlas
                  </a>
                </li>
                <li class="c-menu-dropdown__item inverse">
                  <a :href="SITE_URL + '/lideres'" class="w-xbold text-center padding-horizontal-2">
                    Speakers
                  </a>
                </li>
                <li class="c-menu-dropdown__item inverse">
                  <a :href="SITE_URL + '/blog'" class="w-xbold text-center padding-horizontal-2">
                    Blog
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="c-item position-relative margin-left-1">
            <input v-model="menus.world.switcher" id="cbx-world" class="hide" type="checkbox">
            <label @mouseover="blockMenu('world', true)" @mouseleave="blockMenu('world', false)" for="cbx-world" class="white f2 fs-18 w-medium">Mundo MAB <i class="far fa-chevron-down fs-16 ml-05"></i></label>
            <div @mouseover="blockMenu('world', true)" @mouseleave="blockMenu('world', false)" class="c-menu-dropdown center br--medium position-absolute f2">
              <ul class="bg-white ul-reset br--medium overflow-hidden">
                <li class="c-menu-dropdown__item inverse">
                  <a href="" class="w-xbold text-center padding-horizontal-2 ">
                    Nosotros
                  </a>
                </li>
                <li class="c-menu-dropdown__item inverse">
                  <a href="" class="w-xbold text-center padding-horizontal-2">
                    Empresas
                  </a>
                </li>
                <li class="c-menu-dropdown__item inverse">
                  <a href="" class="w-xbold text-center padding-horizontal-2">
                    Proyectos
                  </a>
                </li>
                <li class="c-menu-dropdown__item inverse">
                  <a href="" class="w-xbold text-center padding-horizontal-2">
                    Servicios
                  </a>
                </li>
                <li class="c-menu-dropdown__item inverse">
                  <a href="" class="w-xbold text-center padding-horizontal-2">
                    Modalidades
                  </a>
                </li>
                <li class="c-menu-dropdown__item inverse">
                  <a href="" class="w-xbold text-center padding-horizontal-2">
                    Metodología
                  </a>
                </li>
                <li class="c-menu-dropdown__item inverse">
                  <a href="" class="w-xbold text-center padding-horizontal-2">
                    Programas
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="c-item margin-left-1">
            <a href="" class="c-item c-link c-link--ho-warning f2 fs-18 w-sbold white">Donaciones</a>
          </div>
        </div>
        <div class="c-nav__right flex-container align-middle">
          <button @click="updateStatusBrowserToggle" class="c-search-toggle margin-right-1" :class="{'enable' : isActiveBrowserToggle}">
            <i class="far fa-search"></i>
          </button>
          <a v-if="logedUser" :href="SITE_URL + '/carrito'" class="c-link c-link--white c-link--ho-warning margin-right-1">
            <i class="far fa-shopping-cart"></i>
          </a>
          <a v-if="logedUser" :href="SITE_URL + '/mis-cursos'" class="c-item c-link c-link--white c-link--ho-warning f2 fs-18 w-sbold margin-right-1">Mis cursos</a>
          <profile v-if="logedUser"></profile>
          <a 
            v-if="!logedUser"
            :href="SITE_URL + '/access'" 
            class="c-link c-link--white c-link--ho-warning br--medium white f2 fs-18 c-lh--18 w-bold margin-left-1"
          >
            Ingresar
          </a>
          <a
            v-if="!logedUser"
            :href="SITE_URL + '/access?auth=register'" 
            class="c-login c-button--mab-warning-black br--medium f2 fs-18 c-lh--18 w-bold margin-left-1 desktop"
          >
            Registrarse
          </a>
        </div>
      </nav>
    </div>
    <browser></browser>
  </header>
  `,
  data() {
    return {
      menus: {
        courses: {
          isActiveMenuOptions: false,
          switcher: false,
        },
        comunity: {
          isActiveMenuOptions: false,
          switcher: false,
        },
        world: {
          isActiveMenuOptions: false,
          switcher: false,
        },
      },

      categories: [],
      subcategories: [],

      isLoadingSubcategories: false,
    }
  },
  computed: {
    ...mapState(['API', 'SITE_URL', 'THEME_URL', 'isActiveBrowserToggle', 'logedUser'])
  },
  watch: {
    'menus.courses.switcher': function(value) {
      document.onclick = () => {
        this.unblockMenu('courses');
      }
    },
    'menus.comunity.switcher': function(value) {
      document.onclick = () => {
        this.unblockMenu('comunity');
      }
    },
    'menus.world.switcher': function(value) {
      document.onclick = () => {
        this.unblockMenu('world');
      }
    },
  },
  mounted() {
    this.getCategories();
  },
  methods: {
    ...mapActions(['updateStatusBrowserToggle']),
    blockMenu: function(menu, state) {
      if (state) {
        this.menus[menu].isActiveMenuOptions = true;
      } else {
        this.menus[menu].isActiveMenuOptions = false;
      }
    },
    unblockMenu: function(menu) {
      if(!this.menus[menu].isActiveMenuOptions) {
        this.menus[menu].switcher = false;
      }
    },
    resetMenusExcept: function(menu) {
      if ('courses' != menu)
        this.menus.courses.switcher = false;

      if ('comunity' != menu)
        this.menus.comunity.switcher = false;

      if ('world' != menu)
        this.menus.world.switcher = false;
    },

    getCategories: function() {
      fetch(`${ this.API }/courses/mab_categories?_wpnonce=${ mab.nonce }`)
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

          this.getSubcategories(null, this.categories[0]);
        }
      })
      .catch(err => {
        throw err;
      })
    },
    getSubcategories: function(e, category) {
      if (e) e.preventDefault();

      if (!category.subcategories.length) {
        this.subcategories = [];
        this.isLoadingSubcategories = true;

        fetch(`${ this.API }/courses/mab_subcategories?categories=${ category.id }&_wpnonce=${ mab.nonce }`)
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json();
          }else{
            throw res
          }
        })
        .then(response => {
          if (response.status) {
            this.subcategories = response.data; category.subcategories = response.data;
          }

          window.setTimeout(() => {
            this.isLoadingSubcategories = false;
          }, 1000)
        })
        .catch(err => {
          this.isLoadingSubcategories = false; throw err;
        })
      } else {
        this.subcategories = category.subcategories;
      }
    },

    getSubcategoryLink: function(subcategory) {
      return `${ this.SITE_URL }/cursos?subcategory=${ subcategory.id }`;
    }
  },
})

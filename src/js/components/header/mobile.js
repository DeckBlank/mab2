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
                  <a class="c-link c-link--sec position-relative f2 fs-18 w-sbold">Mis Cursos</a>
                  <span class="c-icon"><i class="far fa-chevron-down"></i></span>
                </label>              
                <input id="mob-cbx-cursos" type="checkbox" class="hide">

                <ul class="c-submenu ul-reset bg-sec-color br--medium width-100 overflow-hidden">
                  <li>
                    <label :for="'mob-cbx-category-' + 1" class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                      <a class="position-relative">Matemáticas</a>

                      <span class="c-icon white"><i class="far fa-chevron-down"></i></span>                 
                    </label>
                    <input :id="'mob-cbx-category-' + 1" type="checkbox" class="hide">

                    <ul class="c-submenu ul-reset bg-sec-color width-100 overflow-hidden">
                      <div v-for="course of [1,2,3,4]" :key="course.id" class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                        <a href="" class="position-relative">Algebra</a>
                      </div>
                    </ul>
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
                    <a href="" class="position-relative">Charlas</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a href="" class="position-relative">Speakers</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a href="" class="position-relative">Blog</a>
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
                    <a href="" class="position-relative">Nosotros</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a href="" class="position-relative">Empresas</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a href="" class="position-relative">Proyectos</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a href="" class="position-relative">Servicios</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a href="" class="position-relative">Modalidades</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a href="" class="position-relative">Metodología</a>
                  </div>
                  <div class="c-submenu__item flex-container align-justify align-middle padding-horizontal-1" :class="{ active : true }">
                    <a href="" class="position-relative">Programas</a>
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
            </ul>   
          </div>      
        </nav>
      </div>
    </header>
  `,
  computed: {
    ...mapState(['API', 'SITE_URL', 'THEME_URL', 'logedUser', 'isActiveMenu'])
  },
})

import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('toggle',{
  template: /*html*/`
    <label class="c-toggle button-reset padding-horizontal-1 position-fixed">
      <input type="checkbox" class="hide" @change="updateStatusMenu()"></input>
      <div class="c-toggle__content flex-container align-middle">
        <p class="margin-bottom-0 fs-16 w-medium f2 white margin-right-1">Menú</p>
        <div class="c-icons-container overflow-hidden">
          <div class="c-icons">
            <div class="c-icon cell grid-y align-center-middle">
              <span class="cell text-center"><i class="far fa-bars"></i></span>
            </div>
            <div class="c-icon cell grid-y align-center-middle">
              <span class="cell text-center"><i class="far fa-times"></i></span>
            </div>
          </div>
        </div>
      </div>
    </label>
  `,
  methods: {
    ...Vuex.mapActions(['updateStatusMenu'])
  },
})

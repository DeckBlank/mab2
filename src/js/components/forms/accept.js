import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('form-accept',{
  template: /*html*/
  `
    <div class="c-modal position-fixed width-100 height-100" :class="{ opened : switcher }">
      <div class="grid-container height-100">
        <div class="grid-x align-center-middle height-100">
          <div class="cell medium-8 large-6">
            <div class="c-modal__content flex-container align-center-middle">
              <div class="c-accept bg-success modal-content padding-2 br--medium">
                <p>Gracias por dejar tus datos.
                </p>
                <div class="modal_btn_container">
                  <a :href="SITE_URL + '/emotional'">¡Bienvenido!</a>
                </div>
              </div>    
            </div>
          </div>      
        </div>      
      </div>      
    </div>  
  `,
  data() {
    return {
    }
  },
  computed: {
    ...Vuex.mapState(['SITE_URL'])
  },
  props: {
    switcher: Boolean
  }
})

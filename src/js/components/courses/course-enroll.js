import Vue from 'vue'
import { mapState } from 'vuex';

Vue.component('course-enroll',{
  template: /*html*/`
    <div class="c-course-card br--large" :class="'c-course-card--' + body.color">
      <figure class="c-course-card__figure position-relative">
        <img :src="(body.thumbnail) ? body.thumbnail : courseBasicThumbnail" alt="" class="of--cover br-top--large width-100 height-100"> 
        <div class="c-course-card__progress-bar position-absolute width-100">
          <div :style="'width: '+ body.progress +'%'" class="bg-pri-alt height-100"></div>
        </div>
        <div v-if="body.grade" class="c-course-card__grade-tag  position-absolute dark fs-18 flex-container align-center-middle bg-warning-alt">
          {{body.grade}}
        </div>
      </figure>
      <div class="c-course-card__content flex-container fd-column padding-1 br--large">
        <div>
          <div class="c-course-card__titles">
            <p class="c-course-card__name w-xbold fs-18 f2 margin-0">{{ body.name }}</p>
            <p class="c-course-card__teacher fs-16 f2">Con {{ (body.teacher) ? body.teacher : '"Sin docente"' }}</p>
          </div>
          <p class="c-course-card__description fs-16 f2 lh-18">{{ body.description }}</p>
        </div>
        <div class="grid-x align-middle">
          <div class="small-6 medium-6">
            <span class="c-course-card__likes f2"> <i class="fas fa-heart mr-05"></i> {{ body.likes }}</span>
          </div>
          <div class="small-6 medium-6 text-right">
            <a v-if="body.last_class" :href="body.last_class.link" class="c-button c-button--course display-block lh-14 f2">
              Siguiente clase:
              <span>{{ body.last_class.title }}</span>
            </a>
          </div>
        </div>
      </div>
      <a :href="(body.last_class) ? body.last_class.link : '#'" class="c-course-card__footer display-block text-uppercase white fs-18 text-center bg-dark br-bottom--large">
        ¡continuar curso!
      </a>
    </div>
  `,
  props: {
    body: Object,
  },
  computed: {
    ...mapState(['THEME_URL']),
    courseBasicThumbnail: function() {
      return `${ this.THEME_URL }/static/images/og_image.png`;
    },
  },
})

import Vue from 'vue'
import { mapActions, mapState } from 'vuex'

Vue.component('course',{
  template: /*html*/`
    <div class="c-course-card br--large" :class="'c-course-card--' + body.color">
      <figure class="c-course-card__figure position-relative">
        <!--<div v-if="Number(body.price) == 0 && logedUser" class="c-course-card__free-tag position-absolute sec-color fs-18 flex-container align-center-middle">
          <img loading="lazy" class="donaciones" :src="THEME_URL + '/static/images/vectors/heart.png'" alt=""> GRATIS
        </div> _TAMBOS -->
        <div v-if="body.grade" class="c-course-card__grade-tag  position-absolute dark fs-18 flex-container align-center-middle bg-warning-alt">
          {{body.grade}}
        </div>
        <img :src="(body.thumbnail) ? body.thumbnail : courseBasicThumbnail" alt="" class="of--cover br-top--large width-100 height-100"> 
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
          <div class="small-5 medium-5">
            <span class="c-course-card__likes f2"><i class="fas fa-heart mr-05"></i>{{ body.likes }}</span>
          </div>
          <div class="small-7 medium-7 text-right">
            <span v-if="body.enroll" class="c-button c-button--mab-white-black lh-14 f2 w-xbold">Inscrito</span>
            <button v-else-if="Number(body.price) > 0 && logedUser" @click="addCourse(body.id, body.name, body.link)" class="c-button c-button--mab-white-black lh-14 f2 w-xbold">
              S/ {{ Number(body.price).toFixed(2) }} 
              <i class="fas fa-shopping-cart"></i>
            </button>
          </div>
        </div>
      </div>
      <a :href="body.link" class="c-course-card__footer text-uppercase white fs-18 text-center bg-dark br-bottom--large display-block">
        ¡comenzar curso!
      </a>
    </div>
  `,
  props: {
    body: Object,
  },
  computed: {
    ...mapState(['API', 'SITE_URL', 'THEME_URL', 'logedUser']),
    courseBasicThumbnail: function() {
      return `${ this.THEME_URL }/static/images/og_image.png`;
    },
  },
  methods: {
    ...mapActions(['addCourseToShopCart']),
    addCourse: function(course_id, course_title, course_link){
      this.addCourseToShopCart({id: course_id, title: course_title, link: course_link, url: this.SITE_URL});
    },
  },
})

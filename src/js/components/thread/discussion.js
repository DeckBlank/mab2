import Vue from 'vue'
import Vuex from 'vuex'

Vue.component('discussion', {
  template: /*html*/`
    <div class="c-foro-theme">
      <div class="grid-x align-middle">
        <div class="small-8 medium-7 large-7">
          <div class="c-foro-theme__info flex-container align-justify align-middle">
            <div>
              <a @click="setDiscussion" class="c-foro-theme__title f2 w-xbold dark" href="">{{ body.subject }}</a>
              <div>
                <span class="f2 fs-14 w-bold">{{ body.author }},</span> <span class="f2 fs-14">{{ getDateFormated(body.created_at) }}</span>
              </div>
            </div>
            <div>
              <button v-if="authorized" @click="markAsStickyDiscussion(body.id)" class="padding-left-1 fs-21">
                <i v-if="sticky" class="fas fa-thumbtack"></i>
                <i v-else class="far fa-thumbtack"></i>
              </button>
              <span v-else-if="sticky" class="fs-21"><i class="fas fa-thumbtack"></i></span>

              <span v-if="false" class="c-foro-theme__circle bg-success margin-left-1">
              </span>
            </div>
          </div>
        </div>
        <div class="small-4 medium-5 large-5">
          <div class="text-center">
            <span class="f2 w-bold fs-21">{{ body.total_comments }}</span>
          </div>
        </div>
      </div>
    </div>
  `,
  props: ['body', 'selected', 'sticky', 'authorized', 'view', 'handler'],
  computed: {
    ...Vuex.mapState(['API', 'THEME_URL', 'logedUser'])
  },
  mounted() {
    // console.log(this.authorized);
  },
  methods: {
    markAsStickyDiscussion: function(discussionId) {
      const formData = new FormData();

      formData.append('user_id', this.logedUser.user_id)
      formData.append('course_id', mab.course_id);
      formData.append('mode', (!this.sticky) ? 1 : 2);

      if(this.authorized){
        fetch(`${ this.API }/discussions/${ discussionId }/sticky`,{
          method: 'POST',
          body: formData,
        })
        .then(response => {
          this.handler(true);
        })
        .catch(err => {
          throw err;          
        }) 
      }
    },
    setDiscussion: function(e) {
      e.preventDefault();

      this.$emit('update:selected', this.body);
      this.$emit('update:view', 2);
    },

    getDateFormated: function(dateAt) {
      const dateTime  = new Date(dateAt);
      const day       = dateTime.toLocaleDateString('es', { month: 'long', day: 'numeric' });

      return `${ day }, ${ dateTime.getFullYear() }`;
    }
  },
});

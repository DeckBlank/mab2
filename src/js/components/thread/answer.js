import Vue from 'vue'
import Vuex from 'vuex'
import '../likes';
import '../editor';
import './answer';

Vue.component('answer',{
  // <div class="c-comment fs-18">
  //   <div class="flex-container align-middle margin-bottom-1">
  //     <div class="margin-right-1">
  //       <figure class="c-avatar c-avatar--small overflow-hidden rounded">
  //         <img class="width-100 height-100 of--cover" :src="pic" alt="">
  //       </figure>                  
  //     </div>
  //     <div class="flex-container align-middle">
  //       <p class="margin-bottom-0 fs-18 margin-right-1">{{body.comment_author}}</p>
  //       <span class="c-comment__date fs-16 gray-gray">{{(new Date(body.comment_date)).toLocaleDateString('es', { weekday: 'long', month: 'long', day: 'numeric' })}}</span>
  //     </div>
  //   </div>
  //   <div class="c-comment__body">
  //     <div class="c-comment__content margin-bottom-1">
  //       {{body.comment_content}}
  //     </div>            
  //   </div>                      
  // </div>
  template: /*html*/`
    <div class="c-comment c-comment--small br-top--large br-bottom--large margin-bottom-1">
      <div class="grid-x">
        <div class="small-2 medium-2 large-2">
          <figure class="c-comment__figure">
            <img v-if="body.authorAvatar" :src="body.authorAvatar" alt="" class="width-100 height-100 of--cover"> 
            <img v-else :src="THEME_URL + '/static/images/user.png'" alt="" class="width-100 height-100 of--cover">
          </figure>
        </div>
        <div class="small-10 medium-8 large-8">
          <div class="c-comment-content">
            <div class="c-comment__autor f2 w-xbold dark fs-21">
              {{ body.comment_author }}
            </div>
            <div class="c-comment__rol f2 margin-bottom-1 fs-14 lh-14">
              {{body.authorFiel}}
            </div>
            <div v-if="false" class="c-comment__theme f2 w-xbold dark fs-16">
              Re: Carbohidratos en la noche
            </div>
            <p class="c-comment__text f2 dark fs-16 lh-18">
              {{body.comment_content}}
            </p>
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
    ...Vuex.mapState(['API', 'THEME_URL', 'logedUser'])
  },
  props: {
    pic: String,
    body: Object
  },
})

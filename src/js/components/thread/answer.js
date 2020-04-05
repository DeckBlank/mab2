import Vue from 'vue'
import '../likes';
import '../editor';
import './answer';

Vue.component('answer',{
  template: /*html*/`
    <div class="c-comment fs-18">
      <div class="flex-container align-middle margin-bottom-1">
        <div class="margin-right-1">
          <figure class="c-avatar c-avatar--small overflow-hidden rounded">
            <img class="width-100 height-100 of--cover" :src="pic" alt="">
          </figure>                  
        </div>
        <div class="flex-container align-middle">
          <p class="margin-bottom-0 fs-18 margin-right-1">{{body.comment_author}}</p>
          <span class="c-comment__date fs-16 gray-gray">{{(new Date(body.comment_date)).toLocaleDateString('es', { weekday: 'long', month: 'long', day: 'numeric' })}}</span>
        </div>
      </div>
      <div class="c-comment__body">
        <div class="c-comment__content margin-bottom-1">
          {{body.comment_content}}
        </div>            
      </div>                      
    </div>
  `,
  data() {
    return {
    }
  },
  props: {
    pic: String,
    body: Object
  },
})
              
import Vue from 'vue'

Vue.component('video-c',{
  template: /*html*/`
    <article class="c-card-video display-block margin-bottom-1" :class=" 'c-card-video--' + modifier ">
      <a 
        :href="link" 
        class="c-card-video__thumbnail br--medium display-block margin-bottom-2 position-relative overflow-hidden">
        <img class="width-100 height-100 of--cover" :src="thumbnail" alt="">
        <div class="c-card-video__play position-absolute flex-container align-center-middle">
          <span class="c-icon"><i class="far fa-play"></i></span>
        </div>
      </a>
      <h3 class="f2 w-bold dark margin-bottom-2 flex-container align-justify">
        <p class="c-card-video__title fs-30 text-uppercase margin-bottom-0">{{title}}</p>
      </h3>
      <div class="flex-container align-middle">
        <figure class="c-avatar margin-right-1 overflow-hidden rounded">
          <img class="width-100 height-100 of--cover" :src="author.avatar.sizes.thumbnail" alt="">
        </figure>
        <p class="margin-bottom-0 fs-21 w-medium f2 dark">{{author.first_name}} {{author.last_name}}</p>
      </div>
    </article>
  `,
  props: ['title', 'link', 'author', 'thumbnail', 'modifier']
})

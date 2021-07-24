import Vue from 'vue'
import { mapActions } from 'vuex'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

import '../components/thread/discussion';
import '../components/thread/comment';

new Vue({
  ...baseConfig(store),
  data() {
    return {
      metas: new URLSearchParams(window.location.search),

      showTooltipCerticado: false,
      isActiveUnity: false,
      isAvaibleCourse: true,
      isActiveSignUp: false,
      accessGranted: false,

      unities: [],
      courseProgress: 1000,
      lastClass: '',

      view: 1,
      foro: 1,
      commentbox: 0,

      isLoadingUnities: false,

      isOpenedTrailer: false,
      isOpenedCertificateModal: false,

      isOpenedCreateDiscussion: false,
      discussion: {
        isLoading: false,
        try: false,
        sent: 0,

        name: {
          value: '',
          isValid: false,
        },
      },
      discussions: {
        data: [],
        sticky: null,

        is_user_owner: false,
      },
      discussionsPaged: 0,
      selectedDiscussion: null,

      comments: {
        number: 0,
        list: [],
        sticky: null,

        is_user_owner: false,
      },
      commentsPaged: 0,
      isLoadingComments: false, 
    }
  },
  computed: {
    ...baseState(),
    hasCertificate: function() {
      return (this.courseProgress == 100) ? true : false;
    },
    certificate: function() {
      return (this.hasCertificate)
        ? `${ this.SITE_URL }/wp-json/custom/v1/users/${ this.logedUser.user_id }/certificate?course=${ mab.course_id }`
        : '#';
    }
  },
  watch: {
    'discussion.name.value': function (value) {
      this.discussion.name.isValid = (value.length) ? true : false;
    },
    'foro': function (value) {
      if (value == 2) this.getDiscussion();
    },
  },
  mounted: function(){
    this.global();
    this.hideLoading();

    this.isUserAuthOnCourse( mab.course_id )
    this.getUnities( mab.course_id );
    this.getCourseProgress( mab.course_id );
    this.getDiscussions( mab.course_id );
  },
  methods: {
    ...baseActions(),
    ...mapActions(['addCourseToShopCart']),
    isWrongField: function(field) {
      return this.discussion.try && !this.discussion[field].isValid;
    },

    getUnities: function(course_id){
      this.isLoadingUnities = true;

      fetch(`${ this.API }/course/${ course_id }/unities?user=${ this.logedUser.user_email }&user_id=${ this.logedUser.user_id }`)
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json()
        } else {
          throw res;
        }
      })
      .then(response => {
        this.unities    = response.data;
        this.lastClass  = response.lastClass;

        window.setTimeout(() => {
          this.isLoadingUnities = false;
        }, 1000);
      })
      .catch(err => {
        throw err;
      })
    },
    getCourseProgress: function(course_id) {
      fetch(`${ this.API }/courses/${ course_id }/progress?user_email=${ this.logedUser.user_email }`)
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json()
        } else {
          throw res;
        }
      })
      .then(response => {
        if (response.status) {
          this.courseProgress = Number(response.data.percentage);

          if (Number(response.data.percentage) == 100) {
            this.isOpenedCertificateModal = (!response.data.notification) ? true : false;

            window.setTimeout(() => {
              document.querySelector('#certificate_download').download = `${ this.logedUser.user_nicename }.pdf`;
            }, 100);
          }
        }
      })
      .catch(err => {
        throw err;
      })
    },
    isUserAuthOnCourse: function(course_id){
      fetch(`${ this.API }/course/${ course_id }/registration/checkout?user=${ this.logedUser.user_email }`,{
        method: 'GET'
      })
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(registration => {
        this.accessGranted = true;
      })
      .catch(err => {
        throw err;          
      })
    },
    openTrailer: function(e) {
      e.preventDefault();
      
      this.isOpenedTrailer = true;
    },

    startCourse: function(course_id, course_title, course_link) {
      if (this.accessGranted) {
        window.location.href = (this.lastClass) ? this.lastClass.link : '';
      } else {
        this.addCourseToShopCart({id: course_id, title: course_title, link: course_link, url: this.SITE_URL});
      }
    },

    getTopicLink: function(topicLink, topicIndex, unityIndex) {
      const previousUnities = this.unities.slice(0, unityIndex);
      let topicNumber = 0;

      if (unityIndex == 0) {
        topicNumber = topicIndex + (unityIndex + 1);
      } else {
        let unitiesLength = 0;

        previousUnities.forEach(element => {
          unitiesLength += element.topics.length;
        });

        topicNumber = topicIndex + (unityIndex + 1) + (unitiesLength - unityIndex);
      }

      return (this.accessGranted) ? `${ topicLink }?course_id=${ mab.course_id }&topic_number=${ topicNumber }&unity=${ unityIndex + 1 }` : '#';
    },

    resetAccordion: function(unity) {
      this.unities = this.unities.map(q => {
        return (q != unity) ? { ...q, enable : false } : q;
      })
    },

    closeCertificateModal: function() {
      const courseId = mab.course_id;

      const formData = new FormData();

      formData.append('user_email', this.logedUser.user_email);
      formData.append('_wpnonce', mab.nonce);

      fetch(`${ this.API }/courses/${ courseId }/progress/notification`, {
        method: 'POST',
        body: formData,
      })
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        } else {
          throw res;
        }
      })
      .then(response => {
        console.log('Exit');
      })
      .catch(err => {
        throw err;
      })

      this.isOpenedCertificateModal = false;
    },

    getDiscussions: function(reset = false) {
      if (reset) this.discussionsPaged = 0;

      if(this.discussionsPaged != -1){
        fetch(`${this.API}/discussions?paged=${ this.discussionsPaged + 1 }&user_id=${ this.logedUser ? this.logedUser.user_id : '' }&course_id=${ mab.course_id }`,{
          method: 'GET'
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(response => {
          if (response.status) {
            this.discussions.sticky         = response.sticky;
            this.discussions.is_user_owner  = response.is_user_owner;

            if (!reset) this.discussions.data = this.discussions.data.concat(response.data);
            else this.discussions.data = response.data;

            this.discussionsPaged += 1
          }
        })
        .catch(err => {
          this.discussionsPaged = -1
          throw err;          
        })
      }
    },
    getDiscussion: function() {
      this.comments = {
        number: 0,
        list: [],
        sticky: null,

        is_user_owner: false,
      };

      fetch(`${this.API}/discussions/${ this.selectedDiscussion.id }?user_id=${ this.logedUser ? this.logedUser.user_id : '' }&course_id=${ mab.course_id }`,{
        method: 'GET'
      })
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        }else{
          throw res;
        }
      })
      .then(comments => {
        this.comments = comments;

        this.commentsPaged += 1
      })
      .catch(err => {
        this.comments = {
          number: 0,
          list: [],
          sticky: null,

          is_user_owner: false,
        };
        this.commentsPaged = -1;

        throw err;          
      })
    },
    saveDiscussion: function() {
      this.discussion.try = true;

      if (this.discussion.name.isValid) {
        this.discussion.isLoading = true;

        const formData = new FormData();

        formData.append('subject', this.discussion.name.value);
        formData.append('course_id', mab.course_id);
        formData.append('user_id', this.logedUser.user_id);
        formData.append('user_email', this.logedUser.user_email);
        formData.append('_wpnonce', mab.nonce);

        fetch(`${ this.API }/discussions`, {
          method: 'POST',
          body: formData,
        })
        .then(res => { 
          if (res.status >= 200 && res.status < 300) {
            return res.json();
          } else {
            throw res;
          }
        })
        .then(response => {
          if (response.status) {
            this.isOpenedCreateDiscussion = false;

            this.discussions.data = [
              response.data,
              ...this.discussions.data,
            ];
          }
          else
            alert('Error inesperado, intentelo de nuevo');
          })
        .catch(err => {
          alert('Error inesperado, intentelo de nuevo');

          throw err;
        })
      }
    },
    getDateFormated: function(dateAt) {
      const dateTime  = new Date(dateAt);
      const day       = dateTime.toLocaleDateString('es', { month: 'long', day: 'numeric' });

      return `${ day }, ${ dateTime.getFullYear() }`;
    },

    getComments: function(reset = false){
      if (reset) this.commentsPaged = 0;

      if(this.commentsPaged != -1){
        fetch(`${this.API}/topic/${ this.selectedDiscussion.topic_id }/comments?paged=${ this.commentsPaged + 1 }&user_id=${ this.logedUser ? this.logedUser.user_id : '' }&course_id=${ mab.course_id }`,{
            method: 'GET'
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(comments => {
            this.comments.number  = comments.number;
            this.comments.sticky  = comments.sticky;

            this.comments.is_user_owner = comments.is_user_owner;

            if (!reset) this.comments.list.push(...comments.list);
            else this.comments.list = comments.list;

            this.commentsPaged += 1
          })
          .catch(err => {
            this.commentsPaged = -1
            throw err;          
          })          
      }
    },
  }
})

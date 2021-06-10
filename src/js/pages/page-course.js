import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {addCourseToShopCart} from '../libs/shop-cart'
import {store} from '../store'

const course = new Vue({
  ...baseConfig(store),
  data() {
    return {
      showTooltipCerticado: false,
      metas: new URLSearchParams(window.location.search),
      isActiveUnity: false,
      isAvaibleCourse: true,
      isActiveSignUp: false,
      accessGranted: false,

      unities: [],
      courseProgress: 1000,

      view: 1,
      foro: 1,
      commentbox: 0,

      isLoadingUnities: false,

      isOpenedTrailer: false,
      isOpenedCertificateModal: false,
    }
  },
  computed: {
    ...baseState(),
    firstClass: function() {
      return (this.unities.length) ? this.unities[0].topics[0].video.link : '';
    },
    hasCertificate: function() {
      return (this.courseProgress == 100) ? true : false;
    },
    certificate: function() {
      return (this.hasCertificate)
        ? `${ this.SITE_URL }/wp-json/custom/v1/users/${ this.logedUser.user_id }/certificate?course=${ mab.course_id }`
        : '#';
    }
  },
  mounted: function(){
    this.global();
    this.hideLoading();

    this.isUserAuthOnCourse( mab.course_id )
    this.getUnities( mab.course_id );
    this.getCourseProgress( mab.course_id );
  },
  methods: {
    ...baseActions(),
    getUnities: function(course_id){
      this.isLoadingUnities = true;

      fetch(`${ this.API }/course/${ course_id }/unities?user=${ this.logedUser.user_email }`)
      .then(res => { 
        if (res.status >= 200 && res.status < 300) {
          return res.json()
        } else {
          throw res;
        }
      })
      .then(unities => {
        this.unities = unities;

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
        window.location.href = this.firstClass;
      } else {
        addCourseToShopCart(course_id, course_title, course_link, this.SITE_URL, this.metas)
      }
    },
    /* FIXME: Clean me when it's done */
    playVideo: function(video, unity, topic){
      event.preventDefault();

      if (this.metas.get('sector') == 'privado') {
        /*
          TODO: if(this.accessGranted || (unity == 1 && topic == 1)){
        */
        if(this.accessGranted){
          window.location = video;
        } else {
          if (this.logedUser && this.logedUser.user_rol != 'foreign') {    
            if (unity == 1 && topic == 1) {
              window.location = video;
            } else {
              addCourseToShopCart(
                this.$refs.course.getAttribute('data-id'),
                this.$refs.course.getAttribute('data-title'),
                this.$refs.course.getAttribute('data-link'),
                this.SITE_URL,
                this.metas
              );
            }    
          } else {
            this.isActiveSignUp = true;
          }
        }    
      } else if(this.metas.get('sector') == 'publico') {
        if(this.accessGranted){
          window.location = video;
        }else{
          this.isActiveSignUp = true;
        }        
      }
    },
    downloadMaterial: function(unity, topic, topic_id, url, media){
      event.preventDefault();

      if (this.metas.get('sector') == 'privado') {
        /*
          TODO: if(this.accessGranted || (unity == 1 && topic == 1)){
        */
        if(this.accessGranted){
          this.saveMaterialLog(topic_id, url, media)
        }else{
          if (this.logedUser && this.logedUser.user_rol != 'foreign') {
            if (unity == 1 && topic == 1) {
              window.location = video;
            } else {
              addCourseToShopCart(
                this.$refs.course.getAttribute('data-id'),
                this.$refs.course.getAttribute('data-title'),
                this.$refs.course.getAttribute('data-link'),
                this.SITE_URL,
                this.metas
              );
            }         
          } else {
            this.isActiveSignUp = true;
          }
        }    
      } else if(this.metas.get('sector') == 'publico') {
        if(this.accessGranted){
          this.saveMaterialLog(topic_id, url, media)
        }else{
          this.isActiveSignUp = true;
        }        
      }
    },
    saveMaterialLog: function(topic_id, url, media){
      let course_id = this.$refs.course.getAttribute('data-id'),
        user = (this.logedUser) ? this.logedUser.user_email : 'anonimo';
      
      fetch(`${this.API}/topic/${topic_id}/material/log?user=${user}&course_id=${course_id}&media=${media}`,{
          method: 'PUT'
        })
        .then(res => { 
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(response => {
          window.open(url, '_blank');
        })
        .catch(err => {
          window.open(url, '_blank');
          throw err;
        })
    },

    getTopicLink: function(topicLink, topicNumber, unityNumber) {
      return (this.accessGranted) ? `${ topicLink }?course_id=${ mab.course_id }&topic_number=${ topicNumber }&unity=${ unityNumber }` : '#';
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
  }
})

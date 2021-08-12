import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import Swiper from 'swiper'

new Vue({
  ...baseConfig(store),
  data() {
    return {
      metas: new URLSearchParams(window.location.search),
      view: 'login',
      social: '',
      gallery: {
        login: {
          img: 'access/auth/login.png',
          title: '<span>¡Hola!</span><p>Qué gusto verte por aquí de nuevo</p>',
          author: {
            name: 'Macarena Arribas',
            job: 'Fundadora de MAB'
          },
        },
        register: {
          img: 'access/auth/login.png',
          title: '<span class="medium">¡Hola,<br> soy Maca!</span><p>¿Estás listo para girar la educación?</p>',
          author: {
            name: 'Macarena Arribas',
            job: 'Fundadora de MAB'
          },
        },
        steps: [
          {
            img: 'access/steps/0.png',
            sticker: 'access/sticker.png',
            title: '<p>ganadora de los <span>globant awards 2020</span> categoría <span>game changer</span></p>',
          },
          {
            img: 'access/steps/1.png',
            author: {
              name: 'Viviana de Ferrari',
              job: 'Amor Propio'
            },
            title: '<p>aprende con <span>especialistas</span> reconocidos a nivel <span>nacional e internacional</span></p>',
          },
          {
            img: 'access/steps/2.png',
            title: '<p>tutores que han <span>pasado por lo mismo</span> que tú</p>',
          },
          {
            img: 'access/steps/3.png',
            title: '<p>¡ya casi estás listo/a para comenzar a aprender distinto!</p>',
          }
        ],
        slider: [
          {
            img: 'access/slider/1.png',
            author: {
              name: 'Proyecto “De Tambo a Tambo”',
            },
            title: '<span class="tiny">mucho más que una plataforma de cursos</span> <p class="tiny">conoce todos nuestros proyectos y servicios que tenemos para ti</p>',
          },
          {
            img: 'access/slider/2.png',
            author: {
              name: 'Tutorías personalizadas',
            },
            title: '<span class="tiny">mucho más que una plataforma de cursos</span> <p class="tiny">conoce todos nuestros proyectos y servicios que tenemos para ti</p>',
          },
          {
            img: 'access/slider/3.png',
            author: {
              name: 'Talleres, programa Kids',
            },
            title: '<span class="tiny">mucho más que una plataforma de cursos</span> <p class="tiny">conoce todos nuestros proyectos y servicios que tenemos para ti</p>',
          },
        ]
      },

      countries: [],
      cities: [],

      categories: [],
      subcategories: [],

      user: {
        loading: false,
        try: false,
        error: '',

        email: {
          value: '',
          pattern: "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$",
          isValid: false,
        },
        password: {
          value: '',
          isValid: false,
        },
        passwordConfirm: {
          value: '',
          isValid: false,
        },
        terms: false,

        sector: {
          value: '',
          isValid: false,
        },

        role: {
          type: '',
          isValid: false,
          extension: '',
          error: '',
        },

        categories: {
          value: [],
          isValid: false,
        },
        subcategories: {
          value: [],
          isValid: false,
        },
      },

      profile : {
        name: {
          value: '',
          pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{1,70}$",
          isValid: false
        },
        father_name: {
          value: '',
          pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{1,70}$",
          isValid: false
        },
        mother_name: {
          value: '',
          pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{1,70}$",
          isValid: false
        },
        date_birth: {
          value: '',
          isValid: false
        },
        country: {
          value: '',
          isValid: false,
          isLoading: false,
        },
        city: {
          value: '',
          isValid: false,
          isLoading: false,
        },
        phone: {
          value: '',
          pattern: "^[0-9]{5,15}$",
          isValid: false
        },
      },

      steps: [
        { isWrong : false },
      ],

      modals: {
        departments : [],
        provinces : [],

        teacher : {
          isOpened : false,
          students: [ '1', '2', '3', '4', '5+'],
          data: {
            try : false,
            students: {
              value: '',
              isValid: false
            }
          }
        },
        tambero : {
          isOpened : false,
          data: {
            try : false,
            department: {
              value: '',
              isValid: false
            },
            province: {
              value: '',
              isValid: false,
              isLoading: false,
            },
          }
        },
      },

      isLoadingStep: false,
      isOpenedTerms: false,
    }
  },
  computed: {
    ...baseState(),
    callingCode: function() {
      return (this.profile.country.value) ? `+${ this.profile.country.value.callingCodes[0] }` : '+XXX';
    },
  },
  watch: {
    'view': function() {
      if(this.view == 'step-4') {
        setTimeout(() => {
          new Swiper('#slider-mab', {
            autoplay: {
              delay: 3000,
            },
            pagination: {
              el: '.c-image-slider .swiper-pagination',
            },
          });
        },1000)
      } else if (this.view == 'step-1') {
        this.getCountries();
      }
    },

    'user.email.value' : function() {
      this.user.email.isValid = this.validateText(this.user.email);
    },
    'user.password.value' : function() {
      if (this.view == 'login') {
        this.user.password.isValid = (this.user.password.value.length && this.user.password.value.length >= 1) ? true : false;
      } else {
        this.user.password.isValid = (this.user.password.value.length && this.user.password.value.length >= 5) ? true : false;
      }
    },
    'user.passwordConfirm.value' : function() {
      this.user.passwordConfirm.isValid = (
          this.user.passwordConfirm.value.length &&
          this.user.passwordConfirm.value.length >= 5 &&
          this.user.passwordConfirm.value == this.user.password.value
        )
        ? true
        : false;
    },

    'user.sector.value' : function() {
      this.validateSelect(this.user.sector);
    },

    'profile.name.value' : function() {
      this.profile.name.isValid = this.validateText(this.profile.name);
    },
    'profile.father_name.value' : function() {
      this.profile.father_name.isValid = this.validateText(this.profile.father_name);
    },
    'profile.mother_name.value' : function() {
      this.profile.mother_name.isValid = this.validateText(this.profile.mother_name);
    },
    'profile.date_birth.value' : function() {
      this.validateSelect(this.profile.date_birth);
    },
    'profile.country.value' : function(country) {
      this.validateSelect(this.profile.country);

      if (country.alpha2Code == 'PE') this.getCities();
    },
    'profile.city.value' : function() {
      this.validateSelect(this.profile.city);
    },
    'profile.phone.value' : function() {
      this.validateSelect(this.profile.phone);
    },

    'user.role.type' : function(role) {
      this.validateSelect(this.user.role, 'type');
    },

    'user.categories' : {
      handler: function() {
        this.user.categories.isValid = (this.user.categories.value.length && this.user.categories.value.length >= 3) ? true : false;
      },
      deep : true,
    },
    'user.subcategories' : {
      handler: function() {
        this.user.subcategories.isValid = (this.user.subcategories.value.length && this.user.subcategories.value.length >= 3) ? true : false;
      },
      deep : true,
    },

    'modals.teacher.data.students.value' : function() {
      this.validateSelect(this.modals.teacher.data.students);
    },

    'modals.tambero.data.department.value' : function() {
      this.validateSelect(this.modals.tambero.data.department);
    },
    'modals.tambero.data.province.value' : function() {
      this.validateSelect(this.modals.tambero.data.province);
    },
  },
  mounted(){
    this.global();
    this.hideLoading();

    if (this.metas.get('auth') && this.metas.get('auth') == 'register')
      this.view = 'register';

    this.initFacebook();
  },
  methods: {
    ...baseActions(),
    getCountries: function(){
      /* fetch(`https://restcountries.eu/rest/v2/all`)
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json();
          }else{
            throw res;
          }
        })
        .then(countries => {
          this.countries = countries;
        })
        .catch(err => {
          throw err;          
        })  */   
        this.countries = [
          {"name":"Peru","topLevelDomain":[".pe"],"alpha2Code":"PE","alpha3Code":"PER","callingCodes":["51"],"capital":"Lima","altSpellings":["PE","Republic of Peru",
          " República del Perú"],"region":"Americas","subregion":"South America","population":31488700,"latlng":[-10.0,-76.0],"demonym":"Peruvian",
          "area":1285216.0,"gini":48.1,"timezones":["UTC-05:00"],"borders":["BOL","BRA","CHL","COL","ECU"],"nativeName":"Perú","numericCode":"604",
          "currencies":[{"code":"PEN","name":"Peruvian sol","symbol":"S/."}],"languages":[{"iso639_1":"es","iso639_2":"spa","name":"Spanish","nativeName":"Español"}],
          "translations":{"de":"Peru","es":"Perú","fr":"Pérou","ja":"ペルー","it":"Perù","br":"Peru","pt":"Peru","nl":"Peru","hr":"Peru","fa":"پرو"},
          "flag":"https://restcountries.eu/data/per.svg","regionalBlocs":[{"acronym":"PA","name":"Pacific Alliance","otherAcronyms":[],
          "otherNames":["Alianza del Pacífico"]},{"acronym":"USAN","name":"Union of South American Nations","otherAcronyms":["UNASUR","UNASUL","UZAN"],
          "otherNames":["Unión de Naciones Suramericanas","União de Nações Sul-Americanas","Unie van Zuid-Amerikaanse Naties","South American Union"]}],"cioc":"PER"},
          ];  
    },
    getCities: function() {
/*       fetch(`${ this.API }/geo?_wpnonce=${ mab.nonce }`)
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        }else{
          throw res
        }
      })
      .then(response => {
        if (response.status) {
          
        }
      })
      .catch(err => {
        throw err;
      }) */
      let ciudades = [{"id_ubigeo":"2534","nombre_ubigeo":"Amazonas","codigo_ubigeo":"01","etiqueta_ubigeo":"Amazonas, Per\u00fa",
      "buscador_ubigeo":"amazonas per\u00fa","numero_hijos_ubigeo":"7","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"2625","nombre_ubigeo":"Ancash",
      "codigo_ubigeo":"02","etiqueta_ubigeo":"Ancash, Per\u00fa","buscador_ubigeo":"ancash per\u00fa","numero_hijos_ubigeo":"20","nivel_ubigeo":"1",
      "id_padre_ubigeo":"2533"},{"id_ubigeo":"2812","nombre_ubigeo":"Apurimac","codigo_ubigeo":"03","etiqueta_ubigeo":"Apurimac, Per\u00fa","buscador_ubigeo":"apurimac per\u00fa",
      "numero_hijos_ubigeo":"7","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"2900","nombre_ubigeo":"Arequipa","codigo_ubigeo":"04",
      "etiqueta_ubigeo":"Arequipa, Per\u00fa","buscador_ubigeo":"arequipa per\u00fa","numero_hijos_ubigeo":"8","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},
      {"id_ubigeo":"3020","nombre_ubigeo":"Ayacucho","codigo_ubigeo":"05","etiqueta_ubigeo":"Ayacucho, Per\u00fa","buscador_ubigeo":"ayacucho per\u00fa",
      "numero_hijos_ubigeo":"11","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"3143","nombre_ubigeo":"Cajamarca","codigo_ubigeo":"06",
      "etiqueta_ubigeo":"Cajamarca, Per\u00fa","buscador_ubigeo":"cajamarca per\u00fa","numero_hijos_ubigeo":"13","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},
      {"id_ubigeo":"3292","nombre_ubigeo":"Cusco","codigo_ubigeo":"08","etiqueta_ubigeo":"Cusco, Per\u00fa","buscador_ubigeo":"cusco per\u00fa","numero_hijos_ubigeo":"13",
      "nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"3414","nombre_ubigeo":"Huancavelica","codigo_ubigeo":"09","etiqueta_ubigeo":"Huancavelica, Per\u00fa",
      "buscador_ubigeo":"huancavelica per\u00fa","numero_hijos_ubigeo":"7","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"3518","nombre_ubigeo":"Huanuco",
      "codigo_ubigeo":"10","etiqueta_ubigeo":"Huanuco, Per\u00fa","buscador_ubigeo":"huanuco per\u00fa","numero_hijos_ubigeo":"11","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},
      {"id_ubigeo":"3606","nombre_ubigeo":"Ica","codigo_ubigeo":"11","etiqueta_ubigeo":"Ica, Per\u00fa","buscador_ubigeo":"ica per\u00fa","numero_hijos_ubigeo":"5",
      "nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"3655","nombre_ubigeo":"Junin","codigo_ubigeo":"12","etiqueta_ubigeo":"Junin, Per\u00fa",
      "buscador_ubigeo":"junin per\u00fa","numero_hijos_ubigeo":"9","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"3788","nombre_ubigeo":"La Libertad",
      "codigo_ubigeo":"13","etiqueta_ubigeo":"La Libertad, Per\u00fa","buscador_ubigeo":"la libertad per\u00fa","numero_hijos_ubigeo":"12","nivel_ubigeo":"1",
      "id_padre_ubigeo":"2533"},{"id_ubigeo":"3884","nombre_ubigeo":"Lambayeque","codigo_ubigeo":"14","etiqueta_ubigeo":"Lambayeque, Per\u00fa",
      "buscador_ubigeo":"lambayeque per\u00fa","numero_hijos_ubigeo":"3","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"3926","nombre_ubigeo":"Lima",
      "codigo_ubigeo":"15","etiqueta_ubigeo":"Lima, Per\u00fa","buscador_ubigeo":"lima per\u00fa","numero_hijos_ubigeo":"10","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},
      {"id_ubigeo":"4108","nombre_ubigeo":"Loreto","codigo_ubigeo":"16","etiqueta_ubigeo":"Loreto, Per\u00fa","buscador_ubigeo":"loreto per\u00fa","numero_hijos_ubigeo":"6",
      "nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"4165","nombre_ubigeo":"Madre de Dios","codigo_ubigeo":"17","etiqueta_ubigeo":"Madre de Dios, Per\u00fa",
      "buscador_ubigeo":"madre de dios per\u00fa","numero_hijos_ubigeo":"3","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"4180","nombre_ubigeo":"Moquegua",
      "codigo_ubigeo":"18","etiqueta_ubigeo":"Moquegua, Per\u00fa","buscador_ubigeo":"moquegua per\u00fa","numero_hijos_ubigeo":"3","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},
      {"id_ubigeo":"4204","nombre_ubigeo":"Pasco","codigo_ubigeo":"19","etiqueta_ubigeo":"Pasco, Per\u00fa","buscador_ubigeo":"pasco per\u00fa","numero_hijos_ubigeo":"3",
      "nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"4236","nombre_ubigeo":"Piura","codigo_ubigeo":"20","etiqueta_ubigeo":"Piura, Per\u00fa",
      "buscador_ubigeo":"piura per\u00fa","numero_hijos_ubigeo":"8","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"4309","nombre_ubigeo":"Puno",
      "codigo_ubigeo":"21","etiqueta_ubigeo":"Puno, Per\u00fa","buscador_ubigeo":"puno per\u00fa","numero_hijos_ubigeo":"13","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},
      {"id_ubigeo":"4431","nombre_ubigeo":"San Martin","codigo_ubigeo":"22","etiqueta_ubigeo":"San Martin, Per\u00fa","buscador_ubigeo":"san martin per\u00fa",
      "numero_hijos_ubigeo":"10","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"4519","nombre_ubigeo":"Tacna","codigo_ubigeo":"23","etiqueta_ubigeo":"Tacna, Per\u00fa",
      "buscador_ubigeo":"tacna per\u00fa","numero_hijos_ubigeo":"4","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},{"id_ubigeo":"4551","nombre_ubigeo":"Tumbes",
      "codigo_ubigeo":"24","etiqueta_ubigeo":"Tumbes, Per\u00fa","buscador_ubigeo":"tumbes per\u00fa","numero_hijos_ubigeo":"3","nivel_ubigeo":"1","id_padre_ubigeo":"2533"},
      {"id_ubigeo":"4567","nombre_ubigeo":"Ucayali","codigo_ubigeo":"25","etiqueta_ubigeo":"Ucayali, Per\u00fa","buscador_ubigeo":"ucayali per\u00fa","numero_hijos_ubigeo":"4",
      "nivel_ubigeo":"1","id_padre_ubigeo":"2533"}];
      this.cities             = ciudades;
      this.modals.departments = ciudades;
    },
    getProvincias: function(option) {
      const department = this.modals.departments[option.target.selectedIndex - 1].id_ubigeo;

      this.modals.tambero.data.province.value      = '';
      this.modals.tambero.data.province.isLoading  = true;

      this.modals.provinces = [];

      fetch(`${ this.API }/geo?dep=${ department }&_wpnonce=${ mab.nonce }`)
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        }else{
          throw res;
        }
      })
      .then(response => {
        if (response.status) {
          this.modals.provinces = response.data;
        }

        this.modals.tambero.data.province.isLoading = false;
      })
      .catch(err => {
        this.modals.tambero.data.province.isLoading = false;

        throw err;
      })
    },
    getCategories: function(step) {
      this.isLoadingStep = true;

      fetch(`${ this.API }/courses/mab_categories?_wpnonce=${ mab.nonce }`)
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        }else{
          throw res
        }
      })
      .then(response => {
        if (response.status) {
          this.categories = response.data;
        }

        window.setTimeout(() => {
          this.isLoadingStep = false;
          this.view = `step-${ step }`;
        }, 1000)
      })
      .catch(err => {
        this.isLoadingStep = false; throw err;
      })
    },
    getSubcategories: function(step) {
      this.isLoadingStep = true;

      fetch(`${ this.API }/courses/mab_subcategories?categories=${ this.user.categories.value.join(',') }&_wpnonce=${ mab.nonce }`)
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        }else{
          throw res
        }
      })
      .then(response => {
        if (response.status) {
          this.subcategories = response.data;
        }

        window.setTimeout(() => {
          this.isLoadingStep = false;
          this.view = `step-${ step }`;
        }, 1000)
      })
      .catch(err => {
        this.isLoadingStep = false; throw err;
      })
    },

    validateText: function(parameter) {
      let pattern = new RegExp( parameter.pattern );
      let value   = parameter.value.trim()

      if(pattern.test(value)){
        return true;
      }else{
        return false
      }
    },
    validateSelect(parameter, field = 'value') {
      if(parameter[field] != '' && parameter.isValid == false){
        parameter.isValid = true
      }
    },
    isWrongField: function(field, mutiple = true, register = 'user') {
      if (mutiple) {
        return this.user.try && !this[register][field].isValid;
      } else {
        return this.user.try && !this[register][field];
      }
    },
    isWrongModalField: function(field, register) {
      return this.modals[register].data.try && !this.modals[register].data[field].isValid;
    },

    validateStep: function(step) {
      let isValid = false;

      this.user.try = true;

      switch (step) {
        case 0: {
            isValid = this.user.sector.isValid;
          }
          break;

        case 1: {
            isValid = this.profile.name.isValid &&
              this.profile.father_name.isValid &&
              this.profile.mother_name.isValid &&
              this.profile.date_birth.isValid &&
              this.profile.country.isValid &&
              ( (this.profile.country.value && this.profile.country.value.alpha2Code == 'PE')
                ? this.profile.city.isValid
                : true 
              ) &&
              this.profile.phone.isValid;
          }
          break;

        case 2: {
            isValid = this.user.role.isValid &&
              ((this.user.role.type == 'teacher' || this.user.role.type == 'tutor') ? this.modals.teacher.data.students.isValid : true) &&
              ((this.user.role.type == 'tambero') ? this.modals.tambero.data.department.isValid && this.modals.tambero.data.province.isValid : true);

            if (this.user.role.type == 'teacher' || this.user.role.type == 'tutor') {
              this.user.role.error = (!this.modals.teacher.data.students.isValid)
                ? 'Debe especificar la cantidad de estudiantes a cargo'
                : '';
            } else if (this.user.role.type == 'tambero') {
              this.user.role.error = (!this.modals.tambero.data.department.isValid || !this.modals.tambero.data.province.isValid)
                ? 'Debe especificar tu comunidad tambera'
                : '';
            }
          }
          break;

        case 3: {
            isValid = this.user.categories.isValid;
          }
          break;

        case 4: {
            isValid = this.user.subcategories.isValid;
          }
          break;
      }

      return isValid;
    },
    nextStep: function(step) {
      switch (step) {
        case 1:
          if ( !this.validateStep(0) ) return true;

          this.view     = `step-${ step }`;
          this.user.try = false;
          break;

        case 2:
          if ( !this.validateStep(1) ) return true;

          this.view     = `step-${ step }`;
          this.user.try = false;
          break;

        case 3:
          if ( !this.validateStep(2) ) return true;

          if (!this.categories.length) {
            this.getCategories(3);
          } else {
            this.view = `step-${ step }`;
          }

          this.user.try = false;
          break;

        case 4:
          if ( !this.validateStep(3) ) return true;

          this.getSubcategories(4);

          this.user.try = false;
          break;
      }
    },
    prevStep: function (step) {
      if (step == -1) {
        window.location.href = `${ this.SITE_URL }/access?auth=register`;
      } else {
        this.view = `step-${ step }`;
      }
    },
    validateModal: function(role) {
      if (role == 'teacher') {
        this.modals.teacher.data.try = true;

        if (this.modals.teacher.data.students.isValid) {
          this.modals.teacher.isOpened = false;
          this.user.try = false;

          this.getCategories(3);
        }
      } else {
        this.modals.tambero.data.try = true;

        if (this.modals.tambero.data.department.isValid && this.modals.tambero.data.province.isValid) {
          this.modals.tambero.isOpened = false;
          this.user.try = false;

          this.getCategories(3);
        }
      }
    },
    openModal: function(role) {
      this.modals[role].isOpened = true;

      if (role == 'tambero') {
        if (!this.cities.length) this.getCities();
      }
    },

    login: function(e) {
      e.preventDefault();

      this.user.try = true;

      if (this.user.email.isValid && this.user.password.isValid) {
        this.user.loading = true;

        fetch(`${ this.API }/auth/login?email=${ this.user.email.value }&password=${ this.user.password.value }&_wpnonce=${ mab.nonce }`,{
            method: 'GET'
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json();
            }else{
              throw res;
            }
          })
          .then(user => {
            // saveUserLoginSession(user)
            window.location = `${this.SITE_URL}/mis-cursos`;
          })
          .catch(err => {
            this.user.loading = false;
            this.user.error = 'El usuario o contraseña son incorrectos';

            throw err;
          })
      }
    },
    register: function(e, step = 'init') {
      e.preventDefault();

      if (step == 'init') {
        this.user.try = true;

        if (this.user.email.isValid && this.user.password.isValid && this.user.terms) {
          this.user.loading = true;

          fetch(`${ this.API }/auth/check?email=${ this.user.email.value }&_wpnonce=${ mab.nonce }`,{
              method: 'GET'
            })
            .then(res => {
              if (res.status >= 200 && res.status < 300) {
                return res.json();
              }else{
                throw res;
              }
            })
            .then(response => {
              this.user.loading = false;
              this.user.try     = false;

              if (response.status) {
                this.user.error = response.message;
              } else {
                this.view       = 'step-0';
                this.user.error = '';
              }
            })
            .catch(err => {
              throw err;
            })
        }
      } else {
        if ( this.validateStep(4) ) {
          this.user.loading = true;

          const formData = new FormData();

          formData.append('email', this.user.email.value);
          formData.append('password', (this.social) ? '123' : this.user.password.value);
          formData.append('sector', this.user.sector.value);
          formData.append('profile', JSON.stringify({
            name : this.profile.name.value,
            father_name : this.profile.father_name.value,
            mother_name : this.profile.mother_name.value,
            date_birth : this.profile.date_birth.value,
            country : this.profile.country.value.alpha2Code,
            city : this.profile.city.value,
            phone: {
              number: this.profile.phone.value,
              code: this.profile.country.value.callingCodes[0],
            },
          }));
          formData.append('role', JSON.stringify({
            type : this.user.role.type,
            extension : (this.user.role.type == 'teacher' || this.user.role.type == 'tutor')
              ? {
                  students : this.modals.teacher.data.students.value,
                }
              : {
                  comunityDepartment : this.modals.tambero.data.department.value,
                  comunity : this.modals.tambero.data.province.value
                }
          }));
          formData.append('category', this.user.categories.value.join(','));
          formData.append('subcategory', this.user.subcategories.value.join(','));
          formData.append('_wpnonce', mab.nonce);

          if (this.social)
            formData.append('social', this.social);

          fetch(`${ this.API }/auth/register`, {
              method: 'POST',
              body: formData
            })
            .then(res => {
              if (res.status >= 200 && res.status < 300) {
                return res.json();
              }else{
                throw res;
              }
            })
            .then(response => {
              this.user.loading = false;

              if (response.status) {
                window.location = `${this.SITE_URL}/mis-cursos`;
              } else {
                this.user.error = 'El usuario ya existe o ha ocurrido error, intentelo más tarde';
              }
            })
            .catch(err => {
              this.user.loading = false;
              this.user.error = 'Ha ocurrido error, intentelo más tarde';

              throw err;          
            })
        }
      }
    },
    initFacebook: function() {
      window.fbAsyncInit = function() {
        FB.init({
          appId   : mab.facebook_id,
          cookie  : true,
          xfbml   : true,
          version : 'v2.11'
        });

        FB.AppEvents.logPageView();   
      };

      (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    },
    authWithGoogle: function() {
      this.user.loading = true;
      this.user.error   = '';

      gapi.load('auth2', () => {
        const auth2 = gapi.auth2.init({
          client_id: mab.google_id,
          fetch_basic_profile: true,
          scope: 'profile',
        });

        auth2.signIn().then(user => {
          const profile   = user.getBasicProfile();

          this.handleAuthSocial({
            email: profile.getEmail(),
            first_name: profile.getGivenName(),
            last_name: profile.getFamilyName(),
          }, 'google');
        }, (err) => {
          this.user.error = err.error == 'popup_closed_by_user'
            ? 'No se ha podido completar la operación'
            : (err.error == 'access_denied'
              ? 'No se ha autorizado la operación, vuelve a intentarlo'
              : 'Ha ocurrido un error, intentado nuevamente en unos minutos');

          this.user.loading = false;
        })
      })
    },
    authWithFacebook: function() {
      this.user.loading = true;

      FB.getLoginStatus((response) => {
        if (response.status == 'connected') {
          this.handleFacebookAuth();
        } else if(response.status == 'authorization_expired') {
          FB.login((response) => {
            if (response.authResponse) {
              this.handleFacebookAuth();
            } else {
              this.user.loading = false;
              this.user.error   = 'No se ha autorizado la operación, vuelve a intentarlo';
            }
          }, {scope: 'public_profile,email', auth_type: 'reauthorize'});
        } else {
          FB.login((response) => {
            if (response.authResponse) {
              this.handleFacebookAuth();
            } else {
              this.user.loading = false;
              this.user.error   = 'No se ha autorizado la operación, vuelve a intentarlo';
            }
          }, {scope: 'public_profile,email'});
        }
      });
    },
    handleFacebookAuth: function() {
      FB.api('/me', { fields: 'first_name, last_name, email, locale' }, (userData) => {
        this.handleAuthSocial({
          email: userData.email,
          first_name: userData.first_name,
          last_name: userData.last_name,
        }, 'facebook');
      })
    },
    handleAuthSocial: function(user, social) {
      const formData  = new FormData();

      formData.append('email', user.email);
      formData.append('_wpnonce', mab.nonce );

      fetch(`${ this.API }/auth/social`, {
        method: 'POST',
        body: formData,
      })
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          return res.json();
        }else{
          throw res;
        }
      })
      .then(response => {
        if (response.status) {
          // saveUserLoginSession(user)
          window.location = `${this.SITE_URL}/mis-cursos`;
        } else {
          this.view   = 'step-0';
          this.social = social;

          const familyName = user.last_name;

          this.user.email.value           = user.email;

          this.profile.name.value         = user.first_name;
          this.profile.father_name.value  = (familyName.split(' ').length) ? familyName.substr(0, familyName.indexOf(' ')) : familyName;
          this.profile.mother_name.value  = (familyName.split(' ').length) ? familyName.substr(familyName.indexOf(' ') + 1) : '';

          this.user.loading = false;
        }
      })
      .catch(err => {
        this.user.error     = 'Ha ocurrido un error, intentelo más tarde';
        this.user.loading   = false;

        throw err;
      })
    },

    openTerms: function(e) {
      e.preventDefault();

      this.isOpenedTerms = true;
    },
  }
})

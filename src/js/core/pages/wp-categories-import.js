import Vue from 'vue';
import {baseConfig, baseState} from '../wp_admin'
import {store} from '../store'

new Vue({
  ...baseConfig(store),
  data() {
    return {
      coursesDatabase: null,
      isLoading: false,
      step: 'En espera de archivo'
    }
  },
  computed: {
    ...baseState()
  },
  mounted() { },
  methods: {
    changeCoursesDatabase: function(event) {
      this.coursesDatabase = event.target.files[0]
    },
    importCoursesDatabase: function() {
      if (this.coursesDatabase) {
        this.isLoading  = true;
        this.step       = 'Subiendo base de datos';

        const formData = new FormData();

        formData.append('database', this.coursesDatabase)

        fetch(`${this.API}/courses/database`, {
          method: 'POST',
          body: formData,
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(response => {
          this.createCourses(response.id);
        })
        .catch(err => {
          alert('La base de datos no se pudo subir correctamente, intentelo más tarde');

          throw err;          
        }) 
      } else {
        alert('Necesita cargar un archivo .xls con los usuarios')
      }
    },
    createCourses: function(databaseId) {
      this.step = 'Creando cursos';

      const formData = new FormData();

      formData.append('database_id', databaseId)

      fetch(`${this.API}/courses/mab_categories`, {
        method: 'POST',
        body: formData,
      })
      .then(res => {
        if (res.status >= 200 && res.status < 300) {
          this.step = 'Cursos creados exitosamente!';
        }else{
          throw res
        }
      })
      .catch(err => {
        alert('Error al crear los cursos, problemas en ...');

        throw err;          
      })
      .finally(() => {
        this.isLoading = false;
      })
    },
  }
})

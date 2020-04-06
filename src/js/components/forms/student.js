import Vue from 'vue'
import Vuex from 'vuex'
import {baseData, baseWatch, baseMethods} from './libs/form'
import './accept'

Vue.component('form-student',{
  template: /*html*/
  `
  <section class="width-100">
    <form-accept :switcher.sync="isSentForm"></form-accept>
    <form class="c-form-box form_box" action="">
      <div class="input_container">
        <label for="">Nombre Completo</label>
        <input 
          class="c-form-box__input input-reset" 
          :class="{ valid : name.isValid }"
          type="text" 
          v-model="name.value">
      </div>
      <div class="input_container">
        <label for="">Apellido Paterno</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : lastFatherName.isValid }"
          type="text" 
          v-model="lastFatherName.value">
      </div>
      <div class="input_container">
        <label for="">Apellido Materno</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : lastMotherName.isValid }"
          type="text" 
          v-model="lastMotherName.value">
      </div>
      <div class="input_container">
        <label for="">Email</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : email.isValid }"
          type="email" 
          v-model="email.value">
      </div>
      <div class="input_container">
        <label for="">Teléfono</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : phone.isValid }"
          type="tel" 
          v-model="phone.value">
      </div>
      <div class="input_container">
        <label for="">Celular</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : mobile.isValid }"
          type="tel" 
          v-model="mobile.value">
      </div>
      <div class="input_container">
        <label for="">Tipo de colegio</label>
        <select 
          class="c-form-box__select school_type select-reset"
          :class="{ valid : schoolType.isValid }"
          v-model="schoolType.value"
          >
          <option disabled value="" selected>Selecciona una opción</option>
          <option value="privado">Privado</option>
          <option value="publico">Público</option>                  
        </select>
      </div>
      <div 
        v-if="schoolType.value == 'publico' " 
        class="input_container ugel_container d-none">
        <label for="">UGEL</label>
        <select 
          class="c-form-box__select school_type select-reset"
          :class="{ valid : ugel.isValid }"
          v-model="ugel.value">
          <option disabled value="" selected>Selecciona una opción</option>
          <option v-for="ugel of ugels" :key="ugel.id" :value="ugel" >{{ugel}}</option>
        </select>
      </div>
      <div class="input_container">
        <label for="">Nombre de tu colegio</label>
        <select 
          class="c-form-box__select select-reset"
          :class="{ valid : school.isValid }"
          v-model="school.value">
          <option disabled value="" selected>Selecciona una opción</option>
          <option v-for="school of schools" :key="school.id" :value="school" >{{school}}</option>
        </select>
      </div>
      <div class="input_container">
        <label for="">Grado escolar</label>
        <select 
          class="c-form-box__select select-reset"
          :class="{ valid : grade.isValid }"
          v-model="grade.value">
          <option disabled value="" selected>Selecciona una opción</option>
          <option v-for="_grade of grades" :key="_grade.id" :value="_grade" >{{_grade}}</option>
        </select>
      </div>
      <div class="input_container">
        <label for="">Edad</label>
        <input 
          class="c-form-box__input input-reset" 
          :class="{ valid : age.isValid }"
          type="number"
          v-model="age.value">
      </div>
      <div class="input_container">
        <label for="">Departamento</label>
        <select
          class="c-form-box__select select-reset" 
          :class="{ valid : department.isValid }"
          v-model="department.value"
          @change="getProvincias">
          <option disabled value="" selected>Selecciona una opción</option>
          <option v-for="dep of departamentos" :key="dep.id" :value="dep.nombre_ubigeo" >{{dep.nombre_ubigeo}}</option>                               
        </select>
      </div>
      <div class="input_container">
        <label for="">Provincia</label>
        <select 
          name="" 
          class="c-form-box__select select-reset" 
          :class="{ valid : province.isValid }"
          v-model="province.value"
          @change="getDistritos">
          <option disabled value="" selected>Selecciona una opción</option>
          <option v-for="prov of provincias" :key="prov.id" :value="prov.nombre_ubigeo" >{{prov.nombre_ubigeo}}</option>                 
        </select>
      </div>
      <div class="input_container">
        <label for="">Distrito</label>
        <select 
          class="c-form-box__select select-reset" 
          :class="{ valid : district.isValid }" 
          v-model="district.value">
          <option disabled value="" selected>Selecciona una opción</option>
          <option v-for="dis of distritos" :key="dis.id" :value="dis.nombre_ubigeo" >{{dis.nombre_ubigeo}}</option>                                             
        </select>
      </div>
      <div class="btn_container">
        <button 
          class="c-form-box__sender" 
          type="button" 
          :disabled="counter.status != counter.limit"
          @click="sendForm">Listo</button>
      </div>
    </form>  
  </section>
  `,
  data() {
    return {
      ...baseData(),
      counter: {
        status: 0,
        limit: 13,
      },    
      grade: {
        value: '',
        isValid: false
      },
      age: {
        value: '',
        pattern: '^([0-9]+)$',
        isValid: false
      }
    }
  },  
  computed: {
    ...Vuex.mapState(['API'])
  },
  watch: {
    ...baseWatch(),
    'grade.value': function(){
      this.validateSelect(this.grade)
    },
    'age.value': function(){
      this.age.isValid = this.validateText(this.age)
    },
    'school.value': function(value){
      this.validateSelect(this.school)
    }
  },  
  methods: {
    ...baseMethods(),
    sendForm: function(){

      let tutor_form_data = new FormData();

      tutor_form_data.append('name', this.name.value)
      tutor_form_data.append('last_father_name', this.lastFatherName.value)
      tutor_form_data.append('last_mother_name', this.lastMotherName.value)
      tutor_form_data.append('email', this.email.value)
      tutor_form_data.append('phone', this.phone.value)
      tutor_form_data.append('mobile', this.mobile.value)
      tutor_form_data.append('school_type', this.schoolType.value)
      tutor_form_data.append('ugel', this.ugel.value)
      tutor_form_data.append('school', this.school.value)
      tutor_form_data.append('grade', this.grade.value)
      tutor_form_data.append('age', this.age.value)
      tutor_form_data.append('department', this.department.value)
      tutor_form_data.append('province', this.province.value)
      tutor_form_data.append('district', this.district.value)

      fetch(`${this.API}/form/student`,{
          method: 'POST',
          body: tutor_form_data
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(response => {
          this.isSentForm = true;
        })
        .catch(err => {
          throw err;          
        })      
    }    
  },  
});

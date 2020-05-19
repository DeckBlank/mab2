import Vue from 'vue'
import Vuex from 'vuex'
import {baseData, baseWatch, baseMethods} from './libs/form'
import './accept'

Vue.component('form-student',{
  template: /*html*/
  `
  <section class="width-100">
    <form-accept :switcher.sync="isSentForm" :user="email.value" :password="password.value"></form-accept>
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
        <label for="">Crear contraseña</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : password.isValid }"
          type="password" 
          v-model="password.value">
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
      <div
        class="input_container">
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
        v-if="schoolType.value == 'privado' "      
        class="input_container">
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
      <div v-if="isSentFormError" class="margin-bottom-1">
        <p class="white fs-18 f2 w-medium text-center margin-bottom-0">El usuario ya existe</p>
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
      //School
      grades: [
        "1RO PRIMARIA",
        "2DO PRIMARIA",
        "3RO PRIMARIA",
        "4TO PRIMARIA",
        "5TO PRIMARIA",
        "6TO PRIMARIA",
        "1RO SECUNDARIA",
        "2DO SECUNDARIA",
        "3RO SECUNDARIA",
        "4TO SECUNDARIA",
        "5TO SECUNDARIA",
        "6TO SECUNDARIA"
      ],

      counter: {
        status: 0,
        base: 13,
        limit: 14
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
    }
  },  
  methods: {
    ...baseMethods(),
    sendForm: function(){

      let form_data = new FormData();

      form_data.append('type', 'student')
      form_data.append('user_name', `${this.name.value} ${this.lastFatherName.value} ${this.lastMotherName.value}`)
      form_data.append('first_name', this.name.value)
      form_data.append('last_name', `${this.lastFatherName.value} ${this.lastMotherName.value}`)
      form_data.append('email', this.email.value)
      form_data.append('password', this.password.value)
      form_data.append('phone', this.phone.value)
      form_data.append('mobile', this.mobile.value)
      form_data.append('school_type', this.schoolType.value)
      form_data.append('ugel', '')
      form_data.append('school', this.school.value)
      form_data.append('grade', this.grade.value)
      form_data.append('age', this.age.value)
      form_data.append('location', `${this.department.value}, ${this.province.value}, ${this.district.value}`)

      fetch(`${this.API}/user`,{
          method: 'POST',
          body: form_data
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(response => {
          this.isSentFormError = false;
          this.isSentForm = true;
        })
        .catch(err => {
          this.isSentFormError = true;

          throw err;          
        })      
    }    
  },  
});

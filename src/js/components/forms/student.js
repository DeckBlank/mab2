import Vue from 'vue'
import Vuex from 'vuex'
import {baseData, baseWatch, baseMethods} from './libs/form'
import './accept'

Vue.component('form-student',{
  template: /*html*/`
  <section class="width-100">
    <form-accept :switcher.sync="isSentForm" :user="email.value" :password="password.value"></form-accept>
    <form class="c-form-box form_box" action="">
      <div v-if="!isForeign" class="input_container">
        <label for="">Nombres</label>
        <input 
          class="c-form-box__input input-reset" 
          :class="{ valid : name.isValid }"
          type="text" 
          v-model="name.value">
        <p v-if="!name.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">Nombre incorrecto</p>
      </div>
      <div v-if="!isForeign" class="input_container">
        <label for="">Apellido Paterno</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : lastFatherName.isValid }"
          type="text" 
          v-model="lastFatherName.value">
        <p v-if="!lastFatherName.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">Apellido incorrecto</p>          
      </div>
      <div v-if="!isForeign" class="input_container">
        <label for="">Apellido Materno</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : lastMotherName.isValid }"
          type="text" 
          v-model="lastMotherName.value">
        <p v-if="!lastMotherName.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">Apellido incorrecto</p>          
      </div>
      <div class="input_container">
        <label for="">Edad</label>
        <input 
          class="c-form-box__input input-reset" 
          :class="{ valid : age.isValid }"
          type="number"
          v-model="age.value">
        <p v-if="!age.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">Edad incorrecta</p>          
      </div>      
      <div
        class="input_container">
        <label for="">Sexo</label>
        <select 
          class="c-form-box__select school_type select-reset"
          :class="{ valid : gender.isValid }"
          v-model="gender.value"
          >
          <option disabled value="" selected>Selecciona una opción</option>
          <option value="hombre">Hombre</option>
          <option value="mujer">Mujer</option>
        </select>
        <p v-if="!gender.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">No ha seleccionado una opción</p>                         
      </div>      
      <div v-if="!isForeign" class="input_container">
        <label for="">Correo electrónico</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : email.isValid }"
          type="email" 
          v-model="email.value">
        <p v-if="!email.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">Email incorrecto</p>           
      </div>
      <div v-if="!isForeign" class="input_container">
        <label for="">Crear contraseña</label>
        <div class="position-relative">
          <span v-if="!password.visible" @click="password.visible = !password.visible" class="c-form-box__toggle position-absolute">Mostrar</span>
          <span v-else @click="password.visible = !password.visible" class="c-form-box__toggle position-absolute">Ocultar</span>
          <input 
            class="c-form-box__input c-form-box__input--password input-reset"
            :class="{ valid : password.isValid }"
            :type="(password.visible) ? 'text' : 'password'" 
            v-model="password.value">
        </div>
        <p v-if="!password.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">Contraseña vacía</p>           
      </div>
      <div class="input_container">
        <label for="">País de residencia</label>
        <select
          id="countries"
          class="c-form-box__select select-reset" 
          :class="{ valid : country.isValid }"
          v-model="country.value">
          <option disabled value="" selected>Selecciona una opción</option>
          <option v-for="coun of countries" :key="coun.id" :code="coun.callingCodes[0]" :value="coun.name">{{coun.name}}</option>                               
        </select>
        <p v-if="!country.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">No ha seleccionado una opción</p>        
      </div>
      <div v-if="country.value.toLowerCase() == 'peru'" class="input_container">
        <label for="">Departamento</label>
        <select
          class="c-form-box__select select-reset" 
          :class="{ valid : department.isValid }"
          v-model="department.value"
          @change="getProvincias">
          <option disabled value="" selected>Selecciona una opción</option>
          <option v-for="dep of departamentos" :key="dep.id" :value="dep.nombre_ubigeo" >{{dep.nombre_ubigeo}}</option>                               
        </select>
        <p v-if="!department.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">No ha seleccionado una opción</p>        
      </div>
      <div v-if="country.value.toLowerCase() == 'peru'" class="input_container">
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
        <p v-if="!province.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">No ha seleccionado una opción</p>        
      </div>
      <div v-if="country.value.toLowerCase() == 'peru'" class="input_container">
        <label for="">Distrito</label>
        <select 
          class="c-form-box__select select-reset" 
          :class="{ valid : district.isValid }" 
          v-model="district.value">
          <option disabled value="" selected>Selecciona una opción</option>
          <option v-for="dis of distritos" :key="dis.id" :value="dis.nombre_ubigeo" >{{dis.nombre_ubigeo}}</option>                                             
        </select>
        <p v-if="!district.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">No ha seleccionado una opción</p>        
      </div>      
      <div class="input_container">
        <label for="">Celular</label>
        <div class="flex-container">
          <div class="c-form-box__input bg-warning dark flex-container align-middle padding-horizontal-1 margin-right-1">
            + {{callingCode.value}}
          </div>
          <input 
            class="c-form-box__input input-reset"
            :class="{ valid : mobile.isValid }"
            type="tel" 
            v-model="mobile.value">
        </div>
        <p v-if="!mobile.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">Celular incorrecto</p>          
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
          <option value="publico">Público / Curriculo nacional</option>                  
        </select>
        <p v-if="!schoolType.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">No ha seleccionado una opción</p>
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
        <p v-if="!school.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">No ha seleccionado una opción</p>        
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
        <p v-if="!grade.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">No ha seleccionado una opción</p>        
      </div>
      <div v-if="isSentFormError" class="margin-bottom-1">
        <p class="white fs-18 f2 w-medium text-center margin-bottom-0">El usuario ya existe</p>
      </div>      
      <div class="btn_container margin-bottom-1">
        <button 
          v-if="isForeign"
          class="c-form-box__sender" 
          :disabled="isSending"
          type="button"
          @click="updateProfile">Actualizar</button>
        <button
          v-else
          class="c-form-box__sender" 
          :disabled="isSending"
          type="button"
          @click="sendForm">Listo</button>
      </div>
      <p v-if="!is_valid_form" class="w-medium fs-21 white f2 text-center">Uno o más campos no han sido llenados correctamente, ¡Revisalos!</p>
    </form>  
  </section>
  `,
  data() {
    return {
      ...baseData(),
      //School
      grades: [
        "Bebés",
        "Inicial 3 años",
        "Inicial 4 años",
        "Inicial 5 años",
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
    ...Vuex.mapState(['API', 'SITE_URL', 'logedUser']),
    isForeign: function() {
      return (this.logedUser && this.logedUser.user_rol == 'foreign') ? true : false;
    },
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
  beforeMount(){
    this.getCountries();
  },
  methods: {
    ...baseMethods(),
    sendForm: function(){
      this.is_valid_form = this.name.isValid &&
        this.lastFatherName.isValid &&
        this.lastMotherName.isValid &&
        this.gender.isValid &&
        this.email.isValid &&
        this.password.isValid &&
        this.mobile.isValid &&
        this.schoolType.isValid &&
        ((this.schoolType.value == 'privado') ? this.school.isValid : true) &&
        this.grade.isValid &&
        this.age.isValid &&
        this.country.isValid &&
        this.department.isValid &&
        this.province.isValid &&
        this.district.isValid;

      if(this.is_valid_form){
        let form_data = new FormData();
  
        form_data.append('type', 'student')
        form_data.append('user_name', `${this.name.value} ${this.lastFatherName.value} ${this.lastMotherName.value}`)
        form_data.append('first_name', this.name.value)
        form_data.append('last_name', `${this.lastFatherName.value} ${this.lastMotherName.value}`)
        form_data.append('gender', this.gender.value)
        form_data.append('email', this.email.value)
        form_data.append('password', this.password.value)
        form_data.append('phone', this.phone.value)
        form_data.append('calling_code', this.callingCode.value)
        form_data.append('mobile', this.mobile.value)
        form_data.append('school_type', this.schoolType.value)
        form_data.append('ugel', '')
        form_data.append('school', this.school.value)
        form_data.append('grade', this.grade.value)
        form_data.append('age', this.age.value)
        form_data.append('location', `${this.country.value}, ${this.department.value}, ${this.province.value}, ${this.district.value}`)

        this.isSending = true;
  
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
            this.isSending = false;
  
            throw err;          
          })      
      }
    },
    updateProfile: function() {
      this.is_valid_form = this.gender.isValid &&
        this.mobile.isValid &&
        this.schoolType.isValid &&
        ((this.schoolType.value == 'privado') ? this.school.isValid : true) &&
        this.grade.isValid &&
        this.age.isValid &&
        this.country.isValid &&
        this.department.isValid &&
        this.province.isValid &&
        this.district.isValid;

      if(this.is_valid_form){
        this.isSending = true;

        fetch(`${this.API}/user`,{
            method: 'PUT',
            headers: {
              'Content-Type' : 'application/json'
            },
            body: JSON.stringify({
              type: 'student',
              email: this.logedUser.user_email,
              gender: this.gender.value,
              phone: this.phone.value,
              calling_code: this.callingCode.value,
              mobile: this.mobile.value,
              school_type: this.schoolType.value,
              ugel: '',
              school: this.school.value,
              grade: this.grade.value,
              age: this.age.value,
              location: `${this.country.value}, ${this.department.value}, ${this.province.value}, ${this.district.value}`,
            }),
          })
          .then(res => {
            if (res.status >= 200 && res.status < 300) {
              return res.json()
            }else{
              throw res
            }
          })
          .then(response => {
            window.location.href = `${this.SITE_URL}/emotional`;
          })
          .catch(err => {
            this.isSentFormError = true;
            this.isSending = false;

            throw err;          
          })      
      }
    },
  },  
});

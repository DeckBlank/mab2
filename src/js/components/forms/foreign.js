import Vue from 'vue'
import Vuex from 'vuex'
import {baseData, baseWatch, baseMethods} from './libs/form'
import './accept'

Vue.component('form-foreign',{
  template: /*html*/`
  <section class="width-100">
    <form-accept :switcher.sync="isSentForm" :user="email.value" :password="password.value"></form-accept>
    <form class="c-form-box form_box" action="">
      <div class="input_container">
        <label for="">Nombres</label>
        <input 
          class="c-form-box__input input-reset" 
          :class="{ valid : name.isValid }"
          type="text" 
          v-model="name.value">
        <p v-if="!name.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">Nombre incorrecto</p>
      </div>
      <div class="input_container">
        <label for="">Apellido Paterno</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : lastFatherName.isValid }"
          type="text" 
          v-model="lastFatherName.value">
        <p v-if="!lastFatherName.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">Apellido incorrecto</p>          
      </div>
      <div class="input_container">
        <label for="">Apellido Materno</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : lastMotherName.isValid }"
          type="text" 
          v-model="lastMotherName.value">
        <p v-if="!lastMotherName.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">Apellido incorrecto</p>          
      </div>     
      <div class="input_container">
        <label for="">Correo electrónico</label>
        <input 
          class="c-form-box__input input-reset"
          :class="{ valid : email.isValid }"
          type="email" 
          v-model="email.value">
        <p v-if="!email.isValid && !is_valid_form" class="c-form-box__error margin-bottom-0 fs-18 f2 w-medium white">Email incorrecto</p>           
      </div>
      <div class="input_container">
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
      <div v-if="isSentFormError" class="margin-bottom-1">
        <p class="white fs-18 f2 w-medium text-center margin-bottom-0">El usuario ya existe</p>
      </div>      
      <div class="btn_container margin-bottom-1">
        <button 
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
    }
  },  
  computed: {
    ...Vuex.mapState(['API'])
  },
  watch: {
    ...baseWatch(),
  },
  methods: {
    ...baseMethods(),
    sendForm: function(){
      this.is_valid_form = this.name.isValid &&
        this.lastFatherName.isValid &&
        this.lastMotherName.isValid &&
        this.email.isValid &&
        this.password.isValid;

      if(this.is_valid_form){
        let form_data = new FormData();
  
        form_data.append('type', 'foreign')
        form_data.append('user_name', `${this.name.value} ${this.lastFatherName.value} ${this.lastMotherName.value}`)
        form_data.append('first_name', this.name.value)
        form_data.append('last_name', `${this.lastFatherName.value} ${this.lastMotherName.value}`)
        form_data.append('email', this.email.value)
        form_data.append('password', this.password.value)

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
    }
  },  
});

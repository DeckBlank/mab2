function baseData(){
  return {
    //Geo
    departamentos: require('../../../extras/ubigeo/departamentos.json'),
    provincias: [],
    distritos: [],

    //Schools
    schools: [],

    //Form
    is_valid_form: true,
    isSentForm: false,
    isSending: false,
    isSentFormError: false,
    
    //Model
    name: {
      value: '',
      pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$",
      isValid: null
    },
    lastFatherName: {
      value: '',
      pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$",
      isValid: false
    },
    lastMotherName: {
      value: '',
      pattern: "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$",
      isValid: false
    },
    gender: {
      value: '',
      isValid: false
    },    
    email: {
      value: '',
      pattern: "[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$",
      isValid: false
    },
    password: {
      value: '',
      isValid: false
    },
    phone: {
      value: '',
      pattern: '^([0-9]+)$',
      isValid: false
    },
    mobile: {
      value: '',
      pattern: '^([0-9]+)$',
      isValid: false
    },
    schoolType: {
      value: '',
      isValid: false
    },
    school: {
      value: '',
      isValid: false
    },
    department: {
      value: '',
      isValid: false
    },
    province: {
      value: '',
      isValid: false
    },
    district: {
      value: '',
      isValid: false
    }            
  }
}

function baseWatch(){
  return {
    'name.value': function(){
      this.name.isValid = this.validateText(this.name)
    },
    'lastFatherName.value': function(){
      this.lastFatherName.isValid = this.validateText(this.lastFatherName)
    },
    'lastMotherName.value': function(){
      this.lastMotherName.isValid = this.validateText(this.lastMotherName)
    },
    'gender.value': function(value){
      this.validateSelect(this.gender)
    },    
    'email.value': function(){
      this.email.isValid = this.validateText(this.email)
    },
    'password.value': function(value){
      if(value != '' && this.password.isValid == false){
        this.password.isValid = true
      }else if(value == '' && this.password.isValid == true){
        this.password.isValid = false
      }
    },
    'phone.value': function(){
      this.phone.isValid = this.validateText(this.phone)
    },
    'mobile.value': function(){
      this.mobile.isValid = this.validateText(this.mobile)
    },
    'schoolType.value': function(value){
      this.validateSelect(this.schoolType) 

      if(value == 'privado'){
        this.getSchools(value)
      }

      if(this.school && this.school.value != ''){
        this.school.value = ''
        this.school.isValid = false

      }else if(this.childrenSchool && this.childrenSchool.value != ''){
        this.childrenSchool.value = ''
        this.childrenSchool.isValid = false      
      }      
    },
    'school.value': function(value){
      this.validateSelect(this.school)
    },
    'department.value': function(){
      this.validateSelect(this.department)  
    },
    'province.value': function(value){
      this.validateSelect(this.province)  
    },
    'district.value': function(value){
      this.validateSelect(this.district)  
    }
  }
}

function baseMethods(){
  return {
    validateText: function(parameter){
      let input_pattern = new RegExp( parameter.pattern ),
        input_value = parameter.value.trim()

      if(input_pattern.test(input_value)){
        return true;
      }else{
        return false
      }
    },
    validateSelect: function(parameter){
      if(parameter.value != '' && parameter.isValid == false){
        parameter.isValid = true
      }
    },
    getProvincias: function(option) {
      let provincias = require('../../../extras/ubigeo/provincias.json')
      this.provincias = provincias[this.departamentos[option.target.selectedIndex - 1].id_ubigeo]
      this.distritos = [];
    },
    getDistritos: function(option) {
      let distritos = require('../../../extras/ubigeo/distritos.json')
      this.distritos = distritos[this.provincias[option.target.selectedIndex - 1].id_ubigeo]
    },
    getSchools: function(type, ugel){
      if(type == 'privado'){
        this.schools = require('../../../extras/schools/privates.json')
      }
    } 
  }
}

export {baseData, baseWatch, baseMethods}

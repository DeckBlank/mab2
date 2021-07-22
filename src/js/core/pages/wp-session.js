/**
 * @generatePassword
 * @createRoom
 */

function generatePassword() {
  let length = 8,
    charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
    retVal = "";

  for (let i = 0, n = charset.length; i < length; ++i) {
    retVal += charset.charAt(Math.floor(Math.random() * n));
  }

  return retVal;
}

function createRoom(button, api){
  let roomInputEditor = document.querySelector('.acf-fields .acf-field:last-child input')

  fetch(`${api}/session`,{
      method: 'POST'
    })
    .then(res => {
      if (res.status >= 200 && res.status < 300) {
        return res.json()
      }else{
        throw res
      }      
    })
    .then(session => {
      roomInputEditor.value = session.id;
      button.value = "Crear sala"
    })
    .catch(err => {
      throw err;
    })
}

/**
 * DOM
 */
document.getElementById('generate-key').onclick = function(){
  let codeInputEditor = document.querySelector('.acf-fields .acf-field:first-child input')
  codeInputEditor.value = generatePassword();
}

document.getElementById('create-room').onclick = function(){
  this.value = 'Creando...' ;
  createRoom(this, `${ this.getAttribute('data-site') }/wp-json/custom/v1`);
}

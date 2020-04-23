/**
 * Global
 */
const API = ( window.location.hostname == 'localhost' ) ? 'http://localhost/mab/wp-json/custom/v1' : 'https://mabclick.com/wp-json/custom/v1'

/**
 * @exportRegistrations
 * @spinnerLoading
 * @getExpiredRegistrations
 */
function exportRegistrations(){
  spinnerLoading(true);

  fetch(`${API}/courses/expired_registrations/download`)
    .then(res => {
      if (res.status >= 200 && res.status < 300) {
        spinnerLoading(false); window.location = `${API}/courses/expired_registrations/download`
      }else{
        throw res
      }
    })
    .catch(err => {throw err})
}

function spinnerLoading(state){
  if(state){
    document.querySelector('#export-spinner').style = "visibility: visible";
  }else{
    document.querySelector('#export-spinner').style = "visibility: hidden";
  }
}

function getExpiredRegistrations(){
  fetch(`${API}/courses/expired_registrations`)
    .then(res => {
      if (res.status >= 200 && res.status < 300) {
        return res.json()
      }else{
        throw res
      }
    })
    .then(expireds => {
      document.querySelector('#expired-counter').innerHTML = expireds.length
    })
    .catch(err => {
      document.querySelector('#export').setAttribute('disabled', true)

      throw err
    })
}

/**
 * View
 */
let metabox = document.querySelector('#side-sortables');

metabox.innerHTML += `
  <div class="postbox ">
    <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Alternar panel: Exportar</span><span class="toggle-indicator" aria-hidden="true"></span></button>
    <h2 class="hndle ui-sortable-handle"><span>Registos vencidos <b id="expired-counter" style="color: red">0</b></span></h2>
    <div class="inside">
      <div>
        <div style="display: flex; justify-content: flex-end">
          <span id="export-spinner" class="spinner"></span>
          <button class="button button-primary button-large" id="export">Exportar (.xls)</button>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
`;

/**
 * DOM
 */
document.querySelector('#export').onclick = ()=>{
  event.preventDefault(); exportRegistrations();
}
getExpiredRegistrations()

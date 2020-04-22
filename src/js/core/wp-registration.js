let metabox = document.querySelector('#side-sortables');

metabox.innerHTML += `
  <div class="postbox ">
    <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Alternar panel: Exportar</span><span class="toggle-indicator" aria-hidden="true"></span></button>
    <h2 class="hndle ui-sortable-handle"><span>Registos vencidos <b style="color: red">12</b></span></h2>
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
 * @exportRegistrations
 */
function exportRegistrations(){
  alert()
}

function spinnerLoading(state){
  if(state){
    document.querySelector('#export-spinner').style = "visibility: visible";
  }else{
    document.querySelector('#export-spinner').style = "visibility: hidden";
  }
}

/**
 * DOM
 */
document.querySelector('#export').onclick = ()=>{
  spinnerLoading(true);
  event.preventDefault(); exportRegistrations();
  spinnerLoading(false);
}


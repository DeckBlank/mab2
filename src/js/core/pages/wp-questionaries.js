/**
 * View
 */
let season = document.querySelector('[data-name="questionary_season"] input')

/**
 * DOM
 */
document.querySelector('[data-name="questionary_enable"] .acf-switch-input').onchange = (event)=>{
  if(event.target.checked) {
    let season__ = Number(season.value) + 1;
    season.value = season__;
  }
}

const model = {
  nav: document.querySelector('.tablenav.top > .bulkactions')
};

function exportTopics(button) {
  const paged   = (new URLSearchParams(window.location.search)).get('paged');
  const courses = [...document.querySelectorAll('.type-course > .check-column > input')].filter(co => co.checked).map(co => co.value);
  let request   = (paged)
    ? `${ mab.site }/wp-json/custom/v1/topics/export?page=${paged}`
    : `${ mab.site }/wp-json/custom/v1/topics/export?page=1`;
  
  request = (courses.length)
    ? `${request}&ids=${courses.join(',')}`
    : request;

  fetch(request)
    .then(res => {
      if (res.status >= 200 && res.status < 300) {
        return res.json()
      } else {
        throw res
      }      
    })
    .then(courses => {
      const dataStr       = 'data:text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(courses));
      const downloadLink  = document.createElement('a');

      downloadLink.setAttribute('href', dataStr);
      downloadLink.setAttribute('download', 'topics.json');
      downloadLink.click();

      button.disabled = false;
    })
    .catch(err => {
      throw err;
    })
}

model.nav.innerHTML += `
  <button id="export-topics" class="button button-primary ml-2">Exportar temas</button>
`;

document.querySelector('#export-topics').onclick = (e) => {
  e.preventDefault();
  e.target.disabled = true;

  exportTopics(e.target);
}

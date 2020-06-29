<div 
    id="reports-section"
    class="wrap" 
    data-site="<?php echo get_site_url(); ?>">
    <h2 class="mb-1">Matriculas</h2>
    <div class="mb-1">
    </div>
    <div class="d-flex mb-1">
        <input class="mr-1" type="text" placeholder="Usuario (email)" id="user">
        <button id="search" class="button button-primary mr-1">Buscar</button>
        <a  
            id="expired-download"
            href="<?php echo get_site_url() . '/wp-json/custom/v1/courses/enrollments/expired/download' ?>"
            class="button button-success">
            Descargar registros vencidos (.xls)
        </a>
    </div>
    <p>Matriculas vencidas: <strong id="expired" class="trash">0</strong></p>
    <table class="widefat fixed mb-1" cellspacing="0">
        <thead>
            <tr>
                <th id="columnname" class="manage-column column-columnname" scope="col">Usuario</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Email</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Curso</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Estado</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Inscripcion (fecha)</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Vencimiento (fecha)</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Ultima actividad</th>
            </tr>
        </thead>
        <tbody id="results"></tbody>
    </table>
    <div class="flex-container align-center">
        <button id="load-more" class="button button-primary" onclick="loadMore()">Load more...</button>
    </div>
</div>

<script>
    let site_url = document.getElementById('reports-section').getAttribute('data-site'),
        page = 1;

    const API = `${site_url}/wp-json/custom/v1`;

    /**
     * @mountResults
     */
    function mountResults(__logs, reset){
        let resultsDOM = document.querySelector('#results'),
            expiredDOM = document.querySelector('#expired'),
            expiredDownload = document.querySelector('#expired-download')

        if(reset){
            resultsDOM.innerHTML = '';
        }

        __logs.data.forEach((log, index)=>{
            resultsDOM.innerHTML += `
            <tr valign="top" class="${ ((index + 1) % 2 == 0) ? 'alternate' : '' }">
                <td class="manage-column column-columnname" scoape="col">${ (!log.user) ? log.user_email : log.user.data.user_nicename }</td>
                <td class="manage-column column-columnname" scope="col">${log.user_email}</td>
                <td class="manage-column column-columnname" scope="col">${log.course}</td>
                <td class="manage-column column-columnname" scope="col">${log.state}</td>
                <td class="manage-column column-columnname" scope="col">${log.date_at}</td>
                <td class="manage-column column-columnname" scope="col">${log.date_end}</td>
                <td class="manage-column column-columnname" scope="col">${log.last_date}</td>
            </tr>
            `
        })

        if(__logs.expired > 0) {
            expiredDOM.innerHTML = __logs.expired
        }else{
            expiredDownload.setAttribute('href', '#');
        }

        if(__logs.data.length == 0 || __logs.data.length < 25){
            document.querySelector('#load-more').classList.add('hide')
        }
    }

    /**
     * @getResults()
     * @searchUserResult()
     * @loadMore
     */
    function getResults(__page){
        fetch(`${API}/courses/enrollments?page=${__page}`)
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    return res.json()
                }else{
                    throw res
                }
            })
            .then(enrollments => {
                mountResults(enrollments);
            })
            .catch(err => {
                document.querySelector('#load-more').classList.add('hide')
                throw err;       
            })        
    }

    function searchUserResult(){
        event.preventDefault();

        let user = document.querySelector('#user');

        fetch(`${API}/courses/enrollments?user=${user.value}`)
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    return res.json()
                }else{
                    throw res
                }
            })
            .then(enrollments => {
                mountResults(enrollments, true);
            })
            .catch(err => {
                throw err;       
            })        
    }

    function loadMore(){
        getResults(page + 1); page ++;
    }

    /**
     * Main
     */
    getResults(page);

    /**
     * -----------------------------------------------
     * DOM
     * -----------------------------------------------
     */
    let search = document.querySelector('#search'),
        download = document.querySelector('#download')

    search.onclick = ()=>{
        event.preventDefault(); 
        searchUserResult();
    }
</script>
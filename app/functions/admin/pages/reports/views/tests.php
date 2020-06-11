<div 
    id="reports-section"
    class="wrap" 
    data-site="<?php echo get_site_url(); ?>">
    <h2 class="mb-1">Cuestionarios resueltos</h2>
    <div class="mb-1">
    </div>
    <div class="d-flex mb-1">
        <input class="mr-1" type="text" placeholder="Usuario (email)" id="user">
        <button id="search" class="button button-primary">Buscar</button>
    </div>
    <table class="widefat fixed mb-1" cellspacing="0">
        <thead>
            <tr>
                <th id="columnname" class="manage-column column-columnname" scope="col">Usuario</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Email</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Cuestionarios</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Respuestas correctas</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Respuestas incorrectas</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Ultimo tema</th>
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
        let resultsDOM = document.querySelector('#results')

        if(reset){
            resultsDOM.innerHTML = '';
        }

        __logs.forEach((log, index)=>{
            let topic = (log.last_course) ? `${log.last_course} / ${log.last_unity} / ${log.last_topic}` : '';

            resultsDOM.innerHTML += `
            <tr valign="top" class="${ ((index + 1) % 2 == 0) ? 'alternate' : '' }">
                <td class="manage-column column-columnname" scoape="col">${ (!log.user) ? log.user_email : log.user.data.user_nicename }</td>
                <td class="manage-column column-columnname" scope="col">${log.user_email}</td>
                <td class="manage-column column-columnname" scope="col">${log.test_count}</td>
                <td class="manage-column column-columnname" scope="col">${log.right_answers}</td>
                <td class="manage-column column-columnname" scope="col">${log.wrong_answers}</td>
                <td class="manage-column column-columnname" scope="col">${topic}</td>
                <td class="manage-column column-columnname" scope="col">${(log.last_date) ? log.last_date : ''}</td>
            </tr>
            `
        })

        if(__logs.length == 0 || __logs.length < 5){
            document.querySelector('#load-more').classList.add('hide')
        }
    }

    /**
     * @getResults()
     * @searchUserResult()
     * @loadMore
     */
    function getResults(__page){
        fetch(`${API}/topics/test/logs?page=${__page}`)
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    return res.json()
                }else{
                    throw res
                }
            })
            .then(test_logs => {
                mountResults(test_logs);
            })
            .catch(err => {
                throw err;       
            })        
    }

    function searchUserResult(){
        event.preventDefault();

        let user = document.querySelector('#user');

        fetch(`${API}/topics/test/logs?user=${user.value}`)
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    return res.json()
                }else{
                    throw res
                }
            })
            .then(test_logs => {
                mountResults(test_logs, true);
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
    let search = document.querySelector('#search');

    search.onclick = ()=>{
        event.preventDefault(); 
        searchUserResult();
    }
</script>
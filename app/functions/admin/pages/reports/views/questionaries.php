<div 
    id="reports-section"
    class="wrap" 
    data-site="<?php echo get_site_url(); ?>">
    <h2 class="mb-1">Cuestionarios respondidos</h2>
    <div class="mb-1">
    </div>
    <div class="d-flex mb-1">
        <input class="mr-1" type="text" placeholder="Usuario (email)" id="user">
        <button id="search" class="button button-primary mr-1">Buscar</button>
        <a  
            href="<?php echo get_site_url() . '/wp-json/custom/v1/behaviour/questionaries/download' ?>" 
            download 
            class="button button-success">
            Descargar todo (.xls)
        </a>        
    </div>
    <table class="widefat fixed mb-1" cellspacing="0">
        <thead>
            <tr>
                <th id="columnname" class="manage-column column-columnname" scope="col">Temporada</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Usuario</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Email</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Resultado</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Fecha</th>
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
    function mountResults(__registers, reset){
        let resultsDOM = document.querySelector('#results')

        if(reset){
            resultsDOM.innerHTML = '';
        }

        __registers.forEach((reg, index)=>{
            let result = '';

            reg.result.forEach((q, index) => {
                result += `
                    <tr valign="top" class="${ ((index + 1) % 2 == 0) ? 'alternate' : '' }">
                        <td class="manage-column column-columnname" scoape="col">${q.key}</td>
                        <td class="manage-column column-columnname" scope="col">${q.title}</td>
                        <td class="manage-column column-columnname" scope="col">${q.value}</td>
                    </tr>                
                `                
            })

            resultsDOM.innerHTML += `
            <tr valign="top" class="${ ((index + 1) % 2 == 0) ? 'alternate' : '' }">
                <td class="manage-column column-columnname" scoape="col">${ reg.season }</td>
                <td class="manage-column column-columnname" scoape="col">${ reg.user.data.user_nicename }</td>
                <td class="manage-column column-columnname" scope="col">${reg.user_email}</td>
                <td class="manage-column column-columnname" scope="col">
                    <button class='button-primary' onclick="showResult(${reg.id})">Ver resultado</button>
                </td>
                <td class="manage-column column-columnname" scope="col">${reg.date_at}</td>
            </tr>
            <tr id="result-${reg.id}" valign="top" class="result hide">
                <td colspan="4">
                    <table class="widefat fixed mb-1" cellspacing="0">
                        <thead>
                            <tr>
                                <th id="columnname" class="manage-column column-columnname" scope="col">Clave</th>
                                <th id="columnname" class="manage-column column-columnname" scope="col">Pregunta</th>
                                <th id="columnname" class="manage-column column-columnname" scope="col">Respuesta</th>
                            </tr>
                        </thead>
                        <tbody>${result}</tbody>
                    </table>
                </td>               
            </tr>
            `
        })

        if(__registers.length == 0 || __registers.length < 25){
            document.querySelector('#load-more').classList.add('hide')
        }
    }

    /**
     * @getResults()
     * @searchUserResult()
     * @showResult()
     * @loadMore()
     */
    function getResults(__page){
        fetch(`${API}/behaviour/questionaries?page=${__page}`)
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    return res.json()
                }else{
                    throw res
                }
            })
            .then(questionaries => {
                mountResults(questionaries);
            })
            .catch(err => {
                throw err;       
            })        
    }

    function searchUserResult(){
        event.preventDefault();

        let user = document.querySelector('#user');

        fetch(`${API}/behaviour/questionaries?user=${user.value}`)
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    return res.json()
                }else{
                    throw res
                }
            })
            .then(questionaries => {
                mountResults(questionaries, true);
            })
            .catch(err => {
                throw err;       
            })        
    }

    function showResult(email){
        let result = document.querySelector(`#result-${email}`)

        console.log(result)

        if(result.classList.contains('hide')){            
            result.classList.remove('hide')
        }else{
            result.classList.add('hide')
        }
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

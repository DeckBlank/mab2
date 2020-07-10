<div 
    id="reports-section"
    class="wrap" 
    data-site="<?php echo get_site_url(); ?>">
    <h2 class="mb-1">General</h2>
    <div class="mb-1">
    </div>
    <div class="d-flex mb-1">
        <input class="mr-1" type="text" placeholder="Usuario (email)" id="user">
        <button id="search" class="button button-primary mr-1">Buscar</button>
        <a  
            href="<?php echo get_site_url() . '/wp-json/custom/v1/courses/user/logs/download' ?>" 
            download 
            class="button button-success">
            Descargar todo (.xls)
        </a>
    </div>
    <div class="d-flex mb-1">
        <h3 class="mb-1">Descargar usuarios:</h3>
        <a  
            href="<?php echo get_site_url() . '/wp-json/custom/v1/users/download?role=student' ?>" 
            download 
            class="button button-success">
            Estudiantes (.xls)
        </a>
        <a  
            href="<?php echo get_site_url() . '/wp-json/custom/v1/users/download?role=teacher' ?>" 
            download 
            class="button button-success">
            Profesores (.xls)
        </a>
        <a  
            href="<?php echo get_site_url() . '/wp-json/custom/v1/users/download?role=tutor' ?>" 
            download 
            class="button button-success">
            Tutores/padres (.xls)
        </a>
    </div>
    <!-- <p>Total de accesos: <strong id="total_access">123</strong> veces</p> -->
    <table class="widefat fixed mb-1" cellspacing="0">
        <thead>
            <tr>
                <th id="columnname" class="manage-column column-columnname" scope="col">Usuario</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Email</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Curso</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Videos vistos</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Descargas</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Cuestionarios</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Respuestas</th>
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
            resultsDOM.innerHTML += `
            <tr valign="top" class="${ ((index + 1) % 2 == 0) ? 'alternate' : '' }">
                <td class="manage-column column-columnname" scoape="col">${ (!log.user) ? log.user_email : log.user.data.user_nicename }</td>
                <td class="manage-column column-columnname" scope="col">${log.user_email}</td>
                <td class="manage-column column-columnname" scope="col">${log.course}</td>
                <td class="manage-column column-columnname" scope="col">${log.topic_views}</td>
                <td class="manage-column column-columnname" scope="col">${log.material_downloads}</td>
                <td class="manage-column column-columnname" scope="col">${log.test_count}</td>
                <td class="manage-column column-columnname" scope="col">
                    Correctas: ${log.right_answers}<br>
                    Incorrectas: ${log.wrong_answers}
                </td>
                <td class="manage-column column-columnname" scope="col">${(log.last_date) ? log.last_date : ''}</td>
            </tr>
            `
        })

        if(__logs.length == 0 || __logs.length < 25){
            document.querySelector('#load-more').classList.add('hide')
        }
    }

    /**
     * @getResults()
     * @searchUserResult()
     * @loadMore
     */
    function getResults(__page){
        fetch(`${API}/courses/user/logs?page=${__page}`)
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    return res.json()
                }else{
                    throw res
                }
            })
            .then(course_user_logs => {
                mountResults(course_user_logs);
            })
            .catch(err => {
                document.querySelector('#load-more').classList.add('hide')
                throw err;       
            })        
    }

    function searchUserResult(){
        event.preventDefault();

        let user = document.querySelector('#user');

        fetch(`${API}/courses/user/logs?user=${user.value}`)
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    return res.json()
                }else{
                    throw res
                }
            })
            .then(course_user_logs => {
                mountResults(course_user_logs, true);
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
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
    </div>
    <div class="d-flex mb-1">
        <h3 class="mb-1">Acciones</h3>
        <div class="d-flex">
            <button id="save" class="button button-primary mr-1">Matricular</button>
            <button id="delete" class="button button-trash">Desmatricular</button>
        </div>
    </div>
    <!-- <p>Total de accesos: <strong id="total_access">123</strong> veces</p> -->
    <table class="widefat mb-1" cellspacing="0">
        <thead>
            <tr>
                <th id="columnname" class="manage-column column-columnname" scope="col"><input id="mark-all" type="checkbox" class="ml-0"></input></th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Curso</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Estado</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Inscripcion (fecha)</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Vencimiento (fecha)</th>
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
        page = 1,
        enrollments = [];

    const API = `${site_url}/wp-json/custom/v1`;

    /**
     * @mountResults
     * @markAll
     */
    function mountResults(__enrollments, reset){
        let resultsDOM = document.querySelector('#results')

        if(reset){
            resultsDOM.innerHTML = '';
        }

        __enrollments.forEach((enrollment, index)=>{
            resultsDOM.innerHTML += `
            <tr valign="top" class="${ ((index + 1) % 2 == 0) ? 'alternate' : '' }">
                <td class="manage-column column-columnname" scoape="col"><input data-course="${enrollment.course_id}" class="cb-course" type="checkbox"></input></td>
                <td class="manage-column column-columnname" scoape="col">
                    ${enrollment.course} / 
                    ${(enrollment.categories[1]) ? enrollment.categories[1].name : ''} /
                    ${(enrollment.categories[0]) ? enrollment.categories[0].name : ''} 
                </td>
                <td class="manage-column column-columnname" scope="col">${enrollment.state}</td>
                <td class="manage-column column-columnname" scope="col">${enrollment.date_at}</td>
                <td class="manage-column column-columnname" scope="col">${enrollment.date_end}</td>
            </tr>
            `
        })

        if(__enrollments.length == 0 || __enrollments.length < 25){
            document.querySelector('#load-more').classList.add('hide')
        }
    }

    function markAll(){
        let cbCourses = document.querySelectorAll('.cb-course');

        cbCourses.forEach((cb)=>{
            cb.checked = (mark_all.checked) ? true : false;
        })
    }

    /**
     * @getResults()
     * @saveEnrollments()
     * @deleteEnrollments()
     * @searchUserResult()
     * @loadMore
     */
    function getResults(__page){
        let user = document.querySelector('#user');

        fetch(`${API}/user/enrollments/?user=${user.value}&page=${__page}`)
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    return res.json()
                }else{
                    throw res
                }
            })
            .then(__enrollments => {
                enrollments = __enrollments; mountResults(__enrollments);
            })
            .catch(err => {
                document.querySelector('#load-more').classList.add('hide')     
            })
    }

    function saveEnrollments(){
        let user = document.querySelector('#user'),
            form_data = new FormData();

        form_data.append('user', user.value)
        form_data.append('courses', JSON.stringify([...document.querySelectorAll('.cb-course:checked')].map(course => course.getAttribute('data-course'))) )

        fetch(`${API}/user/enrollments`, {
                method: 'POST',
                body: form_data
            })
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    alert('Matriculas registradas')
                }else{
                    throw res
                }
            })
            .catch(err => {
                throw err;
            })
    }

    function deleteEnrollments(){
        let user = document.querySelector('#user')

        fetch(`${API}/user/enrollments`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user: user.value,
                    courses: [...document.querySelectorAll('.cb-course:checked')].map(course => course.getAttribute('data-course'))
                })
            })
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    alert('Matriculas eliminadas')
                }else{
                    throw res
                }
            })
            .catch(err => {
                throw err;
            })
    }

    function searchUserResult(){
        event.preventDefault();

        let user = document.querySelector('#user');

        fetch(`${API}/user/enrollments/?user=${user.value}&page=1`)
            .then(res => {
                if (res.status >= 200 && res.status < 300) {
                    return res.json()
                }else{
                    throw res
                }
            })
            .then(__enrollments => {
                enrollments = __enrollments; mountResults(__enrollments, true);
            })
            .catch(err => {
                alert('El usuario no existe');

                throw err;       
            })
    }

    function loadMore(){
        getResults(page + 1); page ++;
    }

    /**
     * -----------------------------------------------
     * DOM
     * -----------------------------------------------
     */
    let search = document.querySelector('#search'),
        download = document.querySelector('#download'),
        mark_all = document.querySelector('#mark-all'),
        save = document.querySelector('#save'),
        __delete = document.querySelector('#delete')

    search.onclick = ()=>{
        event.preventDefault(); 
        searchUserResult(page + 1);
    }

    mark_all.onchange = ()=>{
        event.preventDefault(); 
        markAll();
    }

    save.onclick = ()=>{
        event.preventDefault(); 
        saveEnrollments();
    }

    __delete.onclick = ()=>{
        event.preventDefault(); 
        deleteEnrollments();
    }
</script>
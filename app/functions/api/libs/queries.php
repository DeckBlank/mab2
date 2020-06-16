<?php
function __getOffset($page){
    $offset = -1;

    if ( !isset($page) || $page == 1) {
        $offset = 0;
    } else {
        $offset = ($page - 1) * 25;
    }

    return $offset;
}

function __getLimit(){
    return 25;
}
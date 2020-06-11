<?php
function __getLimit($page){
    $limit = -1;

    if ( !isset($page) || $page == 1) {
        $limit = 5;
    } else {
        $limit = (($page - 1) * 5 ) . "," . (($page - 1) * 5 + 5 );
    }

    return $limit;
}
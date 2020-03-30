<?php

function __getLikes($request){
    return get_post_meta($request['post_id'], 'post_likes_average');
}

function __updateLikes($request, $table, $likes_count){
    $new_likes_average = bcdiv(($request['now_average'] * $likes_count[0] + $request['level']), ++$likes_count[0], 1);

    $new_likes_average_saved = DBConnection::getConnection()->query("
        INSERT INTO wp_". $table ."_scores(date_at,user,". $table ."_id,score) VALUES(
            '". date("Y-m-d") ."',
            '". $request['user'] ."',
            '". $request['post_id'] ."',
            '". $request['level'] ."'
        )   
    ");

    if ($new_likes_average_saved) {
        update_post_meta($request['post_id'], 'post_likes_average', $new_likes_average);
        update_post_meta($request['post_id'], 'post_likes_count', $likes_count[0]);

        return $new_likes_average;
    } else {
        throw Exception('No score saved');
    }
    
}

function __checkoutUserLike($request, $table){
    return DBConnection::getConnection()->query("
            SELECT
                * 
            FROM 
                wp_". $table ."_scores 
            WHERE 
                user='". $request['user'] ."' and ". $table ."_id=". $request['post_id'] ."
        ");    
}



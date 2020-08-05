<?php

function __getComments($request){
    $comments = get_comments([
        "post_id" => $request['post_id'],
        "parent" => 0,
        "number" => 5,
        "paged" => $request['paged']
    ]);

    if ($comments) {
        $comments_answers = [];

        foreach($comments as $comment){
            array_push($comments_answers, (object)[
                "id" => $comment->comment_ID,
                "author" => $comment->comment_author,
                "date" => $comment->comment_date,
                "content" => $comment->comment_content,
                "answers" => get_comments([
                    "parent" => $comment->comment_ID,
                    "number" => 5,
                    "paged" => $request['paged']                         
                ])
            ]);
        }
        
        return (object)[
            "number" => intval( get_post_meta($request['post_id'], 'post_comments_count', true)[0] ),
            "list" => $comments_answers
        ];
    } else {
        delete_post_meta($request['post_id'], 'post_comments_count');
        add_post_meta($request['post_id'], 'post_comments_count', 0);

        throw new Exception("No comments");
    }   
}

function __addComment($request){
    $result = wp_insert_comment([
        "comment_author" => $request['user'],
        "comment_author_email" => $request['user_email'],
        "comment_content" => $request['content'],
        "comment_post_ID" => $request['post_id']
    ]);

    if ( $result ) {
        $comments_count = get_post_meta($request['post_id'], 'post_comments_count', true)[0];
        update_post_meta($request['post_id'], 'post_comments_count', ++$comments_count);

        return $result;
    } else {
        throw new Exception("No comment added");
    }    
}

function __addAnswer($request){
    $result = wp_insert_comment([
        "comment_author" => $request['user'],
        "comment_author_email" => $request['user_email'],
        "comment_content" => $request['content'],
        "comment_parent" => $request['comment_id']
    ]);

    if ( $result ) {
        $comments_count = get_post_meta($request['post_id'], 'post_comments_count', true)[0];
        update_post_meta($request['post_id'], 'post_comments_count', ++$comments_count);
        
        return $result;
    } else {
        throw new Exception("No answer added");
    }
}
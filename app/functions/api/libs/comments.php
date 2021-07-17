<?php

function __getComments($request){
    $comments = get_comments([
        "post_id" => $request['post_id'],
        "parent" => 0,
        "number" => 5,
        "paged" => $request['paged']
    ]);

    if ($comments) {
        $comments_answers   = [];
        $userId             = $request['user_id'];

        foreach($comments as $comment){
            $user = get_user_by('email', $comment->comment_author_email);

            $answers = get_comments([
                "parent" => $comment->comment_ID,
                "number" => 5,
                "paged" => $request['paged']
            ]);

            foreach($answers as $answer) {
                $userAnswer = get_user_by('email', $answer->comment_author_email);

                $answer->authorAvatar = get_field('avatar', 'user_' . $userAnswer->ID);
                $answer->authorField = get_field('job', 'user_' . $userAnswer->ID);
            }

            $topicCommentSticky = get_post_meta($request['post_id'], 'comment_sticky');
            $isSticky           = ($topicCommentSticky) ? in_array($comment->comment_ID, $topicCommentSticky) : false;

            $userLikesComment   = get_comment_meta($comment->comment_ID, 'user_likes');
            $isLikedUser        = ($userLikesComment) ? in_array($userId, $userLikesComment) : false;

            array_push($comments_answers, (object)[
                "id"            => $comment->comment_ID,
                "author"        => $comment->comment_author,
                "sticky"        => $isSticky,
                "authorAvatar"  => get_field('avatar', 'user_' . $user->ID),
                "authorField"   => get_field('job', 'user_' . $user->ID),
                "date"          => $comment->comment_date,
                "content"       => $comment->comment_content,
                "likes"         => $comment->comment_karma,
                "likedUser"     => $isLikedUser,
                "answers"       => $answers
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

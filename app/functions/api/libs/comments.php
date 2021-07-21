<?php

function __getComments($request){
    $comments = get_comments([
        "post_id" => $request['post_id'],
        "parent" => 0,
        "number" => 5,
        "paged" => $request['paged']
    ]);

    if ($comments) {
        $commentSticky  = null;
        $commentsArray  = [];
        $userId         = $request['user_id'];
        $courseId       = $request['course_id'];

        $topicCommentSticky = get_post_meta($request['post_id'], 'comment_sticky');
        $commentSticky      = ($topicCommentSticky) ? __sanitizeComment( get_comment($topicCommentSticky[0]), $userId, $request) : false;

        foreach($comments as $comment){
            $isSticky = ($topicCommentSticky) ? in_array($comment->comment_ID, $topicCommentSticky) : false;

            $commentObject = __sanitizeComment($comment, $userId, $request);

            if (!$isSticky) array_push($commentsArray, $commentObject);
        }

        $topicCommentSticky = get_post_meta($request['post_id'], 'comment_sticky');

        return (object)[
            "number"        => intval( get_post_meta($request['post_id'], 'post_comments_count', true)[0] ),
            "sticky"        => $commentSticky,
            "list"          => $commentsArray,

            'is_user_owner' => __isUserOwnerOnCourse($userId, $courseId)
        ];
    } else {
        throw new Exception("No comments");
    }   
}

function __addComment($request){
    $result = wp_insert_comment([
        "comment_author"        => $request['user'],
        "comment_author_email"  => $request['user_email'],
        "comment_content"       => $request['content'],
        "comment_post_ID"       => $request['post_id'],
        "comment_meta"          => [
            'attachment' => $request['attachment']
        ]
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
        "comment_author"        => $request['user'],
        "comment_author_email"  => $request['user_email'],
        "comment_content"       => $request['content'],
        "comment_parent"        => $request['comment_id'],
        "comment_meta"          => [
            'attachment' => $request['attachment']
        ]
    ]);

    if ( $result ) {
        $comments_count = get_post_meta($request['post_id'], 'post_comments_count', true)[0];
        update_post_meta($request['post_id'], 'post_comments_count', ++$comments_count);
        
        return $result;
    } else {
        throw new Exception("No answer added");
    }
}

function __getAttachments($commentId) {
    $attachments = get_comment_meta($commentId, 'attachment');

    if (count($attachments)) {
        $attachments        = ($attachments[0]) ? explode(',', $attachments[0]) : [];
        $attachmentsArray   = [];

        foreach ($attachments as $attach) {
            $attachment = Attachment::find($attach);

            if ($attachment) {
                $attachment->src = $attachment->path;

                array_push($attachmentsArray, $attachment);
            }
        }

        return $attachmentsArray;
    } else {
        return [];
    }
}

function __sanitizeComment($comment, $userId, $request) {
    $userComment        = get_user_by('email', $comment->comment_author_email);
    $userCommentAvatar  = get_field('avatar', 'user_' . $userComment->ID);

    $answers = get_comments([
        "parent" => $comment->comment_ID,
        "number" => 5,
        "paged" => ( empty($request['paged']) ) ? 1 : $request['paged']
    ]);

    foreach($answers as $answer) {
        $userAnswer = get_user_by('email', $answer->comment_author_email);
        $userAvatar = get_field('avatar', 'user_' . $userAnswer->ID);

        $answer->authorAvatar   = $userAvatar ? $userAvatar['url'] : '';
        $answer->authorField    = get_field('job', 'user_' . $userAnswer->ID);
        $answer->attachments    = __getAttachments($answer->comment_ID);
    }

    $userLikesComment   = get_comment_meta($comment->comment_ID, 'user_likes');
    $isLikedUser        = ($userLikesComment) ? in_array($userId, $userLikesComment) : false;

    return (object)[
        "id"            => $comment->comment_ID,
        "author"        => $comment->comment_author,
        "authorAvatar"  => $userCommentAvatar ? $userCommentAvatar['url'] : '',
        "authorField"   => get_field('job', 'user_' . $userComment->ID),
        "date"          => $comment->comment_date,
        "content"       => $comment->comment_content,
        "likes"         => $comment->comment_karma,
        "likedUser"     => $isLikedUser,
        "attachments"   => __getAttachments($comment->comment_ID),
        "answers"       => $answers
    ];
}

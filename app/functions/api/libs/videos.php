<?php

function __get_sanitize_videos($videos, $level = "advanced"){
    $sanitize_videos = [];

    foreach($videos as $video){

        if($level == "advanced"){
            array_push($sanitize_videos, (object)[
                "title" => $video->title,
                "link" => $video->link,
                "slug" => $video->slug,
                "thumbnail" => $video->thumbnail,
                "author" => (object)[
                    "first_name" =>  $video->author->first_name,
                    "last_name" =>  $video->author->last_name,
                    "avatar" => get_field('picture', 'user_'. $video->author->ID)
                ]
            ]);
        }else if($level == "simple"){
            array_push($sanitize_videos, (object)[
                "title" => $video->title,
                "link" => $video->link,
                "slug" => $video->slug,
                "thumbnail" => $video->thumbnail
            ]);
        }
    }

    return $sanitize_videos;
}

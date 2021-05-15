<?php

use Illuminate\Database\Eloquent\Model;

class UserTopic extends Model {
    protected $table    = 'wp_user_topic';
    protected $fillable = [
        'user_email',
        'topic_id',
        'video_viewed',
        'summary_downloaded',
        'map_downloaded',
        'worksheet_downloaded',
        'solutions_downloaded',
        'solutions_downloaded'
    ];
}

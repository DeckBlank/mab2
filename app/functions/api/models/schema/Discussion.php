<?php

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model {
    protected $table    = 'wp_discussions';
    protected $fillable = [
        'user_id',
        'course_id',
        'topic_id',
        'subject',
        'total_comments'
    ];
}

<?php

use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model {
    protected $table    = 'wp_user_course';
    protected $fillable = [
        'user_email',
        'course_id',
        'topic_views',
        'material_downloads',
        'test_count',
        'right_answers',
        'wrong_answers',
        'last_date'
    ];
}

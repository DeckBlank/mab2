<?php

use Illuminate\Database\Eloquent\Model;

class UserCourseEnrollment extends Model {
    protected $table    = 'wp_user_course_enrollment';
    protected $fillable = [
        'user_email',
        'course_id',
        'state',
        'date_at',
        'date_end',
        'last_date'
    ];
}

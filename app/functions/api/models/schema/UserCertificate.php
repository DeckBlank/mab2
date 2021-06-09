<?php

use Illuminate\Database\Eloquent\Model;

class UserCertificate extends Model {
    protected $table    = 'wp_user_certificates';
    protected $fillable = [
        'id',
        'user_id',
        'course_id',
        'notification',
        'created_at',
        'updated_at'
    ];
}

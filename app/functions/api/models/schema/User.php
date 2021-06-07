<?php

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table    = (ENV['DB_PREFIX']) ? ENV['DB_PREFIX'] . '_users' : 'wp_users';
    protected $fillable = [
        'ID',
        'user_nicename'
    ];
}

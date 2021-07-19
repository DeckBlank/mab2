<?php

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model {
    protected $table    = 'wp_attachments';
    protected $fillable = [
        'path',
        'filename'
    ];
}

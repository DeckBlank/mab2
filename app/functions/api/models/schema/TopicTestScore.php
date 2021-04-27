<?php

use Illuminate\Database\Eloquent\Model;

class TopicTestScore extends Model {
    protected $table    = 'wp_topic_test_scores';
    protected $fillable = [
        'user',
        'course_id',
        'topic_id',
        'score',
        'date_at'
    ];
}

<?php

use Timber\Timber;

function __getMetaCourse($courseId, $userEmail, $meta) {
    switch ($meta) {
        case 'likes':
            $likes      = 0;
            $unities    = get_field('unities', $courseId);

            if ( $unities && count($unities) ) {
                foreach($unities as $unity){
                    foreach($unity['topics'] as $topic) {
                        if ($topic['topic']) {
                            $likes += floatval( get_post_meta($topic['topic']->ID, 'post_likes_count') );
                        }
                    }
                }
            }

            return $likes;

            break;

        case 'progress':
            $tests = TopicTestScore::where(['user' => $userEmail, 'course_id' => $courseId])
                ->get();

            $topics     = 0;
            $unities    = get_field('unities', $courseId);

            if ( $unities && count($unities) ) {
                foreach($unities as $unity) {
                    $topics += count($unity['topics']);
                }
            }

            return ($topics) ? bcdiv( (count($tests) * 100), $topics, 2 ) : 0;

            break;
    }
}

function __getLastTopic($courseId, $userEmail, $userID) {
    $unities    = get_field('unities', $courseId);
    $topics     = [];
    $unityIndex = 1;
    $topicIndex = 0;
    $lastClass  = '';
    $firstClass = '';

    if ( $unities && count($unities) ) {
        foreach($unities as $unity){
            foreach($unity['topics'] as $topic) {
                array_push(
                    $topics,
                    [
                        'unity' => $unityIndex,
                        'title' => ($topic['topic']) ? $topic['topic']->post_title : '',
                        'id'    => ($topic['topic']) ? $topic['topic']->ID : 0,
                        'link'  => ($topic['topic']) ? get_the_permalink($topic['topic']->ID) : '',
                    ]
                );
            }
        }

        $unityIndex++;
    }

    if ( count($topics) ) {
        $firstClass = $topics[0];
        $topics     = array_reverse($topics);

        foreach($topics as $topic) {
            $userTopic = UserTopic::where(['topic_id' => $topic['id']])
                ->first();

            if ($userTopic && $userTopic->video_viewed) {
                $lastClass = ($topics[$topicIndex++]) ? $topics[$topicIndex++] : $topic;
            }

            if ($lastClass) break;

            $topicIndex++;
        }

        $lastClass = ($lastClass) ? $lastClass : $firstClass;

        if ($lastClass) {
            $topic = Timber::get_post([
                'post_type' => 'topic',
                'p'         => $lastClass['id']
            ]);

            $userSector = get_field('school_type', 'user_' . $userID);

            $lastClass['link'] = sprintf(
                '%s?course_id=%s&course_name=%s&course_slug=%s&unity=%s&sector=%s',
                $lastClass['link'],
                $courseId,
                $topic->title,
                $topic->slug,
                $lastClass['unity'],
                ($userSector) ? $userSector : 'publico'
            );

            return $lastClass;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

function __sanitizeCourse($courseId, $userEmail, $userID, $type = 'enrolled') {
    $course = Timber::get_post([
        'post_type' => 'course',
        'p'         => $courseId
    ]);

    if ($course) {
        $mabCategory = array_filter($course->terms, function($cat){ return $cat->taxonomy == 'tax-mab-course' && !$cat->parent; });
        $mabCategory = count($mabCategory) ? array_values($mabCategory)[0] : false;

        $teacher = get_field('teacher', $course->ID);

        $courseObject = [
            'id'            => $course->ID,
            'name'          => $course->title,
            'thumbnail'     => ($course->thumbnail) ? $course->thumbnail->src : false,
            'teacher'       => ($teacher) ? sprintf('%s %s', $teacher['user_firstname'], $teacher['user_lastname']) : '',
            'description'   => get_the_excerpt($course->ID),
            'likes'         => __getMetaCourse($courseId, $userEmail, 'likes'),
            'color'         => ($mabCategory) ? get_field('color', 'category_' . $mabCategory->term_id) : 'primary',
            'link'          => get_the_permalink($course->ID)
        ];

        if ($type == 'recommend' || $type == 'general') {
            $sell = get_field('sell', 'options');
            $priceSettings = get_field('price_settings', $courseId);

            $courseObject = array_merge($courseObject, [
                'price' => ($priceSettings == 'global') ? floatval( $sell['course_price'] ) : floatval( get_field('price', $courseId) )
            ]);
        } else {
            $courseObject = array_merge($courseObject, [
                'last_class'    => __getLastTopic($courseId, $userEmail, $userID),
                'progress'      => __getMetaCourse($courseId, $userEmail, 'progress'),
            ]);
        }

        return $courseObject;
    } else {
        return false;
    }
}

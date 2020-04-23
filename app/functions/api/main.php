<?php

/**
 * Settings
 */
date_default_timezone_set('America/Lima');

/**
 * Requires
 */
include_once __DIR__ . "/config/requires.php";

/**
 * Routes
 */
//1. User ----------------------------------//
add_action( 'rest_api_init', function () {
    $userController = new UserController();

    register_rest_route( 'custom/v1', '/user/auth', array(
        'methods' => 'GET',
        'callback' => array($userController,'auth')
    ));

    register_rest_route( 'custom/v1', '/user', array(
        'methods' => 'POST',
        'callback' => array($userController,'createUser')
    ));

    register_rest_route( 'custom/v1', '/user/test', array(
        'methods' => 'GET',
        'callback' => array($userController,'getTest')
    ));
});

//2. Video ----------------------------------//
add_action( 'rest_api_init', function () {
    $videoController = new VideoController();

    register_rest_route( 'custom/v1', '/videos', array(
        'methods' => 'GET',
        'callback' => array($videoController,'getAll'),
    ));

    register_rest_route( 'custom/v1', '/video/(?P<post_id>\d+)/likes', array(
        'methods' => 'GET',
        'callback' => array($videoController,'getLikes'),
    ));    

    register_rest_route( 'custom/v1', '/video/(?P<post_id>\d+)/likes', array(
        'methods' => 'PUT',
        'callback' => array($videoController,'updateLikes'),
    ));

    register_rest_route( 'custom/v1', '/video/(?P<post_id>\d+)/likes/checkout', array(
        'methods' => 'GET',
        'callback' => array($videoController,'checkoutUserLike'),
    ));

    register_rest_route( 'custom/v1', '/video/(?P<post_id>\d+)/comments', array(
        'methods' => 'GET',
        'callback' => array($videoController,'getComments'),
    ));

    register_rest_route( 'custom/v1', '/video/(?P<post_id>\d+)/comment', array(
        'methods' => 'POST',
        'callback' => array($videoController,'addComment'),
    ));

    register_rest_route( 'custom/v1', '/video/(?P<post_id>\d+)/comment/(?P<comment_id>\d+)/answer', array(
        'methods' => 'POST',
        'callback' => array($videoController,'addAnswer'),
    ));     
});

//3. Course ----------------------------------//
add_action( 'rest_api_init', function () {
    $courseController = new CourseController();

    register_rest_route( 'custom/v1', '/courses', array(
        'methods' => 'GET',
        'callback' => array($courseController,'getAll'),
    ));

    register_rest_route( 'custom/v1', '/courses/progress', array(
        'methods' => 'GET',
        'callback' => array($courseController,'getProgress'),
    ));

    register_rest_route( 'custom/v1', '/course/(?P<course_id>\d+)/registration/checkout', array(
        'methods' => 'GET',
        'callback' => array($courseController,'registrationCheckout'),
    ));

    register_rest_route( 'custom/v1', '/courses/expired_registrations', array(
        'methods' => 'GET',
        'callback' => array($courseController,'getExpiredRegistrations'),
    ));

    register_rest_route( 'custom/v1', '/courses/expired_registrations/download', array(
        'methods' => 'GET',
        'callback' => array($courseController,'downloadExpiredRegistrations'),
    ));
});

//4. Topic ----------------------------------//
add_action( 'rest_api_init', function () {
    $topicController = new TopicController();

    register_rest_route( 'custom/v1', '/topic/(?P<topic_id>\d+)/questions', array(
        'methods' => 'GET',
        'callback' => array($topicController,'getQuestions'),
    ));

    register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/likes', array(
        'methods' => 'GET',
        'callback' => array($topicController,'getLikes'),
    ));    

    register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/likes', array(
        'methods' => 'PUT',
        'callback' => array($topicController,'updateLikes'),
    ));

    register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/likes/checkout', array(
        'methods' => 'GET',
        'callback' => array($topicController,'checkoutUserLike'),
    ));    

    register_rest_route( 'custom/v1', '/topic/(?P<topic_id>\d+)/test_score', array(
        'methods' => 'GET',
        'callback' => array($topicController,'getTestScore'),
    ));

    register_rest_route( 'custom/v1', '/topic/(?P<topic_id>\d+)/test_score', array(
        'methods' => 'PUT',
        'callback' => array($topicController,'updateTestScore'),
    ));

    register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/comments', array(
        'methods' => 'GET',
        'callback' => array($topicController,'getComments'),
    ));

    register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/comment', array(
        'methods' => 'POST',
        'callback' => array($topicController,'addComment'),
    ));     

    register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/comment/(?P<comment_id>\d+)/answer', array(
        'methods' => 'POST',
        'callback' => array($topicController,'addAnswer'),
    ));     
});

//5. SessionRequest ----------------------------------//
add_action( 'rest_api_init', function () {
    $sessionRequestController = new SessionRequestController();

    register_rest_route( 'custom/v1', '/session_request', array(
        'methods' => 'POST',
        'callback' => array($sessionRequestController,'addSessionRequest'),
    ));    
});

//6. Session ----------------------------------//
add_action( 'rest_api_init', function () {
    $sessionController = new SessionController();

    register_rest_route( 'custom/v1', '/session', array(
        'methods' => 'GET',
        'callback' => array($sessionController,'getSession'),
    ));   

    register_rest_route( 'custom/v1', '/session', array(
        'methods' => 'POST',
        'callback' => array($sessionController,'createSession'),
    ));    
});

//7. Sector ----------------------------------//
add_action( 'rest_api_init', function () {
    $sectorController = new SectorController();

    register_rest_route( 'custom/v1', '/sectors', array(
        'methods' => 'GET',
        'callback' => array($sectorController,'getAll'),
    ));    
});

//8. Test ----------------------------------//
add_action( 'rest_api_init', function () {
    $testController = new TestController();

    register_rest_route( 'custom/v1', '/test', array(
        'methods' => 'GET',
        'callback' => array($testController,'getTest'),
    ));    

    register_rest_route( 'custom/v1', '/test/questions', array(
        'methods' => 'GET',
        'callback' => array($testController,'getQuestions'),
    ));    

    register_rest_route( 'custom/v1', '/test', array(
        'methods' => 'POST',
        'callback' => array($testController,'saveTest'),
    ));    
});

//9. Exercise ----------------------------------//
add_action( 'rest_api_init', function () {
    $exerciseController = new ExerciseController();

    register_rest_route( 'custom/v1', '/exercises', array(
        'methods' => 'GET',
        'callback' => array($exerciseController,'getAll'),
    ));
});

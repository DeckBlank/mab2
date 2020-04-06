<?php

/**
 * Settings
 */
date_default_timezone_set('America/Lima');

/**
 * Libs
 */
include_once __DIR__ . "/config/main.php";
include_once __DIR__ . '/libs/videos.php';
include_once __DIR__ . '/libs/comments.php';
include_once __DIR__ . '/libs/likes.php';

/**
 * Controllers
 */
require_once(__DIR__."/controllers/UserController.php");
require_once(__DIR__."/controllers/VideoController.php");
require_once(__DIR__."/controllers/CourseController.php");
require_once(__DIR__."/controllers/TopicController.php");
require_once(__DIR__."/controllers/SessionRequestController.php");
require_once(__DIR__."/controllers/SessionController.php");
require_once(__DIR__."/controllers/FormController.php");
require_once(__DIR__."/controllers/SectorController.php");
require_once(__DIR__."/controllers/RegistrationController.php");

/**
 * Routes
 */
//1. User ----------------------------------//
add_action( 'rest_api_init', function () {
    $userController = new UserController();

    register_rest_route( 'custom/v1', '/user/auth', array(
        'methods' => 'GET',
        'callback' => array($userController,'auth'),
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
});

//7. Forms ----------------------------------//
add_action( 'rest_api_init', function () {
    $formController = new FormController();

    register_rest_route( 'custom/v1', '/form/tutor', array(
        'methods' => 'POST',
        'callback' => array($formController,'addTutorForm'),
    ));  
    
    register_rest_route( 'custom/v1', '/form/student', array(
        'methods' => 'POST',
        'callback' => array($formController,'addStudentForm'),
    ));
    
    register_rest_route( 'custom/v1', '/form/teacher', array(
        'methods' => 'POST',
        'callback' => array($formController,'addTeacherForm'),
    ));    
});

//8. Sector ----------------------------------//
add_action( 'rest_api_init', function () {
    $sectorController = new SectorController();

    register_rest_route( 'custom/v1', '/sectors', array(
        'methods' => 'GET',
        'callback' => array($sectorController,'getAll'),
    ));    
});

//9. Registration ----------------------------------//
add_action( 'rest_api_init', function () {
    $registrationController = new RegistrationController();

    register_rest_route( 'custom/v1', '/registration', array(
        'methods' => 'GET',
        'callback' => array($registrationController,'getRegistration'),
    ));    
});

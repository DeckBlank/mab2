<?php

/**
 * Settings
 */

require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/database/main.php");

/**
 * Requires
 */
include_once __DIR__ . "/config/requires.php";

/**
 * Routes
 */

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

    register_rest_route( 'custom/v1', '/tests', array(
        'methods' => 'GET',
        'callback' => array($testController,'getTests'),
    ));

    register_rest_route( 'custom/v1', '/tests/download', array(
        'methods' => 'GET',
        'callback' => array($testController,'downloadTests'),
    ));

    register_rest_route( 'custom/v1', '/test/questions', array(
        'methods' => 'GET',
        'callback' => array($testController,'getQuestions'),
    ));    

    register_rest_route( 'custom/v1', '/test', array(
        'methods' => 'POST',
        'callback' => array($testController,'saveTest'),
    ));    

    register_rest_route( 'custom/v1', '/test/behaviour', array(
        'methods' => 'POST',
        'callback' => array($testController,'saveBehaviourTest'),
    ));    

    register_rest_route( 'custom/v1', '/test/behaviour', array(
        'methods' => 'GET',
        'callback' => array($testController,'getBehaviourTest'),
    ));    

    register_rest_route( 'custom/v1', '/tests/behaviour', array(
        'methods' => 'GET',
        'callback' => array($testController,'getBehaviourTests'),
    ));    

    register_rest_route( 'custom/v1', '/tests/behaviour/download', array(
        'methods' => 'GET',
        'callback' => array($testController,'downloadBehaviourTests'),
    ));    
});

//9. Behaviour ----------------------------------//
add_action( 'rest_api_init', function () {
    $behaviourController = new BehaviourController();

    register_rest_route( 'custom/v1', '/behaviour/questionary', array(
        'methods' => 'GET',
        'callback' => array($behaviourController,'getQuestionary'),
    ));

    register_rest_route( 'custom/v1', '/behaviour/questionaries', array(
        'methods' => 'GET',
        'callback' => array($behaviourController,'getQuestionaries'),
    ));

    register_rest_route( 'custom/v1', '/behaviour/questionaries/download', array(
        'methods' => 'GET',
        'callback' => array($behaviourController,'downloadQuestionaries'),
    ));

    register_rest_route( 'custom/v1', '/behaviour/questionary/enable', array(
        'methods' => 'GET',
        'callback' => array($behaviourController,'checkoutQuestionaryEnable'),
    ));

    register_rest_route( 'custom/v1', '/behaviour/questionary', array(
        'methods' => 'POST',
        'callback' => array($behaviourController,'saveQuestionary'),
    ));

    register_rest_route( 'custom/v1', '/behaviour/poll', array(
        'methods' => 'GET',
        'callback' => array($behaviourController,'getPoll'),
    ));

    register_rest_route( 'custom/v1', '/behaviour/polls', array(
        'methods' => 'GET',
        'callback' => array($behaviourController,'getPolls'),
    ));

    register_rest_route( 'custom/v1', '/behaviour/polls/download', array(
        'methods' => 'GET',
        'callback' => array($behaviourController,'downloadPolls'),
    ));

    register_rest_route( 'custom/v1', '/behaviour/poll/enable', array(
        'methods' => 'GET',
        'callback' => array($behaviourController,'checkoutPollEnable'),
    ));    

    register_rest_route( 'custom/v1', '/behaviour/poll', array(
        'methods' => 'POST',
        'callback' => array($behaviourController,'savePoll'),
    ));    
});

//10. Exercise ----------------------------------//
add_action( 'rest_api_init', function () {
    $exerciseController = new ExerciseController();

    register_rest_route( 'custom/v1', '/exercises', array(
        'methods' => 'GET',
        'callback' => array($exerciseController,'getAll'),
    ));   
});

//11. School ----------------------------------//
add_action( 'rest_api_init', function () {
    $schoolController = new SchoolController();

    register_rest_route( 'custom/v1', '/schools', array(
        'methods' => 'GET',
        'callback' => array($schoolController,'getSchoolsBySector'),
    ));
});

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

    register_rest_route( 'custom/v1', '/users', array(
        'methods' => 'GET',
        'callback' => array($userController,'getAll')
    ));

    register_rest_route( 'custom/v1', '/users/download', array(
        'methods' => 'GET',
        'callback' => array($userController,'downloadUsers')
    ));

    register_rest_route( 'custom/v1', '/user/auth', array(
        'methods' => 'GET',
        'callback' => array($userController,'auth')
    ));

    register_rest_route( 'custom/v1', '/user/logout', array(
        'methods' => 'GET',
        'callback' => array($userController,'logout')
    ));

    register_rest_route( 'custom/v1', '/user/recovery_session', array(
        'methods' => 'GET',
        'callback' => array($userController,'getRecoverySession')
    ));

    register_rest_route( 'custom/v1', '/user/recovery_session', array(
        'methods' => 'POST',
        'callback' => array($userController,'createRecoverySession')
    ));

    register_rest_route( 'custom/v1', '/user', array(
        'methods' => 'POST',
        'callback' => array($userController,'createUser')
    ));

    register_rest_route( 'custom/v1', '/user', array(
        'methods' => 'PUT',
        'callback' => array($userController,'updateUser')
    ));

    register_rest_route( 'custom/v1', '/user/teacher/network', array(
        'methods' => 'POST',
        'callback' => array($userController,'sendTeacherData')
    ));

    register_rest_route( 'custom/v1', '/user/password', array(
        'methods' => 'PUT',
        'callback' => array($userController,'resetPassword')
    ));

    register_rest_route( 'custom/v1', '/user/enrollments', array(
        'methods' => 'GET',
        'callback' => array($userController,'getEnrollments'),
    ));

    register_rest_route( 'custom/v1', '/user/enrollments', array(
        'methods' => 'POST',
        'callback' => array($userController,'saveEnrollments'),
    ));

    register_rest_route( 'custom/v1', '/user/enrollments', array(
        'methods' => 'DELETE',
        'callback' => array($userController,'deleteEnrollments'),
    ));

    register_rest_route( 'custom/v1', '/user/access/log', array(
        'methods' => 'PUT',
        'callback' => array($userController,'saveAccessLog')
    ));

    register_rest_route( 'custom/v1', '/users/access/logs', array(
        'methods' => 'GET',
        'callback' => array($userController,'getAccessLogs')
    ));

    register_rest_route( 'custom/v1', '/users/access/logs/download', array(
        'methods' => 'GET',
        'callback' => array($userController,'downloadAccesLogs')
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

    register_rest_route( 'custom/v1', '/course/categories', array(
        'methods' => 'GET',
        'callback' => array($courseController,'getCategories'),
    ));

    register_rest_route( 'custom/v1', '/course/(?P<course_id>\d+)/unities', array(
        'methods' => 'GET',
        'callback' => array($courseController,'getUnities'),
    ));

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

    register_rest_route( 'custom/v1', '/courses/user/logs', array(
        'methods' => 'GET',
        'callback' => array($courseController,'getUserCourseLogs'),
    ));

    register_rest_route( 'custom/v1', '/courses/user/logs/download', array(
        'methods' => 'GET',
        'callback' => array($courseController,'downloadUserCourseLogs'),
    ));    

    register_rest_route( 'custom/v1', '/course/request', array(
        'methods' => 'POST',
        'callback' => array($courseController,'sendCourseRequest'),
    ));    

    register_rest_route( 'custom/v1', '/courses/buy/log', array(
        'methods' => 'POST',
        'callback' => array($courseController,'saveBuyRequest'),
    ));

    register_rest_route( 'custom/v1', '/courses/buy/checkout', array(
        'methods' => 'GET',
        'callback' => array($courseController,'checkoutBuyRequests'),
    ));

    register_rest_route( 'custom/v1', '/courses/enrollments', array(
        'methods' => 'GET',
        'callback' => array($courseController,'getEnrollments'),
    ));

    register_rest_route( 'custom/v1', '/courses/enrollments/expired/download', array(
        'methods' => 'GET',
        'callback' => array($courseController,'downloadExpiredEnrollments'),
    ));

    register_rest_route( 'custom/v1', '/courses/export', array(
        'methods' => 'GET',
        'callback' => array($courseController,'export'),
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

    register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/Video/log', array(
        'methods' => 'PUT',
        'callback' => array($topicController,'saveVideoLog'),
    ));    

    register_rest_route( 'custom/v1', '/topic/(?P<post_id>\d+)/material/log', array(
        'methods' => 'PUT',
        'callback' => array($topicController,'saveMaterialLog'),
    ));

    register_rest_route( 'custom/v1', '/topics/video/logs', array(
        'methods' => 'GET',
        'callback' => array($topicController,'getVideoLogs'),
    ));

    register_rest_route( 'custom/v1', '/topics/video/logs/download', array(
        'methods' => 'GET',
        'callback' => array($topicController,'downloadVideoLogs'),
    ));

    register_rest_route( 'custom/v1', '/topics/material/logs', array(
        'methods' => 'GET',
        'callback' => array($topicController,'getMaterialLogs'),
    ));

    register_rest_route( 'custom/v1', '/topics/material/logs/download', array(
        'methods' => 'GET',
        'callback' => array($topicController,'downloadMaterialLogs'),
    ));

    register_rest_route( 'custom/v1', '/topics/test/logs', array(
        'methods' => 'GET',
        'callback' => array($topicController,'getTestLogs'),
    ));

    register_rest_route( 'custom/v1', '/topics/test/logs/download', array(
        'methods' => 'GET',
        'callback' => array($topicController,'downloadTestLogs'),
    ));

    register_rest_route( 'custom/v1', '/topics/comments', array(
        'methods' => 'GET',
        'callback' => array($topicController,'getAllComments'),
    ));    

    register_rest_route( 'custom/v1', '/topics/comments/download', array(
        'methods' => 'GET',
        'callback' => array($topicController,'downloadAllComments'),
    ));

    register_rest_route( 'custom/v1', '/topics/export', array(
        'methods' => 'GET',
        'callback' => array($topicController,'export'),
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

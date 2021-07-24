<?php

/**
 * Libs
 */
include_once __DIR__ . "/../config/main.php";
include_once __DIR__ . '/../libs/videos.php';
include_once __DIR__ . '/../libs/comments.php';
include_once __DIR__ . '/../libs/course.php';
include_once __DIR__ . '/../libs/likes.php';
include_once __DIR__ . '/../libs/queries.php';
include_once __DIR__ . '/../libs/user.php';

/**
 * Controllers
 */
require_once(__DIR__."/../controllers/VideoController.php");
require_once(__DIR__."/../controllers/SessionRequestController.php");
require_once(__DIR__."/../controllers/SessionController.php");
require_once(__DIR__."/../controllers/SectorController.php");
require_once(__DIR__."/../controllers/TestController.php");
require_once(__DIR__."/../controllers/BehaviourController.php");
require_once(__DIR__."/../controllers/ExerciseController.php");
require_once(__DIR__."/../controllers/SchoolController.php");

require_once(__DIR__ . "/../controllers/UserController.php");
require_once(__DIR__ . "/../controllers/AuthController.php");
require_once(__DIR__ . "/../controllers/CourseController.php");
require_once(__DIR__ . "/../controllers/TopicController.php");
require_once(__DIR__ . "/../controllers/GEOController.php");
require_once(__DIR__ . "/../controllers/EventController.php");
require_once(__DIR__ . "/../controllers/DonationController.php");
require_once(__DIR__ . "/../controllers/DiscussionController.php");

new UserController();
new AuthController();
new CourseController();
new TopicController();
new GEOController();
new EventController();
new DonationController();
new DiscussionController();

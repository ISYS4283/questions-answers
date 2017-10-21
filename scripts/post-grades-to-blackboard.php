<?php

use razorbacks\blackboard\rest\Api;

require_once __DIR__.'/../vendor/autoload.php';
(new \Dotenv\Dotenv(dirname(__DIR__)))->load();

$blackboard = new Api(
    getenv('BB_REST_API_SERVER'),
    getenv('BB_REST_API_APPLICATION_ID'),
    getenv('BB_REST_API_SECRET')
);

$courseId = getenv('BB_REST_API_COURSE_ID');
$users = $blackboard->get("/courses/{$courseId}/users");

print_r($users);

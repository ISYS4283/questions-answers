<?php

if (!isset($argv[1], $argv[2], $argv[3])) {
    die('question date, answer date, and chapter number are required.'.PHP_EOL);
}
$qdate = $argv[1];
$adate = $argv[2];
$chapter = $argv[3];

use isys4283\qa\tests\utilities\Database;
use razorbacks\blackboard\rest\Api;

require_once __DIR__.'/../vendor/autoload.php';
(new \Dotenv\Dotenv(dirname(__DIR__)))->load();

// get scores
$sql = "EXEC qascore @qdate='$qdate', @adate='$adate'";
$grades = Database::student()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
if (empty($grades)) {
    die('qascore returned no grades.'.PHP_EOL);
}

// create bb assignment
$courseId = getenv('BB_REST_API_COURSE_ID');
if (empty($courseId)) {
    die('BB_REST_API_COURSE_ID is empty.'.PHP_EOL);
}

$blackboard = new Api(
    getenv('BB_REST_API_SERVER'),
    getenv('BB_REST_API_APPLICATION_ID'),
    getenv('BB_REST_API_SECRET')
);

$gradeColumn = [
    'name' => "QA $chapter",
    'score' => [
        'possible' => 2,
    ],
    'availability' => [
        'available' => 'Yes',
    ],
];
$gradeColumn = $blackboard->post("/courses/{$courseId}/gradebook/columns", $gradeColumn);

// iterate over scores and patch grades
foreach ($grades as $grade) {
    $endpoint = "/courses/$courseId/gradebook/columns/{$gradeColumn['id']}/users/userName:{$grade['username']}";
    $blackboard->patch($endpoint, [
        'score' => $grade['score'],
    ]);
}

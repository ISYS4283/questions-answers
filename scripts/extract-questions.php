<?php require __DIR__.'/../vendor/autoload.php';

use isys4283\qa\QuestionExtractor;

$questionExtractor = new QuestionExtractor($argv[1]);

$questionExtractor->run();

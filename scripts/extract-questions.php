<?php require __DIR__.'/../vendor/autoload.php';

use isys4283\qa\QuestionExtractor;

// requires argument for date YYYY-MM-DD
(new QuestionExtractor($argv[1]))->run();

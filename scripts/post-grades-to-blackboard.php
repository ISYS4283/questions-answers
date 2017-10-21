<?php

require_once __DIR__.'/../vendor/autoload.php';

(new \Dotenv\Dotenv(dirname(__DIR__)))->load();

var_dump(
    getenv('BB_REST_API_SERVER'),
    getenv('BB_REST_API_APPLICATION_ID'),
    getenv('BB_REST_API_SECRET')
);

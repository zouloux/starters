<?php

require __DIR__ . '/../vendor/autoload.php';

$application = new WordPlate\Application(realpath(__DIR__ . '/../'));
$application->run();

$table_prefix = env('WP_PREFIX', 'wp_');

require_once ABSPATH . 'wp-settings.php';

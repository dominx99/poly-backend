<?php

require 'vendor/autoload.php';

$app = \App\App::create();

require __DIR__ . '/bootstrap/dependencies.php';
require __DIR__ . '/routes/api.php';

$app->run();

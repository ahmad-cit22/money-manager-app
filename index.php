<?php

declare(strict_types=1);

use App\Classes\CLIApplication;

require 'vendor/autoload.php';

$app = new CLIApplication();
$app->run();

// var_dump($app);
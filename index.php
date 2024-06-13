<?php

declare(strict_types=1);

use App\Classes\CLIApplication;

require_once __DIR__ . '/vendor/autoload.php';

// Run the application
$app = new CLIApplication();
$app->run();
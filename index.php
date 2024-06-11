<?php

declare(strict_types=1);

use App\CLIApplication;

require 'vendor/autoload.php';

$app = new CLIApplication();
$app->run();


// $contents = file_get_contents('data/categories.txt');
// $jsonToArr = json_decode($contents, true);
// file_put_contents('data/categoriesss.txt', json_encode($jsonToArr, JSON_PRETTY_PRINT));
// var_dump($jsonToArr);
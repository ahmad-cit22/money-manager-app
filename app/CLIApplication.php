<?php

declare(strict_types=1);

namespace App;

class CLIApplication
{
    public function __construct()
    {
        echo "Hello World";
    }

    public function run(): void
    {
        $manager = new MoneyManager();
        $currentSavings = $manager->getSavings()[0]['amount'];
        $categories = $manager->getCategories();
        var_dump($categories);
    }
}
<?php

declare(strict_types=1);

namespace App\Classes;

class Category {
    public $name;

    public function __construct($name) {
        $this->name = $name;
    }
}
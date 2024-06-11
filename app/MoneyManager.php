<?php

declare(strict_types=1);

namespace App;

class MoneyManager
{

    public function __construct()
    {
        echo "Money Manager\n";
    }

    public function getSavings()
    {
        $filePath = 'data/savings.txt';

        $fileContent = trim(file_get_contents($filePath));

        // Decode JSON contents into an associative array
        $arr = json_decode($fileContent, true);

        // Check for errors during JSON decoding
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'Error decoding JSON: ' . json_last_error_msg() . "\n";
            return null;
        }

        return $arr;
    }
    
    public function getCategories()
    {
        $filePath = 'data/categories.txt';

        $fileContent = trim(file_get_contents($filePath));

        // Decode JSON contents into an associative array
        $arr = json_decode($fileContent, true);

        // Check for errors during JSON decoding
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'Error decoding JSON: ' . json_last_error_msg() . "\n";
            return null;
        }

        $str = '';
        foreach ($arr as $key => $item) {
            foreach ($item as $k => $value) {
                $str .= $k . '. ' . $value . "\n";
            }
        }

        return $str;
    }
}
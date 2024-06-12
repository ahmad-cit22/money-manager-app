<?php

declare(strict_types=1);

namespace App\Classes;

class MoneyManager {

    private $incomes = [];
    private $expenses = [];
    private $categories = [];


    public function __construct() {
        $this->loadData();
    }

    public function addIncome($amount, $category) {
        $this->incomes[] = new Income((int) $amount, $category, date('Y-m-d'));
        $this->saveData();
    }

    public function addExpense($amount, $category) {
        $this->expenses[] = new Expense((int) $amount, $category, date('Y-m-d'));
        $this->saveData();
    }

    public function addCategory($name) {
        $this->categories[] = new Category($name);
        $this->saveData();
    }

    public function getIncomes() {
        if (empty($this->incomes)) {
            echo "No incomes found!\n";
            return;
        }

        echo "Incomes\n";
        echo "--------------\n";

        foreach ($this->incomes as $key => $income) {
            echo "{$key}. Amount: {$income->amount}, Category: {$income->category}, Date: {$income->date}\n";
        }
    }

    public function getExpenses() {
        if (empty($this->expenses)) {
            echo "No expenses found!\n";
            return;
        }

        echo "Expenses\n";
        echo "--------------\n";

        foreach ($this->expenses as $key => $expense) {
            echo "{$key}. Amount: {$expense->amount}, Category: {$expense->category}, Date: {$expense->date}\n";
        }
    }

    public function getSavings() {
        $totalIncome = array_sum(array_map(function ($income) {
            return $income->amount;
        }, $this->incomes ?? []));

        $totalExpense = array_sum(array_map(function ($expense) {
            return $expense->amount;
        }, $this->expenses ?? []));

        $savings = ($totalIncome - $totalExpense) ?? 0;
        echo "Total Savings: {$savings}\n";
    }

    public function getCategories() {
        if (empty($this->categories)) {
            echo "No categories found!\n";
            return;
        }

        echo "Categories\n";
        echo "--------------\n";

        foreach ($this->categories as $key => $category) {
            echo "{$key}. Name: {$category->name}\n";
        }
    }

    public function categoryExists($name) {
        foreach ($this->categories as $category) {
            if ($category->name === $name) {
                return true;
            }
        }

        return false;
    }

    public function saveData() {
        $data = [
            'incomes' => array_map(function ($income) {
                return (array) $income;
            }, $this->incomes ?? []),
            'expenses' => array_map(function ($expense) {
                return (array) $expense;
            }, $this->expenses ?? []),
            'categories' => array_map(function ($category) {
                return (array) $category;
            }, $this->categories ?? []),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT);

        file_put_contents('data/data.json', $json);
    }

    public function loadData() {
        $filePath = 'data/data.json';

        // Check if the file exists and is readable
        if (!file_exists($filePath) || !is_readable($filePath)) {
            echo "Error: File not found or not readable!\n";
            return null;
        }

        // Read the contents of the file
        $fileContent = trim(file_get_contents($filePath));

        if (empty($fileContent) || $fileContent === false) {
            echo "Error: File is empty or not readable!\n";
            return null;
        }

        // Decode JSON contents into an associative array
        $data = json_decode($fileContent, true);

        // Check for errors during JSON decoding
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'Error decoding JSON: ' . json_last_error_msg() . "\n";
            return null;
        }

        if ($data) {
            $this->incomes = array_map(function ($item) {
                return new Income($item['amount'], $item['category'], $item['date']);
            }, $data['incomes'] ?? []);

            $this->expenses = array_map(function ($item) {
                return new Expense($item['amount'], $item['category'], $item['date']);
            }, $data['expenses'] ?? []);

            $this->categories = array_map(function ($item) {
                return new Category($item['name']);
            }, $data['categories'] ?? []);
        } else {
            echo "Error: Failed to load data!\n";
            return null;
        }
    }
}

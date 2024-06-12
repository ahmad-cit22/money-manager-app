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

    public function addIncome(float $amount, string $category): void {
        $this->incomes[] = new Income($amount, $category, date('Y-m-d'));
        $this->saveData();

        echo "\033[32mIncome added successfully!\033[0m\n";
    }

    public function addExpense(float $amount, string $category): void {
        $this->expenses[] = new Expense($amount, $category, date('Y-m-d'));
        $this->saveData();

        echo "\033[32mExpense added successfully!\033[0m\n";
    }

    public function addCategory(string $name): void {
        $this->categories[] = new Category($name);
        $this->saveData();

        echo "\033[32mCategory added successfully!\033[0m\n";
    }

    public function getIncomes(): void {
        if (empty($this->incomes)) {
            echo "\033[31mNo incomes found!\033[0m\n";
            return;
        }

        echo "\033[32mIncomes\n";
        echo "--------------\033[0m\n";

        foreach ($this->incomes as $key => $income) {
            echo "{$key}. Amount: {$income->amount}, Category: {$income->category}, Date: {$income->date}\n";
        }
    }

    public function getExpenses(): void {
        if (empty($this->expenses)) {
            echo "\033[31mNo expenses found!\033[0m\n";
            return;
        }

        echo "\033[32mExpenses\n";
        echo "--------------\033[0m\n";

        foreach ($this->expenses as $key => $expense) {
            echo "{$key}. Amount: {$expense->amount}, Category: {$expense->category}, Date: {$expense->date}\n";
        }
    }

    public function getSavings(): void {
        $totalIncome = array_sum(array_map(function ($income) {
            return $income->amount;
        }, $this->incomes ?? []));

        $totalExpense = array_sum(array_map(function ($expense) {
            return $expense->amount;
        }, $this->expenses ?? []));

        $savings = ($totalIncome - $totalExpense) ?? 0;
        echo "\033[32mTotal Savings: {$savings}\033[0m\n";
    }

    public function getCategories(): void {
        if (empty($this->categories)) {
            echo "\033[31mNo categories found!\033[0m\n";
            return;
        }

        echo "\033[32mCategories\n";
        echo "--------------\033[0m\n";

        foreach ($this->categories as $key => $category) {
            echo "{$key}. Name: {$category->name}\n";
        }
    }

    public function categoryExists(string $name): bool {
        foreach ($this->categories as $category) {
            if (preg_match("/{$name}/i", $category->name)) {
                return true;
            }
        }

        return false;
    }

    public function saveData(): void {
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

    public function loadData(): void {
        $filePath = 'data/data.json';

        // Check if the file exists and is readable
        if (!file_exists($filePath) || !is_readable($filePath)) {
            echo "\033[31mError: File not found or not readable!\033[0m\n";
            return;
        }

        // Read the contents of the file
        $fileContent = trim(file_get_contents($filePath));

        if (empty($fileContent) || $fileContent === false) {
            echo "\033[31mError: File is empty or not readable!\033[0m\n";
            return;
        }

        // Decode JSON contents into an associative array
        $data = json_decode($fileContent, true);

        // Check for errors during JSON decoding
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo '\033[31mError decoding JSON: \033[0m' . json_last_error_msg() . "\n";
            return;
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
            echo "\033[31mError: Failed to load data!\033[0m\n";
            return;
        }
    }
}

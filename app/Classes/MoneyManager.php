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

        echo $this->formatMessage('Income added successfully!', 'success');
    }

    public function addExpense(float $amount, string $category): void {
        $this->expenses[] = new Expense($amount, $category, date('Y-m-d'));
        $this->saveData();

        echo $this->formatMessage('Expense added successfully!', 'success');
    }

    public function addCategory(string $name): void {
        $this->categories[] = new Category($name);
        $this->saveData();

        echo $this->formatMessage('Category added successfully!', 'success');
    }

    public function getIncomes(): void {
        if (empty($this->incomes)) {
            echo $this->formatMessage('No incomes found!', 'error');
            return;
        }

        echo "\n\033[32mIncomes\n";
        echo "----------------\033[0m\n\n";

        foreach ($this->incomes as $key => $income) {
            $k = $key + 1;
            echo "{$k}. Amount: {$income->amount}, Category: {$income->category}, Date: {$income->date}\n";
        }
    }

    public function getExpenses(): void {
        if (empty($this->expenses)) {
            echo $this->formatMessage('No expenses found!', 'error');
            return;
        }

        echo "\n\033[32mExpenses\n";
        echo "----------------\033[0m\n\n";

        foreach ($this->expenses as $key => $expense) {
            $k = $key + 1;
            echo "{$k}. Amount: {$expense->amount}, Category: {$expense->category}, Date: {$expense->date}\n";
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
        echo $this->formatMessage("Total Savings: {$savings}", 'success');
    }

    public function getCategories(): void {
        if (empty($this->categories)) {
            echo $this->formatMessage('No categories found!', 'error');
            return;
        }

        echo "\n\033[32mCategories\n";
        echo "----------------\033[0m\n\n";

        foreach ($this->categories as $key => $category) {
            $k = $key + 1;
            echo "{$k}. Name: {$category->name}\n";
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

        if (!file_exists($filePath) || !is_readable($filePath)) {
            echo $this->formatMessage("Error: File not found or not readable!", 'error');
            return;
        }

        $fileContent = trim(file_get_contents($filePath));

        if (empty($fileContent) || $fileContent === false) {
            echo $this->formatMessage("Error: File is empty or not readable!", 'error');
            return;
        }

        $data = json_decode($fileContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo $this->formatMessage("Error decoding JSON: " . json_last_error_msg(), 'error');
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
            echo $this->formatMessage("Error: Failed to load data!", 'error');
            return;
        }
    }

    public function formatMessage(string $message, string $type = null): string {
        if ($type === 'error') {
            return "\n\033[31m{$message}\033[0m\n\n";
        } elseif ($type === 'success') {
            return "\n\033[32m{$message}\033[0m\n\n";
        }

        return "\n\033[33m{$message}\033[0m\n\n";
    }
}

<?php

declare(strict_types=1);

namespace App\Classes;

class CLIApplication {
    private MoneyManager $moneyManager;

    public function __construct() {
        $this->moneyManager = new MoneyManager();
    }

    public function run(): void {

        while (true) {
            echo "\n";
            echo "\033[34mWelcome to Money Manager App\n";
            echo "=================================\033[0m\n";
            echo "\n";
            echo "Available features:\n";
            echo "\n";
            echo "1. Add Income\n";
            echo "2. Add Expense\n";
            echo "3. View Incomes\n";
            echo "4. View Expenses\n";
            echo "5. View Savings\n";
            echo "6. View Categories\n";
            echo "7. Add New Category\n";
            echo "8. Exit\n";
            echo "\n";
            $option = trim(readline("Enter your option: "));

            switch ($option) {
                case "1":

                    while (true) {
                        $this->insertHeader();

                        $amount = $this->getInput("Enter income amount: ");

                        if (is_numeric($amount) && $amount > 0) {
                            break;
                        }
                        echo "\n";
                        echo "\033[31mError: Invalid amount! Please insert a positive number.\033[0m\n";
                    }

                    while (true) {
                        $this->insertHeader();

                        $category = $this->getInput("Enter income category: ");

                        if (empty($category)) {
                            echo "\n";
                            echo "\033[31mError: Category cannot be empty!\033[0m\n";
                        } elseif (!$this->moneyManager->categoryExists($category)) {
                            echo "\n";
                            echo "\033[31mError: Category does not exist! You must add it first.\033[0m\n";
                        } else {
                            break;
                        }
                    }

                    $this->moneyManager->addIncome((float) $amount, $category);
                    break;

                case "2":
                    while (true) {
                        $this->insertHeader();

                        $amount = $this->getInput("Enter expense amount: ");
                        if (is_numeric($amount) && $amount > 0) {
                            break;
                        }
                        echo "\n";
                        echo "\033[31mError: Invalid amount! Please insert a positive number.\033[0m\n";
                    }

                    while (true) {
                        $this->insertHeader();

                        $category = $this->getInput("Enter expense category: ");
                        if (empty($category)) {
                            echo "\n";
                            echo "\033[31mError: Category cannot be empty!\033[0m\n";
                        } elseif (!$this->moneyManager->categoryExists($category)) {
                            echo "\n";
                            echo "\033[31mError: Category does not exist! You must add it first.\033[0m\n";
                        } else {
                            break;
                        }
                    }

                    $this->moneyManager->addExpense((float) $amount, $category);
                    break;

                case "3":
                    $this->insertHeader();

                    $this->moneyManager->getIncomes();
                    break;

                case "4":
                    $this->insertHeader();

                    $this->moneyManager->getExpenses();
                    break;

                case "5":
                    $this->insertHeader();

                    $this->moneyManager->getSavings();
                    break;

                case "6":
                    $this->insertHeader();

                    $this->moneyManager->getCategories();
                    break;

                case "7":
                    while (true) {
                        $this->insertHeader();

                        $category = $this->getInput("Enter category name: ");
                        if (empty($category)) {
                            $this->insertHeader();

                            echo "\n";
                            echo "\033[31mError: Category name cannot be empty!\033[0m\n";
                        } elseif (!preg_match('/^[A-Za-z]+$/', $category)) {
                            $this->insertHeader();

                            echo "\n";
                            echo "\033[31mError: Category name can only contain letters (A-z).\033[0m\n";
                        } elseif ($this->moneyManager->categoryExists($category)) {
                            $this->insertHeader();

                            echo "\n";
                            echo "\033[31mError: Category already exists!\033[0m\n";
                        } else {
                            break;
                        }
                    }

                    $this->moneyManager->addCategory($category);
                    break;

                case "8":
                    $this->insertHeader();

                    echo "\033[32mThanks for using Money Manager App. Goodbye!\033[0m\n";
                    exit;

                default:
                    $this->insertHeader();

                    echo "\n";
                    echo "\033[31mError: Invalid option! Please enter a valid option.\033[0m\n";
                    break;

                case "":
                    break;
            }
        }
    }

    public function insertHeader() {
        echo "\n";
        echo "\033[34mMoney Manager App\n";
        echo "============================\033[0m\n";
        echo "\n";
        echo "# Go back to main menu\n";
        echo "\n";
    }

    public function getInput(string $prompt): ?string {
        $input = trim(readline($prompt));

        if ($input === '#') {
            // echo "";
            echo "\n\033[32mGoing back to main menu.\033[0m\n";
            $this->run();
        } else {
            return $input;
        }
    }
}

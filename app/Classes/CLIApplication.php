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
            echo "\n\033[34mWelcome to Money Manager App\n";
            echo "=================================\033[0m\n\n";
            echo "Available features:\n";
            echo "\n1. Add Income\n";
            echo "2. Add Expense\n";
            echo "3. View Incomes\n";
            echo "4. View Expenses\n";
            echo "5. View Savings\n";
            echo "6. View Categories\n";
            echo "7. Add New Category\n";
            echo "8. Exit\n\n";

            $option = trim(readline("Enter your option: "));

            switch ($option) {
                case "1":
                    $this->insertHeader();

                    while (true) {
                        $amount = $this->getInput("Enter income amount: ");

                        if (is_numeric($amount) && $amount > 0) {
                            break;
                        }

                        echo $this->moneyManager->formatMessage("Error: Invalid amount! Please insert a positive number.", 'error');
                    }

                    while (true) {
                        $category = $this->getInput("Enter income category: ");

                        if (empty($category)) {
                            echo $this->moneyManager->formatMessage("Error: Category cannot be empty!", 'error');
                        } elseif (!$this->moneyManager->categoryExists($category)) {
                            echo $this->moneyManager->formatMessage("Error: Category does not exist! You must add it first.", 'error');
                        } else {
                            break;
                        }
                    }

                    $this->moneyManager->addIncome((float) $amount, $category);
                    break;

                case "2":
                    $this->insertHeader();
                    while (true) {
                        $amount = $this->getInput("Enter expense amount: ");

                        if (is_numeric($amount) && $amount > 0) {
                            break;
                        }

                        echo $this->moneyManager->formatMessage("Error: Invalid amount! Please insert a positive number.", 'error');
                    }

                    while (true) {
                        $category = $this->getInput("Enter expense category: ");

                        if (empty($category)) {
                            echo $this->moneyManager->formatMessage("Error: Category cannot be empty!", 'error');
                        } elseif (!$this->moneyManager->categoryExists($category)) {
                            echo $this->moneyManager->formatMessage("Error: Category does not exist! You must add it first.", 'error');
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
                    $this->insertHeader();
                    while (true) {

                        $category = $this->getInput("Enter category name: ");

                        if (empty($category)) {

                            echo $this->moneyManager->formatMessage("Error: Category name cannot be empty!", 'error');
                        } elseif (!preg_match('/^[A-Za-z]+$/', $category)) {

                            echo $this->moneyManager->formatMessage("Error: Category name can only contain letters (A-z).", 'error');
                        } elseif ($this->moneyManager->categoryExists($category)) {

                            echo $this->moneyManager->formatMessage("Error: Category already exists!", 'error');
                        } else {
                            break;
                        }
                    }

                    $this->moneyManager->addCategory($category);
                    break;

                case "8":
                    $this->insertHeader();

                    echo $this->moneyManager->formatMessage(
                        "Thanks for using Money Manager App. Goodbye!",
                        'success'
                    );
                    exit;

                default:

                    echo $this->moneyManager->formatMessage("Error: Invalid option! Please enter a valid option.", 'error');
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
            echo $this->moneyManager->formatMessage("Going back to main menu.", 'success');
            $this->run();
        } else {
            return $input;
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Classes;

class CLIApplication {
    public function __construct() {
        $moneyManager = new MoneyManager();
    }

    public function run(): void {

        while (true) {
            echo "Welcome to Money Manager App\n";
            echo "=================================\n";
            echo "\n";
            echo "Available features:\n";
            echo "1. Add Income\n";
            echo "2. Add Expense\n";
            echo "3. View Incomes\n";
            echo "4. View Expenses\n";
            echo "5. View Savings\n";
            echo "6. View Categories\n";
            echo "7. Add New Category\n";
            echo "8. Exit\n";
            $option = trim(readline("Enter your option: "));

            switch ($option) {
                case "1":

            }
        }

        // while (true) {
        //     echo "Enter amount: ";
        //     $amount = trim(fgets(STDIN));
        //     if (is_numeric($amount) && $amount > 0) {
        //         break;
        //     }
        //     echo "Invalid amount. Try again.\n";
        // }
    }
}

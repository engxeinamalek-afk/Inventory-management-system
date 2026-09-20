<?php
namespace App\strategies;

use App\entities\Transaction;

interface TransactionStrategyInterface{
    public function process(Transaction $transaction): void;
    public function validateRules(): array;
}
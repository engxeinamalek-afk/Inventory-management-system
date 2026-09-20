<?php
namespace App\factories;

use App\entities\enums\TransactionType;
use App\strategies\TransactionStrategyInterface;
use App\strategies\SaleTransactionStrategy;
use App\strategies\PurchaseTransactionStrategy;
use Exception;

class TransactionStrategyFactory 
{
    public function __construct(
        private SaleTransactionStrategy $saleStrategy,
        private PurchaseTransactionStrategy $purchaseStrategy
    ) {}

    public function make($type): TransactionStrategyInterface 
    {
        $typeEnum = is_string($type) ? TransactionType::tryFrom($type) : $type;
        return match ($typeEnum) {
            TransactionType::SALE => $this->saleStrategy,
            TransactionType::PURCHASE => $this->purchaseStrategy,
            default => throw new Exception("Unsupported transaction type.")
        };
    }
}
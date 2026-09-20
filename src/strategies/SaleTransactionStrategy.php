<?php
namespace App\strategies;

use App\entities\Transaction;
use App\repository\InventoryRepository;
use App\repository\ProductRepository;
use Exception;

class SaleTransactionStrategy implements TransactionStrategyInterface 
{
    public function __construct(
        private InventoryRepository $inventoryRepo,
        private ProductRepository $productRepo
    ) {}
    public function process(Transaction $transaction): void 
    {
        $availableStock = $this->inventoryRepo->getQuantity($transaction->productId);
        if ($availableStock < $transaction->quantity) {
            throw new Exception("The quantity is not available!");
        }

        $transaction->unitPrice = $this->productRepo->getPrice($transaction->productId);

        $this->inventoryRepo->decreaseStock($transaction->productId, $transaction->quantity);
    }
}
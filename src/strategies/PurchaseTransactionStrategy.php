<?php
namespace App\strategies;

use App\entities\Transaction;
use App\repository\SupplierRepository;
use App\repository\InventoryRepository;

class PurchaseTransactionStrategy implements TransactionStrategyInterface 
{
    public function __construct(
        private SupplierRepository $supplierRepo,
        private InventoryRepository $inventoryRepo
    ) {}
    public function validateRules(): array
    {
        return ['supplier_id' => 'required|integer'];
    }
    public function process(Transaction $transaction): void 
    {
        $this->supplierRepo->checkRelation($transaction->productId, $transaction->supplierId);

        $transaction->unitPrice = $this->supplierRepo->getPrice($transaction->productId, $transaction->supplierId);

        $this->inventoryRepo->increaseStock($transaction->productId, $transaction->quantity);
    }
}
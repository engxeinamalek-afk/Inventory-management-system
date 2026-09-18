<?php
namespace App\services;

use App\repository\InventoryRepository;
use App\repository\ProductRepository;
use App\repository\SupplierRepository;
use App\repository\TransactionRepository;
use App\entities\Transaction;

class TransactionService{
    public function __construct(private ProductRepository $productRepo,
                                private SupplierRepository $supplierRepo,
                                private TransactionRepository $transactionRepo,
                                private InventoryRepository $inventoryRepo)
    {}
    public function store(Transaction $transaction): int {
        if (!$this->productRepo->find($transaction->productId)) {
            return 0;
        }

        if ($transaction->type === 'purchase') {
            if (!$this->supplierRepo->checkRelation($transaction->productId, $transaction->supplierId)) {
                return 0;
            }
        }

        $transactionId = $this->transactionRepo->create($transaction);

        if ($transactionId > 0) {
            if ($transaction->type === 'sale') {
                $this->inventoryRepo->decreaseStock($transaction->productId, $transaction->quantity);
            } else {
                $this->inventoryRepo->increaseStock($transaction->productId, $transaction->quantity);
            }
        }

        return $transactionId;
    }

}
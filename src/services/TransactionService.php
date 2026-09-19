<?php
namespace App\services;

use App\repository\InventoryRepository;
use App\repository\ProductRepository;
use App\repository\SupplierRepository;
use App\repository\TransactionRepository;
use App\entities\Transaction;
use Exception;
use PDO;

class TransactionService{
    public function __construct(private ProductRepository $productRepo,
                                private SupplierRepository $supplierRepo,
                                private TransactionRepository $transactionRepo,
                                private InventoryRepository $inventoryRepo,
                                private PDO $pdo)
    {}
    public function store(Transaction $transaction){
        try{
            $this->pdo->beginTransaction();
            $this->productRepo->find($transaction->productId);

            if ($transaction->type === 'sale'){
                $availableStock = $this->inventoryRepo->getQuantity($transaction->productId);
                if ($availableStock < $transaction->quantity)
                    throw new Exception("The quantity in not available!");
                $transaction->unitPrice = $this->productRepo->getPrice($transaction->productId);
            }

            if ($transaction->type === 'purchase'){
                $this->supplierRepo->checkRelation($transaction->productId, $transaction->supplierId); 
                $transaction->unitPrice = $this->supplierRepo->getPrice($transaction->productId , $transaction->supplierId);
            }

            $transaction->setTotelPrice();
            $this->transactionRepo->create($transaction);

            if ($transaction->type === 'sale') {
                $this->inventoryRepo->decreaseStock($transaction->productId, $transaction->quantity);
            } else {
                $this->inventoryRepo->increaseStock($transaction->productId, $transaction->quantity);
            }
            $this->pdo->commit();
        }catch(Exception $e){
            $this->pdo->rollBack();
            
            throw $e;
        }
    }
}
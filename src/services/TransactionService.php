<?php
namespace App\services;

use App\repository\InventoryRepository;
use App\repository\ProductRepository;
use App\repository\SupplierRepository;
use App\repository\TransactionRepository;
use App\entities\Transaction;
use App\factories\TransactionStrategyFactory;
use Exception;
use PDO;

class TransactionService{
    public function __construct(private ProductRepository $productRepo,
                                private TransactionRepository $transactionRepo,
                                private TransactionStrategyFactory $factory,
                                private PDO $pdo)
    {}
    public function store(Transaction $transaction){
        try{
            $this->pdo->beginTransaction();
            $this->productRepo->find($transaction->productId);

            $strategy = $this->factory->make($transaction->type);
            $strategy->process($transaction);

            $transaction->setTotelPrice();
            $this->transactionRepo->create($transaction);

            $this->pdo->commit();
        }catch(Exception $e){
            $this->pdo->rollBack();
            
            throw $e;
        }
    }
}
<?php
namespace App\repository;
use PDO;
use App\entities\Transaction;
class TransactionRepository{
    public function __construct(Private PDO $PDO)
    {}
    public function create(Transaction $transaction){
        $stmt = $this->PDO->prepare("
            INSERT INTO transactions (product_id, supplier_id, type, quantity, unit_price, total_price, date) 
            VALUES (:product_id, :supplier_id, :type, :quantity, :unit_price, :total_price, :date)
        ");

        $stmt->execute([
            ':product_id'  => $transaction->productId,
            ':supplier_id' => $transaction->supplierId,
            ':type'        => $transaction->type->value,
            ':quantity'    => $transaction->quantity,
            ':unit_price'  => $transaction->unitPrice,
            ':total_price' => $transaction->totalPrice,
            ':date'        => $transaction->date
        ]);
    }

}
<?php
namespace App\repository;
use PDO;
use App\entities\Transaction;
use App\entities\enums\TransactionType;
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


    public function getPurchases(): array 
    {
        $sql = "SELECT 
                t.id,
                t.product_id,
                p.name AS product_name,
                t.supplier_id,
                s.name AS supplier_name,
                t.quantity,
                t.unit_price,
                t.total_price,
                t.date
                FROM transactions t
                INNER JOIN products p ON t.product_id = p.id
                INNER JOIN suppliers s ON t.supplier_id = s.id
                WHERE t.type = :type
                ORDER BY t.date DESC";

        $stmt = $this->PDO->prepare($sql);
        
        $stmt->execute([
            'type' => TransactionType::PURCHASE->value
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSales(): array 
    {
        $sql = "SELECT 
                t.id,
                t.product_id,
                p.name AS product_name,
                t.quantity,
                t.unit_price,
                t.total_price,
                t.date
                FROM transactions t
                INNER JOIN products p ON t.product_id = p.id
                WHERE t.type = :type
                ORDER BY t.date DESC";

        $stmt = $this->PDO->prepare($sql);

        $stmt->execute([
            'type' => TransactionType::SALE->value
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
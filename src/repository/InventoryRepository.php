<?php
namespace App\repository;

use Exception;
use PDO;
class InventoryRepository{
    public function __construct(Private PDO $PDO)
    {}
    // انشاء سجل (قيمة ابتدائية )
    public function create($id){
        $sql = "INSERT INTO inventory (product_id, current_quantity) VALUES (:product_id, :current_quantity)";
        
        $stmt = $this->PDO->prepare($sql);
        
        $stmt->execute([
            ':product_id'  => $id,
            ':current_quantity' => 0
        ]);

    }

    public function increaseStock(int $productId, int $quantity) {
        $stmt = $this->PDO->prepare("
            UPDATE inventory 
            SET current_quantity = current_quantity + :quantity 
            WHERE product_id = :product_id
        ");
        
        return $stmt->execute([
            ':product_id' => $productId,
            ':quantity'   => $quantity
        ]);
    }

    public function getQuantity(int $productId): int {
        $stmt = $this->PDO->prepare("
            SELECT current_quantity 
            FROM inventory 
            WHERE product_id = :product_id 
            LIMIT 1
            FOR UPDATE
        ");
        
        $stmt->execute([':product_id' => $productId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            throw new \Exception("Inventory record not found for product ID {$productId}.");
        }

        return (int) $row['current_quantity'];
    }


    public function decreaseStock(int $productId, int $quantity) {
        $stmt = $this->PDO->prepare("
            UPDATE inventory 
            SET current_quantity = current_quantity - :quantity 
            WHERE product_id = :product_id
        ");
        
        return $stmt->execute([
            ':product_id' => $productId,
            ':quantity'   => $quantity
        ]);
    }
}
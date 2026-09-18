<?php
namespace App\repository;
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
        
        return (int)$this->PDO->lastInsertId();
    }

    public function increaseStock(int $productId, int $quantity): bool {
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
        ");
        
        $stmt->execute([':product_id' => $productId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return -1;
        }

        return (int) $row['current_quantity'];
    }


    public function decreaseStock(int $productId, int $quantity): bool {
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
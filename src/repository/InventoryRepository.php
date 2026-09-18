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
    // تحديث مخزون
    public function update(){
        
    }
}
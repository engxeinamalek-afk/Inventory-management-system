<?php
namespace App\repository;
use PDO;
use App\entities\Supplier;
class SupplierRepository{
    public function __construct(Private PDO $PDO)
    {}
    // اضافة مصدر
    public function create(Supplier $supplier){
        $sql = "INSERT INTO suppliers (name, phone) VALUES (:name, :phone)";
        
        $stmt = $this->PDO->prepare($sql);
        
        $stmt->execute([
            ':name'  => $supplier->name,
            ':phone' => $supplier->phone
        ]);
        
        return (int)$this->PDO->lastInsertId();
    }

    public function find(int $id): bool {
        $stmt = $this->PDO->prepare("SELECT * FROM suppliers WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return false;
        return true;
    }

    public function add(int $productId, int $supplierId, float $price): bool {
        $stmt = $this->PDO->prepare("
            INSERT INTO product_suppliers (product_id, supplier_id, supplier_price) 
            VALUES (:product_id, :supplier_id, :price)
            ON DUPLICATE KEY UPDATE supplier_price = :update_price
        ");
        
        return $stmt->execute([
            ':product_id'  => $productId,
            ':supplier_id' => $supplierId,
            ':price'       => $price,
            ':update_price'       => $price,
        ]);
    }

    public function checkRelation(int $productId, ?int $supplierId): bool {
        if ($supplierId === null) {
            return false;
        }

        $stmt = $this->PDO->prepare("
            SELECT 1 
            FROM product_suppliers 
            WHERE product_id = :product_id AND supplier_id = :supplier_id 
            LIMIT 1
        ");
        
        $stmt->execute([
            ':product_id'  => $productId,
            ':supplier_id' => $supplierId
        ]);

        return (bool) $stmt->fetch();
    }

}
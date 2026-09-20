<?php
namespace App\repository;
use PDO;
use App\entities\Supplier;
use Exception;

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
    }

    // التححق من وجود المصدر
    public function find(int $id): bool {
        $stmt = $this->PDO->prepare("SELECT * FROM suppliers WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            throw new \Exception("Supplier with ID {$id} not found.");
        }
        return true;
    }

    // اضافة سعر منتج من مصدر محدد
    public function add(int $productId, int $supplierId, float $price) {
        $stmt = $this->PDO->prepare("
            INSERT INTO product_suppliers (product_id, supplier_id, supplier_price) 
            VALUES (:product_id, :supplier_id, :price)
            ON DUPLICATE KEY UPDATE supplier_price = :update_price
        ");
        
        $stmt->execute([
            ':product_id'  => $productId,
            ':supplier_id' => $supplierId,
            ':price'       => $price,
            ':update_price'       => $price,
        ]);
    }

    public function checkRelation(int $productId, ?int $supplierId){
        if ($supplierId === null) 
            throw new Exception("Supplier not found");
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
        $exists = $stmt->fetch();
        if (!$exists)
            throw new \Exception("Relation between product ID {$productId} and supplier ID {$supplierId} not found.");
    }

    public function getPrice(int $productId, int $supplierId) {
        $stmt = $this->PDO->prepare("SELECT supplier_price FROM product_suppliers WHERE product_id = :productId AND supplier_id = :supplierId LIMIT 1");
        $stmt->execute([
            'productId' => $productId,
            'supplierId' => $supplierId
        ]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (float) $row['supplier_price'];
    }
}
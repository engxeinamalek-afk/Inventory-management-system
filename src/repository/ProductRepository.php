<?php
namespace App\repository;
use PDO;
class ProductRepository{
    public function __construct(Private PDO $PDO)
    {}
    public function create($product){
        $sql = "INSERT INTO products (name, price) VALUES (:name, :price)";
        
        $stmt = $this->PDO->prepare($sql);
        
        $stmt->execute([
            ':name'  => $product->name,
            ':price' => $product->price
        ]);
        
        return (int)$this->PDO->lastInsertId();
    }

    public function update(int $id, float $newPrice): bool {
        $sql = "UPDATE products SET price = :price WHERE id = :id";
        
        $stmt = $this->PDO->prepare($sql);
        
        $stmt->execute([
            ':id'    => $id,
            ':price' => $newPrice
        ]);
        return $stmt->rowCount() > 0;
    }

    public function find(int $id): bool {
        $stmt = $this->PDO->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return false;
        return true;
    }

}
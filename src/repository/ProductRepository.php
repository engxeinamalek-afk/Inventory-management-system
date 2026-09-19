<?php
namespace App\repository;

use Exception;
use PDO;
class ProductRepository{
    public function __construct(Private PDO $PDO)
    {}
    // انشاء منتج
    public function create($product){
        $sql = "INSERT INTO products (name, price) VALUES (:name, :price)";
        
        $stmt = $this->PDO->prepare($sql);
        
        $stmt->execute([
            ':name'  => $product->name,
            ':price' => $product->price
        ]);
        
        return (int)$this->PDO->lastInsertId();//ما رح توصل لهون اذا ما تمت العملية بنجاح لانو excute بيعمل exception    
}

    // تعديل السعر
    public function update(int $id, float $newPrice){
        $sql = "UPDATE products SET price = :price WHERE id = :id";
        
        $stmt = $this->PDO->prepare($sql);
        
        $stmt->execute([
            ':id'    => $id,
            ':price' => $newPrice
        ]);
    }

    // التحقق من وجود منتج
    public function find(int $id): bool {
        $stmt = $this->PDO->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            throw new \Exception("Product with ID {$id} not found.");
        }
        return true;
    }

    public function getPrice(int $id) {
        $stmt = $this->PDO->prepare("SELECT price FROM products WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (float) $row['price'];
    }


}
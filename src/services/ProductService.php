<?php
namespace App\services;

use App\entities\Product;
use PDO;

class ProductService{
    public function __construct(private $productRepo, private $inventoryRepo, private PDO $pdo)
    {}
    public function store(Product $product) {
        try {
            $this->pdo->beginTransaction();

            $id = $this->productRepo->create($product);
            $this->inventoryRepo->create($id);

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            
            throw $e; 
        }
    }

}
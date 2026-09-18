<?php
namespace App\services;

use App\entities\Product;

class ProductService{
    public function __construct(private $productRepo, private $inventoryRepo)
    {}
    public function store(Product $product){
        $id= $this->productRepo->create($product);
        $this->inventoryRepo->create($id);
    }
}
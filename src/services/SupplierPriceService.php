<?php
namespace App\services;

use App\repository\ProductRepository;
use App\repository\SupplierRepository;
use PDO;

class SupplierPriceService{
    public function __construct(private ProductRepository $productRepo,
                                private SupplierRepository $supplierRepo)
    {}
    public function setPrice($productId, $supplierId, $price) {
        $this->productRepo->find($productId);
        $this->supplierRepo->find($supplierId);
        $this->supplierRepo->add($productId, $supplierId, $price);
    }
}
<?php
namespace App\entities;

class Transaction{
    public function __construct(
        public int $productId,
        public string $type, // 'sale' or 'purchase'
        public int $quantity,
        public float $unitPrice,
        public float $totalPrice,
        public ?int $supplierId = null,
        public ?string $date = null,
        public ?int $id = null
    ) {
    }
}

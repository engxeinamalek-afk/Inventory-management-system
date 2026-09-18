<?php
namespace App\entities;

class Transaction{
    public function __construct(
        public int $productId,
        public string $type, // 'sale' or 'purchase'
        public int $quantity,
        public float $unitPrice,
        public ?int $supplierId = null,
        public ?float $totalPrice = null,
        public ?string $date = null,
        public ?int $id = null
    ) {
        if ($this->totalPrice === null) {
            $this->totalPrice = $this->quantity * $this->unitPrice;
        }
        if ($this->date === null) {
            $this->date = date('Y-m-d H:i:s');
        }
    }
}

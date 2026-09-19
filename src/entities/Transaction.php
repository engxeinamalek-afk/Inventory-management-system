<?php
namespace App\entities;
use App\entities\enums\TransactionType;
class Transaction{
    public function __construct(
        public int $productId,
        public TransactionType $type, // 'sale' or 'purchase'
        public int $quantity,
        public ?int $supplierId = null,
        public ?float $unitPrice =null,
        public ?float $totalPrice = null,
        public ?string $date = null,
        public ?int $id = null
    ) {
        if ($this->date === null) {
            $this->date = date('Y-m-d H:i:s');
        }
    }

    public function setTotelPrice(){
        $this->totalPrice = $this->quantity * $this->unitPrice;
    }
}

<?php
namespace App\entities;

class Product {
    public function __construct(
        public string $name,
        public float $price,
        public ?int $id = null
    ) { }
}

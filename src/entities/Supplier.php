<?php
namespace App\entities;

class Supplier{
    public function __construct(
        public string $name,
        public string $phone,
        public ?int $id = null
    ) { }
}
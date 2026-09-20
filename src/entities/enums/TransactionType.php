<?php
namespace App\entities\enums;
enum TransactionType: string {
    case SALE = 'sale';
    case PURCHASE = 'purchase';

    public function extractSupplierId(array $data): ?int 
    {
        return match ($this) {
            self::PURCHASE => $data['supplier_id'] ?? null,
            self::SALE => null,
        };
    
    }
}
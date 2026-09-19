<?php
namespace App\factories;

use App\entities\Transaction;
use App\entities\enums\TransactionType;

class TransactionFactory 
{
    public static function createFromArray(array $data, int $id): Transaction 
    {
        $typeEnum = TransactionType::from($data['type']);

        return new Transaction($id,
                                $typeEnum,
                                $data['quantity'],
                                $data['supplier_id']);
    }
}
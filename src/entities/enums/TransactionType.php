<?php
namespace App\entities\enums;
enum TransactionType: string {
    case SALE = 'sale';
    case PURCHASE = 'purchase';
}
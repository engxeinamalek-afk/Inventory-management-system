<?php
use App\controllers\ProductController;
use App\controllers\SupplierController;
use App\controllers\TransactionController;

return [
    'POST' => [
        '/add' => ProductController::class.'@store',
        '/update/{id}' => ProductController::class.'@update',
        '/addsupplier' => SupplierController::class.'@store',
        '/setPrice/{productId}/{supplierId}' => SupplierController::class.'@setPrice',
        '/addTransaction/{id}' => TransactionController::class.'@store'
    ],
    'GET' => [
        '/getPurchase' => TransactionController::class.'@getPurchases',
        '/getSales' => TransactionController::class.'@getSales'
    ]
];
<?php
use App\controllers\ProductController;
return [
    'POST' => [
        '/add' => ProductController::class.'@store',
        '/update/{id}' => ProductController::class.'@update'
    ]
];
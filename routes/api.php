<?php
use App\controllers\ProductController;
return [
    'POST' => [
        '/add' => ProductController::class.'@store'
    ]
];
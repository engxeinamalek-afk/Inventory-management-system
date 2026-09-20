<?php

use App\controllers\ProductController;
use App\controllers\SupplierController;
use App\controllers\TransactionController;
use App\core\Container; 
use App\core\Database;
use App\exceptions\ValidationException;
use App\factories\TransactionStrategyFactory;
use App\repository\ProductRepository;
use App\repository\InventoryRepository;
use App\repository\SupplierRepository;
use App\repository\TransactionRepository;
use App\services\ProductService;
use App\services\SupplierPriceService;
use App\services\TransactionService;
use App\services\Validator;
use App\strategies\PurchaseTransactionStrategy;
use App\strategies\SaleTransactionStrategy;

$container = new Container();

$container->set('PDO', function($c) {
    $dbFactory = new Database();
    return $dbFactory->connect(); 
});
// Repositories
$container->set(ProductRepository::class, function($c) {
    return new ProductRepository($c->get('PDO')); 
});
$container->set(InventoryRepository::class, function($c) {
    return new InventoryRepository($c->get('PDO')); 
});
$container->set(SupplierRepository::class, function($c) {
    return new SupplierRepository($c->get('PDO')); 
});
$container->set(TransactionRepository::class, function($c) {
    return new TransactionRepository($c->get('PDO')); 
});

// Services
$container->set(ProductService::class, function($c){
    return new ProductService( $c->get(ProductRepository::class),
                               $c->get(InventoryRepository::class),
                               $c->get('PDO'));
});
$container->set(SupplierPriceService::class, function($c){
    return new SupplierPriceService( $c->get(ProductRepository::class),
                                     $c->get(SupplierRepository::class));
});
$container->set(TransactionService::class, function($c){
    return new TransactionService( $c->get(ProductRepository::class) ,
                                    $c->get(TransactionRepository::class),
                                    $c->get(TransactionStrategyFactory::class),
                                    $c->get('PDO') );
});
$container->set(Validator::class, function($c){
    return new Validator();
});


// Strategies
$container->set(TransactionStrategyFactory::class, function($c){
    return new TransactionStrategyFactory($c->get(SaleTransactionStrategy::class),
                                            $c->get(PurchaseTransactionStrategy::class));
});

$container->set(PurchaseTransactionStrategy::class, function($c){
    return new PurchaseTransactionStrategy($c->get(SupplierRepository::class),
                                            $c->get(InventoryRepository::class));
});

$container->set(SaleTransactionStrategy::class, function($c){
    return new SaleTransactionStrategy($c->get(InventoryRepository::class),
                                            $c->get(ProductRepository::class));
});

//controllers
$container->set(TransactionController::class, function($c){
    return new TransactionController($c->get(TransactionService::class),
        $c->get(Validator::class),
        $c->get(TransactionStrategyFactory::class),
        $c->get(TransactionRepository::class));
});

$container->set(SupplierController::class, function($c){
    return new SupplierController($c->get(SupplierRepository::class),
                                    $c->get(SupplierPriceService::class),
                                    $c->get(Validator::class));
});

$container->set(ProductController::class, function($c){
    return new ProductController($c->get(ProductService::class),
                                $c->get(ProductRepository::class),
                                $c->get(Validator::class));
});
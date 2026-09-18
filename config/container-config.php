<?php
use App\core\Container; 
use App\core\Database;
use App\repository\ProductRepository;
use App\repository\InventoryRepository;
use App\repository\SupplierRepository;
use App\repository\TransactionRepository;
use App\services\ProductService;
use App\services\SupplierPriceService;
use App\services\TransactionService;
$container = new Container();

$container->set('PDO', function($c) {
    $dbFactory = new Database();
    return $dbFactory->connect(); 
});

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
$container->set(ProductService::class, function($c){
    return new ProductService( $c->get(ProductRepository::class) ,$c->get(InventoryRepository::class) );
});
$container->set(SupplierPriceService::class, function($c){
    return new SupplierPriceService( $c->get(ProductRepository::class) ,$c->get(SupplierRepository::class) );
});
$container->set(TransactionService::class, function($c){
    return new TransactionService( $c->get(ProductRepository::class) ,
                                    $c->get(SupplierRepository::class),
                                    $c->get(TransactionRepository::class),
                                    $c->get(InventoryRepository::class) );
});
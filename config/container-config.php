<?php
use App\core\Container; 
use App\core\Database;
use App\repository\ProductRepository;
use App\repository\InventoryRepository;
use App\repository\SupplierRepository;
use App\services\ProductService;
use App\services\SupplierPriceService;

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
$container->set(ProductService::class, function($c){
    return new ProductService( $c->get(ProductRepository::class) ,$c->get(InventoryRepository::class) );
});
$container->set(SupplierPriceService::class, function($c){
    return new SupplierPriceService( $c->get(ProductRepository::class) ,$c->get(SupplierRepository::class) );
});
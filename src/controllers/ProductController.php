<?php
namespace App\controllers;

use App\entities\Product;
use App\repository\ProductRepository;
use App\repository\InventoryRepository;
use App\services\ProductService;

class ProductController{
    private ProductService $service;
    private ProductRepository $repo;
    public function __construct(private $container)
    {
        $this->service= $container->get(ProductService::class);
        $this->repo= $container->get(ProductRepository::class);
    }
    // اضافة منتج
    public function store($request){
        // اضافة منتج واضافة كمية ابتدائية => سيرفس مع ترانساكشن
        $product= new Product($request['name'] , $request['price']);
        $this->service->store($product);
    }
    // تعديل سعر منتج
    public function update($request , $id){
        $this->repo->update($id, $request['newPrice']);
    }
}
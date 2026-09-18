<?php
namespace App\controllers;

use App\repository\SupplierRepository;
use App\entities\Supplier;
use App\services\SupplierPriceService;

class SupplierController{
    private SupplierRepository $repo;
    private SupplierPriceService $service;
    public function __construct($container)
    {
        $this->repo= $container->get(SupplierRepository::class);
        $this->service= $container->get(SupplierPriceService::class);
    }
    //اضافة مصدر
    public function store($request){
        $supplier= new Supplier($request['name'] , $request['phone']);
        $this->repo->create($supplier);
    }
    // تحديد سعر منتج من مصدر
    public function setPrice(array $request ,$productId , $supplierId){
        // التحقق من وجود المنتج والمصدر => سيرفس
        $this->service->setPrice($productId, $supplierId , $request['price']);
    }
}
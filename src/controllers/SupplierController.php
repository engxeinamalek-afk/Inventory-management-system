<?php
namespace App\controllers;

use App\repository\SupplierRepository;
use App\entities\Supplier;
use App\services\SupplierPriceService;
use Exception;

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
        try{
            $this->repo->create($supplier);
            return [
                "status" => 201,
                "success" => true,
                "message" => "Supplier created successfully!"
            ];
        }catch (\PDOException $e) {
            return [
                "status"  => 500,
                "success" => false,
                "message" => "A database error occurred."
            ];
        }catch(Exception $e){
            return [
                "status" => 500,
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }
    // تحديد سعر منتج من مصدر
    public function setPrice(array $request ,$productId , $supplierId){
        // التحقق من وجود المنتج والمصدر => سيرفس
        try{
            $this->service->setPrice($productId, $supplierId , $request['price']);
            return [
                "status" => 201,
                "success" => true,
                "message" => "Price saved successfully!"
            ];
        }catch (\PDOException $e) {
            return [
                "status"  => 500,
                "success" => false,
                "message" => "A database error occurred."
            ];
        }catch(Exception $e){
            return [
                "status" => 500,
                "success" => false,
                "message" => $e->getMessage()
            ];            
        }
    }
}
<?php
namespace App\controllers;

use App\repository\SupplierRepository;
use App\entities\Supplier;
use App\services\SupplierPriceService;
use App\services\Validator;
use App\exceptions\ValidationException;
use Exception;

class SupplierController{
    private SupplierRepository $repo;
    private SupplierPriceService $service;
    private Validator $validator;
    public function __construct($container)
    {
        $this->repo= $container->get(SupplierRepository::class);
        $this->service= $container->get(SupplierPriceService::class);
        $this->validator= $container->get(Validator::class);
    }
    //اضافة مصدر
    public function store($request){
        try{
            $this->validator->validateOrFail($request, [
                "name" => "required|string",
                "phone" => "required|string"
            ]);
            $supplier= new Supplier($request['name'] , $request['phone']);

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
        }catch(ValidationException $e){
            return [
                "status" => 422,
                "success" => false,
                "message" => $e->getErrors()
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
        try{
            $this->validator->validateOrFail($request,[
                "price" => "required|float"
            ]);
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
        }catch(ValidationException $e){
            return [
                "status" => 422,
                "success" => false,
                "message" => $e->getErrors()
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
<?php
namespace App\controllers;

use App\entities\Product;
use App\exceptions\ValidationException;
use App\repository\ProductRepository;
use App\services\Validator;
use App\services\ProductService;
use Exception;

class ProductController{
    private ProductService $service;
    private ProductRepository $repo;
    private Validator $validator;
    public function __construct(private $container)
    {
        $this->service= $container->get(ProductService::class);
        $this->repo= $container->get(ProductRepository::class);
        $this->validator= $container->get(Validator::class);
    }
    // اضافة منتج
    public function store($request){
        //هون لازم اعمل فاليديشن
        try{
            $this->validator->validateOrFail($request, [
                "name" => "required|string",
                "price" => "required|float"
            ]);
            $product= new Product($request['name'] , $request['price']);

            $this->service->store($product);
            return [
                "status" => 201,
                "success" => true,
                "message" => "Product saved successfully!"
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
                "massage" => $e->getMessage()
            ];
        }
    }


    // تعديل سعر منتج
    public function update($request , $id){
        // هون كمان فاليديت
        try{
            $this->repo->find($id);
            $this->validator->validateOrFail($request, [
                "newPrice" => "required|float"
            ]);
            $this->repo->update($id, $request['newPrice']);
            return [
                "status" => 200,
                "success" => true,
                "message" => "Price updated successfully!"
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
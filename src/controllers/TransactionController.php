<?php
namespace App\controllers;

use App\repository\TransactionRepository;
use App\entities\Transaction;
use App\repository\ProductRepository;
use App\services\TransactionService;

class TransactionController{
    private TransactionRepository $repo;
    private ProductRepository $productRepo;
    private TransactionService $service;
    public function __construct($container)
    {
        $this->repo= $container->get(TransactionRepository::class);
        $this->productRepo= $container->get(ProductRepository::class);
        $this->service= $container->get(TransactionService::class);
    }
    //عمليات البيع والشراء
    public function store(array $request, $id){
        $tarnsaction= new Transaction($id,
                                    $request['type'],
                                    $request['quantity'],
                                    $request['supplier_id']);
        try{
            $this->service->store($tarnsaction);
            return [
                "status" => 201,
                "success" => true,
                "message" => "Transaction created successfully!"
            ];
        }catch (\PDOException $e) {
            return [
                "status"  => 500,
                "success" => false,
                "message" => "A database error occurred while processing the transaction."
            ];

        } catch (\Exception $e) {
            return [
                "status"  => 400,
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
        
    }
}
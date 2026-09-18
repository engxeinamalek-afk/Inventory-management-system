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
        // اضافة سجل وتحديث الكمية => سيرفس وترانزاكشن
        $price= $this->productRepo->getPrice($id);
        $tarnsaction= new Transaction($id,
                                    $request['type'],
                                    $request['quantity'],
                                    $price,
                                    $request['supplier_id']);
        $this->service->store($tarnsaction);
        
    }
}
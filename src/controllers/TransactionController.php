<?php
namespace App\controllers;

use App\entities\Transaction;
use App\services\TransactionService;
use App\services\Validator;
use App\entities\enums\TransactionType;

use App\exceptions\ValidationException;
use App\factories\TransactionFactory;

class TransactionController{
    private TransactionService $service;
    private Validator $validator;
    public function __construct($container)
    {
        $this->service= $container->get(TransactionService::class);
        $this->validator= $container->get(Validator::class);
    }
    //عمليات البيع والشراء
    public function store(array $request, $id){
        try{
            $types = implode(',', array_column(TransactionType::cases(), 'value'));
            $this->validator->validateOrFail($request,[
                "type" => "required|string|in:".$types,
                "quantity" => "required|integer",
                "supplier_id" => "required|integer"
            ]);
            $tarnsaction= TransactionFactory::createFromArray($request, $id);

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

        }catch(ValidationException $e){
            return [
                "status" => 422,
                "success" => false,
                "message" => $e->getErrors()
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
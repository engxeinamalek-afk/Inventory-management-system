<?php
namespace App\controllers;
use App\services\TransactionService;
use App\services\Validator;
use App\entities\enums\TransactionType;

use App\exceptions\ValidationException;
use App\factories\TransactionFactory;
use App\factories\TransactionStrategyFactory;
use App\repository\TransactionRepository;

class TransactionController{

    public function __construct(private TransactionService $service,
                                private Validator $validator,
                                private TransactionStrategyFactory $factory,
                                private TransactionRepository $repo){}
    //عمليات البيع والشراء
    public function store(array $request, $id){
        try{
            $types = implode(',', array_column(TransactionType::cases(), 'value'));
            $rules = [
                "type"     => "required|string|in:" . $types,
                "quantity" => "required|integer",
            ];

            $strategy = $this->factory->make($request['type'] ?? '');
            $rules = array_merge($rules, $strategy->validateRules());

            $this->validator->validateOrFail($request, $rules);

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

    public function getPurchases() 
    {
        $purchases = $this->repo->getPurchases();
        
        return [
            'status' => 'success',
            'data'   => $purchases
        ];
    }

    public function getSales() 
    {
        $sales = $this->repo->getSales();
        
        return [
            'status' => 'success',
            'data'   => $sales
        ];
    }
}
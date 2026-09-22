# Inventory Management System

A System API built with PHP and MySQL. The system provides a structured backend for managing products and inventory data, using a layered architecture with PDO for database access and Composer for dependency management.

## Problems It Solves

- Records product information and allows product prices to be updated.
- Records available suppliers and their products.
- Defines the purchase price of a product from a specific supplier.
- Records sales and purchase transactions.
- Automatically updates the available stock quantity after transactions.

## Technologies Used

- PHP
- MySQL
- PDO
- Composer
- PSR-4 Autoloading
- JSON

## API Endpoints

### Product

```
Add Product
POST /add
```

```
Update Product Price
POST /update/{id}
```


### Supplier

```
Add Supplier
POST /addsupplier
```

```
Set Product Price for a Supplier
POST /setPrice/{productId}/{supplierId}
```


### Transactions
```
Add Transaction
POST /addTransaction/{id}
```

```
Get Purchases
GET /getPurchase
```

```
Get Sales
GET /getSales
```

## SOLID Principles Implementation

This project applies SOLID principles directly within its architecture:

- **Single Responsibility Principle (SRP)** : Isolated responsibilities across distinct layers, example: `Validator` handles request validation, `TransactionRepository` executes SQL queries, `TransactionFactory` instantiates transaction entities, `TransactionStrategyFactory` resolves process strategies, and `TransactionService` orchestrates the core business logic flow.

- **Open/Closed Principle (OCP)**: Transaction types are processed using dedicated strategy classes (`PurchaseTransactionStrategy`, `SaleTransactionStrategy`). New transaction types can be seamlessly introduced by implementing `TransactionStrategyInterface` without modifying existing service logic.

- **Liskov Substitution Principle (LSP)** : All strategy implementations fulfill the contract defined by `TransactionStrategyInterface`. This allows `TransactionStrategyFactory` to instantiate and substitute any concrete strategy at runtime without breaking system behavior or expectations.  

- **Interface Segregation Principle (ISP)**: Features a lean and highly focused `TransactionStrategyInterface` with minimal methods (`validateRules`, `process`), preventing implementing classes from being forced to depend on unused methods.

- **Dependency Inversion Principle (DIP)** : Core business logic relies on abstractions (`TransactionStrategyInterface`) rather than concrete implementations. Controller and Service dependencies are automatically resolved and injected via constructor injection using a custom DI Container, while repositories currently utilize concrete implementations for simplicity. 

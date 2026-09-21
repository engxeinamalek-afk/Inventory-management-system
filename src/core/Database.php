<?php
namespace App\core;
use PDO, Exception, PDOException;

class Database {
    public function __construct(
        private string $host = 'localhost',
        private string $db   = 'store_db',
        private string $user = 'root',
        private string $pass = ''
    ) {}

    public function connect(): PDO {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";       
        try {
            return new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }
}

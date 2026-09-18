<?php
namespace App\core;

use Exception;

class Container 
{
    private array $bindings = [];
    // الكائنات التي تم انشاؤها
    private array $instances = [];

    public function set($key, callable  $resolver): void //bind
    {
        $this->bindings[$key] = $resolver;
    }

    public function get(string $key) //resolve
    {
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        if (!isset($this->bindings[$key])) {
            throw new Exception("Requested service [{$key}] is not registered in the container.");
        }

        $this->instances[$key] = $this->bindings[$key]($this);

        return $this->instances[$key];
    }

    public function has(string $key): bool {
        return isset($this->bindings[$key]) || isset($this->instances[$key]);
    }
}

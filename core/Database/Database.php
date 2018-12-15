<?php

namespace Core\Database;

use PDO;

class Database
{
    /**
     * @var Database|null
     */
    private static $singleton = null;

    /**
     * @var \PDO
     */
    private $connection;

    private $host;

    private $database;

    private $user;

    private $password;

    public function __construct(string $host, string $database, string $user, string $password = '')
    {
        $this->host = $host;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @param string $host
     * @param string $database
     * @param string $user
     * @param string $password
     * @return Database
     */
    public static function createSingleDatabaseConnection(string $host, string $database, string $user, string $password = ''): Database
    {
        if (self::$singleton === null) {
            self::$singleton = new self($host, $database, $user, $password);
        }

        return self::$singleton;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::$singleton, '_' . 'query'], $arguments);
    }

    public function connect(bool $force = false)
    {
        if (!$this->connection || $force) {
            $this->connection = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->user, $this->password);
            $this->connection->query('SET NAMES utf8');
        }
    }

    public function _query(string $query, array $data = [], bool $lazyExecution = true): DatabaseQuery
    {
        if (!$this->db) {
            $this->db->connect();
        }

        return new DatabaseQuery($this->connection, $query, $data, $lazyExecution);
    }
}

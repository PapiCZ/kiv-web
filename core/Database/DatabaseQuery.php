<?php

namespace Core\Database;

use Core\Database\Exceptions\DatabaseQueryException;
use MultipleQueryRunException;
use PDO;

class DatabaseQuery
{
    /**
     * @var PDO
     */
    private $connection;

    /**
     * @var string
     */
    private $query;

    /**
     * @var bool
     */
    private $lazyLoading;

    /**
     * @var array
     */
    private $data;

    private $queryExecuted = false;

    private $result;

    public function __construct(PDO $connection, string $query, array $data, bool $lazyExecution = true)
    {
        $this->query = $query;
        $this->data = $data;
        $this->lazyLoading = $lazyExecution;

        if (!$this->lazyLoading) {
            $this->runQuery();
        }

        $this->connection = $connection;
    }

    public function __call($name, $arguments)
    {
        if (!$this->queryExecuted && ($name == 'fetch' || $name == 'fetchAll')) {
            $this->execute();
        }

        return call_user_func_array([$this->result, $name], $arguments);
    }

    public function execute()
    {
        $this->runQuery();
    }

    private function runQuery()
    {
        if (!$this->queryExecuted) {
            $statement = $this->connection->prepare($this->query);

            if($statement) {
                $statement->setFetchMode(PDO::FETCH_ASSOC);

                $statement->execute($this->data);
                $this->queryExecuted = true;

                $this->result = $statement;
            }
        }
    }
}

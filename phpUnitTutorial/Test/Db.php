<?php

class Db
{
    protected $pdo;
    public function __construct()
    {
        $this->pdo = new \PDO('mysql:host='.HOST.';dbname='.DB, USER, PASSWORD);
        if(!$this->pdo)
        {
            return false;
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function execQuery($sql)
    {
        $count = $this->pdo->exec($sql);
        if ($count === false)
        {
            return false;
        }
        return $count;
    }
}
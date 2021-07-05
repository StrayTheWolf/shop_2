<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

class DatabaseConnection
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO('mysql:dbname=shop;host=127.0.0.1', 'root', 'Werewolf1989*');
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

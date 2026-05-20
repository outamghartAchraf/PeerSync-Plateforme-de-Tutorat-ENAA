<?php

namespace Src\Repositories;
require_once __DIR__ . "/../../config/DB.php";

class UserRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = \DB::connect();
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->pdo->prepare("SELECT *
            FROM users
            WHERE email = ?");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_OBJ);

        return $user ?: null;
    }

}
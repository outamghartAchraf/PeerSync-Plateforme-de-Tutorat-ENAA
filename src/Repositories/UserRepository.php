<?php

namespace Src\Repositories;
require_once __DIR__ . "/../../config/DB.php";
use PDO;

class UserRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = \DB::connect();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT users.*, roles.label AS role_name
        FROM users
        JOIN roles ON users.role_id = roles.id
        WHERE users.email = ?
    ");        $stmt->execute([$email]);

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $user ?: null;
    }

}
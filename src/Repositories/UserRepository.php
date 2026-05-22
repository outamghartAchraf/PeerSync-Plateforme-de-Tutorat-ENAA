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

    public function findByEmail(string $email): ?object
    {
        $stmt = $this->pdo->prepare("SELECT users.*, roles.label AS role_name
        FROM users
        JOIN roles ON users.role_id = roles.id
        WHERE users.email = ?
    ");        $stmt->execute([$email]);

        $user = $stmt->fetch(\PDO::FETCH_OBJ);

        return $user ?: null;
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->pdo->prepare("SELECT users.*, roles.label AS role_name
        FROM users
        JOIN roles ON users.role_id = roles.id
        WHERE users.id = ?");
        $stmt->execute([$id]);

        $user = $stmt->fetch(\PDO::FETCH_OBJ);

        return $user ;
    }

    public function incrementPoints(int $userId, int $points): bool
    {
        $stmt = $this->pdo->prepare('UPDATE users SET points = points + ? WHERE id = ?');
        return $stmt->execute([$points, $userId]);
    }

    public function allByRole(string $roleLabel): array
    {
        $stmt = $this->pdo->prepare("SELECT users.*, roles.label AS role_name
        FROM users
        JOIN roles ON users.role_id = roles.id
        WHERE roles.label = ?
        ORDER BY users.name ASC");
        $stmt->execute([$roleLabel]);

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function addPoints($userId, $points)
    {
        $sql = "UPDATE users 
            SET points = points + ?
            WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $points,
            $userId
        ]);
    }

        public function getById(int $id): ?object
    {
        $statement = $this->pdo->prepare("
            SELECT *
            FROM users
            WHERE id = ?
        ");

        $statement->execute([
            $id
        ]);

        $user = $statement->fetch(PDO::FETCH_OBJ);

        return $user ?: null;
    }

     public function updateProfile(
        int $id,
        string $name,
        string $email
    ): bool {

        $statement = $this->pdo->prepare("
            UPDATE users
            SET name = ?,
                email = ?
            WHERE id = ?
        ");

        return $statement->execute([
            $id,
            $name,
            $email
        ]);
    }

  

}
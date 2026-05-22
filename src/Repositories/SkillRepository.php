<?php

namespace Src\Repositories;

require_once __DIR__ . '/../../config/DB.php';

use DB;
use PDO;

class SkillRepository
{
   
    private $pdo;

    public function __construct()
    {
      
        $this->pdo = DB::connect();
    }

        public function getAllSkills(): array
    {
        $statement = $this->pdo->query("
            SELECT * FROM skills
            ORDER BY name ASC
        ");

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getUserSkills(int $userId, string $type): array
    {
        $statement = $this->pdo->prepare("
            SELECT skills.*
            FROM skills
            INNER JOIN user_skill
            ON skills.id = user_skill.skill_id
            WHERE user_skill.user_id = :user_id
            AND user_skill.type = :type
        ");

        $statement->execute([
            'user_id' => $userId,
            'type' => $type
        ]);

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    
}
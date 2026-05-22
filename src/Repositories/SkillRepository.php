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
            WHERE user_skill.user_id = ?
            AND user_skill.type = ?
        ");

        $statement->execute([
            $userId,
            $type
        ]);

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function addSkillToUser(
        int $userId,
        int $skillId,
        string $type
    ): bool {

        $statement = $this->pdo->prepare("
            INSERT INTO user_skill(user_id, skill_id, type)
            VALUES(?, ?, ?)
        ");

        return $statement->execute([
            $userId,
            $skillId,
            $type
        ]);
    }

    public function deleteSkill(
        int $userId,
        int $skillId,
        string $type
    ): bool {

        $statement = $this->pdo->prepare("
            DELETE FROM user_skill
            WHERE user_id = ?
            AND skill_id = ?
            AND type = ?
        ");

        return $statement->execute([
            $userId,
            $skillId,
            $type
        ]);
    }
}

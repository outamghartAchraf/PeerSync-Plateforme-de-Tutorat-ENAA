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

    
}
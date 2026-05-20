<?php

namespace Src\Services;
use Src\Repositories\UserRepository;

require_once __DIR__ . "/../../config/DB.php";
require_once __DIR__ . "/../Repositories/UserRepository.php";

class AuthService
{
    private $repo;

    public function __construct()
    {
        $this->repo = new UserRepository();
    }

    public function login(string $email, string $password): bool
    {
          if(empty($email) || empty($password)) {
              throw new \Exception("All fields are required");
          }
         $user = $this->repo->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        $_SESSION['user'] = $user;

        return true;
    }

}
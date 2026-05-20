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

        public function login(string $email, string $password)
    {
        $user = $this->repo->findByEmail($email);

        if (!$user) {
            return null;
        }

        if (password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }



}
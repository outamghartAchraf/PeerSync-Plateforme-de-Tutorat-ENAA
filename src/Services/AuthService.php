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

            if (password_verify($password, $user->password)) {
                $user->role = $user->role ?? ($user->role_name === 'admin' ? 'Tutor' : 'Student');
            return $user;
        }

        return null;
    }

     public function getUserById(int $id): ?object
    {
        return $this->repo->getById($id);
    }
     
    public function updateProfile(
        int $id,
        string $name,
        string $email
    ): bool {

        return $this->repo->updateProfile(
            $id,
            $name,
            $email
        );
    }


}
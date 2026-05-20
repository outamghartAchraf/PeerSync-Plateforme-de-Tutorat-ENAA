<?php

declare(strict_types=1);

namespace Src\Entities;

class User
{
    private string $name;
    private string $email;
    private string $password;
    private int $points;
    private bool $availability;
    private int $roleId;

    public function __construct(
        string $name,
        string $email,
        string $password,
        int $points = 0,
        bool $availability = true,
        int $roleId = 1
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->points = $points;
        $this->availability = $availability;
        $this->roleId = $roleId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function isAvailable(): bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): void
    {
        $this->availability = $availability;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }
}
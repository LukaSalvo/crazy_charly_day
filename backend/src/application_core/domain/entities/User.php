<?php

namespace toybox\core\domain\entities;

class User
{
    public function __construct(
        private string $id,
        private string $nom,
        private string $email,
        private string $passwordHash,
        private string $role = 'abonne'
    ) {}

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getEmail(): string { return $this->email; }
    public function getPasswordHash(): string { return $this->passwordHash; }
    public function getRole(): string { return $this->role; }
}

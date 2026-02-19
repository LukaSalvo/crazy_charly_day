<?php

namespace toybox\infra\repositories;

use PDO;
use toybox\core\domain\entities\User;

class UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT id, nom, mail, mdp, admin FROM utilisateur WHERE mail = :email');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $role = $row['admin'] ? 'admin' : 'abonne';
        return new User($row['id'], $row['nom'] ?? '', $row['mail'], $row['mdp'], $role);
    }

    public function create(string $email, string $passwordHash, string $nom = ''): User
    {
        $id = \Ramsey\Uuid\Uuid::uuid4()->toString();

        $stmt = $this->pdo->prepare(
            'INSERT INTO utilisateur (id, nom, mail, mdp, admin) VALUES (:id, :nom, :mail, :mdp, :admin)'
        );
        $stmt->execute([
            ':id'    => $id,
            ':nom'   => $nom,
            ':mail'  => $email,
            ':mdp'   => $passwordHash,
            ':admin' => 0,
        ]);

        return new User($id, $nom, $email, $passwordHash, 'abonne');
    }

}

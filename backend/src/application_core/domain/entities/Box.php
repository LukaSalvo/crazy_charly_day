<?php

namespace toybox\core\domain\entities;

use Ramsey\Uuid\Uuid;

class Box
{
    public function setId(string $id): void
    {
        $this->id = $id;
    }
    private string $id;
    private string $id_client;
    private float $poids;
    private float $prix;
    private int $score;

    /**
     * @param string $id_client
     * @param float $poids
     * @param float $prix
     * @param int $score
     */
    public function __construct(string $id_client, float $poids, float $prix, int $score)
    {
        $this->id = Uuid::uuid4();
        $this->id_client = $id_client;
        $this->poids = $poids;
        $this->prix = $prix;
        $this->score = $score;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getIdClient(): string
    {
        return $this->id_client;
    }

    public function getPoids(): float
    {
        return $this->poids;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function getScore(): int
    {
        return $this->score;
    }
}
<?php

namespace toybox\core\domain\entities;

use Ramsey\Uuid\Uuid;

class Campagne
{
    private string $id;
    private float $poids_max;
    private float $prix_min;
    private float $prix_max;

    /**
     * @param float $poids_max
     * @param float $prix_min
     * @param float $prix_max
     */
    public function __construct(float $poids_max, float $prix_min, float $prix_max)
    {
        $this->id = Uuid::uuid4();
        $this->poids_max = $poids_max;
        $this->prix_min = $prix_min;
        $this->prix_max = $prix_max;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPoidsMax(): float
    {
        return $this->poids_max;
    }

    public function getPrixMin(): float
    {
        return $this->prix_min;
    }

    public function getPrixMax(): float
    {
        return $this->prix_max;
    }

}
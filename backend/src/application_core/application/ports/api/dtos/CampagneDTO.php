<?php

namespace toybox\core\application\ports\api\dtos;

class CampagneDTO
{
    public string $id;
    public float $poids_max;
    public float $prix_min;
    public float $prix_max;

    public function __construct($id, $poids_max, $prix_min, $prix_max)
    {
        $this->id = $id;
        $this->poids_max = $poids_max;
        $this->prix_min = $prix_min;
        $this->prix_max = $prix_max;
    }
}
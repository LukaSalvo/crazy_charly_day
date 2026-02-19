<?php

namespace toybox\core\application\ports\api\dtos;

class ArticleDTO
{
    public string $id;
    public string $designation;
    public string $categorie;
    public string $age;
    public string $etat;
    public float $prix;
    public float $poids;

    public function __construct(string $id, string $designation, string $categorie, string $age, string $etat, float $prix, float $poids){
        $this->id = $id;
        $this->designation = $designation;
        $this->categorie = $categorie;
        $this->age = $age;
        $this->etat = $etat;
        $this->prix = $prix;
        $this->poids = $poids;
    }
}
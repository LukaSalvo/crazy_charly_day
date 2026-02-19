<?php

namespace toybox\core\domain\entities;

class Article
{
    private string $id;
    private string $designation;
    private int $id_categ;
    private int $id_age;
    private int $id_etat;
    private float $prix;
    private float $poids;

    public function __construct(string $id , string $designation, int $id_categ, int $id_age, int $id_etat, float $prix, float $poids){
        $this->id = $id;
        $this->designation = $designation;
        $this->id_categ = $id_categ;
        $this->id_age = $id_age;
        $this->id_etat = $id_etat;
        $this->prix = $prix;
        $this->poids = $poids;
    }

    public function getId():string{
        return $this->id;
    }

    public function getDesignation():string{
        return $this->designation;
    }

    public function getIdCategorie():int{
        return $this->id_categ;
    }

    public function getIdAge():int{
        return $this->id_age;
    }

    public function getIdEtat():int{
        return $this->id_etat;
    }

    public function getPrix():float{
        return $this->prix;
    }

    public function getPoids():float{
        return $this->poids;
    }
}
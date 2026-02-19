<?php

namespace toybox\core\application\ports\api\dtos;

class BoxDTO
{
    public $id;
    public $poids;
    public $prix;
    public $score;
    public array $articles;

    public function __construct($getId, $getPoids, $getPrix, $getScore, array $articlesDTO)
    {
        $this->id = $getId;
        $this->poids = $getPoids;
        $this->prix = $getPrix;
        $this->score = $getScore;
        $this->articles = $articlesDTO;
    }
}
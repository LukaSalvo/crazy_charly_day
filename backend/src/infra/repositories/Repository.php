<?php

namespace toybox\infra\repositories;

use PDO;

class Repository
{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function findAllArticles():array {
        $sql = "SELECT * FROM article";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $articles = [];
        foreach($rows as $article){
            $articles[]= new Article(
                $article['id'],
                $article['designation'],
                $article['id_categorie'],
                $article['id_age'],
                $article['id_etat'],
                $article['prix'],
                $article['poids']
            );
        }
        return $articles;
    }
}
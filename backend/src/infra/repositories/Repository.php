<?php

namespace toybox\infra\repositories;

use PDO;
use toybox\core\domain\entities\Age;
use toybox\core\domain\entities\Article;
use toybox\core\domain\entities\Categorie;
use toybox\core\domain\entities\Etat;

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
                $article['id_categ'],
                $article['id_age'],
                $article['id_etat'],
                $article['prix'],
                $article['poids']
            );
        }
        return $articles;
    }

    public function findCategorieById(int $id): Categorie{
        $sql = "SELECT * FROM categorie WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $categorie = new Categorie(
            $row['id'],
            $row['libelle']
        );
        return $categorie;
    }

    public function findAgeById(int $id): Age{
        $sql = "SELECT * FROM age WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $age = new Age($row['id'], $row['libelle']);
        return $age;
    }

    public function findEtatById(int $id): Etat{
        $sql = "SELECT * FROM etat WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $etat = new Etat($row['id'], $row['libelle']);
        return $etat;
    }
}
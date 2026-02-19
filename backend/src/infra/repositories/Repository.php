<?php

namespace toybox\infra\repositories;

use PDO;
use toybox\core\domain\entities\Age;
use toybox\core\domain\entities\Article;
use toybox\core\domain\entities\Categorie;
use toybox\core\domain\entities\Etat;
use toybox\core\domain\entities\User;

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

    public function deleteArticleById(string $id){
        try {
            $sql = "DELETE FROM article WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
        }catch (\Throwable $th){
            return false;
        }
        return true;
    }

    public function ajouterArticle(Article $article){
        $sql = "INSERT INTO article (id, designation, id_categ, id_age, id_etat, prix, poids) VALUES (:id, :designation, :id_categ, :id_age, :id_etat, :prix, :poids)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $article->getId(), PDO::PARAM_STR);
        $stmt->bindValue(':designation', $article->getDesignation(), PDO::PARAM_STR);
        $stmt->bindValue(':id_categ', $article->getIdCategorie(), PDO::PARAM_INT);
        $stmt->bindValue(':id_age', $article->getIdAge(), PDO::PARAM_INT);
        $stmt->bindValue(':id_etat', $article->getIdEtat(), PDO::PARAM_INT);
        $stmt->bindValue(':prix', $article->getPrix(), PDO::PARAM_STR);
        $stmt->bindValue(':poids', $article->getPoids(), PDO::PARAM_STR);
        $stmt->execute();
        return true;
    }

    public function findAllClient(){
        $sql = "SELECT * FROM utilisateur WHERE admin = FALSE";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $clients = [];
        foreach($rows as $row){
            $clients[] = new User(
                $row['id'],
                $row['nom'],
                $row['mail'],
                $row['mdp'],
                $row['admin']
            );
        }
        return $clients;
    }

    public function findCategoriesByClient($getId)
    {
        $sql = "SELECT * FROM client WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $getId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $categories = [$row['categ_1'], $row['categ_2'], $row['categ_3'], $row['categ_4'], $row['categ_5'], $row['categ_6']];
        return $categories;
    }

    public function findAgeByClient($getId)
    {
        $sql = "SELECT age FROM client WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $getId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['age'];
    }
}
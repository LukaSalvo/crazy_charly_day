<?php

namespace toybox\infra\repositories;

use Ramsey\Uuid\Uuid;
use PDO;
use toybox\core\domain\entities\Age;
use toybox\core\domain\entities\Article;
use toybox\core\domain\entities\Box;
use toybox\core\domain\entities\Campagne;
use toybox\core\domain\entities\Categorie;
use toybox\core\domain\entities\Client;
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

    public function findAllClients():array {
        $sql = "SELECT * FROM client";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $clients = [];
        foreach($rows as $client){
            $clients[]= new Client(
                $client['id'],
                $client['age'],
                $client['categ_1'],
                $client['categ_2'],
                $client['categ_3'],
                $client['categ_4'],
                $client['categ_5'],
                $client['categ_6']
            );
        }
        return $clients;
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
            $role = $row['admin'] ? 'admin' : 'abonne';
            $clients[] = new User(
                (string)$row['id'],
                (string)($row['nom'] ?? ''),
                (string)$row['mail'],
                (string)$row['mdp'],
                $role
            );
        }
        return $clients;
    }

    public function findCategoriesByClient($getId)
    {
        $sql = "SELECT * FROM client WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $getId, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!$row) return [null, null, null, null, null, null];
        $categories = [$row['categ_1'], $row['categ_2'], $row['categ_3'], $row['categ_4'], $row['categ_5'], $row['categ_6']];
        return $categories;
    }

    public function findAgeByClient($getId)
    {
        $sql = "SELECT age FROM client WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $getId, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        if (!$row) return null;
        return $row['age'];
    }

    public function supprimerUtilisateur(mixed $id)
    {
        $sql = "DELETE FROM utilisateur WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    }

    public function supprimerClient(mixed $id)
    {
        $sql = "DELETE FROM client WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    }
    public function createCampagne(Campagne  $campagne){
        $sql = "INSERT INTO campagne VALUES (:id, :poids, :prix_min, :prix_max)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $campagne->getId(), PDO::PARAM_STR);
        $stmt->bindValue(':poids', $campagne->getPoidsMax(), PDO::PARAM_STR);
        $stmt->bindValue(':prix_min', $campagne->getPrixMin(), PDO::PARAM_STR);
        $stmt->bindValue(':prix_max', $campagne->getPrixMax(), PDO::PARAM_STR);
        $stmt->execute();
    }

    public function createBox(Box $box){
        $sql = "INSERT INTO box VALUES (:id, :id_client, :poids, :prix, :score)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $box->getId(), PDO::PARAM_STR);
        $stmt->bindValue(':id_client', $box->getIdClient(), PDO::PARAM_STR);
        $stmt->bindValue(':poids', $box->getPoids(), PDO::PARAM_STR);
        $stmt->bindValue(':prix', $box->getPrix(), PDO::PARAM_STR);
        $stmt->bindValue(':score', $box->getScore(), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createBoxObj(string $id_box, string $id_obj){
        $sql = "INSERT INTO boxobj VALUES (:id_box, :id_obj)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_box', $id_box, PDO::PARAM_STR);
        $stmt->bindValue(':id_obj', $id_obj, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function findBoxObjByIdObj(string $id_obj):bool{
        $sql = "SELECT * FROM boxobj WHERE id_article = :id_obj";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_obj', $id_obj, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($row) > 0;
    }

    public function createBoxCampagne(string $id_box, string $id_campagne){
        $sql = "INSERT INTO boxcampagne VALUES (:id_box, :id_campagne)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_box', $id_box, PDO::PARAM_STR);
        $stmt->bindValue(':id_campagne', $id_campagne, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function findBoxByClientId(string $clientId): ?Box
    {
        $sql = "SELECT * FROM box WHERE id_client = :id_client LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_client', $clientId, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $box = new Box($row['id_client'], (float)$row['poids'], (int)$row['score'], (float)$row['prix']);
        $box->setId($row['id']);
        return $box;
    }

    public function findArticlesByBoxId(string $boxId): array
    {
        $sql = "SELECT a.* FROM article a 
                JOIN boxobj bo ON a.id = bo.id_article 
                WHERE bo.id_box = :id_box";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_box', $boxId, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach ($rows as $article) {
            $articles[] = new Article(
                $article['id'],
                $article['designation'],
                $article['id_categ'],
                $article['id_age'],
                $article['id_etat'],
                (float)$article['prix'],
                (float)$article['poids']
            );
        }
        return $articles;
    }

    public function findArticles(mixed $id_article)
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id_article, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        return new Article(
            $row['id'],
            $row['designation'],
            $row['id_categ'],
            $row['id_age'],
            $row['id_etat'],
            $row['prix'],
            $row['poids']
        );
    }

    public function validerBox(mixed $id)
    {
        $sql = "UPDATE box SET valide = TRUE WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    }

    public function findAllCampagnes()
    {
        $sql = "SELECT * FROM campagne";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $campagnes = [];
        foreach($rows as $campagne){
            $campagnes[] = new Campagne($campagne['poids_max'], $campagne['prix_min'], $campagne['prix_max'], $campagne['id']);
        }
        return $campagnes;
    }
}

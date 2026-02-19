<?php

namespace toybox\core\application\usecases;

use toybox\core\application\ports\api\dtos\AbonneDTO;
use toybox\core\application\ports\api\dtos\AjoutArticleDTO;
use Ramsey\Uuid\Uuid;
use toybox\core\application\ports\api\dtos\ArticleDTO;
use toybox\core\domain\entities\Article;
use toybox\core\domain\entities\Box;
use toybox\core\domain\entities\Campagne;
use toybox\core\domain\entities\Client;
use toybox\infra\repositories\Repository;

class Service
{
    private Repository $repository;

    public function __construct(Repository $repository){
        $this->repository = $repository;
    }

    public function listerArticles(){
        $articles = $this->repository->findAllArticles();
        $articlesDTO = [];
        foreach($articles as $article){
            $categorie = $this->repository->findCategorieById($article->getIdCategorie());
            $etat = $this->repository->findEtatById($article->getIdEtat());
            $age = $this->repository->findAgeById($article->getIdAge());
            $articlesDTO[] = new ArticleDTO($article->getId(),
                $article->getDesignation(),
                $categorie->getLibelle(),
                $age->getLibelle(),
                $etat->getLibelle(),
                $article->getPrix(),
                $article->getPoids());
        }
        return $articlesDTO;
    }

    public function supprimerArticle(string $id): bool
    {
        return $this->repository->deleteArticleById($id);
    }

    public function AjouterArticle(AjoutArticleDTO $articleDTO)
    {
        $article = new Article(
            $articleDTO->id,
            $articleDTO->designation,
            $articleDTO->categorie,
            $articleDTO->age,
            $articleDTO->etat,
            $articleDTO->prix,
            $articleDTO->poids
        );
        $this->repository->ajouterArticle($article);
        return true;
    }

    public function listerAbonnes(){
        $abonnes = $this->repository->findAllClient();
        $abonnesDTO = [];
        foreach($abonnes as $abonne){
            $categories = $this->repository->findCategoriesByClient($abonne->getId());
            $id_age = $this->repository->findAgeByClient($abonne->getId());
            $age = $this->repository->findAgeById($id_age)->getLibelle();
            $abonnesDTO[] = new AbonneDTO(
                $abonne->getId(),
                $abonne->getNom(),
                $abonne->getEmail(),
                $age,
                $categories);
        }
        return $abonnesDTO;
    }

	public function creerCampagne(float $poids, float $prix_min, float $prix_max){
        $clients = $this->repository->findAllClients();

        $campagne = new Campagne($poids, $prix_min, $prix_max);
        $this->repository->createCampagne($campagne);

        foreach ($clients as $client){
            if($client->getAbonne()){
                $box_id = $this->creerBox($client, $campagne);
                $this->repository->createBoxCampagne($box_id, $campagne->getId());
            }
        }
	}

    private function creerBox(Client $client, Campagne $campagne):string{
        $articles = $this->repository->findAllArticles();
        $score_total = 0;
        $poids_total = 0;
        $prix_total = 0;
        $possible = true;
        $id_box = Uuid::uuid4();
        while ($possible){
            $possible = false;
            $best_score = -100;
            $best_article = null;
            foreach ($articles as $article){
                if($poids_total + $article->getPoids() < $campagne->getPoidsMax() &&
                    $prix_total + $article->getPrix() < $campagne->getPrixMax() &&
                    $prix_total + $article->getPrix() > $campagne->getPrixMin() &&
                    !$this->repository->findBoxObjByIdObj($article->getIdObj()) &&
                    $client->getAge() == $article->getAge()
                ){
                    $new_score = $this->calculerScore($article, $client);
                    if($new_score > $best_score){
                        $best_score = $new_score;
                        $best_article = $article;
                        $possible = true;
                    }
                }
            }
            if($possible){
                $score_total += $best_score;
                $poids_total += $best_article->getPoids();
                $prix_total += $best_article->getPrix();
                $this->repository->createBoxObj($id_box, $best_article->getId());
            }
        }
        $box = new Box($client->getId(), $poids_total, $score_total, $prix_total);
        $box->setId($id_box);
        $this->repository->createBox($box);
        return 'test';
    }

    private function calculerScore(Article $article, Client $client):float {
        $score = 0;
        switch ($article->getIdCategorie()){
            case $client->getCateg1():
                $score += 10;
                break;
            case $client->getCateg2():
                $score += 8;
                break;
            case $client->getCateg3():
                $score += 6;
                break;
            case $client->getCateg4():
                $score += 4;
                break;
            case $client->getCateg5():
                $score += 2;
                break;
            case $client->getCateg6():
                $score += 1;
                break;
        }
        return $score;
    }
}

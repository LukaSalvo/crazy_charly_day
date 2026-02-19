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
use toybox\core\application\ports\api\dtos\BoxDTO;

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
            $age_label = 'Non renseigné';
            if ($id_age !== null) {
                try {
                    $age_obj = $this->repository->findAgeById((int)$id_age);
                    $age_label = $age_obj ? $age_obj->getLibelle() : 'Inconnu';
                } catch (\Exception $e) {
                    $age_label = 'Invalide';
                }
            }
            $abonnesDTO[] = new AbonneDTO(
                $abonne->getId(),
                $abonne->getNom(),
                $abonne->getEmail(),
                $age_label,
                $categories);
        }
        return $abonnesDTO;
    }

    public function supprimerAbonne(mixed $id)
    {
        $res2 = $this->repository->supprimerClient($id);
        $res = $this->repository->supprimerUtilisateur($id);
        return  ($res && $res2);
    }

	public function creerCampagne(float $poids, float $prix_min, float $prix_max){
        $clients = $this->repository->findAllClients();

        $campagne = new Campagne($poids, $prix_min, $prix_max);
        $this->repository->createCampagne($campagne);

        foreach ($clients as $client){
            $box_id = $this->creerBox($client, $campagne);
            $this->repository->createBoxCampagne($box_id, $campagne->getId());
        }
	}

    private function creerBox(Client $client, Campagne $campagne):string{
        $articles = $this->repository->findAllArticles();
        $score_total = 0;
        $poids_total = 0;
        $prix_total = 0;
        $possible = true;
        $articles_selected = [];
        while ($possible){
            $possible = false;
            $best_score = -100;
            $best_article = null;
            foreach ($articles as $article){
                if($poids_total + $article->getPoids() < $campagne->getPoidsMax() &&
                    $prix_total + $article->getPrix() < $campagne->getPrixMax() &&
                    $prix_total + $article->getPrix() > $campagne->getPrixMin() &&
                    !$this->repository->findBoxObjByIdObj($article->getId()) &&
                    $client->getAge() == $article->getIdAge()
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
                $articles_selected[] = $best_article;

            }
        }
        $box = new Box($client->getId(), $poids_total, $prix_total, $score_total);
        $this->repository->createBox($box);
        foreach ($articles_selected as $article){
            $this->repository->createBoxObj($box->getId(), $article->getId());
        }
        return $box->getId();
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

    public function listerBoxUser(string $clientId): array
    {
        $box = $this->repository->findBoxByClientId($clientId);
        if (!$box) return [];

        $articles = $this->repository->findArticlesByBoxId($box->getId());
        $articlesDTO = [];

        foreach($articles as $article){
            $categorie = $this->repository->findCategorieById($article->getIdCategorie());
            $etat = $this->repository->findEtatById($article->getIdEtat());
            $age = $this->repository->findAgeById($article->getIdAge());
            $articlesDTO[] = new ArticleDTO(
                $article->getId(),
                $article->getDesignation(),
                $categorie->getLibelle(),
                $age->getLibelle(),
                $etat->getLibelle(),
                $article->getPrix(),
                $article->getPoids()
            );
        }
        return $articlesDTO;
    }
}

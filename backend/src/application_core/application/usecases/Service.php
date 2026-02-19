<?php

namespace toybox\core\application\usecases;

use toybox\core\application\ports\api\dtos\AbonneDTO;
use toybox\core\application\ports\api\dtos\AjoutArticleDTO;
use toybox\core\application\ports\api\dtos\ArticleDTO;
use toybox\core\domain\entities\Article;
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

    public function supprimerAbonne(mixed $id)
    {
        $res2 = $this->repository->supprimerClient($id);
        $res = $this->repository->supprimerUtilisateur($id);
        return ($res && $res2);
    }

}
<?php

namespace toybox\core\application\usecases;

use toybox\core\application\ports\api\dtos\ArticleDTO;
use toybox\infra\repositories\Repository;

class Service
{
    private Repository $repository;

    public function __construct(Repository $repository){
        $this->repository = $repository;
    }

    public function ListerArticles(){
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

    public function SupprimerArticle(string $id): bool
    {
        return $this->repository->DeleteArticleById($id);
    }

}
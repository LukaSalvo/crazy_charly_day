<?php

namespace toybox\core\application\ports\api\dtos;

class AbonneDTO
{
    public string $id;
    public string $nom;
    public string $email;
    public string $age;
    public array $categories;

    public function __construct(string $id, string $nom, string $email, string $age, array $categories){
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->age = $age;
        $this->categories = $categories;
    }
}
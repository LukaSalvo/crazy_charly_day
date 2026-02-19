<?php

namespace toybox\core\domain\entities;

class Client
{
    private string $id;
    private int $age;
    private ?int $categ_1;
    private ?int $categ_2;
    private ?int $categ_3;
    private ?int $categ_4;
    private ?int $categ_5;
    private ?int $categ_6;

    public function __construct(string $id, int $age, ?int $categ_1, ?int $categ_2, ?int $categ_3, ?int $categ_4, ?int $categ_5, ?int $categ_6){
        $this->id = $id;
        $this->age = $age;
        $this->categ_1 = $categ_1;
        $this->categ_2 = $categ_2;
        $this->categ_3 = $categ_3;
        $this->categ_4 = $categ_4;
        $this->categ_5 = $categ_5;
        $this->categ_6 = $categ_6;
    }

    public function getId():string{
        return $this->id;
    }
    public function getAge():int{
        return $this->age;
    }
    public function getCateg1(){
        return $this->categ_1;
    }
    public function getCateg2(){
        return $this->categ_2;
    }
    public function getCateg3(){
        return $this->categ_3;
    }
    public function getCateg4(){
        return $this->categ_4;
    }
    public function getCateg5(){
        return $this->categ_5;
    }
    public function getCateg6(){
        return $this->categ_6;
    }
}

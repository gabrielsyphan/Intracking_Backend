<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Neighborhood extends DataLayer
{
    public function __construct()
    {
        parent::__construct("bairros", [], '', false);
    }
}

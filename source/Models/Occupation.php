<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Occupation extends DataLayer
{
    public function __construct()
    {
        parent::__construct("uso_de_solo", [], 'id', false);
    }
}

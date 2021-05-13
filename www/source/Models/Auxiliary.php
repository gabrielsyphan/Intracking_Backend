<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Auxiliary extends DataLayer
{
    public function __construct()
    {
        parent::__construct("auxiliares", [], 'id', false);
    }
}

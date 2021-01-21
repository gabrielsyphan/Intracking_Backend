<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Company extends DataLayer
{
    public function __construct()
    {
        parent::__construct("empresas", [], 'id', false);
    }
}

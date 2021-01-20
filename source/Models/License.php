<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class License extends DataLayer
{
    public function __construct()
    {
        parent::__construct("licencas", [], 'id', false);
    }
}

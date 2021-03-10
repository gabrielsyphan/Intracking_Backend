<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Role extends DataLayer
{
    public function __construct()
    {
        parent::__construct("tipo_fiscal", [], 'id', false);
    }
}

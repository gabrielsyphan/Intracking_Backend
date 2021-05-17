<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Salesman extends DataLayer
{
    public function __construct()
    {
        parent::__construct("ambulantes", [], 'id', false);
    }
}

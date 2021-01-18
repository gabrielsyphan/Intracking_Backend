<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Agent extends DataLayer
{
    public function __construct()
    {
        parent::__construct("fiscais", [], 'id', false);
    }
}

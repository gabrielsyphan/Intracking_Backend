<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Market extends DataLayer
{
    public function __construct()
    {
        parent::__construct("mercado", [], 'id', false);
    }
}

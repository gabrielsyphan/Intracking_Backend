<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Publicity extends DataLayer
{
    public function __construct()
    {
        parent::__construct("publicidade", ['descricao', 'tipo', 'id_licenca', 'latitude', 'longitude', 'dimensoes', 'tipo'], 'id', false);
    }
}
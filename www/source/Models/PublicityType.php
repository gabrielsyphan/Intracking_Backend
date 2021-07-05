<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class PublicityType extends DataLayer
{
    public function __construct()
    {
        parent::__construct("tipo_publicidade", ['nome, numero, valor, unidade'], 'id', false);
    }
}
<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Publicity extends DataLayer
{
    public function __construct()
    {
        parent::__construct("publicidade", ['descricao'], 'id', false);
    }

    public function getLicense()
    {
        $license = (new License())->findById($this->id_licenca);
        $this->license = $license;
    }
}
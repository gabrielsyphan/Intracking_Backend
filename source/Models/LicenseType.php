<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class LicenseType extends DataLayer
{
    public function __construct()
    {
        parent::__construct("tipo_licenca", [], 'id', false);
    }
}

<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class Zone extends DataLayer
{
    public function __construct()
    {
        parent::__construct("zonas", [], "id", "false");
    }
}

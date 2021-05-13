<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class AgentType extends DataLayer
{
    public function __construct()
    {
        parent::__construct("tipo_fiscal", [], "id", false);
    }
}

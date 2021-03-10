<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

class Punishment extends DataLayer
{
    public function __construct()
    {
        parent::__construct("penalidades", [], 'id', false);
    }
}

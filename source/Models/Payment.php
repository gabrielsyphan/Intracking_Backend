<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

class Payment extends DataLayer
{
    public function __construct()
    {
        parent::__construct("boletos", [], 'id', false);
    }
}

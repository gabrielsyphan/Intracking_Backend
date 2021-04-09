<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

class FoodTruck extends DataLayer
{
    public function __construct()
    {
        parent::__construct("food_trucks", [], 'id', false);
    }
}

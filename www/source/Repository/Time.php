<?php

namespace Source\Repository;

use CoffeeCode\DataLayer\DataLayer;

class Time extends DataLayer {
  
  public function __construct() {
      parent::__construct("TAB_TIME", ["name"], 'id', false);
  }
}

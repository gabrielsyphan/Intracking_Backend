<?php

namespace Source\Repository;

use CoffeeCode\DataLayer\DataLayer;

class Status extends DataLayer {
  
  public function __construct() {
      parent::__construct("TAB_STATUS", ["name"], 'id', false);
  }
}

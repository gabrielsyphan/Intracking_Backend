<?php

namespace Source\Repository;

use CoffeeCode\DataLayer\DataLayer;

class User extends DataLayer {
  
  public function __construct() {
      parent::__construct("TAB_USERS", ["name", "email", "password"], "id", false);
  }
}

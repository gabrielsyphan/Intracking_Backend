<?php

namespace Source\Repository;

use CoffeeCode\DataLayer\DataLayer;

class Task extends DataLayer {
  
  public function __construct() {
      parent::__construct("TAB_TASKS", ["title", "description", "cod_status"], 'id', false);
  }
}

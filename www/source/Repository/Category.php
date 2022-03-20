<?php

namespace Source\Repository;

use CoffeeCode\DataLayer\DataLayer;

class Category extends DataLayer {

  public function __construct() {
      parent::__construct("TAB_CATEGORIES", ["task_id", "name", "color"], 'id', false);
  }
}

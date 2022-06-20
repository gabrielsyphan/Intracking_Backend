<?php

namespace Source\Repository;

use CoffeeCode\DataLayer\DataLayer;

class TaskCategory extends DataLayer {
  
  public function __construct() {
      parent::__construct("TAB_TASKS_CATEGORIES", ["task_id", "category_id"], 'task_id', false);
  }
}

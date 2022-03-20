<?php

namespace Source\Repository;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\TaskDto;

class Task extends DataLayer {
  
  public function __construct() {
      parent::__construct("TAB_TASKS", ["user_id", "title", "description", "cod_status"], 'id', false);
  }

  public function saveByDto(UserDto $userDto) {
    try {
      $this->user_id = $userDto->user_id;
      $this->title = $userDto->title;
      $this->description = $userDto->description;
      $this->cod_status = $userDto->cod_status;
      $this->save();
    } catch (\Exception $e) {
      echo json_encode(["error" => $e->getMessage()]);
    }
  }

  public function updateByDto($taskId, UserDto $userDto) {
    $this->id = $taskId;
    $this->saveByDto();
  }
}

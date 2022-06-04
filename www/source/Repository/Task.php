<?php

namespace Source\Repository;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\TaskCategoryDto;
use Source\Models\TaskDto;

class Task extends DataLayer {
  
  public function __construct() {
      parent::__construct("TAB_TASKS", ["user_id", "title", "description", "cod_status"], 'id', false);
  }

  public function saveByDto(TaskDto $taskDto) : void {
    try { 
      if ($taskDto->getCategoryId()) {
        $category = (new Category())
        ->find("id = :id AND user_id = :userId", "id={$taskDto->getCategoryId()}&userId={$taskDto->getUserId()}")
        ->fetch(false);

        if (!$category) {
          echo json_encode(["error" => "Categoria não existe"]);
        }
      }

      $this->user_id = $taskDto->getUserId();
      $this->title = $taskDto->getTitle();
      $this->description = $taskDto->getDescription();
      $this->cod_status = $taskDto->getCodStatus();
      $this->save();
      
      if ($this->fail()) {
        $this->setPortInternalServerError();
        echo json_encode(["error" => $this->fail()->getMessage()]);
      }

      if ($taskDto->getCategoryId()) {
        $taskCategory = new TaskCategory();
        $taskCategory->task_id = $this->id;
        $taskCategory->category_id = $taskDto->getCategoryId();
        $taskCategory->save();

        if ($taskCategory->fail()) {
          $this->setPortInternalServerError();
          echo json_encode(["error" => $taskCategory->fail()->getMessage()]);
        }
      }

    } catch (\Exception $e) {
      $this->setPortInternalServerError();
      echo json_encode(["error" => $e->getMessage()]);
    }
  }

  public function insertCategory(TaskCategoryDto $dataTaskCategoryDto): void {
    try {
      $taskCategory = new TaskCategory();

      if ($taskCategory
        ->find("task_id = :tId AND category_id = :cId", "tId={$dataTaskCategoryDto->getTaskId()}&cId={$dataTaskCategoryDto->getCategoryId()}")
        ->fetch(false)) {
          $this->setPortInternalServerError();
          echo json_encode(["error" => "Essa task já possui a categoria informada."]);
          exit();
      }

      $taskCategory->task_id = $dataTaskCategoryDto->getTaskId();
      $taskCategory->category_id = $dataTaskCategoryDto->getCategoryId();
      $taskCategory->save();
      
      if ($taskCategory->fail()) {
        $this->setPortInternalServerError();
        echo json_encode(["error" => $this->fail()->getMessage()]);
      }

    } catch (\Exception $e) {
      $this->setPortInternalServerError();
      echo json_encode(["error" => $e->getMessage()]);
    }
  }

  private function setPortInternalServerError(): void {
    http_response_code(500);
  }

  public function updateByDto($taskId, TaskDto $taskDto): void {
    $this->id = $taskId;
    $this->saveByDto($taskDto);
  }
}

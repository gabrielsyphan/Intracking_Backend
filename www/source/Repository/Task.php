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
      $categoryFind = null;

      if ($taskDto->getCategoryId()) {
        $category = (new Category())
        ->find("id = :id AND user_id = :userId", "id={$taskDto->getCategoryId()}&userId={$taskDto->getUserId()}")
        ->fetch(false);

        if (!$category) {
          echo json_encode(["error" => "Categoria não existe"]);
          exit();
        }

        $categoryFind = $category;
      }

      $this->user_id = $taskDto->getUserId();
      $this->title = $taskDto->getTitle();
      $this->description = $taskDto->getDescription();
      $this->cod_status = $taskDto->getCodStatus();
      $this->deadline = $taskDto->getDeadline();

      if($taskDto->getOpeningDate()) {
        $this->opening_date = $taskDto->getOpeningDate();
      }

      if($taskDto->getFiniShingDate()) {
        $this->finishing_date = $taskDto->getFiniShingDate();
      }


      $this->save();
      
      if ($this->fail()) {
        $this->setPortInternalServerError();
        echo json_encode(["error" => $this->fail()->getMessage()]);
      }

      if ($taskDto->getCategoryId()) {
        $categories = (new TaskCategory)->find("task_id = :tId", "tId={$this->id}")->fetch(false);
        if ($categories) {
          foreach ($categories as $category) {
            $category->destroyd();
          }
        }

        $taskCategory = new TaskCategory();
        if(!$taskCategory->find("task_id = :tId AND category_id = :cId", "tId={$this->id}&cId={$taskDto->getCategoryId()}")->fetch(false)) {
          $taskCategory->task_id = $this->id;
          $taskCategory->category_id = $taskDto->getCategoryId();
          $taskCategory->save();
  
          if ($taskCategory->fail()) {
            $this->setPortInternalServerError();
            echo json_encode(["error" => $taskCategory->fail()->getMessage()]);
            exit();
          } 
        }
      }

      echo json_encode($this->taskConvert($this, $categoryFind));
    } catch (\Exception $e) {
      $this->setPortInternalServerError();
      echo json_encode(["error" => $e->getMessage()]);
    }
  }

  /**
   * @return array
   * Method to convert a task into a array to be converted to json
  */
  private function taskConvert($task, $category = null): array {
    $tasksToJson = [];
    if ($category) {
      $tasksToJson = [
        "id" => $task->id,
        "user_id" => $task->user_id,
        "title" => $task->title,
        "description" => $task->description,
        "deadline" => $task->deadline,
        "cod_status" => $task->cod_status,
        "opening_date" => $task->opening_date,
        "finishing_date" => $task->finishing_date,
        "categories" => ["id" => $category->id, "name" => $category->name, "color" => $category->color]
      ];
    } else {
      $tasksToJson = [
        "id" => $task->id,
        "user_id" => $task->user_id,
        "title" => $task->title,
        "description" => $task->description,
        "deadline" => $task->deadline,
        "cod_status" => $task->cod_status,
        "opening_date" => $task->opening_date,
        "finishing_date" => $task->finishing_date
      ];
    }

    return $tasksToJson;
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
        exit();
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

    $task = (new Task)->findById($taskId);
    if($task && $task->cod_status != 3 && $taskDto->getCodStatus() == 3) {
      $taskDto->setFinishingDate(date("Y-m-d H:i:s"));
    }

    $this->saveByDto($taskDto);
  }
}

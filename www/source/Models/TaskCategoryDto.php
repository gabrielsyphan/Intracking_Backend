<?php

namespace Source\Models;

/**
 * @author Gabriel Syphan
 * DTO for TaskCategoryDto
*/
class TaskCategoryDto {

  private $categoryId;
  private $taskId;

  public function __construct($data) {
    $this->categoryId = $data->categoryId;
    $this->taskId = $data->taskId;
  }

  public function getCategoryId(): int {
    return $this->categoryId;
  }

  public function setCategoryId($categoryId): void {
    $this->categoryId = $categoryId;
  }

  public function getTaskId(): int {
    return $this->taskId;
  }

  public function setTaskId($taskId): void {
    $this->taskId = $taskId;
  }
}
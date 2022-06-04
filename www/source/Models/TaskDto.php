<?php

namespace Source\Models;

/**
 * @author Gabriel Syphan
 * DTO for Task
*/
class TaskDto {

  private $userId;
  private $title;
  private $description;
  private $codStatus;
  private $categoryId;

  public function __construct($userId = null, $data) {
    $this->userId = $userId;
    $this->title = $data->title;
    $this->description = $data->description;
    $this->codStatus = $data->codStatus;
    $this->categoryId = $data->categoryId || null;
  }

  public function getUserId(): int {
    return $this->userId;
  }

  public function setUserId($userId): void {
    $this->userId = $userId;
  }

  public function getTitle(): String {
    return $this->title;
  }

  public function setTitle($title): void {
    $this->title = $title;
  }

  public function getDescription(): String {
    return $this->description;
  }

  public function setDescription($description): void {
    $this->description = $description;
  }

  public function getCodStatus(): int {
    return $this->codStatus;
  }

  public function setCodStatus($codStatus): void {
    $this->codStatus = $codStatus;
  }

  public function getCategoryId(): int {
    return $this->categoryId;
  }

  public function setCategoryId($categoryId): void {
    $this->categoryId = $categoryId;
  }
}
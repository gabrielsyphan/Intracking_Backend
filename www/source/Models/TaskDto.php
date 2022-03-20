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

  public function __construct($userId = null, $data) {
    $this->userId = $userId;
    $this->title = $data->title;
    $this->description = $data->description;
    $this->codStatus = $data->codStatus;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function setUserId($userId) {
    $this->userId = $userId;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDescription($description) {
    $this->description = $description;
  }

  public function getCodStatus() {
    return $this->codStatus;
  }

  public function setCodStatus($codStatus) {
    $this->codStatus = $codStatus;
  }
}
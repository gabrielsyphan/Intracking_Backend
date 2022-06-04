<?php

namespace Source\Models;

/**
 * @author Gabriel Syphan
 * DTO for Category
*/
class CategoryDto {

  private $userId;
  private $name;
  private $color;

  public function __construct($userId = null, $data) {
    $this->userId = $userId;
    $this->name = $data->name;
    $this->color = $data->color;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function setUserId($userId) {
    $this->userId = $userId;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getColor() {
    return $this->color;
  }

  public function setColor($color) {
    $this->color = $color;
  }
}
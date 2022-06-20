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
  private $deadline;
  private $openingDate = null;
  private $finiShingDate = null;

  public function __construct($userId = null, $data) {   
    $this->userId = $userId;
    $this->title = $data->title;
    $this->description = $data->description;
    $this->codStatus = $data->codStatus;
    $this->categoryId = $data->categoryId;
    $this->deadline = $data->deadline;

    if($data->finishing_date) {
      $this->finiShingDate = $data->finishing_date;
    }

    if($data->opening_date) {
      $this->openingDate = $data->opening_date;
    }
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

  public function getCategoryId() {
    return $this->categoryId;
  }

  public function setCategoryId($categoryId): void {
    $this->categoryId = $categoryId;
  }

  public function getDeadline() {
    return $this->deadline;
  }

  public function setDeadline($deadline): void {
    $this->deadline = $deadline;
  }

  public function getFiniShingDate() {
    return $this->finiShingDate;
  }

  public function setFiniShingDate($finiShingDate): void {
    $this->finiShingDate = $finiShingDate;
  }

  public function getOpeningDate() {
    return $this->openingDate;
  }

  public function setOpeningDate($openingDate): void {
    $this->openingDate = $openingDate;
  }
}
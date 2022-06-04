<?php

namespace Source\Repository;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\CategoryDto;

class Category extends DataLayer {

  public function __construct() {
      parent::__construct("TAB_CATEGORIES", ["name", "color"], 'id', false);
  }

  public function saveByDto(CategoryDto $categoryDto) : void {
    try { 
      $this->user_id = $categoryDto->getUserId();
      $this->name = $categoryDto->getName();
      $this->color = $categoryDto->getColor();
      $this->save();

      if($this->fail()){
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

  public function updateByDto($taskId, CategoryDto $categoryDto): void {
    $this->id = $taskId;
    $this->saveByDto($categoryDto);
  }
}

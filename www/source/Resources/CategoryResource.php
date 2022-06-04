<?php

namespace Source\Resources;

use CoffeeCode\Router\Router;
use Source\Repository\Category;
use Source\Resources\AuthenticationResource;
use Source\Models\CategoryDto;

/**
 * Class CategoryResource
 *
 * @package Source\Resources
*/
class CategoryResource {

  /**
   * @var Router
  */
  private $router;

  /**
   * @var Data
  */
  private $data;

  /**
   * @var User
  */
  public $userId;

  /**
   * @var Category
  */
  private $category;

  /**
   * Class constructor.
  */
  public function __construct($router) {
    $this->router = $router;
    $this->data = json_decode(file_get_contents("php://input"));
    $this->category = new Category();

    setlocale(LC_TIME, "pt_BR", "pt_BR.utf-8", "pt_BR.utf-8", "portuguese");
    date_default_timezone_set("America/Sao_Paulo");

    $authentication = (new AuthenticationResource($this->router));
    $authentication->validateSessionToken();

    if(!$authentication->getIsAuthenticated()) {
      exit();
    }
    
    $this->userId = $authentication->getUserId();
  }

  /**
   * @return void
   * Method to create new categories
   * POST Method /api/category
  */
  public function create(): void {
    $this->category->saveByDto(new CategoryDto($this->userId, $this->data));
  }

  /**
   * @return void
   * Method to update categories
   * PUT Method /api/category
  */
  public function update($data): void {
    $this->category->updateByDto($data["categoryId"], new CategoryDto($this->userId, $this->data));
  }

  /**
   * @return void
   * Method to delete categories
   * DELETE Method /api/category
  */
  public function delete(): void {
    $this->category->findById($this->data->id)->destroy();
  }

  /**
   * @return void
   * Method to delete all categories
   * DELETE Method /api/category/delete-all
  */
  public function deleteAll(): void {
    $categories = $this->category->find("user_id = :userId", "userId={$this->userId}")->fetch(true);

    if ($categories) {
      foreach($categories as $category) {
        try {
          $category->destroy();
        } catch (\Exception $e) {
          $this->setPortInternalServerError();
          echo json_encode(["error" => $e->getMessage()]);
        }
      }
    }
  }

  /**
   * @return void
   * Method to list categories
   * GET Method /api/category
  */
  public function listAll(): void {
    $categories = $this->category->find("user_id = :userId", "userId={$this->userId}")->fetch(true);
    $categoriesToJson = [];

    if ($categories) {
      foreach($categories as $category) {
        $categoriesToJson[] = $this->convertCategory($category);
      }
    }

    echo json_encode($categoriesToJson);
  }

  /**
   * @return void
   * Method to list categories by user
   * GET Method /api/category/{id}
  */
  public function listById($data): void {
    $category = $this->category->findById($data['categoryId']);
    if ($category) {
      $category = $this->convertCategory($category);
    }
    echo json_encode($category);
  }

  private function convertCategory($category): array {
    $categoriesToJson = [
      "id" => $category->id,
      "user_id" => $category->user_id,
      "name" => $category->name,
      "color" => $category->color
    ];

    return $categoriesToJson;
  }

  /**
   * @return void
   * Method to set http response port to 500
  */
  private function setPortInternalServerError(): void {
    http_response_code(500);
  }
}
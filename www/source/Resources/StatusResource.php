<?php

namespace Source\Resources;

use CoffeeCode\Router\Router;
use Source\Resources\AuthenticationResource;
use Source\Repository\Status;

/**
 * Class StatusResource
 *
 * @package Source\Status
*/
class StatusResource {

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
   * Class constructor.
  */
  public function __construct($router) {
    $this->router = $router;
    $this->data = json_decode(file_get_contents("php://input"));

    $authentication = (new AuthenticationResource($this->router));
    $authentication->validateSessionToken();

    if(!$authentication->getIsAuthenticated()) {
      exit();
    }
    
    $this->userId = $authentication->getUserId();
  }

  /**
   * @return void
   * Method to list status
   * GET Method /status
  */
  public function listAll(): void {
    $status = (new Status())->find()->fetch(true);
    $statusToJson = [];

    if ($status) {
      foreach($status as $stat) {
        $statusToJson[] = $stat->data();
      }
    }

    echo json_encode($statusToJson);
  }
}

<?php

namespace Source\Resources;

use CoffeeCode\Router\Router;
use Source\Resources\AuthenticationResource;
use Source\Repository\Time;

/**
 * Class TimeResource
 *
 * @package Source\Time
*/
class TimeResource {

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
   * Method to list all times
   * GET Method /time
  */
  public function listAll(): void {
    $times = (new Time())->find()->fetch(true);
    $timeToJson = [];

    if ($times) {
      foreach($times as $time) {
        $timeToJson[] = $time->data();
      }
    }

    echo json_encode($timeToJson);
  }
}

<?php

namespace Source\Resources;

use CoffeeCode\Router\Router;
use Source\Repository\User;
use Source\Repository\Task;
use Source\Models\TaskDto;
use Source\Resources\AuthenticationResource;

/**
 * Class TaskResource
 *
 * @package Source\Resources
*/
class TaskResource {

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
   * @var Task
  */
  private $task;

  /**
   * Class constructor.
  */
  public function __construct($router) {
    $this->router = $router;
    $this->data = json_decode(file_get_contents("php://input"));
    $this->task = new Task();

    setlocale(LC_TIME, "pt_BR", "pt_BR.utf-8", "pt_BR.utf-8", "portuguese");
    date_default_timezone_set("America/Sao_Paulo");

    $authentication = (new AuthenticationResource($this->router));
    $authentication->validateSessionToken();
    if($authentication->getIsAuthenticated()) {
      $this->userId = $authentication->getUserId();
    }
  }

  /**
   * @return void
   * Method to create new tasks
   * POST Method /api/task
  */
  public function create(): void {
    $this->task->saveByDto(new TaskDto($this->userId, $this->data));
  }

  /**
   * @return void
   * Method to update tasks
   * PUT Method /api/task
  */
  public function update(): void {
    $this->task->updateByDto($this->data()->id, new TaskDto($this->userId, $this->data));
  }

  /**
   * @return void
   * Method to delete tasks
   * DELETE Method /api/task
  */
  public function delete(): void {
    $this->task()->findById($this->data()->id)->delete();
  }

  /**
   * @return void
   * Method to list tasks
   * GET Method /api/task
  */
  public function listAll(): void {
    echo json_encode($this->task()->find()->fetch(true));
  }

  /**
   * @return void
   * Method to list tasks by user
   * GET Method /api/task/user
  */
  public function listByUser(): void {
    echo json_encode($this->task()->find("user_id = :userId", "userId={$this->userId}")->fetch(true));
  }

  /**
   * @return void
   * Method to list tasks by user
   * GET Method /api/task/{id}
  */
  public function listById($data): void {
    echo json_encode($this->task()->findById($data['taskId'])->fetch(false));
  }
}

<?php

namespace Source\Resources;

use CoffeeCode\Router\Router;
use Source\Repository\User;
use Source\Repository\Task;
use Source\Models\TaskDto;
use Source\Models\TaskCategoryDto;
use Source\Repository\Category;
use Source\Repository\TaskCategory;
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

    if(!$authentication->getIsAuthenticated()) {
      exit();
    }

    $this->userId = $authentication->getUserId();
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
  public function update($data): void {
    $this->task->updateByDto($data["taskId"], new TaskDto($this->userId, $this->data));
  }

  /**
   * @return void
   * Method to delete tasks
   * DELETE Method /api/task
  */
  public function delete(): void {
    $this->task->findById($this->data->id)->destroy();
  }

  /**
   * @return void
   * Method to delete all tasks
   * DELETE Method /api/task/delete-all
  */
  public function deleteAll(): void {
    $tasks = $this->task->find("user_id = :userId", "userId={$this->userId}")->fetch(true);

    if ($tasks) {
      foreach($tasks as $task) {
        try {
          $task->destroy();
        } catch (\Exception $e) {
          $this->setPortInternalServerError();
          echo json_encode(["error" => $e->getMessage()]);
        }
      }
    }
  }

  /**
   * @return void
   * Method to list tasks
   * GET Method /api/task
  */
  public function listAll(): void {
    $tasks = $this->task->find("user_id = :userId", "userId={$this->userId}")->fetch(true);
    $tasksToJson = [];

    if ($tasks) {
      foreach($tasks as $task) {
        $categories = [];

        $taskCategory = (new TaskCategory())->find("task_id = :id", "id={$task->id}")->fetch(true);
        if ($taskCategory) {
          foreach($taskCategory as $tCategory) {
            if ($tCategory) {
              $category = (new Category())->findById($tCategory->category_id);
              $categories[] = ["id" => $category->id, "name" => $category->name, "color" => $category->color];
            }
          }
        }

        $tasksToJson[] = $this->convertTask($task, $categories);
      }
    }

    echo json_encode($tasksToJson);
  }

  /**
   * @return void
   * Method to list tasks by user
   * GET Method /api/task/{id}
  */
  public function listById($data): void {
    $task = $this->task->findById($data['taskId']);
    if ($task) {
      $task = $this->convertTask($task);
    }
    echo json_encode($task);
  }

  /**
   * @return void
   * Method to insert a category into a task
   * POST Method /api/task/add-task-category
  */
  public function addTaskCategory(): void {
    $this->task->insertCategory(new TaskCategoryDto($this->data));
  }

  private function convertTask($task, $categories = null): array {
    if ($categories) {
      $tasksToJson = [
        "id" => $task->id,
        "user_id" => $task->user_id,
        "title" => $task->title,
        "description" => $task->description,
        "deadline" => $task->deadline,
        "cod_status" => $task->cod_status,
        "opening_date" => $task->opening_date,
        "finishing_date" => $task->finishing_date,
        "categories" => $categories
      ];
    } else {
      $tasksToJson = [
        "id" => $task->id,
        "user_id" => $task->user_id,
        "title" => $task->title,
        "description" => $task->description,
        "deadline" => $task->deadline,
        "cod_status" => $task->cod_status,
        "opening_date" => $task->opening_date,
        "finishing_date" => $task->finishing_date
      ];
    }

    return $tasksToJson;
  }

  /**
   * @return void
   * Method to set http response port to 500
  */
  private function setPortInternalServerError(): void {
    http_response_code(500);
  }
}

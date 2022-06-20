<?php

namespace Source\Resources;

use CoffeeCode\Router\Router;
use Source\Repository\User;
use Source\Repository\Task;
use Source\Models\TaskDto;
use Source\Models\TaskCategoryDto;
use Source\Repository\Category;
use Source\Repository\TaskCategory;
use Source\Repository\Time;
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
   * POST Method /task
  */
  public function create(): void {
    $this->data->opening_date = date("Y-m-d H:i:s");
    $this->task->saveByDto(new TaskDto($this->userId, $this->data));
  }

  /**
   * @return void
   * Method to update tasks
   * PUT Method /task
  */
  public function update($data): void {
    $this->task->updateByDto($data["taskId"], new TaskDto($this->userId, $this->data));
  }

  /**
   * @return void
   * Method to delete tasks
   * DELETE Method /task
  */
  public function delete($data): void {
    $task = (new Task)->find("id = :id AND user_id = :userId", "id={$data["taskId"]}&userId={$this->userId}")->fetch(false);

    if(!$task) {
      http_response_code(500);
      echo json_encode(["error" => "Essa task não existe"]);
      exit();
    }

    $taskCategory = (new TaskCategory)->find("task_id = {$data["taskId"]}")->fetch(true);
    if($taskCategory) {
      foreach($taskCategory as $tCategory) {
        $tCategory->destroy();
      }
    }

    $task->destroy();

    if($task->fail()) {
      http_response_code(500);
      echo json_encode(["error" => $task->fail()->getMessage()]);
    }
  }

  /**
   * @return void
   * Method to delete all tasks
   * DELETE Method /task/delete-all
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
          exit();
        }
      }
    }
  }

  /**
   * @return void
   * Method to list tasks
   * GET Method /task
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

        $tasksToJson[] = $this->taskConvert($task, $categories);
      }
    }

    echo json_encode($tasksToJson);
  }

  /**
   * @return void
   * Method to list tasks by user
   * GET Method /task/{id}
  */
  public function listById($data): void {
    $task = $this->task->findById($data["taskId"]);
    if ($task) {
      $task = $this->taskConvert($task);
    }
    echo json_encode($task);
  }

  /**
   * @return void
   * Method to insert a category into a task
   * POST Method /task/add-task-category
  */
  public function addTaskCategory(): void {
    $this->task->insertCategory(new TaskCategoryDto($this->data));
  }

  /**
   * @return void
   * Method to get total tasks by user
   * GET Method /task/total-registered-tasks
  */
  public function totalRegisteredTasks(): void {
    $total = $this->task->find("user_id = :userId", "userId={$this->userId}")->count();
    echo json_encode(["total" => $total]);
  }

  /**
   * @return void
   * Method to get total pending tasks by user
   * GET Method /task/total-pending-tasks
  */
  public function totalPendingTasks(): void {
    $total = $this->task->find("user_id = :userId AND cod_status != 3", "userId={$this->userId}")->count();
    echo json_encode(["total" => $total]);
  }

  /**
   * @return void
   * Method to get total overdue tasks by user
   * GET Method /task/total-overdue-tasks
  */
  public function totalOverdueTasks(): void {
    $total = $this->task
      ->find("user_id = :userId AND cod_status != 3 AND CURRENT_TIMESTAMP > deadline", "userId={$this->userId}")
      ->count();
    echo json_encode(["total" => $total]);
  }

  /**
   * @return void
   * Method to get tasks by a category
   * GET Method /task/tasks-by-category
  */
  public function tasksByCategory($data): void {
    $taskCategory = (new TaskCategory())->find("category_id = :id", "id={$data["categoryId"]}")->fetch(true);
    $tasks = [];

    if($taskCategory) {
      foreach($taskCategory as $tCategory) {
        $task = (new Task)->findById($tCategory->task_id);
        if ($task) {
          $tasks[] = $task->data();
        }
      }
    }

    echo json_encode($tasks);
  }

  /**
   * @return void
   * Method to get total punctual tasks by user
   * GET Method /task/total-punctual-tasks
  */
  public function totalPunctualTasks(): void {
    $total = $this->task
      ->find("user_id = 21 AND cod_status != 3 AND deadline > CURRENT_TIMESTAMP OR deadline IS NULL", "userId={$this->userId}")
      ->count();
    echo json_encode(["total" => $total]);
  }

  /**
   * @return void
   * Method to get total done tasks by user
   * GET Method /task/total-done-task
  */
  public function totalDoneTask() {
    $total = $this->task
      ->find("user_id = :userId AND cod_status = 3", "userId={$this->userId}")
      ->count();
    echo json_encode(["total" => $total]);
  }


  /**
   * @return void
   * Method to get total tasks by category
   * GET Method /task/total-tasks-by-category
  */
  public function totalTasksByCategories() {
    $total = [];

    $categories = (new Category)->find("user_id = {$this->userId}")->fetch(true);
    if($categories) {
      foreach($categories as $category) {
        $taskCategoryCount = (new TaskCategory)->find("category_id = :id", "id={$category->id}")->count();
        $total[] = ["name" => $category->name, "total" => $taskCategoryCount, "color" => $category->color];
      }
    }

    echo json_encode($total);
  }

    /**
   * @return void
   * Method to get total tasks by time type
   * GET Method /task/tasks-by-time
  */
  public function tasksByTime($data): void {
    $tasksToJson = [];
    $tasks = [];
    $timeModel = null;

    $today = date("Y-m-d");
    $lastWeek = date("Y-m-d", strtotime("-7 day", strtotime(date("Y-m-d"))));
    $lastMonth = date("Y-m-d", strtotime("-1 month", strtotime(date("Y-m-d"))));

    if($data["timeId"] != 0) {
      $time = (new Time)->findById($data["timeId"]);
      if(!$time) {
        $this->setPortInternalServerError();
        echo json_encode(["error" => "O id informado não pertence a um tipo de tempo."]);
        exit();
      }

      if($data["timeId"] == 1){
        $timeModel = $today;
      }
  
      if($data["timeId"] == 2){
        $timeModel = $lastWeek;
      }
  
      if($data["timeId"] == 3){
        $timeModel = $lastMonth;
      }
    }

    if($data["timeId"] == 0 && $data["statusId"] == 0 && $data["categoryId"] == 0) {
      http_response_code(500);
      echo json_encode(["error" => "Consulta inválid!"]);
      exit();
    }

    if($data["timeId"] != 0 && $data["statusId"] == 0 && $data["categoryId"] == 0) {
      $tasks = @(new Task)->find("user_id = :userId AND opening_date > '{$timeModel} 00:00:00'", "userId={$this->userId}")->fetch(true);
    }

    if($data["timeId"] == 0 && $data["statusId"] != 0 && $data["categoryId"] == 0) {
      $tasks = @(new Task)->find("user_id = :userId AND cod_status = :status", "status={$data["statusId"]}&userId={$this->userId}")->fetch(true);
    }

    if($data["timeId"] == 0 && $data["statusId"] == 0 && $data["categoryId"] != 0) {
      $taskCategory = (new TaskCategory)->find("category_id = :id", "id={$data["categoryId"]}")->fetch(true);
      if($taskCategory) {
        foreach($taskCategory as $tFind) {
          $taskToList = @(new Task)->find("id = {$tFind->task_id} AND user_id = :userId", "userId={$this->userId}")->fetch(false);

          if($taskToList) {
            $tasks[] = $taskToList;
          }
        }
      }
    }

    if($data["timeId"] == 0 && $data["statusId"] != 0 && $data["categoryId"] != 0) {
      $taskCategory = (new TaskCategory)->find("category_id = :id", "id={$data["categoryId"]}")->fetch(true);
      if($taskCategory) {
        foreach($taskCategory as $tFind) {
          $taskToList = @(new Task)->find("id = {$tFind->task_id} AND cod_status = {$data["statusId"]} AND user_id = :userId", "userId={$this->userId}")->fetch(false);

          if($taskToList) {
            $tasks[] = $taskToList;
          }
        }
      }
    }

    if($data["timeId"] != 0 && $data["statusId"] != 0 && $data["categoryId"] == 0) {
      $tasks = @(new Task)->find("user_id = :userId AND cod_status = :status AND opening_date > '{$timeModel} 00:00:00'", "status={$data["statusId"]}&userId={$this->userId}")->fetch(true);
    }

    if($data["timeId"] != 0 && $data["statusId"] != 0 && $data["categoryId"] != 0) {
      $taskCategory = (new TaskCategory)->find("category_id = :id", "id={$data["categoryId"]}")->fetch(true);
      if($taskCategory) {
        foreach($taskCategory as $tFind) {
          $taskToList = @(new Task)->find("id = :id AND user_id = :userId AND cod_status = :status AND opening_date > '{$timeModel} 00:00:00'", "id={$tFind->task_id}&userId={$this->userId}&status={$data["statusId"]}")->fetch(false);

          if($taskToList) {
            $tasks[] = $taskToList;
          }
        }
      }
    }

    if($tasks) {
      $categories = [];
      foreach($tasks as $task) {
        $taskCategory = (new TaskCategory)->find("task_id = :id", "id={$task->id}")->fetch(true);
        if($taskCategory) {
          foreach($taskCategory as $tCategory) {
            $category = (new Category)->findById($tCategory->category_id);
            if($category) {
              $categories[] = ["id" => $category->id, "name" => $category->name, "color" => $category->color];
            }
          }
        }

        $tasksToJson[] = [
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
      }
    }

    echo json_encode(["tasks" => $tasksToJson]);
  }

  /**
   * @return void
   * Method to get standard time task by user
   * GET Method /task/standard-time-task
  */
  public function standardTimeTask() {
    $tasks = $this->task->find("user_id = :userId AND cod_status = 3", "userId={$this->userId}")->fetch(true);
    $count = 0;

    if(!$tasks) {
      http_response_code(500);
      echo json_encode(["time" => "Não há tasks concluídas"]);
      exit();
    }

    $time = 0;

    foreach($tasks as $task) {
      $taskTimeFinishing = 0;
      $taskTimeOpening = 0;

      $finishingDate = new \DateTime($task->finishing_date);
      $openingDate = new \DateTime($task->opening_date);

      $taskTimeFinishing += $finishingDate->format("s");
      $taskTimeFinishing += $finishingDate->format("m") * 60;
      $taskTimeFinishing += ($finishingDate->format("G") * 60) * 60;
      $taskTimeFinishing += ($finishingDate->format("d") * 24) * 60 * 60;
      $taskTimeFinishing += ($finishingDate->format("m") * 30) * 24 * 60 * 60;
      $taskTimeFinishing += ($finishingDate->format("y") * 365) * 24 * 60 * 60;

      $taskTimeOpening += $openingDate->format("s");
      $taskTimeOpening += $openingDate->format("m") * 60;
      $taskTimeOpening += ($openingDate->format("G") * 60) * 60;
      $taskTimeOpening += ($openingDate->format("d") * 24) * 60 * 60;
      $taskTimeOpening += ($openingDate->format("m") * 30) * 24 * 60 * 60;
      $taskTimeOpening += ($openingDate->format("y") * 365) * 24 * 60 * 60;

      $time += $taskTimeFinishing - $taskTimeOpening;
      $count += 1;
    }

    echo json_encode(["time" => (($time / $count) / 60) / 60]);
  }

  /**
   * @return void
   * Method to get standard time task by user
   * GET Method /task/standard--week-time-task
  */
  public function standardWeekTimeTask() {
    $lastYear = date("Y-m-d", strtotime("-1 year", strtotime(date("Y-m-d"))));
    $tasks = $this->task->find("user_id = :userId AND cod_status = 3 AND finishing_date > {$lastYear}", "userId={$this->userId}")->fetch(true);

    if(!$tasks) {
      http_response_code(500);
      echo json_encode(["error" => "Não há tasks concluídas"]);
      exit();
    }

    $time = 0;

    foreach($tasks as $task) {
      $taskTimeFinishing = 0;
      $taskTimeOpening = 0;

      $finishingDate = new \DateTime($task->finishing_date);
      $openingDate = new \DateTime($task->opening_date);

      $taskTimeFinishing += $finishingDate->format("s");
      $taskTimeFinishing += $finishingDate->format("m") * 60;
      $taskTimeFinishing += ($finishingDate->format("G") * 60) * 60;
      $taskTimeFinishing += ($finishingDate->format("d") * 24) * 60 * 60;
      $taskTimeFinishing += ($finishingDate->format("m") * 30) * 24 * 60 * 60;
      $taskTimeFinishing += ($finishingDate->format("y") * 365) * 24 * 60 * 60;

      $taskTimeOpening += $openingDate->format("s");
      $taskTimeOpening += $openingDate->format("m") * 60;
      $taskTimeOpening += ($openingDate->format("G") * 60) * 60;
      $taskTimeOpening += ($openingDate->format("d") * 24) * 60 * 60;
      $taskTimeOpening += ($openingDate->format("m") * 30) * 24 * 60 * 60;
      $taskTimeOpening += ($openingDate->format("y") * 365) * 24 * 60 * 60;

      $time += $taskTimeFinishing - $taskTimeOpening;
    }

    echo json_encode(["time" => (($time / 52) / 60) / 60]);
  }

  /**
   * @return void
   * Method to get standard time task by user
   * GET Method /task/standard--month-time-task
  */
  public function standardMonthTimeTask() {
    $lastYear = date("Y-m-d", strtotime("-1 year", strtotime(date("Y-m-d"))));
    $tasks = $this->task->find("user_id = :userId AND cod_status = 3 AND finishing_date > {$lastYear}", "userId={$this->userId}")->fetch(true);
    $dates = [];
    $standardTime = null;

    if(!$tasks) {
      http_response_code(500);
      echo json_encode(["error" => "Não há tasks concluídas"]);
      exit();
    }
    $time = 0;

    foreach($tasks as $task) {
      $taskTimeFinishing = 0;
      $taskTimeOpening = 0;

      $finishingDate = new \DateTime($task->finishing_date);
      $openingDate = new \DateTime($task->opening_date);

      $taskTimeFinishing += $finishingDate->format("s");
      $taskTimeFinishing += $finishingDate->format("m") * 60;
      $taskTimeFinishing += ($finishingDate->format("G") * 60) * 60;
      $taskTimeFinishing += ($finishingDate->format("d") * 24) * 60 * 60;
      $taskTimeFinishing += ($finishingDate->format("m") * 30) * 24 * 60 * 60;
      $taskTimeFinishing += ($finishingDate->format("y") * 365) * 24 * 60 * 60;

      $taskTimeOpening += $openingDate->format("s");
      $taskTimeOpening += $openingDate->format("m") * 60;
      $taskTimeOpening += ($openingDate->format("G") * 60) * 60;
      $taskTimeOpening += ($openingDate->format("d") * 24) * 60 * 60;
      $taskTimeOpening += ($openingDate->format("m") * 30) * 24 * 60 * 60;
      $taskTimeOpening += ($openingDate->format("y") * 365) * 24 * 60 * 60;

      $time += $taskTimeFinishing - $taskTimeOpening;
    }

    echo json_encode(["time" => (($time / 12) / 60) / 60]);
  }

  /**
   * @return array
   * Method to convert a task into a array to be converted to json
  */
  private function taskConvert($task, $categories = null): array {
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

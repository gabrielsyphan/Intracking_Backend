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

        $tasksToJson[] = $this->taskConvert($task, $categories);
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
    $task = $this->task->findById($data["taskId"]);
    if ($task) {
      $task = $this->taskConvert($task);
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

  /**
   * @return void
   * Method to get total tasks by user
   * GET Method /api/task/total-registered-tasks
  */
  public function totalRegisteredTasks(): void {
    $total = $this->task->find("user_id = :userId", "userId={$this->userId}")->count();
    echo json_encode(["total" => $total]);
  }

  /**
   * @return void
   * Method to get total pending tasks by user
   * GET Method /api/task/total-pending-tasks
  */
  public function totalPendingTasks(): void {
    $total = $this->task->find("user_id = :userId AND cod_status != 3", "userId={$this->userId}")->count();
    echo json_encode(["total" => $total]);
  }

  /**
   * @return void
   * Method to get total overdue tasks by user
   * GET Method /api/task/total-overdue-tasks
  */
  public function totalOverdueTasks(): void {
    $total = $this->task
      ->find("user_id = :userId AND cod_status != 3 AND CURRENT_TIMESTAMP > deadline", "userId={$this->userId}")
      ->count();
    echo json_encode(["total" => $total]);
  }

  /**
   * @return void
   * Method to get total punctual tasks by user
   * GET Method /api/task/total-punctual-tasks
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
   * GET Method /api/task/total-done-task
  */
  public function totalDoneTask() {
    $total = $this->task
      ->find("user_id = :userId AND cod_status = 3", "userId={$this->userId}")
      ->count();
    echo json_encode(["total" => $total]);
  }

  /**
   * @return void
   * Method to get standard time task by user
   * GET Method /api/task/standard-time-task
  */
  public function standardTimeTask() {
    $tasks = $this->task->find("user_id = :userId AND cod_status = 3", "userId={$this->userId}")->fetch(true);
    $dates = [];
    $standardTime = null;
    $count = 0;

    foreach($tasks as $task) {
      $finishingDate = new \DateTime($task->finishing_date);
      $currentDate = new \DateTime();
      $dates[] = strtotime($currentDate->diff($finishingDate)->format("%H:%I:%S %Y-%m-%d"));
    }

    foreach($dates as $date) {
      $standardTime += $date;
    }

    echo json_encode(date("H:i:s", $standardTime));
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

  public function exportCsv(): void {
    $tableName = "relatorio";

    $content = "<tr><td>Total de atividades cadastradas</td><td>". json_decode($this->totalRegisteredTasks())->total ."</td></tr>";
    $content .= "<tr><td>Total de atividades pendentes</td><td>". json_decode($this->totalPendingTasks())->total ."</td></tr>";
    $content .= "<tr><td>Total de atividades em atraso;</td><td>". json_decode($this->totalOverdueTasks())->total ."</td></tr>";
    $content .= "<tr><td>Total de atividades em dia;</td><td>". json_decode($this->totalPendingTasks())->total ."</td></tr>";
    $content .= "<tr><td>Média de atividades concluídas por mês</td><td></td></tr>";
    $content .= "<tr><td>Média de atividades concluídas por semana</td><td></td></tr>";
    $content .= "<tr><td>Tempo médio passado nas atividades</td><td>". json_decode($this->totalRegisteredTasks())->total ."</td></tr>";

    $file_name = $tableName . ".xls";

    $html = "";
    $html .= "<table>";
    $html .= "<tr>";
    $html .= "<td colspan="5">Planilha de " . $tableName . " - Intracking</td>";
    $html .= "</tr>";
    $html .= $content;
    $html .= "</table>";

    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/x-msexcel");
    header("Content-Disposition: attachment; filename=\"{$file_name}\"");
    header("Content-Description: PHP Generated Data");

    echo $html;
  }
}

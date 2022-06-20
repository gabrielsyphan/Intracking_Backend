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
    }

    $taskCategory = (new TaskCategory)->find("task_id = {$task->id}")->fetch(true);
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
  public function totalTasksByCategory($data) {
    $total = 0;
    $taskCategory = (new TaskCategory)->find("category_id = :id", "id={$data["categoryId"]}")->fetch(true);
    if($taskCategory) {
      foreach($taskCategory as $tCategory) {
        $task = (new Task)->find("id = :id AND user_id = :userId", "id={$tCategory->task_id}&userId={$this->userId}");
        if($task) {
          $total += 1;
        }
      }
    }

    echo json_encode(["total" => $total]);
  }

    /**
   * @return void
   * Method to get total tasks by time type
   * GET Method /task/tasks-by-time
  */
  public function tasksByTime($data) {
    $tasksToJson = [];
    $tasks = [];
    $timeModel = null;

    $today = date("Y-m-d");
    $lastWeek = date("Y-m-d", strtotime("-7 day", strtotime(date("Y-m-d"))));
    $lastMonth = date("Y-m-d", strtotime("-1 month", strtotime(date("Y-m-d"))));

    $time = (new Time)->findById($data["timeId"]);
    if(!$time) {
      $this->setPortInternalServerError();
      echo json_encode(["error" => "O id informado não pertence a um tipo de tempo."]);
      exit();
    }

    if($data["timeId"] != 0) {
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
      foreach($tasks as $task) {
        $tasksToJson[] = $task->data();
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
        $task->data(),
        "categories" => $categories
      ];
    } else {
      $tasksToJson = [
        $task->data()
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

  /**
   * @return void
   * Method to get tasks csv
   * GET Method /task/export-csv
  */
  public function exportCsv(): void {
    $tableName = "relatorio";
    $content = "";

    $content = "<tr><td>Total de atividades cadastradas</td><td>". $this->task->find("user_id = :userId", "userId={$this->userId}")->count() ."</td></tr>";
    // $content .= "<tr><td>Total de atividades pendentes</td><td>". json_decode($this->totalPendingTasks())->total ."</td></tr>";
    // $content .= "<tr><td>Total de atividades em atraso;</td><td>". json_decode($this->totalOverdueTasks())->total ."</td></tr>";
    // $content .= "<tr><td>Total de atividades em dia;</td><td>". json_decode($this->totalPendingTasks())->total ."</td></tr>";
    // $content .= "<tr><td>Média de atividades concluídas por mês</td><td></td></tr>";
    // $content .= "<tr><td>Média de atividades concluídas por semana</td><td></td></tr>";
    // $content .= "<tr><td>Tempo médio passado nas atividades</td><td>". json_decode($this->totalRegisteredTasks())->total ."</td></tr>";

    $file_name = $tableName . ".xls";

    $html = "";
    $html .= "<table>";
    $html .= "<tr>";
    $html .= "<td colspan='5'>Planilha de " . $tableName . " - Intracking</td>";
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

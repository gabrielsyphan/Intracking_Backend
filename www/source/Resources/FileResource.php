<?php

namespace Source\Resources;

use CoffeeCode\Router\Router;
use Source\Repository\User;
use Source\Repository\Task;

/**
 * Class FileResource
 *
 * @package Source\FileResource
*/
class FileResource {

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
    $this->task = new Task();
    $this->data = json_decode(file_get_contents("php://input"));
  }

  /**
   * @return void
   * Method to get tasks csv
   * GET Method /task/export-csv
  */
  public function exportCsv($data = null): void {

    $file = json_encode(["error" => "Token inválido."]);

    if($data) {
      $user = (new User)->find("session_token = '". $data["bearerToken"] ."'")->fetch(false);
      if ($user) {
        $tableName = "relatorio";
    
        $content = "<tr><td>Total de atividades cadastradas</td><td>". $this->task->find("user_id = :userId", "userId={$user->id}")->count() ."</td></tr>";
        $content .= "<tr><td>Total de atividades pendentes</td><td>". $this->task->find("user_id = :userId AND cod_status != 3", "userId={$user->id}")->count() ."</td></tr>";
        $content .= "<tr><td>Total de atividades em atraso</td><td>". $this->task->find("user_id = :userId AND cod_status != 3 AND CURRENT_TIMESTAMP > deadline", "userId={$user->id}")->count() ."</td></tr>";
        $content .= "<tr><td>Total de atividades em dia</td><td>". $this->task->find("user_id = :userId AND cod_status != 3", "userId={$user->id}")->count() ."</td></tr>";
        $content .= "<tr><td>Total de atividades concluídas</td><td>". $this->task->find("user_id = :userId AND cod_status = 3", "userId={$user->id}")->count() ."</td></tr>";
        // $content .= "<tr><td>Média de atividades concluídas por mês</td><td></td></tr>";
        // $content .= "<tr><td>Média de atividades concluídas por semana</td><td></td></tr>";
        // $content .= "<tr><td>Tempo médio passado nas atividades</td><td>". json_decode($this->totalRegisteredTasks())->total ."</td></tr>";
    
        $file_name = $tableName . ".xls";
    
        $file = "<table>";
        $file .= "<tr><td colspan='5'>Planilha de " . $tableName . " - Intracking</td></tr>";
        $file .= $content;
        $file .= "</table>";
    
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=\"{$file_name}\"");
        header("Content-Description: PHP Generated Data");
      }
    }

    echo $file;
  }
}

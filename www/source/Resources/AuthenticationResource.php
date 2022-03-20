<?php

namespace Source\Resources;

use CoffeeCode\Router\Router;
use Source\Repository\User;

/**
 * Class AuthenticationResource
 *
 * @package Source\Resources
 */
class AuthenticationResource {
  /**
  * @var Router
  */
  private $router;

  /**
  * @var Data
  */
  private $data;

  /**
  * Web constructor.
  */
  public function __construct($router) {
    $this->router = $router;

    setlocale(LC_TIME, "pt_BR", "pt_BR.utf-8", "pt_BR.utf-8", "portuguese");
    date_default_timezone_set("America/Sao_Paulo");

    $this->data = json_decode(file_get_contents("php://input"));
  }

  /**
  * @return void
  * Login method
  * POST Method /api/authentication
  */
  public function login(): void {
    $user = (new User())
      ->find(
        "email = :email AND password = :password",
        "email={$this->data->email}&password={$this->data->password}"
      )->fetch(false);
        
    if(!$user) {
      http_response_code(404);
      echo json_encode(["error" => "Usuário não encontrado"]);
      return;
    }

    try {
      $user->session_token = md5(uniqid(rand() . "" . $user->data()->email, true));
      $user->save();
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(["error" => $e->getMessage()]);
      return;
    }

    echo json_encode($user->data()->session_token);
  }

  /**
  * @return void
  * Create account
  * POST Method /api/create-account
  */
  public function createAccount(): void {
    $user = new User();
    $user->email = $this->data->email;
    $user->password = $this->data->password;

    try {
      $user->save();
    } catch (\Exception $e) {
      http_response_code(500);
      echo json_encode(["error" => $e->getMessage()]);
      return;
    }

    echo json_encode(["success" => "Usuário criado com sucesso"]);
  }

  /**
   * @return void
   * Validate session token sent by client
   */
  public function validateSessionToken(): void {
    $bearerToken = (trim(apache_request_headers()['Authorization'], "Bearer "));

    if (empty($bearerToken)) {
      http_response_code(401);
      echo json_encode(["error" => "Usuário não autenticado"]);
      return;
    }

    $user = (new User())
      ->find("session_token = :token", "token={$bearerToken}")
      ->fetch(false);
    
    if(!$user) {
      http_response_code(404);
      echo json_encode(["error" => "Usuário não encontrado"]);
    }
  }
}

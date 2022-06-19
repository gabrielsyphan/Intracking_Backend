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
   * @var UserId
  */
  private $userId = 0;

  /**
   * @var IsAuthenticated
  */
  private $isAuthenticated = false;

  /**
   * Class constructor.
  */
  public function __construct($router) {
    $this->router = $router;
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
        "email={$this->data->email}&password=". md5($this->data->password)
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

    echo json_encode(["token" => $user->data()->session_token]);
  }

  /**
   * @return void
   * Create account
   * POST Method /api/create-account
  */
  public function createAccount(): void {
    try {
      $user = new User();
      $user->name = $this->data->name;
      $user->email = $this->data->email;
      $user->password = md5($this->data->password);
      $user->save();

      if ($user->fail()) {
        $this->setPortInternalServerError();
        echo json_encode(["error" => $user->fail()->getMessage()]);
        exit();
      }

      $this->login();
    } catch (\Exception $e) {
      $this->setPortInternalServerError();
      echo json_encode(["error" => $e->getMessage()]);
    }
  }

  /**
   * @return void
   * Validate session token sent by client
  */
  public function validateSessionToken(): void {
    $bearerToken = (trim(apache_request_headers()["Authorization"], "Bearer "));

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
      echo json_encode(["error" => "Token inválido"]);
      return;
    }

    $this->userId = $user->data()->id;
    $this->isAuthenticated = true;
  }

  /**
   * @return void
   * Error Handler
   * GET Method /api/error/{errorCode}
  */
  public function errorHandler(array $data): void {
    http_response_code($data["code"]);
    echo json_encode(["error" => "Houve um erro ao processar a requisição. Por favor, tente novamente."]);
    exit();
  }

  /**
   * @return int
   * Method to get userId
  */
  public function getUserId(): int {
    return $this->userId;
  }

  /**
   * @return bool
   * Method to get isAuthenticated
  */
  public function getIsAuthenticated(): bool {
    return $this->isAuthenticated;
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
   * Method used to logout user and revoke his access token
   * GET Method /authentication/logout
  */
  public function logout(): void {
    $this->validateSessionToken();

    if(!$this->getIsAuthenticated()) {
      exit();
    }

    $bearerToken = (trim(apache_request_headers()["Authorization"], "Bearer "));

    $user = (new User())
      ->find("session_token = :token", "token={$bearerToken}")
      ->fetch(false);

    $user->session_token = null;
    $user->save();

    if ($user->fail()) {
      $this->setPortInternalServerError();
      echo json_encode(["error" => $user->fail()->getMessage()]);
    }
  }
}

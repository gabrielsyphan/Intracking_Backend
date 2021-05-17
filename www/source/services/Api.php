<?php

namespace Source\App;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Accept: application/json');


use Source\Models\Attach;
use Source\Models\License;
use Source\Models\LicenseType;
use Source\Models\Neighborhood;
use Source\Models\Punishment;
use Source\Models\Role;
use Source\Models\User;
use Stonks\Router\Router;
use League\Plates\Engine;

use SimpleXMLElement;
use Source\Models\Agent;
use Source\Models\Company;
use Source\Models\Email;
use Source\Models\Notification;
use Source\Models\PagSeguro;
use Source\Models\Payment;
use Source\Models\Salesman;
use Source\Models\Zone;

$data['b']='a';
echo json_encode($data);

class Api
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var Engine
     */
    private $view;
    private $service;

    /**
     * Web constructor.
     */
    public function __construct($router)
    {
        $this->router = $router;
        $this->view = Engine::create(THEMES, 'php');
        $this->service = Engine::create(SERVICES, 'php');
        $this->view->addData([
            'router' => $router,
        ]);

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function login(): void
    {
        $data['a']='a';
        echo json_encode($data);
    }

    public function registerUser($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $user = (new User())->find('cpf = :identity', 'identity=' . $data['identity'])->fetch();
        if ($user){
            $return['message'] = "Já existe um usuário cadastrado com esse CPF!";
            $return['code'] = "0";
            exit();
        }

        $adress = $data['street'] . ', ' . $data['city'] . ', ' . $data['neighborhood'] . ', ' . $data['number'];
        $user = new User();
        $user->cpf = $data['identity'];
        $user->rg = $data['rg'];
        $user->nome = $data['name'];
        $user->endereco = $adress;
        $user->email = $data['email'];
        $user->telefone = $data['phone'];
        $user->nome_mae = $data['maternalName'];
        $user->save();

        if($user->fail()){
            $return['message'] = $user->fail()->getMessage();
            $return['code'] = '0';
        } else {
            $return['message'] = 'Usuário cadastrado com sucesso';
        }

        echo json_encode($return);

    }
}

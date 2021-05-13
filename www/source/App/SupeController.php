<?php

namespace Source\App;

use SimpleXMLElement;
use League\Plates\Engine;
use Stonks\Router\Router;
use PHPMailer\PHPMailer\Exception;

class SupeController
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var Engine
     */
    private $view;

    
    private $token = null;
    private $hash = '2H32FC293716KM96X810B84B7TFJZM740V4C259A5F8E62G18G3B6PQ2U9I2152H7';

    /**
     * Class constructor.
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

    /**
     * Supe authentication
     */
    public function supeAuthentication(): void
    {
        $data = '{"login": "gmeister", "senha": "123456", "hashSistema": "'. $this->hash .'"}';
        $authentication = json_decode($this->postSupe($data, 'autenticar'));
        
        if (isset($authentication->stacktrace)) {
            throw new Exception($authentication->stacktrace);
        } else {
            $this->token = 'Authorization: Bearer '. $authentication->token;
        }

        var_dump ($this->openProcess('111.657.194-36', 'Teste abrir processo'));
    }

    /**
     * Open new process
     */
    public function openProcess($identity, $userName): string
    {
        $response = false;
        $data = '{
            "assunto": "Cadastro de nova licença - Orditi",
            "cpfCnpjInteressado": "'. $identity .'",
            "natureza": 7095,
            "nomeInteressado": "'. $userName .'",
            "notificarMovimentacoes": true,
            "origem": "I",
            "secretaria": 2700,
            "setor": 4282,
            "tipoDocumento": 31,
            "tipoInteressado": "F"
        }';

        $soap = json_decode($this->postSupe($data, 'processo'));
        if (isset($soap->numero)) {
            $response = true;
        }

        return json_encode($soap);
    }

    /**
     * Post request to Supe
     */
    public function postSupe($data, $url): string
    {
        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'http://integracao.homologacao.maceio.al.gov.br/autenticacao-api/api/'. $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $this->token));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $soapResponse = curl_exec($curl);

            return $soapResponse;
        } catch (\Exception $e){
            return ($e->getMessage());
        }
    }
}
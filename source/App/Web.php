<?php

namespace Source\App;

use Source\Models\AgentType;
use Source\Models\Attach;
use Source\Models\Auxiliary;
use Source\Models\Fixed;
use Source\Models\FoodTruck;
use Source\Models\License;
use Source\Models\LicenseType;
use Source\Models\Market;
use Source\Models\Neighborhood;
use Source\Models\Punishment;
use Source\Models\Role;
use Source\Models\Team;
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
use Source\Models\Occupation;

/**
 * Class Web
 *
 * @package Source\App
 */
class Web
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

    /**
     * @return void
     */
    public function home(): void
    {
        if (empty($_SESSION['user'])) {
            $this->login();
        } else {
            echo $this->view->render('home', [
                'title' => 'Início | ' . SITE
            ]);
        }
    }

    /**
     * @return void
     */
    public function dashboard()
    {
        $this->checkLogin();

        echo $this->view->render("dashboard", [
            'title' => 'Dashboard'
        ]);
    }

    /**
     * @return void
     */
    public function login(): void
    {
        $this->checkIsOff();

        echo $this->view->render('login', [
            'title' => 'Acesso - Usuário | ' . SITE,
        ]);
    }

    /**
     * @return void
     */
    public function validateLogin($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $user = (new User())->find('cpf = :identity AND senha = :password',
            'identity=' . $data['identity'] . '&password=' . md5($data['psw']))->fetch();
        $validate = 0;

        if ($user) {
            $attachs = (new Attach())->find('id_usuario = :id', 'id=' . $user->id)->fetch(true);
            if ($attachs) {
                foreach ($attachs as $attach) {
                    $attachName = explode('.', $attach->nome)[0];
                    if ($attachName == 'userImage') {
                        $_SESSION['user']['image'] = ROOT . '/themes/assets/uploads/users/' . $attach->id_usuario
                            . '/' . $attach->nome;
                        $_SESSION['user']['login'] = 0;
                        $_SESSION['user']['id'] = $user->id;
                        $_SESSION['user']['name'] = $user->nome;
                        $_SESSION['user']['email'] = $user->email;
                        $_SESSION['user']['identity'] = $user->cpf;
                        $_SESSION['user']['role'] = 0;

                        $validate = 1;
                    }
                }
            }
        } else {
            $agent = (new Agent())->find('cpf = :identity AND senha = :password', 'identity=' .
                $data['identity'] . '&password=' . md5($data['psw']))->fetch();
            if ($agent) {
                if ($agent->situacao == 2) {
                    $validate = 3;
                } else {
                    $attach = (new Attach())->find('id_usuario = :id', 'id=' . $agent->id)->fetch(false);
                    if ($attach) {
                        $_SESSION['user']['login'] = 3;
                        $_SESSION['user']['identity'] = $agent->cpf;
                        $_SESSION['user']['role'] = $agent->tipo_fiscal;
                        $_SESSION['user']['id'] = $agent->id;
                        $_SESSION['user']['name'] = $agent->nome;
                        $_SESSION['user']['team'] = $agent->id_orgao;
                        $_SESSION['user']['image'] = ROOT . '/themes/assets/uploads/agents/' . $attach->id_usuario
                            . '/' . $attach->nome;
                        $_SESSION['user']['email'] = $agent->email;

                        $validate = 1;
                    }
                }
            }
        }

        echo $validate;
    }

    /**
     * @return void
     */
    public function agent(): void
    {
        $this->checkIsOff();

        echo $this->view->render('agentLogin', [
            'title' => 'Acesso - Agente | ' . SITE
        ]);
    }

    /**
     * @return void
     * New user image
     */
    public function updateUserImg($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if ($_FILES) {
            $response = 'fail';
            /**
             * Load all images
             */
            foreach ($_FILES as $key => $file) {
                $target_file = basename($file['name']);

                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                $extensions_arr = array("jpg", "jpeg", "png");

                if (in_array($imageFileType, $extensions_arr)) {
                    $attach = (new Attach())->find('id_usuario = :id AND tipo_usuario = :type',
                        'id=' . $_SESSION['user']['id'] . '&type=' . $_SESSION['user']['login'])
                        ->fetch(true);
                    if ($attach) {
                        foreach ($attach as $att) {
                            $ext = explode('.', $att->nome);
                            if ($ext[0] == 'userImage') {

                                if ($att->tipo_usuario == 3) {
                                    $folder = THEMES . '/assets/uploads/agents';
                                    $folder2 = ROOT . '/themes/assets/uploads/agents';
                                } else {
                                    $folder = THEMES . '/assets/uploads/users';
                                    $folder2 = ROOT . '/themes/assets/uploads/users';
                                }

                                if ($folder) {
                                    if (!file_exists($folder) || !is_dir($folder)) {
                                        mkdir($folder, 0755);
                                    }

                                    $fileName = $ext[0] . '.' . $imageFileType;

                                    $dir = $folder . '/' . $att->id_usuario . '/';
                                    $dir2 = $folder2 . '/' . $att->id_usuario . '/' . $fileName;

                                    if (file_exists($dir . $att->nome)) {
                                        unlink($dir . $att->nome);
                                    }

                                    $dir = $dir . $fileName;

                                    $att->nome = $fileName;
                                    $att->save();

                                    if ($att->fail()) {
                                        $att->fail()->getMessege();
                                        $att->destroy();
                                    } else {
                                        move_uploaded_file($file['tmp_name'], $dir);
                                        $_SESSION['user']['image'] = $dir2;
                                        $response = 'success';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        echo $response;
    }

    /**
     * @return void
     */
    public function newPsw($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $salesman = (new Salesman())->find('identidade = :identity', 'identity=' . $data['identity'])->fetch();
        if ($salesman) {
            $salesman->senha = md5($data['psw']);
            $salesman->senha_temporaria = '';
            $salesman->save();
        } else {
            $company = (new Company())->find('cnpj = :identity', 'identity=' . $data['identity'])->fetch();
            if ($company) {
                $company->senha = md5($data['psw']);
                $company->senha_temporaria = '';
                $company->save();
            }
        }

        if ($salesman || $company) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * @return void
     */
    public function createAccount(): void
    {
        echo $this->view->render('createAccount', [
            'title' => 'Cadastro | ' . SITE,
        ]);
    }

    public function checkCmc($cpf): bool
    {
        $cpf = filter_var($cpf, FILTER_SANITIZE_STRIPPED);
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        $validate = false;

        $soap_input =
            '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:e="e-Agata_18.11">
               <soapenv:Header/>
               <soapenv:Body>
                  <e:PWSRetornoPertences.Execute>
                     <e:Flagtipopesquisa>C</e:Flagtipopesquisa>
                     <e:Ctgcpf>' . $cpf . '</e:Ctgcpf>
                     <e:Ctiinscricao></e:Ctiinscricao>
                  </e:PWSRetornoPertences.Execute>
               </soapenv:Body>
            </soapenv:Envelope>';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, PERTENCES);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $soap_input);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $soap_response = curl_exec($curl);

        $xml_response = str_ireplace(['SOAP-ENV:', 'SOAP:', '.executeresponse', '.SDTRetornoPertences'], '', $soap_response);

        @$xml = new SimpleXMLElement($xml_response, NULL, FALSE);
        $companys = $xml->Body->PWSRetornoPertences->Sdtretornopertences->SDTRetornoPertencesItem->SDTRetornoPertencesEmpresa->SDTRetornoPertencesEmpresaItem;

        if ($companys != "") {
            $validate = true;
        }

        return $validate;
    }

    public function checkCnpj($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $soap_input =
            '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:e="e-Agata_18.11">
               <soapenv:Header/>
               <soapenv:Body>
                  <e:PWSRetornoPertences.Execute>
                     <e:Flagtipopesquisa>C</e:Flagtipopesquisa>
                     <e:Ctgcpf>' . $data['cpf'] . '</e:Ctgcpf>
                     <e:Ctiinscricao></e:Ctiinscricao>
                  </e:PWSRetornoPertences.Execute>
               </soapenv:Body>
            </soapenv:Envelope>';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, PERTENCES);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $soap_input);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $soap_response = curl_exec($curl);

        $xml_response = str_ireplace(['SOAP-ENV:', 'SOAP:', '.executeresponse', '.SDTRetornoPertences'], '', $soap_response);

        @$xml = new SimpleXMLElement($xml_response, NULL, FALSE);
        $companys = $xml->Body->PWSRetornoPertences->Sdtretornopertences->SDTRetornoPertencesItem->SDTRetornoPertencesEmpresa->SDTRetornoPertencesEmpresaItem;

        if ($companys == "") {
            echo 0;
        } else {
            $aux = 0;

            foreach ($companys as $company) {
                if ($company->SRPAutonomo == "A") {
                    if ($company->SRPInscricaoEmpresa == $data['cmc']) {
                        $aux = 1;
                    }
                }
            }
            echo $aux;
        }
    }

    /**
     * @return void
     * @var $data
     */
    public function validateAccount($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if ($this->checkCpf($data['identity']) == false) {
            echo 'identity_fail';
            exit();
        }

        $user = (new User())->find('cpf = :identity', 'identity=' . $data['identity'])->fetch();
        if ($user) {
            echo 'already_exist';
            exit();
        }

        if ($_FILES) {
            $street = $data['street'] . ', ' . $data['city'] . ', ' . $data['neighborhood'] . ', ' . $data['number'];

            $user = new User();
            $user->cpf = $data['identity'];
            $user->rg = $data['rg'];
            $user->nome = $data['name'];
            $user->endereco = $street;
            $user->email = $data['email'];
            $user->telefone = $data['phone'];
            $user->nome_mae = $data['maternalName'];
            $user->save();

            if ($user->fail()) {
                var_dump($user->fail()->getMessage());
            } else {
                /**
                 * Load all images
                 */
                foreach ($_FILES as $key => $file) {
                    $target_file = basename($file['name']);

                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    $extensions_arr = array("jpg", "jpeg", "png");

                    if (in_array($imageFileType, $extensions_arr)) {
                        $folder = THEMES . '/assets/uploads/users';
                        if (!file_exists($folder) || !is_dir($folder)) {
                            mkdir($folder, 0755);
                        }
                        $fileName = $key . '.' . $imageFileType;
                        $dir = $folder . '/' . $user->id;

                        if (!file_exists($dir) || !is_dir($dir)) {
                            mkdir($dir, 0755);
                        }

                        $dir = $dir . '/' . $fileName;

                        move_uploaded_file($file['tmp_name'], $dir);

                        $attach = new Attach();
                        $attach->id_usuario = $user->id;
                        $attach->tipo_usuario = 0;
                        $attach->nome = $fileName;
                        $attach->save();

                        if ($attach->fail()) {
                            $user->destroy();
                            var_dump($attach->fail()->getMessage());
                            exit();
                        }
                    }
                }

                $email = new Email();

                $message = file_get_contents(THEMES . "/assets/emails/confirmRegisterEmail.php");

                $url = ROOT . "/confirmAccount/" . md5($user->id);

                $template = array("%title", "%textBody", "%button", "%link", "%name");
                $dataReplace = array("Confirmação de Cadastro", "Para confirmar seu cadastro", "Confirmar", $url, $user->nome);
                $message = str_replace($template, $dataReplace, $message);

                $email->add(
                    "Confirmação de cadastro",
                    $message,
                    $user->nome,
                    $user->email
                )->send();
                if ($email->error()) {
                    $attach->destroy();
                    $user->destroy();
                    var_dump($email->error()->getMessage());
                } else {
                    echo "success";
                }
            }
        } else {
            echo "fail";
        }
    }

    /**
     * @return void
     * @var $data
     */
    public function confirmAccount($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $validate = false;
        $user = (new User())->find('MD5(id) = :id AND senha IS NULL', 'id=' . $data['userId'])->fetch();

        if (!$user) {
            $user = (new Agent())->find('MD5(id) = :id AND senha IS NULL', 'id=' . $data['userId'])->fetch();
        }

        if ($user) {
            $validate = true;
            $attach = (new Attach())->find('id_usuario = :id', 'id=' . $user->id)->fetch(true);
            if ($attach) {
                foreach ($attach as $att) {
                    if (explode('.', $att->nome)[0] == 'userImage') {
                        if ($att->tipo_usuario == 3) {
                            $userImage = ROOT . '/themes/assets/uploads/agents/' . $user->id
                                . '/' . $att->nome;
                        } else {
                            $userImage = ROOT . '/themes/assets/uploads/users/' . $user->id
                                . '/' . $att->nome;
                        }
                    }
                }
            }
        }

        if ($validate == false) {
            $this->router->redirect('web.home');
        } else {
            echo $this->view->render('confirmPassword', [
                'title' => 'Confirmar senha | ' . SITE,
                'userId' => $data['userId'],
                'userName' => $user->nome,
                'userImage' => $userImage
            ]);

        }

        echo $validate;
    }

    /**
     * @return void
     * @var $data
     */
    public function requestLicense(): void
    {
        $this->checkLogin();

        //$cmc = $this->checkCmc($_SESSION['user']['identity']);

        echo $this->view->render('requestLicense', [
            'title' => 'Nova licença | ' . SITE,
            'cmc' => null
        ]);
    }

    public function requestLicenseUser($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $user = (new User())->find('MD5(id) = :id', 'id=' . $data['id'])->fetch(false);

        //$cmc = $this->checkCmc($user->cpf);


        echo $this->view->render('requestLicense', [
            'title' => 'Nova licença | ' . SITE,
            'cmc' => null,
            'user' => $user
        ]);
    }

    /**
     * @return void
     * @var $data
     */
    public function salesmanLicense($companyId = null): void
    {
        $this->checkLogin();

        $zones = (new Zone())->find('', '', 'id, ST_AsText(coordenadas) as poligono, ST_AsText(ST_Centroid(coordenadas)) as centroide, nome, limite_ambulantes, quantidade_ambulantes')->fetch(true);
        $company = null;

        if ($companyId) {
            $company = (new Company())->findById($companyId);
        }

        if ($zones) {
            foreach ($zones as $zone) {
                $polygon = explode("POLYGON((", $zone->poligono);
                $polygon = explode("))", $polygon[1]);
                $polygon = explode(",", $polygon[0]);

                $aux = array();
                foreach ($polygon as $polig) {
                    $polig = explode(" ", $polig);
                    $aux[] = $polig;
                }

                $polygon = $aux;

                $zone->poligono = $polygon;
                $zoneData[] = $zone;
            }
        }

        echo $this->view->render('salesmanLicense', [
            'title' => 'Licença de Ambulante | ' . SITE,
            'zones' => $zones,
            'company' => $company,
        ]);
    }

    public function salesmanLicenseUser($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $zones = (new Zone())->find('', '', 'id, ST_AsText(coordenadas) as poligono, ST_AsText(ST_Centroid(coordenadas)) as centroide, nome, limite_ambulantes, quantidade_ambulantes')->fetch(true);
        $user = (new User())->find('MD5(id) = :id', 'id=' . $data['id'])->fetch(false);

        if ($zones) {
            foreach ($zones as $zone) {
                $polygon = explode("POLYGON((", $zone->poligono);
                $polygon = explode("))", $polygon[1]);
                $polygon = explode(",", $polygon[0]);

                $aux = array();
                foreach ($polygon as $polig) {
                    $polig = explode(" ", $polig);
                    $aux[] = $polig;
                }

                $polygon = $aux;

                $zone->poligono = $polygon;
                $zoneData[] = $zone;
            }
        }

        echo $this->view->render('salesmanLicense', [
            'title' => 'Licença de Ambulante | ' . SITE,
            'zones' => $zones,
            'userId' => md5($user->id),
            'company' => null
        ]);
    }

    public function marketLicense(): void
    {
        $zones = (new Zone())->find('vagas_fixas > 0', '', 'id, nome')->fetch(true);

        echo $this->view->render('marketLicense', [
            'title' => 'Licença - Mercado | ' . SITE,
            'zones' => $zones,
            'userId' => null
        ]);
    }

    public function marketLicenseUser($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $zones = (new Zone())->find('vagas_fixas > 0', '', 'id, nome')->fetch(true);

        echo $this->view->render('marketLicense', [
            'title' => 'Licença - Mercado | ' . SITE,
            'zones' => $zones,
            'userId' => $data['id']
        ]);
    }

    public function marketData($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $response = array();

        $zone = (new Zone())
            ->find(
                'md5(id) = :id',
                'id=' . $data['referenceCode'],
                'id, ST_AsText(coordenadas) as polygon, ST_AsText(ST_Centroid(coordenadas)) as centroid, nome'
            )->fetch(false);
        if ($zone) {
            $centroid = explode("POINT(", $zone->centroid);
            $centroid = explode(")", $centroid[1]);
            $centroid = explode(" ", $centroid[0]);

            $polygon = explode("POLYGON((", $zone->polygon);
            $polygon = explode("))", $polygon[1]);
            $polygon = explode(",", $polygon[0]);

            $aux = array();
            foreach ($polygon as $polig) {
                $polig = explode(" ", $polig);
                $aux[] = $polig;
            }

            $fixeds = (new Fixed())
                ->find(
                    'id_zona = :id',
                    'id=' . $zone->id,
                    'cod_identificador, nome'
                )->fetch(true);

            if ($fixeds) {
                foreach ($fixeds as $fixed) {
                    if (!$fixed->id_licenca) {
                        $response['success'][] = [
                            'name' => $fixed->nome,
                            'referenceCode' => $fixed->cod_identificador
                        ];
                    }
                }

                $response['zoneData'] = [
                    'polygon' => $aux,
                    'lat' => $centroid[1],
                    'lng' => $centroid[0],
                    'name' => $zone->nome
                ];
            }
        } else {
            $response['fail'] = 'Zone not found.';
        }

        echo json_encode($response);
    }

    public function validateMarketLicense($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $zone = (new Zone())
            ->find('MD5(id) = :id', 'id=' . $data['marketSelect'], 'id')
            ->fetch(false);

        if ($data['userId']) {
            $userId = (new User())->find('MD5(id)=:id', 'id=' . $data['userId'], 'id')->fetch(false);
            if ($userId) {
                $userId = $userId->id;
            } else {
                $userId = $_SESSION['user']['id'];
            }
        } else {
            $userId = $_SESSION['user']['id'];
        }

        $license = new License();
        $license->cmc = 99000279;
        $license->tipo = 7;
        $license->id_usuario = $userId;
        $license->data_inicio = date('Y-m-d');
        $license->data_fim = date('Y-m-d', strtotime("+3 days"));
        $license->status = 0;
        $license->id_orgao = 2;
        $license->save();

        if ($license->fail()) {
            var_dump($license->fail()->getMessage());
        }

        $fixed = (new Fixed())
            ->find('cod_identificador = :code', 'code=' . $data['fixedSelect'])
            ->fetch(false);
        if ($fixed) {
            $fixed->id_licenca = $license->id;
            $fixed->save();

            if ($fixed->fail()) {
                var_dump($fixed->fail()->getMessage());
                $license->destroy();
            } else {
                $products = "";
                $productDescription = $data['productDescription'];
                $productsData = $data['productSelect'];

                foreach ($productsData as $product) {
                    $products = $products . "" . $product;
                }

                $workedDays = "";
                foreach ($data['workedDays'] as $workedDay) {
                    $workedDays = $workedDays . "" . $workedDay;
                }

                $market = new Market();
                $market->produtos = $products;
                $market->relato_atividade = $productDescription;
                $market->id_zona = $zone->id;
                $market->id_licenca = $license->id;
                $market->id_vaga = $fixed->id;
                $market->atendimento_dias = $workedDays;
                $market->atendimento_hora_inicio = $data['initHour'];
                $market->atendimento_hora_fim = $data['endHour'];
                $market->save();

                if ($market->fail()) {
                    var_dump($market->fail()->getMessage());
                }

                $paymentDate = date('Y-m-d', strtotime("+3 days"));
                $payment = new Payment();
                $payment->id_licenca = $license->id;
                $payment->cod_referencia = null;
                $payment->cod_pagamento = null;
                $payment->valor = $fixed->valor;
                $payment->id_usuario = $userId;
                $payment->tipo = 1;
                $payment->pagar_em = $paymentDate;
                $payment->save();

                $extCode = 'ODTP-' . $payment->id;
                $payment->cod_referencia = 15123;
                $payment->cod_pagamento = 'teste';
                $payment->save();

                echo "success";
            }
        } else {
            $license->destroy();
        }
    }

    /**
     * @return void
     * @var $data
     */
    public function occupationLicense($companyId = null): void
    {
        $this->checkLogin();

        $zones = (new Zone())->find('', '', 'id, ST_AsText(coordenadas) as poligono, ST_AsText(ST_Centroid(coordenadas)) as centroide, nome, limite_ambulantes, quantidade_ambulantes')->fetch(true);

        if ($zones) {
            foreach ($zones as $zone) {
                $polygon = explode("POLYGON((", $zone->poligono);
                $polygon = explode("))", $polygon[1]);
                $polygon = explode(",", $polygon[0]);

                $aux = array();
                foreach ($polygon as $polig) {
                    $polig = explode(" ", $polig);
                    $aux[] = $polig;
                }

                $polygon = $aux;

                $zone->poligono = $polygon;
                $zoneData[] = $zone;
            }
        }

        echo $this->view->render('occupationLicense', [
            'title' => 'Licença de Uso de Solo | ' . SITE,
            'userId' => null,
            'zones' => $zones,
        ]);
    }

    public function validateOccupationLicense($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $response = 'fail';

        if ($_FILES) {

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, PERTENCES);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);


            $point = 'POINT(' . $data['latitude'] . " " . $data['longitude'] . ')';
            $neighborhood = (new Neighborhood())->find('ST_CONTAINS(ST_GEOMFROMTEXT(ST_AsText(coordenadas)), ST_GEOMFROMTEXT("' . $point . '"))=1', '', 'id, coordenadas')->fetch(false);
            $neighborhoodId = "";

            if ($neighborhood) {
                $neighborhoodId = $neighborhood->id;
                $neighborhood->quantidade_ambulantes = $neighborhood->quantidade_ambulantes + 1;
                $neighborhood->save();
            }
            $license = new License();
            $license->tipo = 5;

            $license->status = 3;

            if ($data['userId']) {
                $userId = (new User())->find('MD5(id)=:id', 'id=' . $data['userId'], 'id')->fetch(false);
                $userId = $userId->id;
            } else {
                $userId = $_SESSION['user']['id'];
            }
            $license->id_usuario = $userId;
            $license->data_inicio = date('Y-m-d');
            $license->data_fim = date('Y-m-d', strtotime("+3 days"));
            $license->cmc = '';
            $license->id_orgao = 1;
            $license->save();

            if ($license->fail()) {
                var_dump($license->fail()->getMessage());
            } else {
                /**
                 * Load all images
                 */
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, True);
                curl_setopt($curl, CURLOPT_URL, 'https://nominatim.openstreetmap.org/reverse.php?lat=' . $data['latitude'] . '&lon=' . $data['longitude'] . '&zoom=18&format=jsonv2');
                curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
                $street = curl_exec($curl);
                curl_close($curl);
                $street = json_decode($street);
                $street = $street->display_name;
                $area = $data['width'] * $data['length'];
                $valueToPayment = array();

                $workedDays = "";
                foreach ($data['workedDays'] as $workedDay) {
                    $workedDays = $workedDays . "" . $workedDay;
                }

                $occupation = new Occupation();
                $occupation->id_licenca = $license->id;
                $occupation->cnpj = $data['cnpj'];
                $occupation->latitude = $data['latitude'];
                $occupation->longitude = $data['longitude'];
                $occupation->atendimento_dias = $workedDays;
                $occupation->atendimento_hora_inicio = $data['initHour'];
                $occupation->atendimento_hora_final = $data['endHour'];
                $occupation->tipo_equipamento = $data['howWillSell'];
                $occupation->area_equipamento = $data['width'] . "x" . $data['length'];
                $occupation->nome_empresa = $data['fantasyName'];
                $occupation->endereco = $street;

                $occupation->save();

                if ($occupation->fail()) {
                    $license->destroy();
                    var_dump($occupation->fail()->getMessage());
                    exit();
                } else {
                    $paymentDate = date('Y-m-d', strtotime("+3 days"));
                    $payment = new Payment();
                    $payment->id_licenca = $license->id;
                    $payment->cod_referencia = null;
                    $payment->cod_pagamento = null;
                    $payment->valor = 1;
                    $payment->id_usuario = $_SESSION['user']['id'];
                    $payment->tipo = 1;
                    $payment->pagar_em = $paymentDate;
                    $payment->save();
                    $extCode = 'ODT' . $payment->id;
                    $payment->cod_referencia = 15123;
                    $payment->cod_pagamento = 'teste';
                    $payment->save();

                    if ($payment->fail()) {
                        $license->destroy();
                        $occupation->destroy();
                        var_dump($payment->fail()->getMessage());
                        exit();
                    } else {
                        foreach ($_FILES as $key => $file) {
                            $target_file = basename($file['name']);

                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                            $extensions_arr = array("jpg", "jpeg", "png");

                            if (in_array($imageFileType, $extensions_arr)) {
                                $folder = THEMES . '/assets/uploads/occupation';
                                if (!file_exists($folder) || !is_dir($folder)) {
                                    mkdir($folder, 0755);
                                }
                                $fileName = $key . '.' . $imageFileType;
                                $dir = $folder . '/' . $license->id;

                                if (!file_exists($dir) || !is_dir($dir)) {
                                    mkdir($dir, 0755);
                                }

                                $dir = $dir . '/' . $fileName;

                                move_uploaded_file($file['tmp_name'], $dir);

                                $attach = new Attach();
                                $attach->id_usuario = $license->id;
                                $attach->tipo_usuario = 5;
                                $attach->nome = $fileName;
                                $attach->save();

                                if ($attach->fail()) {
                                    $license->destroy();
                                    var_dump($attach->fail()->getMessage());
                                    exit();
                                } else {
                                    $response = 'success';
                                }
                            }
                        }
                    }
                }
            }
        }
        echo $response;
    }

    /**
     * @return void
     * @var $data
     */
    public function licenseInfo($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $validate = false;
        $aux = false;
        $notifications = false;

        $license = (new License())->find('MD5(id) = :id', 'id=' . $data['licenseId'])->fetch();
        $agents = (new Agent())->find('', '', 'id, nome')->fetch(true);
        if ($license) {
            $payments = (new Payment())->find('id_licenca = :id', 'id=' . $license->id)->fetch(true);
            $user = (new User())->findById($license->id_usuario);
            if ($user) {
                switch ($data['licenseType']) {
                    case 1:
                        $licenseInfo = (new Salesman())->find('id_licenca = :id', 'id=' . $license->id)->fetch();
                        $templateName = 'salesmanLicenseInfo';
                        $groupName = 'salesmans';

                        $companyLicenses = (new License())->find('id_usuario = :id AND tipo = 2', 'id=' . $_SESSION['user']['id'], 'id')->fetch(true);
                        if ($companyLicenses) {
                            foreach ($companyLicenses as $companyLicense) {
                                if ($licenseInfo->id_empresa == $companyLicense->id) {
                                    $aux = true;
                                }
                            }
                        }

                        if ($aux == false && $_SESSION['user']['login'] != 3 && $license->id_usuario != $_SESSION['user']['id']) {
                            $this->router->redirect('web.home');
                        }

                        $notifications = (new Notification())->find('id_licenca = :id', 'id=' . $license->id)->fetch(true);
                        if ($notifications) {
                            foreach ($notifications as $notification) {
                                $agent = (new Agent())->findById($notification->id_fiscal);
                                if ($notification->id_boleto) {
                                    $notificationPayment = (new Payment())->findById($notification->id_boleto, 'cod_referencia');
                                    $notification->cod_referencia = $notificationPayment->cod_referencia;
                                }
                                $notification->agentName = $agent->nome;
                            }
                        }
                        break;
                    case 2:
                        $licenseInfo = (new Company())->find('id_licenca = :id', 'id=' . $license->id)
                            ->fetch();
                        $templateName = 'companyLicenseInfo';
                        $groupName = 'companys';
                        break;
                    case 5:
                        $licenseInfo = (new Occupation())->find('id_licenca = :id', 'id=' . $license->id)
                            ->fetch();
                        $templateName = 'occupationLicenseInfo';
                        $groupName = 'occupation';
                        break;
                    case 6:
                        $licenseInfo = (new FoodTruck())->find('id_licenca = :id', 'id=' . $license->id)
                            ->fetch();
                        $templateName = 'foodTrucksLicenseInfo';
                        $groupName = 'foodtruck';
                        break;
                    case 7:
                        $licenseInfo = (new Market())->find('id_licenca = :id', 'id=' . $license->id)
                            ->fetch(false);
                        $zone = (new Zone())->findById($licenseInfo->id_zona, 'nome');
                        $fixed = (new Fixed())->findById($licenseInfo->id_vaga, 'cod_identificador, nome');

                        $licenseInfo->zone = $zone->nome;
                        $licenseInfo->code = $fixed->cod_identificador;
                        $licenseInfo->fixed = $fixed->nome;

                        $templateName = 'marketLicenseInfo';
                        $groupName = 'markets';
                        break;
                }

                if ($licenseInfo) {
                    $uploads = array();
                    $attachments = (new Attach())->find('id_usuario = :id AND tipo_usuario = :type',
                        'id=' . $license->id . '&type=' . $data['licenseType'])->fetch(true);
                    $userAttachments = (new Attach())->find('id_usuario = :id AND tipo_usuario = 0',
                        'id=' . $license->id_usuario)->fetch(true);

                    $salesmans = (new Salesman())->find('id_empresa = :id', 'id=' . $license->id)
                        ->fetch(true);
                    $arrayAux = array();

                    if ($salesmans) {
                        foreach ($salesmans as $salesman) {
                            $salesmanLicense = (new License())->findById($salesman->id_licenca);
                            if ($salesmanLicense) {
                                $salesmanUser = (new User())->findById($salesmanLicense->id_usuario);
                                if ($salesmanUser) {
                                    $salesmanUser->status = $salesmanLicense->status;
                                    $arrayAux[] = $salesmanUser;
                                }
                            }
                        }
                    }

                    $userImage = '';

                    if ($attachments) {
                        foreach ($attachments as $attach) {
                            $uploads[] = [
                                'fileName' => $attach->nome,
                                'groupName' => $groupName,
                                'userId' => $license->id
                            ];
                        }

                        foreach ($userAttachments as $userAttachment) {
                            if (explode('.', $userAttachment->nome)[0] == 'userImage') {
                                $userImage = $userAttachment->nome;
                            }
                        }

                        $validate = true;

                    }

                    if ($data['licenseType'] == 7) {
                        $validate = true;
                    }
                }
            }
        }

        if ($validate == false) {
            $this->router->redirect('web.home');
        } else {
            echo $this->view->render($templateName, [
                'title' => 'Minha Licença | ' . SITE,
                'license' => $licenseInfo,
                'licenseValidate' => $license,
                'licenseStatus' => $license->status,
                'user' => $user,
                'uploads' => $uploads,
                'payments' => $payments,
                'agents' => $agents,
                'notifications' => $notifications,
                'companyConfirm' => $aux,
                'salesmans' => $arrayAux,
                'userImage' => $userImage
            ]);
        }
    }

    /**
     * @return void
     * @var $data
     */

    public function foodTruckLicense(): void
    {
        echo $this->view->render('foodTrucksLicense', [
            'title' => 'Licença de FoodTrucks | ' . SITE
        ]);
    }

    public function validateFoodTruckLicense($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $response = array();

        if ($_FILES) {
            $products = "";

            foreach ($data['productSelect'] as $product) {
                $products = $products . "" . $product;
            }

            $license = new License();
            $license->tipo = 6;
            $license->status = 3;
            $license->id_usuario = $_SESSION['user']['id'];
            $license->data_inicio = date('Y-m-d');
            $license->data_fim = date('Y-m-d', strtotime("+3 days"));
            $license->id_orgao = 1;
            $license->save();

            if ($license->fail()) {
                var_dump($license->fail()->getMessage());
                exit();
            } else {
                $point = 'POINT(' . $data['latitude'] . " " . $data['longitude'] . ')';
                $neighborhood = (new Neighborhood())->find('ST_CONTAINS(ST_GEOMFROMTEXT(ST_AsText(coordenadas)), ST_GEOMFROMTEXT("' . $point . '"))=1', '', 'id, coordenadas')->fetch(false);
                $neighborhoodId = "";

                if ($neighborhood) {
                    $neighborhoodId = $neighborhood->id;
                } else {
                    $neighborhoodId = 0;
                }

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, True);
                curl_setopt($curl, CURLOPT_URL, 'https://nominatim.openstreetmap.org/reverse.php?lat=' . $data['latitude'] . '&lon=' . $data['longitude'] . '&zoom=18&format=jsonv2');
                curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
                $street = curl_exec($curl);
                curl_close($curl);
                $street = json_decode($street);
                $street = $street->display_name;

                /**
                 * Load all images
                 */
                $folder = THEMES . '/assets/uploads/foodtruck';
                if (!file_exists($folder) || !is_dir($folder)) {
                    mkdir($folder, 0755);
                }
                $dir2 = $folder . '/' . $license->id;

                if (!file_exists($dir2) || !is_dir($dir2)) {
                    mkdir($dir2, 0755);
                }

                foreach ($_FILES as $key => $file) {
                    $target_file = basename($file['name']);
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    $extensions_arr = array("jpg", "jpeg", "png");
                    $fileName = $key . '.' . $imageFileType;

                    if (in_array($imageFileType, $extensions_arr)) {
                        $dir = $dir2 . '/' . $fileName;

                        move_uploaded_file($file['tmp_name'], $dir);

                        $attach = new Attach();
                        $attach->id_usuario = $license->id;
                        $attach->tipo_usuario = 6;
                        $attach->nome = $fileName;
                        $attach->save();
                    }
                }

                if ($attach->fail()) {
                    $license->destroy();
                    var_dump($attach->fail()->getMessage());
                    exit();
                } else {
                    $foodTrucks = new FoodTruck();
                    $foodTrucks->endereco = $street;
                    $foodTrucks->id_licenca = $license->id;
                    $foodTrucks->cnpj = $data['cnpj'];
                    $foodTrucks->cmc = $data['cmc'];
                    $foodTrucks->nome_fantasia = $data['fantasyName'];
                    $foodTrucks->id_bairro = $neighborhoodId;
                    $foodTrucks->produto = $products;
                    $foodTrucks->relato_equipamento = $data['equipmentDescription'];
                    $foodTrucks->descricao = $data['infoDescription'];
                    $foodTrucks->latitude = $data['latitude'];
                    $foodTrucks->longitude = $data['longitude'];

                    $foodTrucks->save();

                    if ($foodTrucks->fail()) {
                        $attach->destroy();
                        $license->destroy();
                        var_dump($foodTrucks->fail()->getMessage());
                        exit();
                    } else {
                        $response[] = [
                            "success" => true
                        ];
                    }
                }
            }
        }
        echo json_encode($response);
    }

    public function licenseStatus($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $register = (new License())->findById($data['id']);

        if ($register) {
            $register->status = $data['status'];
            $register->save();
        }
    }

    public function licenseCancel($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $response = array();
        $salesman = (new Salesman())->find('MD5(id) = :id', 'id=' . $data['id'], 'id_licenca')
            ->fetch(false);

        if ($salesman) {
            $license = (new License())->findById($salesman->id_licenca);
            if ($license) {
                if ($license->status == 2) {
                    $response = ['blocked' => true];
                } else {
                    $payments = (new Payment())->find('id_licenca = :id AND status = 0', 'id=' . $license->id)
                        ->fetch(true);

                    if ($payments) {
                        $response = ['payments' => true];
                    } else {
                        $license->status = 4;
                        $license->save();

                        if (!$license->fail()) {
                            $response = ['success' => true];
                        }
                    }
                }
            }
        }

        echo json_encode($response);
    }

    /**
     * @return void
     * @var $data
     */
    public function validateSalesmanLicense($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $response = 'fail';

        if ($_FILES) {
            if ($data['userId']) {
                $user = (new User())->find('MD5(id)=:id', 'id=' . $data['userId'])->fetch(false);
                $userId = $user->id;
            } else {
                $user = (new User())->findById($_SESSION['user']['id']);
                $userId = $_SESSION['user']['id'];
            }

            $cpf = preg_replace('/[^0-9]/is', '', $user->cpf);
            $soap_input =
                '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:e="e-Agata_18.11">
               <soapenv:Header/>
               <soapenv:Body>
                  <e:PWSRetornoPertences.Execute>
                     <e:Flagtipopesquisa>C</e:Flagtipopesquisa>
                     <e:Ctgcpf>' . $cpf . '</e:Ctgcpf>
                     <e:Ctiinscricao></e:Ctiinscricao>
                  </e:PWSRetornoPertences.Execute>
               </soapenv:Body>
            </soapenv:Envelope>';

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, PERTENCES);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $soap_input);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $soap_response = curl_exec($curl);

            $xml_response = str_ireplace(['SOAP-ENV:', 'SOAP:', '.executeresponse', '.SDTRetornoPertences'], '', $soap_response);

            @$xml = new SimpleXMLElement($xml_response, NULL, FALSE);
            $companys = $xml->Body->PWSRetornoPertences->Sdtretornopertences->SDTRetornoPertencesItem->SDTRetornoPertencesEmpresa->SDTRetornoPertencesEmpresaItem;

            if ($companys != '') {
                $companyAux = 0;
                foreach ($companys as $company) {
                    if ($company->SRPAutonomo == 'A') {
                        $companyAux = $company->SRPInscricaoEmpresa;
                    }
                }
            }

            // if ($companyAux == 0) {
            //     exit();
            // }

            $companyAux = 0;

            $point = 'POINT(' . $data['longitude'] . " " . $data['latitude'] . ')';
            $zone = (new Zone())->find('ST_CONTAINS(ST_GEOMFROMTEXT(ST_AsText(coordenadas)), ST_GEOMFROMTEXT("' . $point . '"))=1', '', 'id, limite_ambulantes, quantidade_ambulantes')->fetch();
            $zoneId = null;

            if ($zone) {
                if ($zone->quantidade_ambulantes < $zone->limite_ambulantes) {
                    $zone->quantidade_ambulantes++;
                    $zone->save();

                    $zoneId = $zone->id;
                }
            }

            $point = 'POINT(' . $data['latitude'] . " " . $data['longitude'] . ')';
            $neighborhood = (new Neighborhood())->find('ST_CONTAINS(ST_GEOMFROMTEXT(ST_AsText(coordenadas)), ST_GEOMFROMTEXT("' . $point . '"))=1', '', 'id, coordenadas')->fetch(false);
            $neighborhoodId = "";

            if ($neighborhood) {
                $neighborhoodId = $neighborhood->id;
                $neighborhood->quantidade_ambulantes = $neighborhood->quantidade_ambulantes + 1;
                $neighborhood->save();
            }

            if ($zoneId === null) {
                $salesmans = (new Salesman)->find('latitude = :lat AND longitude = :lng', 'lat=' . $data['latitude'] . '&lng=' . $data['longitude'], 'latitude, longitude')->fetch(true);
                if ($salesmans) {
                    $response = 'somebodySameLocation';
                    $zoneId = null;

                    if ($zone) {
                        $zone->quantidade_ambulantes--;
                        $zone->save();
                    }
                } else {
                    $zoneId = "";
                }
            }

            if (($zoneId || $zoneId == "") && $zoneId != null) {
                $license = new License();
                $license->tipo = 1;
                $license->status = 2;
                $license->id_usuario = $userId;
                $license->data_inicio = date('Y-m-d');
                $license->data_fim = date('Y-m-d', strtotime("+3 days"));
                $license->cmc = $companyAux;
                $license->id_orgao = 1;
                $license->save();

                if ($license->fail()) {
                    $neighborhood->destroy();
                    if ($zone) {
                        $zone->quantidade_ambulantes--;
                        $zone->save();
                    }
                    var_dump($license->fail()->getMessage());
                } else {
                    /**
                     * Load all images
                     */
                    foreach ($_FILES as $key => $file) {
                        $target_file = basename($file['name']);

                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                        $extensions_arr = array("jpg", "jpeg", "png");

                        if (in_array($imageFileType, $extensions_arr)) {
                            $folder = THEMES . '/assets/uploads/salesmans';
                            if (!file_exists($folder) || !is_dir($folder)) {
                                mkdir($folder, 0755);
                            }
                            $fileName = $key . '.' . $imageFileType;
                            $dir = $folder . '/' . $license->id;

                            if (!file_exists($dir) || !is_dir($dir)) {
                                mkdir($dir, 0755);
                            }

                            $dir = $dir . '/' . $fileName;

                            move_uploaded_file($file['tmp_name'], $dir);

                            $attach = new Attach();
                            $attach->id_usuario = $license->id;
                            $attach->tipo_usuario = 1;
                            $attach->nome = $fileName;
                            $attach->save();

                            if ($attach->fail()) {
                                $license->destroy();
                                var_dump($attach->fail()->getMessage());
                                exit();
                            } else {
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, True);
                                curl_setopt($curl, CURLOPT_URL, 'https://nominatim.openstreetmap.org/reverse.php?lat=' . $data['latitude'] . '&lon=' . $data['longitude'] . '&zoom=18&format=jsonv2');
                                curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
                                $street = curl_exec($curl);
                                curl_close($curl);
                                $street = json_decode($street);
                                $street = $street->display_name;
                                $area = $data['width'] * $data['length'];
                                $valueToPayment = array();
                                $products = "";
                                $productDescription = null;
                                $productsData = $data['productSelect'];

                                foreach ($productsData as $product) {
                                    $products = $products . "" . $product;
                                    if ($product == 0 || $product == 1) {
                                        if ($area <= 1.50) {
                                            $valueToPayment[] = 40.00;
                                        } else {
                                            $valueToPayment[] = 72.00;
                                        }
                                    } else if ($product == 2 || $product == 3 || $product == 4 || $product == 5) {
                                        if ($area <= 1.50) {
                                            $valueToPayment[] = 80.00;
                                        } else {
                                            $valueToPayment[] = 144.00;
                                        }
                                    } else if ($product == 6) {
                                        if ($area <= 1.50) {
                                            $valueToPayment[] = 72.00;
                                        } else {
                                            $valueToPayment[] = 80.00;
                                        }
                                    } else if ($product == 7) {
                                        if ($area <= 1.50) {
                                            $valueToPayment[] = 72.00;
                                        } else {
                                            $valueToPayment[] = 80.00;
                                        }
                                    }
                                }
                                rsort($valueToPayment);

                                $workedDays = "";
                                foreach ($data['workedDays'] as $workedDay) {
                                    $workedDays = $workedDays . "" . $workedDay;
                                }

                                $salesman = new Salesman();
                                $salesman->id_zona = $zoneId;
                                $salesman->id_bairro = $neighborhoodId;
                                $salesman->id_licenca = $license->id;

                                if (isset($data['companyId'])) {
                                    $company = (new Company)->findById($data['companyId']);
                                    if ($company) {
                                        $salesman->id_empresa = $company->id_licenca;
                                    }
                                }

                                $salesman->local_endereco = $street;
                                $salesman->latitude = $data['latitude'];
                                $salesman->longitude = $data['longitude'];
                                $salesman->produto = $products;
                                $salesman->atendimento_dias = $workedDays;
                                $salesman->atendimento_hora_inicio = $data['initHour'];
                                $salesman->atendimento_hora_fim = $data['endHour'];
                                $salesman->relato_atividade = $data['productDescription'];
                                $salesman->area_equipamento = $data['width'] . " x " . $data['length'];
                                $salesman->tipo_equipamento = $data['howWillSell'];
                                $salesman->save();

                                if ($salesman->fail()) {
                                    $attach->destroy();
                                    $license->destroy();
                                    var_dump($salesman->fail()->getMessage());
                                    exit();
                                } else {
                                    $paymentDate = date('Y-m-d', strtotime("+3 days"));
                                    $payment = new Payment();
                                    $payment->id_licenca = $license->id;
                                    $payment->cod_referencia = null;
                                    $payment->cod_pagamento = null;
                                    $payment->valor = $valueToPayment[0];
                                    $payment->id_usuario = $_SESSION['user']['id'];
                                    $payment->tipo = 1;
                                    $payment->pagar_em = $paymentDate;
                                    $payment->save();

                                    $extCode = 'ODT' . $payment->id;

//                                $soap_input = '
//                                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:eag="EAgata" xmlns:e="e-Agata_18.11">
//                                   <soapenv:Header/>
//                                   <soapenv:Body>
//                                      <eag:WSTaxaExternas.Execute>
//                                         <eag:Chave>TAXA_EXTERNA</eag:Chave>
//                                         <eag:Usulogin>CIDADAO</eag:Usulogin>
//                                         <eag:Ususenha>123456</eag:Ususenha>
//                                         <eag:Sdttaxaexterna>
//                                            <e:SDTTaxaExternas.SDTTaxaExternasItem>
//                                               <e:TipoMode>INS</e:TipoMode>
//                                               <e:EXTTipoContr>3</e:EXTTipoContr>
//                                               <e:EXTCodigo>'. $extCode .'</e:EXTCodigo>
//                                               <e:EXTDescricao>numero da licenca</e:EXTDescricao>
//                                               <e:EXTTipoMulta></e:EXTTipoMulta>
//                                               <e:EXTDescMulta></e:EXTDescMulta>
//                                               <e:EXTanolct>2020</e:EXTanolct>
//                                               <e:EXTtpoTaxaExternas>2</e:EXTtpoTaxaExternas>
//                                               <e:EXTCTBid>1254</e:EXTCTBid>
//                                               <e:EXTcpfcnpjpropr></e:EXTcpfcnpjpropr>
//                                               <e:EXTInscricao>'. $companyAux .'</e:EXTInscricao>
//                                               <e:EXTvlrvvt>'. $valueToPayment[0] .'</e:EXTvlrvvt>
//                                               <e:EXTvlrvvtdesconto>0.00</e:EXTvlrvvtdesconto>
//                                               <e:EXTvencimento>'. $paymentDate .'</e:EXTvencimento>
//                                               <e:EXTSituacao>A</e:EXTSituacao>
//                                               <e:Nome></e:Nome>
//                                               <e:Endereco></e:Endereco>
//                                               <e:Numero></e:Numero>
//                                               <e:complemento></e:complemento>
//                                               <e:Municipio></e:Municipio>
//                                               <e:cep></e:cep>
//                                               <e:uf>AL</e:uf>
//                                            </e:SDTTaxaExternas.SDTTaxaExternasItem>
//                                         </eag:Sdttaxaexterna>
//                                      </eag:WSTaxaExternas.Execute>
//                                   </soapenv:Body>
//                                </soapenv:Envelope>';
//
//                                $curl = curl_init();
//
//                                curl_setopt($curl, CURLOPT_URL, EAGATA);
//                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
//                                curl_setopt($curl, CURLOPT_POSTFIELDS, $soap_input);
//                                curl_setopt($curl, CURLOPT_HEADER, false);
//                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//
//                                $soap_response = curl_exec($curl);
//
//                                $xml_response = str_ireplace(['SOAP-ENV:', 'SOAP:', '.executeresponse', '.SDTConsultaParcelamentoItem', '.SDTMensagem_TaxaExternaItem'], '', $soap_response);
//
//                                @$xml = new SimpleXMLElement($xml_response, NULL, FALSE);
//                                $code = $xml->Body->WSTaxaExternas->Mensagem->SDTMensagem_TaxaExterna->NossoNumero;

//                                $payment->cod_referencia = $code;
//                                $payment->cod_pagamento = $extCode;

                                    $payment->cod_referencia = 15123;
                                    $payment->cod_pagamento = 'teste';
                                    $payment->save();

                                    if ($payment->fail()) {
                                        $attach->destroy();
                                        $license->destroy();
                                        $salesman->destroy();
                                        var_dump($payment->fail()->getMessage());
                                        exit();
                                    } else {
                                        $response = 'success';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        echo $response;
    }

    public function licenseBlock($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $response = false;
        $salesman = (new Salesman())->find('MD5(id) = :id', 'id=' . $data['licenseId'], 'id, id_licenca')
            ->fetch(false);

        if ($salesman) {
            $license = (new License())->findById($salesman->id_licenca);
            $user = (new User())->findById($license->id_usuario);
            if ($license) {
                $punishment = new Punishment();
                $punishment->titulo = $data['punishmentTitle'];
                $punishment->descricao = $data['punishmentDesciption'];
                $punishment->id_fiscal = $_SESSION['user']['id'];
                $punishment->id_ambulante = $salesman->id;

                if ($data['punishmentValue'] == 0) {
                    $punishment->id_boleto = null;
                }

                $punishment->save();

                if (!$punishment->fail()) {
                    if ($license->status == 2 && $data['punishmentStatus'] == 0) {
                        $email = new Email();
                        $email->add(
                            'Licença desbloqueada',
                            'Você teve sua licença desbloqueada no orditi. Acesse seu perfil para saber mais.',
                            $user->nome,
                            $user->email
                        )->send();
                        $license->status = 0;
                    }

                    if ($data['punishmentStatus'] == 1) {
                        $email = new Email();
                        $email->add(
                            'Licença bloqueada',
                            'Você teve sua licença bloqueada no orditi. Acesse seu perfil para saber mais.',
                            $user->nome,
                            $user->email
                        )->send();
                        $license->status = 2;
                    }

                    $license->save();

                    if (!$license->fail()) {
                        $response = true;
                    } else {
                        $punishment->destroy();
                    }
                }
            }
        }

        if ($response == false) {
            echo json_encode(['fail' => 'General error']);
        } else {
            echo json_encode(['success' => 'Update success']);
        }
    }

    public function licenseUser($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $company = (new Company())->find('acesso = :url', 'url=' . $data['url'])->fetch();
        if ($company) {
            $this->salesmanLicense($company->id);
        } else {
            $this->home();
        }
    }

    public function validateLicenseUser($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $validate = false;
        $company = (new Company())->find('MD5(id) = :id', 'id=' . $data['id'])->fetch();

        if ($company) {
            $company->acesso = $data['link'];
            $company->save();

            if (!$company->fail()) {
                $validate = true;
            }
        }

        echo $validate;
    }

    /**
     * @return void
     * @var $data
     */
    public function validateCompanyLicense($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $response = 'fail';

        if ($_FILES) {
            $user = (new User())->findById($_SESSION['user']['id']);
            $cpf = preg_replace('/[^0-9]/is', '', $user->cpf);

            $soap_input =
                '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:e="e-Agata_18.11">
               <soapenv:Header/>
               <soapenv:Body>
                  <e:PWSRetornoPertences.Execute>
                     <e:Flagtipopesquisa>C</e:Flagtipopesquisa>
                     <e:Ctgcpf>' . $cpf . '</e:Ctgcpf>
                     <e:Ctiinscricao></e:Ctiinscricao>
                  </e:PWSRetornoPertences.Execute>
               </soapenv:Body>
            </soapenv:Envelope>';

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, PERTENCES);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $soap_input);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $soap_response = curl_exec($curl);

            $xml_response = str_ireplace(['SOAP-ENV:', 'SOAP:', '.executeresponse', '.SDTRetornoPertences'], '', $soap_response);

            @$xml = new SimpleXMLElement($xml_response, NULL, FALSE);
            $companys = $xml->Body->PWSRetornoPertences->Sdtretornopertences->SDTRetornoPertencesItem->SDTRetornoPertencesEmpresa->SDTRetornoPertencesEmpresaItem;

            $companyAux = 0;
            if ($companys !== "") {
                foreach ($companys as $company) {
                    if ($company->SRPAutonomo == "A") {
                        if ($company->SRPInscricaoEmpresa == $data['cmc']) {
                            $companyAux = 1;
                        }
                    }
                }
            }

            if ($companyAux == 0) {
                $products = "";
                foreach ($data['productSelect'] as $product) {
                    $products = $products . "" . $product;
                }

                $license = new License();
                $license->tipo = 2;
                $license->status = 1;
                $license->id_usuario = $_SESSION['user']['id'];
                $license->data_inicio = date('Y-m-d');
                $license->data_fim = date('Y-m-d', strtotime("+3 days"));
                $license->cmc = $data['cmc'];
                $license->id_orgao = 1;
                $license->save();

                if ($license->fail()) {
                    var_dump($license->fail()->getMessage());
                    exit();
                } else {
                    /**
                     * Load all images
                     */
                    foreach ($_FILES as $key => $file) {
                        $target_file = basename($file['name']);
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                        $extensions_arr = array("jpg", "jpeg", "png");

                        if (in_array($imageFileType, $extensions_arr)) {
                            $folder = THEMES . '/assets/uploads/companys';
                            if (!file_exists($folder) || !is_dir($folder)) {
                                mkdir($folder, 0755);
                            }
                            $fileName = $key . '.' . $imageFileType;
                            $dir = $folder . '/' . $license->id;

                            if (!file_exists($dir) || !is_dir($dir)) {
                                mkdir($dir, 0755);
                            }

                            $dir = $dir . '/' . $fileName;

                            move_uploaded_file($file['tmp_name'], $dir);

                            $attach = new Attach();
                            $attach->id_usuario = $license->id;
                            $attach->tipo_usuario = 2;
                            $attach->nome = $fileName;
                            $attach->save();
                        }
                    }

                    if ($attach->fail()) {
                        $license->destroy();
                        var_dump($attach->fail()->getMessage());
                        exit();
                    } else {
                        $company = new Company();
                        $company->id_licenca = $license->id;
                        $company->cnpj = $data['cnpj'];
                        $company->cmc = $data['cmc'];
                        $company->nome_fantasia = $data['fantasyName'];
                        $company->endereco = $data['street'];
                        $company->numero = $data['number'];
                        $company->bairro = $data['neighborhood'];
                        $company->cidade = $data['city'];
                        $company->bairro = $data['neighborhood'];
                        $company->cep = $data['postcode'];
                        $company->produto = $products;
                        $company->outro_produto = $data['productDescription'];
                        $company->relato_atividade = $data['ativityDescription'];
                        $company->quantidade_equipamentos = $data['equipamentAmount'];
                        $company->save();

                        if ($company->fail()) {
                            $attach->destroy();
                            $license->destroy();
                            var_dump($company->fail()->getMessage());
                            exit();
                        } else {
                            $response = 'success';
                        }
                    }
                }
            }
        }

        echo $response;
    }

    /**
     * @return void
     * @var $data
     */
    public function companyLicense(): void
    {
        $this->checkLogin();

        echo $this->view->render('companyLicense', [
            'title' => 'Licença de Empresa | ' . SITE,
            'zones' => null
        ]);
    }

    /**
     * @return void
     * @var $data
     */
    public function companyLicenseUser($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $user = (new User())->find('MD5(id)=:id', 'id=' . $data['id'])->fetch(false);

        echo $this->view->render('companyLicense', [
            'title' => 'Licença de Empresa | ' . SITE,
            'zones' => null,
            'user' => md5($user->id)
        ]);
    }

    /**
     * @return void
     * @var $data
     */
    public function licenseList(): void
    {
        $this->checkLogin();

        $license_type = (new LicenseType())->find()->fetch(true);
        $users = array();
        if ($_SESSION['user']['login'] == 3) {
            $licenses = (new License())->find('id_orgao = :id', 'id=' . $_SESSION['user']['team'])
                ->fetch(true);

            $auxPaid = 0;
            $auxPending = 0;
            $auxBlocked = 0;
            $countLicense = 0;

            if ($licenses) {
                foreach ($licenses as $license) {
                    if ($license->status == 1) {
                        $auxPaid++;
                    } else if ($license->status == 2) {
                        $auxBlocked++;
                    } else {
                        $auxPending++;
                    }

                    $user = (new User())->findById($license->id_usuario, 'id, nome, cpf');
                    $users[$user->id] = $user;
                }
                $countLicense = count($licenses);
            }

            echo $this->view->render('salesmanList', [
                'title' => 'Licenças | ' . SITE,
                'licenses' => $licenses,
                'registered' => $countLicense,
                'paid' => $auxPaid,
                'pending' => $auxPending,
                'blocked' => $auxBlocked,
                'types' => $license_type,
                'users' => $users
            ]);
        } else {
            $licenses = (new License())->find('id_usuario = :id', 'id=' . $_SESSION['user']['id'])
                ->fetch(true);

            echo $this->view->render('licenseList', [
                'title' => 'Minhas licenças | ' . SITE,
                'licenses' => $licenses,
                'types' => $license_type
            ]);
        }
    }

    /**
     * @return void
     * @var $data
     */
    public function confirmAccountPassword($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if ($data['password'] === $data['rePassword']) {
            $user = (new User())->find('MD5(id) = :id AND senha IS NULL', 'id=' . $data['userId'])->fetch();
            if (!$user) {
                $user = (new Agent())->find('MD5(id) = :id AND senha IS NULL', 'id=' . $data['userId'])->fetch();
                $user->situacao = 1;
            }
            if ($user) {
                $user->senha = md5($data['password']);
                $user->save();
                if ($user->fail()) {
                    $user->fail()->getMessage();
                } else {
                    echo 'pswSuccess';
                }
            } else {
                $this->router->redirect('web.home');
            }
        } else {
            echo 'pswFail';
        }
    }

    /**
     * @return void
     * @var $data
     */
    public function checkZone($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $point = 'POINT(' . $data['longitude'] . " " . $data['latitude'] . ')';
        $zone = (new Zone())->find('ST_CONTAINS(ST_GEOMFROMTEXT(ST_AsText(coordenadas)), ST_GEOMFROMTEXT("' . $point . '"))=1', '', 'id, nome, limite_ambulantes, quantidade_ambulantes')->fetch();
        if ($zone) {
            $aux = intval(($zone->quantidade_ambulantes * 100) / $zone->limite_ambulantes);
            if ($aux <= 49) {
                echo 1;
            } elseif ($aux >= 50 && $aux <= 99) {
                echo 2;
            } else {
                echo 3;
            }
        } else {
            echo 1;
        }
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        if (!empty($_SESSION['user'])) {
            unset($_SESSION['user']);
        }

        $this->router->redirect('web.home');
    }

    /**
     * @return void
     * @var $data
     */
    public function pswRecovery($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $user = (new User())->find('cpf = :identity', 'identity=' . $data['identity'])->fetch();

        if (!$user) {
            $user = (new Agent())->find('cpf = :identity', 'identity=' . $data['identity'])->fetch();
        }

        if ($user->cpf === $data['identity']) {

            $user->senha = NULL;
            $user->save();

            if ($user->fail()) {
                var_dump($user->fail()->getMessege());
                exit;
            }

            /**
             * Send email with new temporary recovery password
             */
            $email = new Email();

            $message = file_get_contents(THEMES . "/assets/emails/confirmRegisterEmail.php");

            $url = ROOT . "/confirmAccount/" . md5($user->id);
            $template = array("%title", "%textBody", "%button", "%link", "%name");
            $dataReplace = array("Recuperação de senha", "Para recuperar sua senha", "Recuperar", $url, $user->nome);
            $message = str_replace($template, $dataReplace, $message);

            $email->add(
                "Recuperação de senha",
                $message,
                $user->nome,
                $user->email
            )->send();

            if ($email->error()) {
                var_dump($email->error()->getMessage());
                exit;
            } else {
                echo 'pswSuccess';
            }
        }
    }


    /**
     * @return void
     */
    public function profile(): void
    {
        $this->checkLogin();

        if ($_SESSION['user']['login'] === 0) {
            $user = (new User())->findById($_SESSION['user']['id']);
            $payments = (new Payment())->find('id_usuario = :id', 'id=' . $_SESSION['user']['id'])
                ->fetch(true);

            $folder = ROOT . '/themes/assets/uploads';
            $uploads = array();
            $attachments = (new Attach())->find('id_usuario = :id AND tipo_usuario = 0', 'id=' . $user->id)
                ->fetch(true);
            if ($attachments) {
                foreach ($attachments as $attach) {
                    $attachName = explode('.', $attach->nome);
                    if ($attachName[0] == 'userImage') {
                        $userImage = ROOT . '/themes/assets/uploads/users/' . $attach->id_usuario
                            . '/' . $attach->nome;
                    }

                    $uploads[] = [
                        'fileName' => $attach->nome,
                        'groupName' => 'users',
                        'userId' => $user->id
                    ];
                }
            }

            echo $this->view->render('profile', [
                'title' => 'Perfil | ' . SITE,
                'user' => $user,
                'payments' => $payments,
                'uploads' => $uploads,
                'userImage' => $userImage,
                'type' => 2
            ]);
        } else if ($_SESSION['user']['login'] === 3) {
            $agent = (new Agent())->findById($_SESSION['user']['id']);

            if ($agent) {
                $folder = ROOT . '/themes/assets/uploads';
                $uploads = array();
                $attachments = (new Attach())->find('id_usuario = :id AND tipo_usuario = 3', 'id=' . $agent->id)
                    ->fetch(true);

                $role = (new Role())->findById($agent->tipo_fiscal);
                $team = (new Team())->findById($agent->id_orgao);

                if ($attachments) {
                    foreach ($attachments as $attach) {
                        $attachName = explode('.', $attach->nome);
                        if ($attachName[0] == 'userImage') {
                            $userImage = ROOT . '/themes/assets/uploads/agents/' . $attach->id_usuario
                                . '/' . $attach->nome;
                        }

                        $uploads[] = [
                            'fileName' => $attach->nome,
                            'groupName' => 'agents',
                            'userId' => $agent->id
                        ];
                    }
                }

                echo $this->view->render('profile', [
                    'title' => 'Perfil | ' . SITE,
                    'user' => $agent,
                    'uploads' => $uploads,
                    'userImage' => $userImage,
                    'role' => $role,
                    'team' => $team,
                    'type' => 1
                ]);
            }
        } else {
            $this->router->redirect('web.salesmanList');
        }
    }

    public function profileUser($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $user = (new User())->find('MD5(id) = :id', 'id=' . $data['id'])->fetch(false);
        $payments = (new Payment())->find('id_usuario = :id', 'id=' . $data['id'])
            ->fetch(true);

        $folder = ROOT . '/themes/assets/uploads';
        $uploads = array();
        $attachments = (new Attach())->find('MD5(id_usuario) = :id AND tipo_usuario = 0', 'id=' . $data['id'])
            ->fetch(true);
        if ($attachments) {
            foreach ($attachments as $attach) {
                $attachName = explode('.', $attach->nome);
                if ($attachName[0] == 'userImage') {
                    $userImage = $folder . '/users/' . $attach->id_usuario
                        . '/' . $attach->nome;
                }

                $uploads[] = [
                    'fileName' => $attach->nome,
                    'groupName' => 'users',
                    'userId' => $user->id
                ];
            }
        }

        echo $this->view->render('profile', [
            'title' => 'Perfil | ' . SITE,
            'type' => 2,
            'user' => $user,
            'payments' => $payments,
            'uploads' => $uploads,
            'userImage' => $userImage
        ]);


    }

    public function editProfile($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $user = (new User())->find("MD5(id) = :id", "id=" . $data['id'])->fetch(false);
        $agent = (new Agent())->find("MD5(id) = :id", "id=" . $data['id'])->fetch(false);
        $response = false;
        if ($user) {
            $user->email = $data['email'];
            $user->telefone = $data['phone'];
            $user->endereco = $data['street'];
            $user->save();

            if (!$user->fail()) {
                $response = true;
            }
        } else if ($agent) {
            $agent->email = $data['email'];
            $agent->telefone = $data['phone'];
            $agent->save();

            if (!$agent->fail()) {
                $response = true;
            }
        }

        echo $response;
    }

    /**
     * @param $data
     */
    public function createNotification($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $agent = (new Agent())->findById($data['agentSelect']);
        if ($agent) {
            $salesman = (new Salesman())->find('MD5(id) = :id', 'id=' . $data['licenseId'])->fetch(false);
            if ($salesman) {
                $notification = new Notification();
                $notification->id_licenca = $salesman->id_licenca;
                $notification->data_notificacao = $data['date'];
                $notification->hora_notificacao = $data['time'];
                $notification->titulo = $data['title'];
                $notification->descricao = $data['noticationDescription'];
                $notification->id_fiscal = $agent->id;
                $notification->save();

                if (!isset($data['blockAccess']) || $data['blockAccess'] == 1) {
                    if ($salesman->regiao != null) {
                        $zone = (new Zone())->findById($salesman->regiao);
                        if ($zone) {
                            $zone->quantidade_ambulantes = $zone->quantidade_ambulantes - 1;
                            $zone->save();
                        }
                    }

                    $salesman->suspenso = 1;
                    $salesman->situacao = 0;
                    $salesman->regiao = null;
                    $salesman->latitude = null;
                    $salesman->longitude = null;
                    $salesman->save();

                    // $email = new Email();

                    // $message = file_get_contents(THEMES . "/assets/emails/notificationEmail.php");

                    // $url = ROOT;
                    // $template = array("%title", "%textBody", "%status", "%titleStatus", "%button", "%link", "%name", "%dataTitle", "%dataDescription");
                    // $dataReplace = array("Notificação", "Sua conta encontra-se", "SUSPENSA", "suspensão", "Acesse", $url, $salesman->nome, $data['title'], $data['description']);
                    // $message = str_replace($template, $dataReplace, $message);

                    // $email->add(
                    //     "Notificação",
                    //     $message,
                    //     $salesman->nome,
                    //     $salesman->email
                    // )->send();

                    if ($notification->fail()) {
                        var_dump($notification->fail()->getMessage());
                    } else {
                        echo 1;
                    }
                }
            }
        }
    }

    /**
     * @return void
     */
    public function paymentList(): void
    {
        $this->checkAgent();

        $paymentArray = array();
        $payments = (new Payment())->find()->fetch(true);

        $auxPaid = 0;
        $auxPendent = 0;
        $auxExpired = 0;
        $paymentCount = 0;

        if ($payments) {
            foreach ($payments as $payment) {
                $license = (new License())->findById($payment->id_licenca);
                if (($license != null) && ($license->id_orgao == $_SESSION['user']['team'])) {
                    $user = (new User())->findById($license->id_usuario);
                    switch ($license->tipo):
                        case 7:
                            $market = (new Market())->find("id_licenca = :ilic", "ilic=" . $license->id)->fetch();
                            $zone = (new Zone())->find("id = :izon", "izon=" . $market->id_zona)->fetch();
                            $box = (new Fixed())->find("id = :ivag", "ivag=" . $market->id_vaga)->fetch();
                            $payment->name_zone = $zone->nome;
                            if ($box->nome != NULL) {
                                $payment->name_box = $box->nome;
                            } else {
                                $payment->name_box = $box->cod_identificador;
                            }
                            break;
                        default:
                            break;
                    endswitch;

                    $payment->name = $user->nome;
                    $paymentArray[] = $payment;

                    if ($payment->status == 0 || $payment->status == 3) {
                        $auxPendent++;
                    } else if ($payment->status == 1) {
                        $auxPaid++;
                    } else {
                        $auxExpired++;
                    }
                    $paymentCount++;
                }
            }
        } else {
            $paymentArray = null;
        }
        $zones = (new Zone())->find('id_orgao = :id', 'id=' . $_SESSION['user']['team'])->fetch(true);

        echo $this->view->render('paymentList', [
            'title' => 'Pagamentos | ' . SITE,
            'payments' => $paymentArray,
            'amount' => $paymentCount,
            'paid' => $auxPaid,
            'pendent' => $auxPendent,
            'expired' => $auxExpired,
            'zones' => $zones
        ]);
    }

    /**
     * @return void
     */
    public function agentList(): void
    {
        $agents = (new Agent)->find('id_orgao = :team', 'team=' . $_SESSION['user']['team'])->fetch(true);
        $apporved = 0;
        $blocked = 0;
        $pendding = 0;
        foreach ($agents as $agent) {
            $attach = (new Attach())->find('tipo_usuario = 3 AND id_usuario = :id', 'id=' . $agent->id)
                ->fetch(false);
            $team = (new Team())->findById($agent->id_orgao);
            $agentType = (new AgentType())->findById($agent->tipo_fiscal);

            if ($attach) {
                $agent->image = ROOT . '/themes/assets/uploads/agents/' . $attach->id_usuario . '/' . $attach->nome;
                $agent->team = $team->sigla;
                $agent->role = $agentType->nome;
                if ($agent->situacao == 1) {
                    $apporved++;
                } else if ($agent->situacao == 0) {
                    $pendding++;
                } else {
                    $blocked++;
                }
            }
        }

        echo $this->view->render('agentList', [
            'title' => 'Agentes | ' . SITE,
            'agents' => $agents,
            'agentCount' => count($agents),
            'approved' => $apporved,
            'blocked' => $blocked,
            'pendding' => $pendding
        ]);
    }

    /**
     * @return void
     */
    public function changeAgentStatus($data): void
    {
        $this->checkAgent();
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $agent = (new Agent())->findById($data['agentId']);
        if ($agent) {
            if ($agent->situacao == 1) {
                $agent->situacao = 2;
            } else {
                $agent->situacao = 1;
            }

            $agent->save();
            if ($agent->fail()) {
                var_dump($agent->fail()->getMessage());
            } else {
                $this->router->redirect('web.agentList');
            }
        }
    }

    /**
     * @return void
     */
    public function userList(): void
    {
        $users = (new User)->find('', '', 'id, cpf, email, nome, telefone')->fetch(true);

        $userCount = 0;

        if ($users) {
            foreach ($users as $user) {
                $attachs = (new Attach())->find('tipo_usuario = 0 AND id_usuario = :id', 'id=' . $user->id)
                    ->fetch(true);

                if ($attachs) {
                    foreach ($attachs as $attach) {
                        $attachName = explode('.', $attach->nome);
                        if ($attachName[0] == 'userImage') {
                            $user->image = ROOT . '/themes/assets/uploads/users/' . $attach->id_usuario . '/' . $attach->nome;
                        }
                    }
                }

                $licenses = (new License())->find('id_usuario = :id', 'id=' . $user->id, 'id')
                    ->fetch(true);
                if ($licenses) {
                    $user->licenses = count($licenses);
                } else {
                    $user->licenses = 0;
                }
            }

            $userCount = count($users);
        }

        echo $this->view->render('userList', [
            'title' => 'Usuários | ' . SITE,
            'users' => $users,
            'userCount' => $userCount
        ]);
    }

    /**
     * @return void
     * Export payment list in xls file
     */
    public function exportData(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $this->checkAgent();
        $tableHead = '';
        $tableBody = '';

        if ($data['fileType'] == 1) {
            $tableName = 'licencas';

            $tableHead .= '<tr>';
            $tableHead .= '<td><b>Tipo</b></td>';
            $tableHead .= '<td><b>Cpf</b></td>';
            $tableHead .= '<td><b>Proprietário</b></td>';
            $tableHead .= '<td><b>Início</b></td>';
            $tableHead .= '<td><b>Fim</b></td>';
            $tableHead .= '<td><b>Status</b></td>';
            $tableHead .= '</tr>';

            $licenses = (new License())->find()->fetch(true);
            if ($licenses) {
                $license_type = (new LicenseType())->find()->fetch(true);
                if ($license_type) {
                    foreach ($licenses as $license) {
                        $user = (new User())->findById($license->id_usuario, 'id, nome, cpf');

                        switch ($license->status):
                            case 0:
                                $textStatus = 'Pendente';
                                break;
                            case 1:
                                $textStatus = 'Ativo';
                                break;
                            default:
                                $textStatus = 'Bloqueado';
                                break;
                        endswitch;

                        $tableBody .= '<tr>';
                        $tableBody .= '<td>' . $license_type[$license->tipo - 1]->nome . '</td>';
                        $tableBody .= '<td>' . $user->cpf . '</td>';
                        $tableBody .= '<td>' . $user->nome . '</td>';
                        $tableBody .= '<td>' . $license->data_inicio . '</td>';
                        $tableBody .= '<td>' . $license->data_fim . '</td>';
                        $tableBody .= '<td>' . $textStatus . '</td>';
                        $tableBody .= '</tr>';
                    }
                }
            }
        } else if ($data['fileType'] == 2) {
            $tableName = 'pagamentos';

            $tableHead .= '<tr>';
            $tableHead .= '<td><b>Valor</b></td>';
            $tableHead .= '<td><b>Vencimento</b></td>';
            $tableHead .= '<td><b>Cod Referência</b></td>';
            $tableHead .= '<td><b>Tipo</b></td>';
            $tableHead .= '<td><b>Status</b></td>';
            $tableHead .= '<td><b>Proprietário</b></td>';
            $tableHead .= '</tr>';

            $payments = (new Payment())->find()->fetch(true);
            if ($payments) {
                foreach ($payments as $payment) {
                    $license = (new License())->findById($payment->id_licenca);
                    if ($license) {
                        $user = (new User())->findById($license->id_usuario);
                        $payment->name = $user->nome;
                    }

                    switch ($payment->status) {
                        case 1:
                            $textStatus = 'Ativo';
                            break;
                        case 2:
                            $textStatus = 'Bloqueado';
                            break;
                        default:
                            $textStatus = 'Pendente';
                            break;
                    }

                    switch ($payment->tipo) {
                        case 1:
                            $paymentType = 'Recorrente';
                            break;
                        default:
                            $paymentType = 'Vencido';
                            break;
                    }

                    $tableBody .= '<tr>';
                    $tableBody .= '<td>' . $payment->valor . '</td>';
                    $tableBody .= '<td>' . date('d-m-Y', strtotime($payment->pagar_em)) . '</td>';
                    $tableBody .= '<td>' . $payment->cod_referencia . '</td>';
                    $tableBody .= '<td>' . $paymentType . '</td>';
                    $tableBody .= '<td>' . $textStatus . '</td>';
                    $tableBody .= '<td>' . $payment->name . '</td>';
                    $tableBody .= '</tr>';
                }
            }
        }

        $file_name = $tableName . '.xls';

        $html = '';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td colspan="5">Planilha de ' . $tableName . ' - ORDITI</td>';
        $html .= '</tr>';
        $html .= $tableHead;
        $html .= $tableBody;

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=\"{$file_name}\"");
        header("Content-Description: PHP Generated Data");

        echo $html;
    }

    public function exportNeighborhood($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $this->checkAgent();
        $tableHead = '';
        $tableBody = '';

        $salesmans = (new Salesman())->find('MD5(id_bairro) = :id', 'id=' . $data['neighborhoodId'])
            ->fetch(true);
        $neihborhood = (new Neighborhood())
            ->find('MD5(id) = :id', 'id=' . $data['neighborhoodId'], 'nome')->fetch(false);
        if ($salesmans) {
            $tableHead .= '<tr>';
            $tableHead .= '<td><b>Tipo</b></td>';
            $tableHead .= '<td><b>Cpf</b></td>';
            $tableHead .= '<td><b>Proprietário</b></td>';
            $tableHead .= '<td><b>Início</b></td>';
            $tableHead .= '<td><b>Fim</b></td>';
            $tableHead .= '<td><b>Status</b></td>';
            $tableHead .= '</tr>';

            $license_type = (new LicenseType())->find()->fetch(true);
            if ($license_type) {
                foreach ($salesmans as $salesman) {
                    $license = (new License())->find('id = :id', 'id=' . $salesman->id_licenca)
                        ->fetch(false);
                    if ($license) {
                        $user = (new User())->findById($license->id_usuario, 'id, nome, cpf');

                        switch ($license->status) {
                            case 0:
                                $textStatus = 'Pendente';
                                break;
                            case 1:
                                $textStatus = 'Ativo';
                                break;
                            default:
                                $textStatus = 'Bloqueado';
                                break;
                        }

                        $tableBody .= '<tr>';
                        $tableBody .= '<td>' . $license_type[$license->tipo - 1]->nome . '</td>';
                        $tableBody .= '<td>' . $user->cpf . '</td>';
                        $tableBody .= '<td>' . $user->nome . '</td>';
                        $tableBody .= '<td>' . $license->data_inicio . '</td>';
                        $tableBody .= '<td>' . $license->data_fim . '</td>';
                        $tableBody .= '<td>' . $textStatus . '</td>';
                        $tableBody .= '</tr>';
                    }
                }
            }
        }

        $file_name = 'salesman.xls';

        $html = '';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td colspan="5">Ambulantes no bairro: ' . $neihborhood->nome . ' - ORDITI</td>';
        $html .= '</tr>';
        $html .= $tableHead;
        $html .= $tableBody;
        $html .= '</table>';

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=\"{$file_name}\"");
        header("Content-Description: PHP Generated Data");

        echo $html;
    }

    /**
     * @return void
     */
    public function salesmanMap(): void
    {
        $pending = array();
        $expired = array();
        $paid = array();
        $zoneData = null;

        if ($_SESSION['user']['login'] == 3) {
            $zoneData = array();
            $zones = (new Zone())->find('id_orgao = :team', 'team=' . $_SESSION['user']['team'],
                'id, 
                ST_AsText(coordenadas) as poligono, 
                ST_AsText(ST_Centroid(coordenadas)) as centroide, 
                nome, limite_ambulantes, quantidade_ambulantes, vagas_fixas')
                ->fetch(true);

            $salesmans = (new Salesman())->find('', '', 'id_licenca, latitude, longitude')
                ->fetch(true);
            if ($salesmans) {
                foreach ($salesmans as $salesman) {
                    $license = (new License())->findById($salesman->id_licenca, 'status, id_usuario');
                    $user = (new User())->findById($license->id_usuario);
                    if ($license) {
                        $salesman->status = $license->status;
                        $salesman->nome = $user->nome;
                        $salesman->cpf = $user->cpf;
                        $salesman->telefone = $user->telefone;
                        $salesman->id_licenca = md5($salesman->id_licenca);

                        if ($license->status == 1) {
                            $paid[] = $salesman;
                        } else if ($license->status == 2) {
                            $expired[] = $salesman;
                        } else {
                            $pending[] = $salesman;
                        }
                    }
                }
            }

            if ($zones) {
                foreach ($zones as $zone) {
                    $centroid = explode("POINT(", $zone->centroide);
                    $centroid = explode(")", $centroid[1]);
                    $centroid = explode(" ", $centroid[0]);

                    $polygon = explode("POLYGON((", $zone->poligono);
                    $polygon = explode("))", $polygon[1]);
                    $polygon = explode(",", $polygon[0]);

                    $aux = array();
                    foreach ($polygon as $polig) {
                        $polig = explode(" ", $polig);
                        $aux[] = $polig;
                    }

                    $polygon = $aux;

                    $zone->centroide = $centroid;
                    $zone->poligono = $polygon;
                    unset($zone->detalhes, $zone->foto);
                    $zoneData[] = $zone;
                }
            }
        }

        echo $this->view->render('salesmanMap', [
            'title' => 'Mapa',
            'zones' => $zoneData,
            'pendings' => $pending,
            'paids' => $paid,
            'expireds' => $expired
        ]);
    }


    /**
     * @return void
     */
    public function createZone(): void
    {
        $this->checkAgent();

        echo $this->view->render("createZone", [
            'title' => 'Cadastrar nova zona | ' . SITE
        ]);
    }

    /**
     * @return void
     */
    public function createAgent(): void
    {
        $this->checkAgent();
        $agentTeam = $_SESSION['user']['team'];

        echo $this->view->render("createAgent", [
            "title" => "Cadastrar agente | " . SITE,
            "agentTeam" => $agentTeam
        ]);
    }

    /**
     * @return void
     */
    public function createUser(): void
    {
        $this->checkAgent();

        echo $this->view->render("createUser", [
            'title' => 'Cadastrar novo usuário | ' . SITE
        ]);
    }

    /**
     * @return void
     */
    public function validateAgent($data): void
    {
        /**
         * Filter all form data
         */
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $agent = (new Agent())->find(
            'matricula = :matricula',
            'matricula=' . $data['registration'])
            ->fetch();

        $folder = THEMES . '/assets/uploads';
        $aux = 0;

        if (!$agent) {
            $psw = substr(md5(date('Y-m-d H:i:s')), 1, 5);

            $empty = array_keys($data, "");

            $validateEmail = ($data["email"] === $data["confirm_email"]);

            if ($empty) {
                echo json_encode(["required" => $empty]);
                exit;
            }

            if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(["formatInvalid" => [
                    "email" => "Formato de email inválido!"
                ]]);
                exit;
            }

            if (!$validateEmail) {
                echo json_encode(["validateResponse" => "registrationError"]);
                exit;
            }

            $agent = new Agent();
            $agent->matricula = $data['registration'];
            $agent->cpf = $data['identity'];
            $agent->email = $data['email'];
            $agent->nome = $data['name'];
            $agent->tipo_fiscal = 3;
            $agent->situacao = 0;
            $agent->id_orgao = $_SESSION['user']['team'];
            $agent->telefone = $data['phone'];
            $agent->tipo_fiscal = $data['jobRole'];
            $agent->save();

            $dir = $folder . '/agents/' . $agent->id;
            if (!file_exists($dir) || !is_dir($dir)) {
                mkdir($dir, 0755);
            }

            if ($_FILES && $_FILES['agentImage']['name'] !== '') {
                foreach ($_FILES as $key => $file) {
                    $target_file = basename($file['name']);

                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    $extensions_arr = array("jpg", "jpeg", "png");

                    if (in_array($imageFileType, $extensions_arr)) {
                        if (!file_exists($folder) || !is_dir($folder)) {
                            mkdir($folder, 0755);
                        }
                        $fileName = 'userImage.' . $imageFileType;

                        $dir = $dir . '/' . $fileName;

                        if (move_uploaded_file($file['tmp_name'], $dir)) {
                            $aux = 1;
                        }
                    }
                }
            } else {
                $fileName = 'userImage.png';
                $dir = $dir . '/' . $fileName;
                $picture = THEMES . '/assets/img/picture.png';

                if (copy($picture, $dir)) {
                    $aux = 1;
                }
            }

            if ($aux == 0) {
                exit;
            }

            if ($agent->fail()) {
                var_dump($agent->fail()->getMessage());
                unlink($dir);
            } else {
                $attach = new Attach();
                $attach->id_usuario = $agent->id;
                $attach->tipo_usuario = 3;
                $attach->nome = $fileName;
                $attach->save();

                if ($attach->fail()) {
                    $agent->destroy();
                    unlink($dir);
                    var_dump($attach->fail()->getMessage());
                } else {
                    $email = new Email();
                    $message = file_get_contents(THEMES . "/assets/emails/confirmRegisterEmail.php");

                    $url = ROOT . "/confirmAccount/" . md5($agent->id);
                    $template = array("%title", "%textBody", "%button", "%link", "%name");
                    $dataReplace = array("Confirmação de Cadastro", "Sua conta foi cadastrada", "Confirmar", $url, $data['name']);
                    $message = str_replace($template, $dataReplace, $message);

                    $email->add(
                        "Cadastro Orditi",
                        $message,
                        $data['name'],
                        $data['email']
                    )->send();
                }

                if ($email->error()) {
                    echo 'identity_fail';
                    $agent->destroy();
                } else {
                    echo 'success';
                }
            }
        } else {
            echo 'already_exist';
        }
    }

    /**
     * @return void
     */
    public function validateZone($data): void
    {
        $image = null;
        if (is_uploaded_file($_FILES['zoneImage']['tmp_name'])) {
            $target_file = basename($_FILES['zoneImage']['name']);

            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $extensions_arr = array("jpg", "jpeg", "png");

            if (in_array($imageFileType, $extensions_arr)) {
                $image_base64 = base64_encode(file_get_contents($_FILES['zoneImage']['tmp_name']));
                $image = 'data:image/' . $imageFileType . ';base64,' . $image_base64;
            }
        }

        if ($data['description']) {
            $description = $data['description'];
        } else {
            $description = "Zona cadastrada";
        }

        if (json_decode($data['geojson'])) {
            $coodinates = json_decode($data['geojson']);
            $array_point = array();

            foreach ($coodinates as $coodinate) {
                $array_point[] = $coodinate->lng . " " . $coodinate->lat;
            }

            $array_point[] = $coodinates[0]->lng . " " . $coodinates[0]->lat;

            $str = implode(',', $array_point);
            $polygon = 'POLYGON((' . $str . '))';

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $zone = new Zone();
            $zone->nome = $data['zoneName'];
            if ($image !== null) {
                $zone->foto = $image;
            }
            $zone->descricao = $description;
            $zone->limite_ambulantes = $data['available'];
            $zone->quantidade_ambulantes = $data['occupied'];
            $zone->coordenadas = $polygon;
            $zone->vagas_fixas = $data['fixed'];
            $zone->id_orgao = $_SESSION['user']['team'];
            $zone->save(['polygon']);

            if ($zone->fail()) {
                var_dump($zone->fail()->getMessage());
            } else {
                if ($data['fixed'] > 0) {
                    for ($i = 0; $i < $data['fixed']; $i++) {
                        $fixed = new Fixed();
                        $fixed->cod_identificador = 'ODTF-' . (new \DateTime('' . date('d-m-Y H:i:s')))
                                ->getTimestamp() . $i;
                        $fixed->id_zona = $zone->id;
                        $fixed->save();

                        if ($fixed->fail()) {
                            var_dump($fixed->fail()->getMessage());
                            $zone->destroy();
                        }
                    }
                }

                echo 1;
            }
        } else {
            echo 0;
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function zone(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $zone = (new Zone())->find('MD5(id) = :id', 'id=' . $data['id'], 'id, ST_AsText(coordenadas) as poligono, 
            ST_AsText(ST_Centroid(coordenadas)) as centroide, nome, limite_ambulantes, quantidade_ambulantes,
             vagas_fixas, foto, descricao')->fetch(false);
        if ($zone) {
            $salesmans = (new Salesman())->find('id_zona = :zoneId', 'zoneId=' . $data['id'], 'id_licenca')->fetch(true);
            $users = array();


            if ($salesmans) {
                foreach ($salesmans as $salesman) {
                    $license = (new License())->findById($salesman->id_licenca);
                    if ($license) {
                        $users[] = (new User)->findById($license->id_usuario);
                    }
                }
            }

            if ($zone !== null) {
                $centroid = explode("POINT(", $zone->centroide);
                $centroid = explode(")", $centroid[1]);
                $centroid = explode(" ", $centroid[0]);
                $zone->centroide = $centroid;

                $polygon = explode("POLYGON((", $zone->poligono);
                $polygon = explode("))", $polygon[1]);
                $polygon = explode(",", $polygon[0]);

                $aux = array();
                foreach ($polygon as $polig) {
                    $polig = explode(" ", $polig);
                    $aux[] = $polig;
                }
                $polygon = $aux;
                $zone->poligono = $polygon;

                if (!isset($_SESSION['user']['login']) || (isset($_SESSION['user']['login']) && $_SESSION['user']['login'] === 1)) {
                    echo $this->view->render('zone', [
                        'title' => 'Área | ' . SITE,
                        'salesmans' => null,
                        'zone' => $zone
                    ]);
                } else if ((isset($_SESSION['user']['login'])) &&
                    ($_SESSION['user']['login'] === 3) &&
                    ($_SESSION['user']['team'] == 2)) {

                    $fixed = (new Fixed())->find('MD5(id_zona) = :id_zone', 'id_zone=' . $data['id'],
                        'cod_identificador,id_licenca, nome, valor')->fetch(true);


                    echo $this->view->render('marketplace', [
                        'title' => $zone->nome . ' | ' . SITE,
                        'salesmans' => $users,
                        'zone' => $zone,
                        'fixed' => $fixed
                    ]);
                } else {
                    echo $this->view->render('zone', [
                        'title' => 'Área | ' . SITE,
                        'zone' => $zone,
                        'salesmans' => $users
                    ]);
                }
            } else {
                $this->router->redirect('web.salesmanMap');
            }
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function editFixedZones($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $zone = (new Zone())
            ->find(
                'MD5(id) = :id AND vagas_fixas > 0',
                'id=' . $data['id'],
                'id, nome, descricao, vagas_fixas,
                        ST_AsText(coordenadas) as polygon, 
                        ST_AsText(ST_Centroid(coordenadas)) as centroid,
                        quantidade_ambulantes, limite_ambulantes'
            )->fetch(false);
        $fixed = (new Fixed())->find('id_zona = :id', 'id=' . $zone->id, 'cod_identificador')
            ->fetch(true);

        if ($zone) {
            $centroid = explode("POINT(", $zone->centroid);
            $centroid = explode(")", $centroid[1]);
            $centroid = explode(" ", $centroid[0]);

            $polygon = explode("POLYGON((", $zone->polygon);
            $polygon = explode("))", $polygon[1]);
            $polygon = explode(",", $polygon[0]);

            $aux = array();
            foreach ($polygon as $polig) {
                $polig = explode(" ", $polig);
                $aux[] = $polig;
            }


            echo $this->view->render('editZoneFixed', [
                'title' => 'Editar vagas fixas | ' . SITE,
                'fixed' => $fixed,
                'zone' => $zone,
                'centroid' => $centroid,
                'polygon' => $aux
            ]);
        } else {
            $this->router->redirect('web.salesmanMap');
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function zoneFixedData($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $response = array();

        $fixed = (new Fixed())->find('cod_identificador = :cod', 'cod=' . $data['referenceCode'],
            'ST_AsText(coordenadas) as polygon, id_zona, id_licenca, nome, descricao, valor')->fetch(false);

        $aux = array();
        $neighborhoodData = array();

        if ($fixed) {
            if ($fixed->polygon) {
                $polygon = explode("POLYGON((", $fixed->polygon);
                $polygon = explode("))", $polygon[1]);
                $polygon = explode(",", $polygon[0]);

                $aux = array();
                foreach ($polygon as $polig) {
                    $polig = explode(" ", $polig);
                    $aux[] = $polig;
                }
            }

            $response['success'] = [
                'zoneId' => $fixed->id_zona,
                'license' => $fixed->id_licenca,
                'name' => $fixed->nome,
                'description' => $fixed->descricao,
                'value' => $fixed->valor,
                'polygon' => $aux
            ];
        } else {
            $response['fail'] = 'Reference code not found';
        }

        echo json_encode($response);
    }

    public function validateEditFixedZone($data): void
    {
        $geojson = $data['geojson'];
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $response = array();

        $fixed = (new Fixed())->find('cod_identificador = :cod', 'cod=' . $data['fixedSelect'])->fetch(false);
        if ($fixed) {
            $uFixed = (new Fixed())->findById($fixed->id);
            $coodinates = json_decode($geojson);
            $array_point = array();
            foreach ($coodinates as $coodinate) {
                $array_point[] = $coodinate->lng . " " . $coodinate->lat;
            }

            $array_point[] = $coodinates[0]->lng . " " . $coodinates[0]->lat;
            $str = implode(',', $array_point);
            $polygon = 'POLYGON((' . $str . '))';

            $uFixed->nome = $data['fixedName'];
            $uFixed->descricao = $data['fixedDescription'];
            $uFixed->valor = $data['fixedValue'];
            $uFixed->coordenadas = $polygon;
            $uFixed->save(['polygon']);

            if ($uFixed->fail()) {
                var_dump($uFixed->fail()->getMessage());
            } else {
                $response['success'] = 'Data updated';
            }
        } else {
            $response['fail'] = 'Reference code not found';
        }

        echo json_encode($response);
    }

    public function newFixedArea($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $response = false;
        $zone = (new Zone())->find('MD5(id) = :id', 'id=' . $data['zone'], 'id, vagas_fixas')->fetch(false);
        if ($zone) {
            $zone->vagas_fixas = $zone->vagas_fixas + 1;
            $zone->save();

            if (!$zone->fail) {
                $fixed = (new Fixed())
                    ->find('id_zona = :id', 'id=' . $zone->id, 'id, cod_identificador')
                    ->order('id DESC')->fetch(false);

                $nFixed = new Fixed();
                $aux = substr($fixed->cod_identificador, -1);
                $fixed->cod_identificador = substr($fixed->cod_identificador, 0, -1);
                $nFixed->cod_identificador = $fixed->cod_identificador . ($aux + 1);
                $nFixed->id_zona = $zone->id;
                $nFixed->save();

                if ($nFixed->fail()) {
                    $zone->vagas_fixas = $zone->vagas_fixas - 1;
                    $zone->save();
                } else {
                    $response = true;
                }
            }
        }

        echo $response;
    }

    /**
     * @return void
     */
    public function checkLogin(): void
    {
        if (!isset($_SESSION['user']['login'])) {
            $this->router->redirect('web.home');
        }
    }

    /**
     * Check if an agent
     */
    public function checkAgent(): void
    {
        if (!isset($_SESSION['user']['login']) || (isset($_SESSION['user']['login']) && !($_SESSION['user']['login'] === 3))) {
            $this->router->redirect('web.home');
        }
    }

    public function checkUser(): void
    {
        if (!isset($_SESSION['user']['login']) || (isset($_SESSION['user']['login']) && ($_SESSION['user']['login'] === 1))) {
            $this->router->redirect('web.home');
        }
    }

    /**
     * Only to SingIn and SignUp
     * @return void
     */
    public function checkIsOff(): void
    {
        if (!empty($_SESSION['user']['login'])) {
            $this->router->redirect('web.home');
        }
    }

    /**
     * @param array $data
     * @return void
     * Open file get method
     */
    public function downloadFile(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $this->checkLogin();

        $file = (new Attach())->find('nome = :fileName', 'fileName=' . $data['fileName'])
            ->fetch(false);

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel");
        header("Content-Description: PHP Generated Data");

        if ($file) {
            $fileToDownload = file_get_contents(
                THEMES . "/assets/uploads/{$data['groupName']}/{$data['userId']}/{$data['fileName']}"
            );

            header("Content-Disposition: attachment; filename=\"{$file->nome}\"");
            echo($fileToDownload);
        }
    }

    public function removeSuspension($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $salesman = (new Salesman())->findById($data['id'], 'id, suspenso');
        if ($salesman) {
            $payments = (new Payment())->find('id_ambulante = :id', 'id=' . $data['id'])->fetch(true);
            $aux = 0;
            foreach ($payments as $payment) {
                if ($payment->status != 1) {
                    $aux = 1;
                }
            }

            if ($aux == 0) {
                $salesman->situacao = 1;
            } else {
                $salesman->situacao = 0;
            }

            $salesman->suspenso = 0;
            $salesman->save();

            $email = new Email();

            $message = file_get_contents(THEMES . "/assets/emails/removeNotificationEmail.php");

            $url = "https://www.google.com";
            $template = array("%title", "%status", "%textBody", "%button", "%link", "%name");
            $dataReplace = array("Notificação", "MULTA", "foi removida", "Acesse", $url, $salesman->nome);
            $message = str_replace($template, $dataReplace, $message);

            $email->add(
                "Notificação",
                $message,
                $salesman->nome,
                $salesman->email
            )->send();

            echo 1;
        } else {
            echo 0;
        }
    }

    public function validateAuxiliary($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $salesman = (new Salesman())->find('MD5(id) = :id', 'id=' . $data['licenseId'], 'id')
            ->fetch(false);

        if ($salesman) {
            $auxiliary = new Auxiliary();
            $auxiliary->id_ambulante = $salesman->id;
            $auxiliary->nome = $data['auxiliaryName'];
            $auxiliary->cpf = $data['auxiliaryIdentity'];
            $auxiliary->save();

            if ($auxiliary->fail()) {
                var_dump($auxiliary->fail()->getMessage());
            } else {
                echo 1;
            }
        } else {
            echo 0;
        }
    }

    public function zoneConfirm($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        /**
         * Check if is in a zone
         */
        $point = 'POINT(' . $data['longitude'] . " " . $data['latitude'] . ')';
        $zone = (new Zone())->find('ST_CONTAINS(ST_GEOMFROMTEXT(ST_AsText(coordenadas)), ST_GEOMFROMTEXT("' . $point . '"))=1', '', 'id, nome, limite_ambulantes, quantidade_ambulantes')->fetch();
        if ($zone) {
            if (($zone->quantidade_ambulantes + 1) <= $zone->limite_ambulantes) {
                $zone->quantidade_ambulantes++;
                $zoneId = $zone->id;
                $zone->save();
            } else {
                echo 2;
                exit();
            }
        } else {
            $zoneId = null;
        }

        $aux = 0;

        if ($zoneId === null) {
            /**
             * Check if another salesman at the same location
             */
            $salesmans = (new Salesman)->find('latitude = :lat AND longitude = :lng', 'lat=' . $data['latitude'] . '&lng=' . $data['longitude'], 'latitude, longitude')->fetch(true);
            if ($salesmans != NULL) {
                $aux = 1;
            }
        }

        if ($aux == 0) {
            $salesman = (new Salesman())->findById($data['id']);
            if ($salesman) {
                $salesman->regiao = $zoneId;
                $salesman->latitude = $data['latitude'];
                $salesman->longitude = $data['longitude'];
                $salesman->save();

                $payments = (new Payment())->find('id_ambulante = :id', 'id=' . $data['id'])->fetch(true);
                if ($payments) {
                    $payAux = 0;
                    $companyId = null;
                    $paymentValue = 0;
                    foreach ($payments as $payment) {
                        if ($payment->status != 1 && $payment->tipo = 1) {
                            $payAux = 1;
                        }

                        if ($payment->tipo = 1) {
                            $paymentValue = $payment->valor;
                        }

                        if ($payment->id_empresa != null) {
                            $companyId = $payment->id_empresa;
                        }
                    }

                    if ($payAux == 0) {
                        $nPayment = new Payment();
                        $nPayment->id_ambulante = $data['id'];
                        $nPayment->id_empresa = $companyId;
                        $nPayment->cod_referencia = null;
                        $nPayment->cod_pagamento = null;
                        $nPayment->valor = $paymentValue;
                        $nPayment->tipo = 1;
                        $nPayment->pagar_em = date('Y-m-d H:i:s', strtotime("+3 days"));
                        $nPayment->save();

                        $extCode = 'ODT' . $nPayment->id;

                        // Cadastra o novo boleto no eagata
                        $soap_input = '
                                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:eag="EAgata" xmlns:e="e-Agata_18.11">
                                   <soapenv:Header/>
                                   <soapenv:Body>
                                      <eag:WSTaxaExternas.Execute>
                                         <eag:Chave>TAXA_EXTERNA</eag:Chave>
                                         <eag:Usulogin>CIDADAO</eag:Usulogin>
                                         <eag:Ususenha>123456</eag:Ususenha>
                                         <eag:Sdttaxaexterna>
                                            <e:SDTTaxaExternas.SDTTaxaExternasItem>
                                               <e:TipoMode>INS</e:TipoMode>
                                               <e:EXTTipoContr>3</e:EXTTipoContr>
                                               <e:EXTCodigo>' . $extCode . '</e:EXTCodigo>
                                               <e:EXTDescricao>numero da licenca</e:EXTDescricao>
                                               <e:EXTTipoMulta></e:EXTTipoMulta>
                                               <e:EXTDescMulta></e:EXTDescMulta>
                                               <e:EXTanolct>2020</e:EXTanolct>
                                               <e:EXTtpoTaxaExternas>2</e:EXTtpoTaxaExternas>
                                               <e:EXTCTBid>1254</e:EXTCTBid>
                                               <e:EXTcpfcnpjpropr></e:EXTcpfcnpjpropr>
                                               <e:EXTInscricao>' . $salesman->cmc . '</e:EXTInscricao>
                                               <e:EXTvlrvvt>' . $paymentValue . '</e:EXTvlrvvt>
                                               <e:EXTvlrvvtdesconto>0.00</e:EXTvlrvvtdesconto>
                                               <e:EXTvencimento>' . date('Y-m-d', strtotime("+3 days")) . '</e:EXTvencimento>
                                               <e:EXTSituacao>A</e:EXTSituacao>
                                               <e:Nome></e:Nome>
                                               <e:Endereco></e:Endereco>
                                               <e:Numero></e:Numero>
                                               <e:complemento></e:complemento>
                                               <e:Municipio></e:Municipio>
                                               <e:cep></e:cep>
                                               <e:uf>AL</e:uf>
                                            </e:SDTTaxaExternas.SDTTaxaExternasItem>
                                         </eag:Sdttaxaexterna>
                                      </eag:WSTaxaExternas.Execute>
                                   </soapenv:Body>
                                </soapenv:Envelope>';

                        $curl = curl_init();

                        curl_setopt($curl, CURLOPT_URL, EAGATA);
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $soap_input);
                        curl_setopt($curl, CURLOPT_HEADER, false);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

                        $soap_response = curl_exec($curl);

                        $xml_response = str_ireplace(['SOAP-ENV:', 'SOAP:', '.executeresponse', '.SDTConsultaParcelamentoItem', '.SDTMensagem_TaxaExternaItem'], '', $soap_response);

                        @$xml = new SimpleXMLElement($xml_response, NULL, FALSE);
                        $code = $xml->Body->WSTaxaExternas->Mensagem->SDTMensagem_TaxaExterna->NossoNumero;

                        $nPayment->cod_referencia = $code;
                        $nPayment->cod_pagamento = $extCode;
                        $nPayment->save();
                    }
                }

                if ($salesman->fail()) {
                    var_dump($salesman->fail());
                } else {
                    echo 1;
                }
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function videos(): void
    {
        echo $this->view->render('videos', [
            'title' => "Vídeos | " . SITE
        ]);
    }

    /**
     * Return from PagSeguro
     * @return void
     */
    public function securePayment(): void
    {
        if (isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction') {
            $PagSeguro = new PagSeguro();
            $response = $PagSeguro->executeNotification($_POST);

            if ($response->status == 3 || $response->status == 4) {
                if ($response->items->item->description == 'Pagamento ambulante') {
                    $aux = 1;
                } else if ($response->items->item->description == 'Pagamento multa') {
                    $aux = 0;
                }

                $referenceId = explode("-", $response->reference);
                if ($referenceId[1] == "1") {
                    $payment = (new Payment())->findById(intval($referenceId[0]));

                    $payment->status = 1;
                    $payment->pago_em = date('Y-m-d H:i:s');
                    $paymentValue = $payment->valor;
                    $paymentDate = $payment->pagar_em;
                    $payment->save();

                    $paymentDate = date('Y-m-d H:i:s', strtotime("+1 month", strtotime($paymentDate)));

                    if ($aux === 1) {
                        $salesman = (new Salesman())->findById($payment->id_ambulante);
                        $salesman->situacao = 1;
                        $salesman->save();


                        $payment = new Payment();
                        $payment->pagar_em = $paymentDate;
                        $payment->valor = $paymentValue;
                        $payment->status = 0;
                        $payment->tipo = 1;
                        $payment->id_ambulante = $salesman->id;
                        $payment->save();
                    }
                } else {
                    $payment = (new Payment())->findById(intval($referenceId[0]));

                    $payment->status = 1;
                    $payment->pago_em = date('Y-m-d H:i:s');
                    $paymentValue = $payment->valor;
                    $paymentDate = $payment->pagar_em;
                    $payment->save();

                    $companyId = $payment->id_empresa;
                    $paymentDate = date('Y-m-d H:i:s', strtotime("+1 month", strtotime($paymentDate)));

                    $payment = new Payment();
                    $payment->pagar_em = $paymentDate;
                    $payment->valor = $paymentValue;
                    $payment->status = 0;
                    $payment->tipo = 1;
                    $payment->id_empresa = intval($companyId);
                    $payment->save();
                }
            }
        }
    }

    function checkCpf($cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);
        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    public function order($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $validate = false;

        if ($data['type'] == 1) {
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" .
                url("order") . "/1/" . $data['licenseId'];
            $salesman = (new Salesman())->find('MD5(id) = :id', 'id=' . $data['licenseId'])->fetch();
            if ($salesman) {
                $license = (new License())->findById($salesman->id_licenca);
                if ($license) {
                    $user = (new User())->findById($license->id_usuario);
                    $auxiliaries = '';
                    $auxs = (new Auxiliary())
                        ->find('id_ambulante = :id', 'id=' . $salesman->id, 'nome')
                        ->fetch(true);
                    if ($auxs) {
                        foreach ($auxs as $aux) {
                            $auxiliaries .= $aux->nome . ', ';
                        }
                    }

                    $template = file_get_contents(THEMES . "/assets/orders/salesmanOrder.php");
                    $variables = array("%qrcode%", "%process%", "%name%", "%identity%", "%ativity%", "%equipaments%", "%width%",
                        "%street%", "%aux%", "%day%", "%month%", "%year%", "%day2%", "%month2%", "%year2%");
                    $dataReplace = array($qrUrl, "", $user->nome, $user->cpf, $salesman->relato_atividade,
                        $salesman->tipo_equipamento, $salesman->area_equipamento, $salesman->local_endereco, $auxiliaries,
                        date('d', strtotime($license->data_inicio)), date('m', strtotime($license->data_inicio)),
                        date('Y', strtotime($license->data_inicio)),
                        date('d', strtotime($license->data_fim)), date('m', strtotime($license->data_fim)),
                        date('Y', strtotime($license->data_fim)));
                    $template = str_replace($variables, $dataReplace, $template);

                    if ($template) {
                        $validate = true;
                    }
                }
            }
        } else if ($data['type'] == 7) {
            $license = (new License())->find("MD5(id) = :id", "id=" . $data['licenseId'])->fetch(false);
            $user = (new User())->findById($license->id_usuario);
            $market = (new Market())->find("MD5(id_licenca) = :lid", "lid=" . $data['licenseId'])->fetch();
            $box = (new Fixed())->findById($market->id_vaga);
            $zone = (new Zone())->findById($market->id_zona);
            $template = file_get_contents(THEMES . "/assets/orders/marketOrder.html");
            $variables = array("%cpf%", "%rg%", "%residencia%", "%box%", "%market%", "%valor%", "%valorextenso%");
            if ($box->nome == NULL) {
                $box_nome = $box->cod_identificador;
            } else {
                $box_nome = $box->nome;
            }
            $dataReplace = array($user->cpf, $user->rg, $user->endereco, $box_nome, $zone->nome, $box->valor, $box->valor);
            $template = str_replace($variables, $dataReplace, $template);

            if ($template) {
                $validate = true;
            }
        } else {
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" .
                url("order") . "/2/" . $data['licenseId'];
            $company = (new Company())->find('MD5(id) = :id', 'id=' . $data['licenseId'])->fetch();

            if ($company) {
                $neighborhoods = array();
                $salesmans = (new Salesman())
                    ->find('id_empresa = :id', 'id=' . $company->id, 'id_bairro')->fetch(true);

                if ($salesmans) {
                    foreach ($salesmans as $salesman) {
                        $neighborhood = (new Neighborhood())->findById($salesman->id_bairro, 'nome');
                        if ($neighborhood) {
                            $aux = false;
                            $lenght = count($neighborhoods);
                            if ($lenght > 0) {
                                for ($i = 0; $i < $lenght; $i++) {
                                    if ($neighborhoods[$i] == $neighborhood->nome) {
                                        $aux = true;
                                    }
                                }
                            }
                            if ($aux == false) {
                                $neighborhoods[$lenght] = $neighborhood->nome;
                            }
                        }
                    }
                }

                $neighAux = '';
                $neighLenght = count($neighborhoods);
                if ($neighLenght > 0) {
                    foreach ($neighborhoods as $neighborhood) {
                        if ($neighborhood == $neighborhoods[$neighLenght - 1]) {
                            $neighAux .= $neighborhood;
                        } else {
                            $neighAux .= $neighborhood . ', ';
                        }
                    }
                }

                $license = (new License())->findById($company->id_licenca);
                $template = file_get_contents(THEMES . "/assets/orders/companyOrder.php");
                $variables = array("%qrcode%", "%process%", "%companyName%", "%identity%", "%ativity%", "%equipaments%", "%width%",
                    "%autorizedQuantity%", "%neighborhoods%", "%day%", "%month%", "%year%", "%day2%", "%month2%", "%year2%");
                $dataReplace = array($qrUrl, "", $company->nome_fantasia, $company->cnpj, $company->relato_atividade, "",
                    "", $company->quantidade_equipamentos, $neighAux,
                    date('d', strtotime($license->data_inicio)), date('m', strtotime($license->data_inicio)),
                    date('Y', strtotime($license->data_inicio)),
                    date('d', strtotime($license->data_fim)), date('m', strtotime($license->data_fim)),
                    date('Y', strtotime($license->data_fim)));
                $template = str_replace($variables, $dataReplace, $template);

                if ($template) {
                    $validate = true;
                }
            }
        }

        if ($validate) {
            echo $template;
        } else {
            $this->router->redirect('web.home');
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public
    function error(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        echo $this->view->render('error', [
            'title' => "Erro {$data['errcode']} | " . SITE,
            'error' => $data['errcode'],
        ]);
    }

    /**
     * @return void
     * Send contact Email
     */
    public function formContact($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $email = new Email();

        $message = file_get_contents(THEMES . "/assets/emails/notificationEmail.php");

        $template = array("%title", "%textBody", "%name", "%dataTitle", "%dataDescription");
        $dataReplace = array("Recebemos uma mensagem", "recebemos uma mensagem de", $_SESSION['user']['name'], $data['phone'], $data['description']);
        $message = str_replace($template, $dataReplace, $message);

        $email->add(
            "Recebemos uma mensagem",
            $message,
            COMPANY,
            EMAIL
        )->send();

        if ($email->error()) {
            var_dump($email->error()->getMessage());
        } else {
            echo 1;
        }
    }

    public function createNeighborhood(): void
    {
        $geojson = json_decode(file_get_contents(THEMES . "/assets/geojson/bairros.json"))->features;
        foreach ($geojson as $neigh) {
            $coordinates = $neigh->geometry->coordinates;

            $array_point = array();

            foreach ($coordinates as $coordinate) {
                for ($i = 0; $i < count($coordinate); $i++) {
                    $array_point[] = $coordinate[$i][1] . " " . $coordinate[$i][0];
                }

                $str = implode(',', $array_point);
                $polygon = 'POLYGON((' . $str . '))';

                $neighborhood = new Neighborhood();
                $neighborhood->id = $neigh->properties->ID_BAIRROS;
                $neighborhood->nome = $neigh->properties->BAIRRO;
                $neighborhood->coordenadas = $polygon;
                $neighborhood->save(['polygon']);

                if ($neighborhood->fail()) {
                    var_dump($neighborhood->fail()->getMessage());
                }
            }
        }
    }

    public function neighborhood($data): void
    {
        $this->checkAgent();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $neighborhood = (new Neighborhood())->find('MD5(id) = :id', 'id=' . $data['id'],
            'ST_AsText(ST_Centroid(coordenadas)) as centroid, id, nome, ST_AsText(coordenadas) as polygon')->fetch(false);

        $aux = array();
        $neighborhoodData = array();

        if ($neighborhood) {
            $centroid = explode("POINT(", $neighborhood->centroid);
            $centroid = explode(")", $centroid[1]);
            $centroid = explode(" ", $centroid[0]);
            $neighborhood->centroid = $centroid;

            $polygon = explode("POLYGON((", $neighborhood->polygon);
            $polygon = explode("))", $polygon[1]);
            $polygon = explode(",", $polygon[0]);

            $aux = array();
            foreach ($polygon as $polig) {
                $polig = explode(" ", $polig);
                $aux[] = $polig;
            }

            $users = array();

            $salesmans = (new Salesman())->find('id_bairro = :id', 'id=' . $neighborhood->id)->fetch(true);
            if ($salesmans) {
                foreach ($salesmans as $salesman) {
                    $license = (new License())->findById($salesman->id_licenca, 'id_usuario');
                    if ($license) {
                        $user = (new User())->findById($license->id_usuario, 'id, cpf, nome, telefone');
                        if ($user) {
                            $users[] = ['id' => $salesman->id, 'identity' => $user->cpf, 'name' => $user->nome,
                                'phone' => $user->telefone, 'licenseId' => md5($salesman->id_licenca)];
                        }
                    }
                }
            }

            if (count($users) == 0) {
                $users = null;
            }


            $neighborhood->id = md5($neighborhood->id);

            echo $this->view->render('neighborhood', [
                'title' => 'Bairro | ' . SITE,
                'neighborhood' => $neighborhood,
                'coordinates' => $aux,
                'users' => $users
            ]);
        } else {
            $this->router->redirect('web.salesmanMap');
        }
    }

    public function neighborhoodPolygon(): void
    {
        $neighborhoods = (new Neighborhood())->find('', '', 'id, nome, ST_AsText(coordenadas) as polygon')->fetch(true);
        $aux = array();
        $neighborhoodData = array();
        if ($neighborhoods) {
            foreach ($neighborhoods as $neighborhood) {
                $polygon = explode("POLYGON((", $neighborhood->polygon);
                $polygon = explode("))", $polygon[1]);
                $polygon = explode(",", $polygon[0]);

                $neighborhood->id = md5($neighborhood->id);

                $aux = array();
                foreach ($polygon as $polig) {
                    $polig = explode(" ", $polig);
                    $aux[] = $polig;
                }

                $polygon = $aux;
                $neighborhoodData[] = ['id' => $neighborhood->id, 'name' => $neighborhood->nome, 'polygon' => $polygon];
            }
        }
        echo json_encode($neighborhoodData);
    }

    public function teste(): void
    {
        $cpf = "03432534701";

        $soap_input =
            '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:e="e-Agata_18.11">
               <soapenv:Header/>
               <soapenv:Body>
                  <e:PWSRetornoPertences.Execute>
                     <e:Flagtipopesquisa>C</e:Flagtipopesquisa>
                     <e:Ctgcpf>' . $cpf . '</e:Ctgcpf>
                     <e:Ctiinscricao></e:Ctiinscricao>
                  </e:PWSRetornoPertences.Execute>
               </soapenv:Body>
            </soapenv:Envelope>';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, PERTENCES);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $soap_input);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $soap_response = curl_exec($curl);

        $xml_response = str_ireplace(['SOAP-ENV:', 'SOAP:', '.executeresponse', '.SDTRetornoPertences'], '', $soap_response);

        @$xml = new SimpleXMLElement($xml_response, NULL, FALSE);
        $companys = $xml->Body->PWSRetornoPertences->Sdtretornopertences->SDTRetornoPertencesItem->SDTRetornoPertencesEmpresa->SDTRetornoPertencesEmpresaItem;

        $companyAux = 0;
        if ($companys !== "") {
            foreach ($companys as $company) {
                if ($company->SRPAutonomo == "A") {
                    var_dump($company);
                }
            }
        }
    }

    public function findNeighborhood($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $neighborhood = (new Neighborhood())->find('MD5(id) = :id', 'id=' . $data['id'],
            'ST_AsText(ST_Centroid(coordenadas)) as centroid, id, nome, ST_AsText(coordenadas) as polygon')
            ->fetch(false);

        $aux = array();
        $neighborhoodData = array();

        if ($neighborhood) {
            $centroid = explode("POINT(", $neighborhood->centroid);
            $centroid = explode(")", $centroid[1]);
            $centroid = explode(" ", $centroid[0]);

            $polygon = explode("POLYGON((", $neighborhood->polygon);
            $polygon = explode("))", $polygon[1]);
            $polygon = explode(",", $polygon[0]);

            $aux = array();
            foreach ($polygon as $polig) {
                $polig = explode(" ", $polig);
                $aux[] = $polig;
            }

            $users = array();

            $salesmans = (new Salesman())->find('id_bairro = :id', 'id=' . $neighborhood->id)->fetch(true);
            if ($salesmans) {
                foreach ($salesmans as $salesman) {
                    $license = (new License())->findById($salesman->id_licenca, 'id_usuario, status');
                    if ($license) {
                        $user = (new User())->findById($license->id_usuario, 'id, cpf, nome, telefone');
                        if ($user) {
                            $users[] = ['id' => $salesman->id, 'identity' => $user->cpf, 'name' => $user->nome,
                                'phone' => $user->telefone, 'licenseId' => md5($salesman->id_licenca),
                                'lat' => $salesman->latitude, 'lng' => $salesman->longitude, 'status' => $license->status];
                        }
                    }
                }
            }

            $respose = array();
            $respose[] = ['salesmans' => $users, 'centroid' => $centroid, 'coordinates' => $aux,
                'neighborhoodName' => $neighborhood->nome, 'neighborhoodId' => $data['id']];

            echo json_encode($respose);
        }
    }

    public function neighborhoodList(): void
    {
        $this->checkAgent();

        $neighborhoods = (new Neighborhood())->find('', '', 'id, nome, quantidade_ambulantes')->fetch(true);

        echo $this->view->render('neighborhoodList', [
            'title' => 'Bairros | ' . SITE,
            'neighborhoods' => $neighborhoods
        ]);
    }
}

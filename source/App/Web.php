<?php

namespace Source\App;

use Source\Models\Attach;
use Source\Models\License;
use Source\Models\LicenseType;
use Source\Models\Neighborhood;
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

                        $validate = 1;
                    }
                }
            }
        } else {
            $agent = (new Agent())->find('cpf = :identity AND senha = :password', 'identity=' . $data['identity'] . '&password=' . md5($data['psw']))->fetch();
            if ($agent) {
                $attach = (new Attach())->find('id_usuario = :id', 'id=' . $agent->id)->fetch(false);
                if ($attach) {
                    $_SESSION['user']['login'] = 3;
                    $_SESSION['user']['tipo'] = $agent->tipo_fiscal;
                    $_SESSION['user']['id'] = $agent->id;
                    $_SESSION['user']['name'] = $agent->nome;
                    $_SESSION['user']['image'] = ROOT . '/themes/assets/uploads/agents/' . $attach->id_usuario
                        . '/' . $attach->nome;
                    $_SESSION['user']['email'] = $agent->email;

                    $validate = 1;
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

    public function checkAccount($data): void
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
                    $aux = 1;
                }
            }
            echo $aux;
        }
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

        $cpf = preg_replace('/[^0-9]/is', '', $data['identity']);
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

        if ($companys == '' || $companyAux === 0) {
            echo 'require_registration';
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
    }

    /**
     * @return void
     * @var $data
     */
    public function requestLicense(): void
    {
        $this->checkLogin();

        echo $this->view->render('requestLicense', [
            'title' => 'Nova licença | ' . SITE
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

        $license = (new License())->find('MD5(id) = :id', 'id=' . $data['licenseId'])->fetch();
        $agents = (new Agent())->find('', '', 'id, nome')->fetch(true);
        $notifications = false;
        $notificatonAgents = array();
        if ($license) {
            $payments = (new Payment())->find('id_licenca = :id', 'id=' . $license->id)->fetch(true);
            $user = (new User())->findById($license->id_usuario, 'id, nome');
            if ($user) {
                switch ($data['licenseType']) {
                    case 1:
                        $licenseInfo = (new Salesman())->find('id_licenca = :id', 'id=' . $license->id)->fetch();
                        $templateName = 'salesmanLicenseInfo';
                        $groupName = 'salesmans';

                        $companyLicenses = (new License())->find('id_usuario = :id AND tipo = 2', 'id=' . $_SESSION['user']['id'], 'id')->fetch(true);
                        if ($companyLicenses) {
                            foreach ($companyLicenses as $companyLicense) {
                                if ($licenseInfo->id_empresa == $companyLicense->id){
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
                        $licenseInfo = (new Company())->find('id_licenca = :id', 'id=' . $license->id)->fetch();
                        $templateName = 'companyLicenseInfo';
                        $groupName = 'companys';
                        break;
                }

                if ($licenseInfo) {
                    $uploads = array();
                    $attachments = (new Attach())->find('id_usuario = :id AND tipo_usuario = :type', 'id=' . $license->id . '&type=' . $data['licenseType'])->fetch(true);
                    $salesmans = (new Salesman())->find('id_empresa= :id', 'id=' . $license->id)->fetch(true);
                    $arrayAux = array();

                    if($salesmans){
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

                    if ($attachments) {
                        foreach ($attachments as $attach) {
                            $uploads[] = [
                                'fileName' => $attach->nome,
                                'groupName' => $groupName,
                                'userId' => $license->id
                            ];
                        }

                        $validate = true;

                        echo $this->view->render($templateName, [
                            'title' => 'Minha Licença | ' . SITE,
                            'license' => $licenseInfo,
                            'licenseValidate' => $license,
                            'licenseStatus' => $license->status,
                            'user' => $user->nome,
                            'userId' => $user->id,
                            'uploads' => $uploads,
                            'payments' => $payments,
                            'agents' => $agents,
                            'notifications' => $notifications,
                            'companyConfirm' => $aux,
                            'salesmans' => $arrayAux
                        ]);
                    }
                }
            }
        }

        if ($validate == false) {
            $this->router->redirect('web.home');
        }
    }

    public function licenseStatus($data): void
    {
        $this->checkLogin();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $register = (new License())->findById($data['id']);

        if($register){
            $register->status = $data['status'];
            $register->save();
        }
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

            if ($companys != '') {
                $companyAux = 0;
                foreach ($companys as $company) {
                    if ($company->SRPAutonomo == 'A') {
                        $companyAux = $company->SRPInscricaoEmpresa;
                    }
                }
            }

            if ($companyAux == 0) {
                exit();
            }

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

            $neighborhood = (new Neighborhood())->find('ST_CONTAINS(ST_GEOMFROMTEXT(ST_AsText(coordenadas)), ST_GEOMFROMTEXT("' . $point . '"))=1', '', 'id, coordenadas')->fetch(false);
            $neighborhoodId = "";

            if ($neighborhood) {
                $neighborhoodId = $neighborhood->id;
            }

            if ($zoneId === null) {
                $salesmans = (new Salesman)->find('latitude = :lat AND longitude = :lng', 'lat=' . $data['latitude'] . '&lng=' . $data['longitude'], 'latitude, longitude')->fetch(true);
                if ($salesmans) {
                    $response = 'somebodySameLocation';
                    $zoneId = null;
                    $zone->quantidade_ambulantes--;
                    $zone->save();
                }
            }

            if ($zoneId) {
                $license = new License();
                $license->tipo = 1;

                if ($data['companyId']) {
                    $company = (new Company)->findById($data['companyId']);
                    if ($company) {
                        $license->status = 3;
                    }
                } else {
                    $license->status = 0;
                }

                $license->id_usuario = $_SESSION['user']['id'];
                $license->data_inicio = date('Y-m-d');
                $license->data_fim = date('Y-m-d', strtotime("+3 days"));
                $license->cmc = $companyAux;
                $license->save();

                if ($license->fail()) {
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
                                        $productDescription = $data['productDescription'];
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

                                if ($data['companyId']) {
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
                                $salesman->relato_atividade = $productDescription;
                                $salesman->area_equipamento = $data['width'] . " x " . $data['length'];
                                $salesman->tipo_equipamento = $data['howWillSell'];
                                $salesman->save();

                                /**
                                 * Send email with new temporary recovery password
                                 */
                                $email = new Email();

                                $message = file_get_contents(THEMES . "/assets/emails/confirmSalesman.php");

                                $url = ROOT . "/licenseInfo/1/" . md5($license->id);
                                $template = array("%title", "%textBody", "%button", "%link", "%companyName", "%name");
                                $dataReplace = array("Confirmação de vínculo", "Para confirmar vínculo de", "Visualizar", $url, $company->nome_fantasia, $user->nome);
                                $message = str_replace($template, $dataReplace, $message);

                                $email->add(
                                    "Confirmar vínculo",
                                    $message,
                                    $user->nome,
                                    $user->email
                                )->send();

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
    public function licenseList(): void
    {
        $this->checkLogin();

        $license_type = (new LicenseType())->find()->fetch(true);
        $users = array();
        if ($_SESSION['user']['login'] == 3) {
            $licenses = (new License())->find()->fetch(true);

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
            $payments = (new Payment())->find('id_usuario = :id', 'id=' . $_SESSION['user']['id'])->fetch(true);

            $folder = ROOT . '/themes/assets/uploads';
            $uploads = array();
            $attachments = (new Attach())->find('id_usuario = :id AND tipo_usuario = 0', 'id=' . $user->id)->fetch(true);
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
                'userImage' => $userImage
            ]);
        } else {
            $this->router->redirect('web.salesmanList');
        }
    }

    /**
     * @param $data
     */
    public function createNotification($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $agent = (new Agent())->findById($data['agentSelect']);
        if ($agent) {
            $salesman = (new Salesman())->findById($data['licenseId']);
            if ($salesman) {
                $notification = new Notification();
                $notification->id_licenca = $salesman->id_licenca;
                $notification->data_notificacao = $data['date'];
                $notification->hora_notificacao = $data['time'];
                $notification->titulo = $data['title'];
                $notification->descricao = $data['noticationDescription'];
                $notification->id_fiscal = $agent->id;
                $notification->save();

                if (isset($data['blockAccess']) && $data['blockAccess'] == 1) {
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
                        exit();
                    }

                    if (!($data['penality'] == '' || $data['penality'] == ' ' || $data['penality'] == 0 || $data['penality'] == 00)) {
                        $license = (new License())->findById($salesman->id_licenca);
                        if (!$license) {
                            exit();
                        }

                        $paymentDate = date('Y-m-d', strtotime("+3 days"));
                        $payment = new Payment();
                        $payment->id_licenca = $license->id;
                        $payment->cod_referencia = null;
                        $payment->cod_pagamento = null;
                        $payment->valor = $data['penality'];
                        $payment->id_usuario = $license->id_usuario;
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

                        $email = new Email();

                        $message = file_get_contents(THEMES . "/assets/emails/notificationEmail.php");

                        $url = ROOT;
                        $template = array("%title", "%textBody", "%status", "%titleStatus", "%button", "%link", "%name", "%dataTitle", "%dataDescription");
                        $dataReplace = array("Notificação", "Sua conta apresenta uma", "MULTA", "multa", "Acesse", $url, $salesman->nome, $data['title'], $data['description']);
                        $message = str_replace($template, $dataReplace, $message);

                        $email->add(
                            "Multa",
                            $message,
                            $salesman->nome,
                            $salesman->email
                        )->send();

                        if ($payment->fail()) {
                            $notification->destroy();
                            var_dump($payment->fail()->getMessage());
                            exit();
                        }

                        $notification->id_boleto = $payment->id;
                        $notification->save();
                    }

                    if ($notification->fail()) {
                        echo 0;
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
                if ($license) {
                    $user = (new User())->findById($license->id_usuario);
                    $payment->name = $user->nome;
                    $paymentArray[] = $payment;
                }

                if ($payment->status == 0 || $payment->status == 3) {
                    $auxPendent++;
                } else if ($payment->status == 1) {
                    $auxPaid++;
                } else {
                    $auxExpired++;
                }
            }
            $paymentCount = count($payments);
        } else {
            $paymentArray = null;
        }

        echo $this->view->render('paymentList', [
            'title' => 'Pagamentos | ' . SITE,
            'payments' => $paymentArray,
            'amount' => $paymentCount,
            'paid' => $auxPaid,
            'pendent' => $auxPendent,
            'expired' => $auxExpired
        ]);
    }

    /**
     * @return void
     */
    public function agentList(): void
    {
        $agents = (new Agent)->find('', '', 'id, matricula, cpf, email, nome, situacao')->fetch(true);
        $apporved = 0;
        $blocked = 0;
        $pendding = 0;
        foreach ($agents as $agent) {
            if ($agent->situacao == 1) {
                $apporved++;
            } else if ($agent->situacao == 0) {
                $pendding++;
            } else {
                $blocked++;
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

    /**
     * @return void
     */
    public function salesmanMap(): void
    {
        $zoneData = array();
        $zones = (new Zone())->find('', '', 'id, ST_AsText(coordenadas) as poligono, ST_AsText(ST_Centroid(coordenadas)) as centroide, nome, limite_ambulantes, quantidade_ambulantes')->fetch(true);

        $pending = array();
        $expired = array();
        $paid = array();

        if ($_SESSION['user']['login'] == 3) {
            $salesmans = (new Salesman())->find('', '', 'id_licenca, latitude, longitude')->fetch(true);
            if ($salesmans) {
                foreach ($salesmans as $salesman) {
                    $license = (new License())->findById($salesman->id_licenca, 'status');
                    if ($license) {
                        $salesman->status = $license->status;

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
        } else {
            $zoneData = null;
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
    public function createZone()
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

        echo $this->view->render("createAgent", [
            "title" => "Cadastrar fiscal | " . SITE
        ]);
    }

    /**
     * @return void
     */
    /**
     * @return void
     */
    public function validateNewAgent($data): void
    {
        /**
         * Filter all form data
         */
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $agent = (new Agent())->find('matricula = :matricula', 'matricula=' . $data['registration'])->fetch();
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
            $agent->save();

            $dir = $folder . '/agents/' . $agent->id;
            if (!file_exists($dir) || !is_dir($dir)) {
                mkdir($dir, 0755);

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
    }

    /**
     * @return void
     */
    public
    function validateZone($data)
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

            $zone->save(['polygon']);

            if ($zone->fail()) {
                var_dump($zone->fail()->getMessage());
            } else {
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
    public
    function zone(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if (is_numeric($data['id'])) {
            $zone = (new Zone())->find('id = :zoneId', 'zoneId=' . $data['id'], 'id, ST_AsText(coordenadas) as poligono, ST_AsText(ST_Centroid(coordenadas)) as centroide, nome, limite_ambulantes, quantidade_ambulantes, foto, descricao')->fetch();
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
                        'title' => 'Zona | ' . SITE,
                        'salesmans' => null,
                        'zone' => $zone
                    ]);
                } else {
                    echo $this->view->render('zone', [
                        'title' => 'Zona | ' . SITE,
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
     * @return void
     */
    public
    function checkLogin(): void
    {
        if (!isset($_SESSION['user']['login'])) {
            $this->router->redirect('web.home');
        }
    }

    /**
     * Check if an agent
     * @return data
     */
    public
    function checkAgent(): void
    {
        if (!isset($_SESSION['user']['login']) || (isset($_SESSION['user']['login']) && !($_SESSION['user']['login'] === 3))) {
            $this->router->redirect('web.home');
        }
    }

    /**
     * Check if an agent/company
     * @return data
     */
    public
    function checkuser(): void
    {
        if (!isset($_SESSION['user']['login']) || (isset($_SESSION['user']['login']) && ($_SESSION['user']['login'] === 1))) {
            $this->router->redirect('web.home');
        }
    }

    /**
     * Only to SingIn and SignUp
     * @return void
     */
    public
    function checkIsOff(): void
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
    public
    function downloadFile(array $data): void
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

    public
    function removeSuspension($data): void
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

    public
    function zoneConfirm($data): void
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

    public
    function videos(): void
    {
        echo $this->view->render('videos', [
            'title' => "Vídeos | " . SITE
        ]);
    }

    /**
     * Return from PagSeguro
     * @return void
     */
    public
    function securePayment(): void
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
        $salesman = (new Salesman())->find('MD5(id) = :id', 'id='. $data['licenseId'])->fetch();
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" .
            url("order") . "/" . $data['licenseId'];
        if ($salesman) {
           $license = (new License())->findById($salesman->id_licenca);
           if ($license) {
               $user = (new User())->findById($license->id_usuario);
               $template = file_get_contents(THEMES . "/assets/orders/salesmanOrder.php");
               $variables = array("%qrcode%" ,"%process%", "%name%", "%identity%", "%ativity%", "%equipaments%", "%width%",
                   "%street%", "%aux%", "%day%", "%month%", "%year%", "%day2%", "%month2%", "%year2%");
               $dataReplace = array($qrUrl ,"", $user->nome, $user->cpf, $salesman->relato_atividade,
                   $salesman->tipo_equipamento, $salesman->area_equipamento, $salesman->local_endereco, "",
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
            echo "<script>alert('Aguarde até que o qrcode seja gerado.');</script>";
            echo $template;
        } else {
            $this->router->redirect('web.home');
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function error(array $data): void
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

        $template = array("%title", "%textBody", "%status", "%titleStatus", "%name", "%dataTitle", "%dataDescription");
        $dataReplace = array("Recebemos uma mensagem", "recebemos uma mensagem de", "MULTA", "multa", $salesman->nome, $data['title'], $data['description']);
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

    public function neighborhood(): void
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

    public function neighborhoodList(): void
    {
        $neighborhoods = (new Neighborhood())->find('', '', 'id, nome, ST_AsText(coordenadas) as polygon')->fetch(true);
        $aux = array();
        $neighborhoodData = array();
        if ($neighborhoods) {
            foreach ($neighborhoods as $neighborhood) {
                $polygon = explode("POLYGON((", $neighborhood->polygon);
                $polygon = explode("))", $polygon[1]);
                $polygon = explode(",", $polygon[0]);

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
}

<?php

namespace Source\App;

use Source\Models\Attach;
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
use Source\Models\Report;
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
            $salesmans = (new Salesman())
                ->find('situacao = 1', '', 'latitude, longitude, nome, foto')->fetch(true);

            echo $this->view->render('home', [
                'title' => 'Início | ' . SITE,
                'salesmans' => $salesmans
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
        $salesman = (new Salesman())->find('identidade = :identity AND senha = :password',
            'identity=' . $data['identity'] . '&password=' . md5($data['psw']))->fetch();
        if ($salesman) {
            $attachs = (new Attach())->find('id_usuario = :id', 'id=' . $salesman->id)->fetch(true);
            if ($attachs) {
                foreach ($attachs as $attach) {
                    $attachName = explode('.', $attach->file_name)[0];
                    if ($attachName == 'userImage') {
                        $_SESSION['user']['image'] = ROOT . '/themes/assets/uploads/salesmans/' . $attach->id_usuario
                            . '/' . $attach->file_name;
                        $_SESSION['user']['login'] = 1;
                        $_SESSION['user']['id'] = $salesman->id;
                        $_SESSION['user']['name'] = $salesman->nome;
                        $_SESSION['user']['email'] = $salesman->email;
                    }
                }
            }
            echo 2;
        } else {
            $salesman = (new Salesman())->find('identidade = :identity AND senha_temporaria = :password',
                'identity=' . $data['identity'] . '&password=' . md5($data['psw']))->fetch();
            if ($salesman) {
                $attachs = (new Attach())->find('id_usuario = :id', 'id=' . $salesman->id)->fetch(true);
                if ($attachs) {
                    foreach ($attachs as $attach) {
                        $attachName = explode('.', $attach->file_name)[0];
                        if ($attachName == 'userImage') {
                            $_SESSION['user']['image'] = ROOT . '/themes/assets/uploads/salesmans/' . $attach->id_usuario
                                . '/' . $attach->file_name;
                            $_SESSION['user']['login'] = 1;
                            $_SESSION['user']['id'] = $salesman->id;
                            $_SESSION['user']['name'] = $salesman->nome;
                            $_SESSION['user']['email'] = $salesman->email;
                            $salesman->senha = md5($data['psw']);
                            $salesman->senha_temporaria = '';
                            $salesman->save();
                        }
                    }
                }
                echo 1;
            } else {
                $company = (new Company())->find('cnpj = :cnpj AND senha = :password',
                    'cnpj=' . $data['identity'] . '&password=' . md5($data['psw']))->fetch();
                if ($company) {
                    $attachs = (new Attach())->find('id_usuario = :id', 'id=' . $company->id)->fetch(true);
                    if ($attachs) {
                        foreach ($attachs as $attach) {
                            $attachName = explode('.', $attach->file_name)[0];
                            if ($attachName == 'userImage') {
                                $_SESSION['user']['image'] = ROOT . '/themes/assets/uploads/companys/' . $attach->id_usuario
                                    . '/' . $attach->file_name;
                                $_SESSION['user']['login'] = 2;
                                $_SESSION['user']['id'] = $company->id;

                                $_SESSION['user']['name'] = $company->nome_fantasia;
                                $_SESSION['user']['email'] = $company->email;
                            }
                        }
                    }
                    echo 2;
                } else {
                    $company = (new Company())->find('cnpj = :cnpj AND senha_temporaria = :password',
                        'cnpj=' . $data['identity'] . '&password=' . md5($data['psw']))->fetch();
                    if ($company) {
                        $attachs = (new Attach())->find('id_usuario = :id', 'id=' . $company->id)->fetch(true);
                        if ($attachs) {
                            foreach ($attachs as $attach) {
                                $attachName = explode('.', $attach->file_name)[0];
                                if ($attachName == 'userImage') {
                                    $_SESSION['user']['image'] = ROOT . '/themes/assets/uploads/companys/' . $attach->id_usuario
                                        . '/' . $attach->file_name;
                                    $_SESSION['user']['login'] = 2;
                                    $_SESSION['user']['id'] = $company->id;
                                    $_SESSION['user']['name'] = $company->nome_fantasia;
                                    $_SESSION['user']['email'] = $company->email;
                                    $company->senha = md5($data['psw']);
                                    $company->senha_temporaria = '';
                                    $company->save();
                                }
                            }
                        }
                        echo 1;
                    }
                }
            }
        }

        if (!isset($_SESSION['user']['login'])) {
            echo 0;
        }
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
     */
    public function validateAgent($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $agent = (new Agent())->find('matricula = :registration AND senha = :password', 'registration=' . $data['registration'] . '&password=' . md5($data['password']))->fetch();
        if ($agent) {
            $attach = (new Attach())->find('id_usuario = :id', 'id=' . $agent->id)->fetch(false);
            if ($attach) {
                $_SESSION['user']['login'] = 3;
                $_SESSION['user']['id'] = $agent->id;
                $_SESSION['user']['name'] = $agent->nome;
                $_SESSION['user']['image'] = ROOT . '/themes/assets/uploads/agents/' . $attach->id_usuario
                    . '/' . $attach->file_name;
                $_SESSION['user']['email'] = $agent->email;
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    /**
     * @return void
     * New user image
     */
    public function updateUserImg($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if ($_FILES) {
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
                            $ext = explode('.', $att->file_name);
                            if ($ext[0] == 'userImage') {
                                switch ($att->tipo_usuario) {
                                    case 1:
                                        $folder = THEMES . '/assets/uploads/salesmans';
                                        $folder2 = ROOT . '/themes/assets/uploads/salesmans';
                                        break;
                                    case 2:
                                        $folder = THEMES . '/assets/uploads/companys';
                                        $folder2 = ROOT . '/themes/assets/uploads/companys';
                                        break;
                                    case 3:
                                        $folder = THEMES . '/assets/uploads/agents';
                                        $folder2 = ROOT . '/themes/assets/uploads/agents';
                                        break;
                                    default:
                                        $folder = null;
                                        break;
                                }

                                if ($folder) {
                                    if (!file_exists($folder) || !is_dir($folder)) {
                                        mkdir($folder, 0755);
                                    }

                                    $filename = $ext[0] . '.' . $imageFileType;

                                    $dir = $folder . '/' . $att->id_usuario . '/' . $filename;
                                    $dir2 = $folder2 . '/' . $att->id_usuario . '/' . $filename;

                                    if (file_exists($dir)) {
                                        unlink($dir);
                                    }

                                    $att->file_name = $filename;
                                    $att->save();

                                    move_uploaded_file($file['tmp_name'], $dir);
                                    $_SESSION['user']['image'] = $dir2;

                                    echo 1;
                                }
                            }
                        }
                    }
                }
            }
        }
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
     */
    public function companyProfile(): void
    {
        $this->checkLogin();

        if ($_SESSION['user']['login'] == 1) {
            $this->router->redirect("web.profile");
        }

        $company = (new Company())->findById($_SESSION['user']['id']);
        if ($company !== null) {
            $payments = (new Payment())->find('id_empresa = :id', 'id=' . $company->id)->fetch(true);
            $salesmans = (new Salesman())->find('id_empresa = :id', 'id=' . $company->id)->fetch(true);
            $zones = (new Zone())->find('', '', 'id, ST_AsText(coordenadas) as poligono, ST_AsText(ST_Centroid(coordenadas)) as centroide, nome, limite_ambulantes, quantidade_ambulantes')->fetch(true);

            $paymentArray = array();
            if ($payments) {
                foreach ($payments as $payment) {
                    $salesmanName = (new Salesman())->findById($payment->id_ambulante, 'nome');
                    if ($salesmanName) {
                        $payment->name = $salesmanName->nome;
                        $paymentArray[] = $payment;
                    }
                }
            } else {
                $paymentArray == null;
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

            $folder = ROOT . '/themes/assets/uploads';
            $uploads = array();
            $aux = 1;
            $attachments = (new Attach())->find('id_usuario = :id AND tipo_usuario = 2', 'id=' . $company->id)->fetch(true);
            if ($attachments) {
                foreach ($attachments as $attach) {
                    $attachName = explode('.', $attach->file_name);
                    if ($attachName[0] == 'userImage') {
                        $userImage = ROOT . '/themes/assets/uploads/companys/' . $attach->id_usuario
                            . '/' . $attach->file_name;
                    }

                    $uploads[] = [
                        'fileName' => $attach->file_name,
                        'groupName' => 'companys',
                        'userId' => $company->id
                    ];
                    $aux++;
                }
            }

            if ($salesmans) {
                $count = count($salesmans);
            } else {
                $count = 0;
            }

            echo $this->view->render("companyProfile", [
                'title' => 'Empresa | ' . SITE,
                'company' => $company,
                'salesmans' => $salesmans,
                'salesmansCount' => $count,
                'zones' => $zoneData,
                'payments' => $paymentArray,
                'userImage' => $userImage,
                'uploads' => $uploads
            ]);
        } else {
            $this->router->redirect('web.salesmanList');
        }
    }

    /**
     * @return void
     * @var $data
     */
    public function validateAccount($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $street = $data['street'] . ', ' . $data['city'] . ', ' . $data['neighborhood'] . ', ' . $data['number'];

        $user = new \Source\Models\User();
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
            $email = new Email();
            $email->add(
                "Confirmação de cadastro",
                "<p>Olá " . $user->name . "! Para confirmar seu cadastro no Orditi, clique no botão abaixo.</p>
                        <a class='btn-3 primary' href='https://maceio.orditi.com/" . md5($user->id) . "'>Confirmar</a>
                    <div> <img style='width: 20%' src='https://www.maceio.orditi.com/i/themes/assets/img/nav-logo.png'> </div>",
                $user->nome,
                $user->email
            )->send();
            echo 0;
        }
    }

    /**
     * @return void
     * @var $data
     */
    public function confirmAccount($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $user = (new User())->find('MD5(id) = :id AND senha = NULL', 'id=' . $data['userId'])->fetch();
        if ($user) {
            echo 1;
        } else {
            echo 0;
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
        if (!empty($_SESSION['user']['login'])) {
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
        $salesman = (new Salesman())->find('identidade = :identity', 'identity=' . $data['identity'])->fetch();
        if ($salesman !== NULL) {
            if ($salesman->identidade === $data['identity']) {
                /**
                 * Generate a new recovery password
                 */
                $editSalesman = (new Salesman)->findById($salesman->id);
                $tempPsw = md5($salesman->senha);
                $tempPsw = substr($tempPsw, 1, 5);
                $editSalesman->senha_temporaria = md5($tempPsw);
                $editSalesman->save();

                /**
                 * Send email with new temporary recovery password
                 */
                $name = explode(" ", $salesman->nome);
                $name = $name[0] . " " . $name[1];
                $email = new Email();
                $email->add(
                    "Recuperação de senha Orditi",
                    "<p style='font-family: \"Dosis\", sans-serif;'>Olá " . $name . ", sua senha de recuperação no Orditi é <span style='color: #157881;'>" . $tempPsw . "</span></p>
                    <div> <img style='width: 20%' src='https://www.maceio.orditi.com/i/themes/assets/img/nav-logo.png'> </div>",
                    $salesman->nome,
                    $salesman->email
                )->send();
            }
        }

        $company = (new Company())->find('cnpj = :identity', 'identity=' . $data['identity'])->fetch();
        if ($company !== NULL) {
            if ($company->cnpj === $data['identity']) {
                /**
                 * Generate a new recovery password
                 */
                $editCompany = (new Company())->findById($company->id);
                $tempPsw = md5($company->senha);
                $tempPsw = substr($tempPsw, 1, 5);
                $editCompany->senha_temporaria = md5(123);
                $editCompany->save();

                /**
                 * Send email with new temporary recovery password
                 */
                $email = new Email();
                $email->add(
                    "Recuperação de senha Orditi",
                    "<p style='font-family: \"Dosis\", sans-serif;'>Olá " . $company->nome_fantasia . ", sua senha de recuperação no Orditi é <span style='color: #157881;'>" . $tempPsw . "</span></p>
                    <div> <img style='width: 20%' src='https://www.maceio.orditi.com/i/themes/assets/img/nav-logo.png'> </div>",
                    $company->nome_fantasia,
                    $company->email
                )->send();
            }
        }
    }


    /**
     * @return void
     */
    public function profile(): void
    {
        $this->checkLogin();

        if ($_SESSION['user']['login'] == 2) {
            $this->router->redirect("web.companyProfile");
        }

        if ($_SESSION['user']['login'] === 1) {
            $salesman = (new Salesman())->findById($_SESSION['user']['id']);
            $payments = (new Payment())->find('id_ambulante = :id', 'id=' . $_SESSION['user']['id'])->fetch(true);

            if ($salesman->regiao != null) {
                $zone = (new Zone())->findById($salesman->regiao, 'id, nome, ST_AsText(coordenadas) as poligono, limite_ambulantes, quantidade_ambulantes');
            } else {
                $zone = null;
            }

            if ($zone) {
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
            }

            if ($salesman->suspenso == 0 && ($salesman->latitude == null || $salesman->longitude == null)) {
                $zoneData = array();
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
                } else {
                    $zoneData = null;
                }
            } else {
                $zoneData = null;
            }

            if ($salesman->id_empresa != null) {
                $company = (new Company())->findById($salesman->id_empresa, 'nome_fantasia');
            } else {
                $company = null;
            }


            $folder = ROOT . '/themes/assets/uploads';
            $uploads = array();
            $aux = 1;
            $attachments = (new Attach())->find('id_usuario = :id AND tipo_usuario = 1', 'id=' . $salesman->id)->fetch(true);
            if ($attachments) {
                foreach ($attachments as $attach) {
                    $attachName = explode('.', $attach->file_name);
                    if ($attachName[0] == 'userImage') {
                        $userImage = ROOT . '/themes/assets/uploads/salesmans/' . $attach->id_usuario
                            . '/' . $attach->file_name;
                    }

                    $uploads[] = [
                        'fileName' => $attach->file_name,
                        'groupName' => 'salesmans',
                        'userId' => $salesman->id
                    ];
                    $aux++;
                }
            }

            echo $this->view->render('profile', [
                'title' => 'Perfil | ' . SITE,
                'salesman' => $salesman,
                'payments' => $payments,
                'zone' => $zone,
                'company' => $company,
                'zones' => $zoneData,
                'uploads' => $uploads,
                'userImage' => $userImage
            ]);
        } else if ($_SESSION['user']['login'] === 3) {
            $this->router->redirect('web.salesmanList');
        }
    }

    /**
     * @param $data
     */
    public function createNotification($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $agent = (new Agent())->findById($data['id2']);
        if ($agent == NULL) {
            echo 0;
        } else {
            $salesman = (new Salesman())->findById($data['id1']);
            if ($salesman == NULL) {
                echo 0;
            } else {
                $notification = new Notification();
                $notification->ambulante_id = $data['id1'];
                $notification->data_notificacao = $data['date'];
                $notification->hora_notificacao = $data['time'];
                $notification->titulo = $data['title'];
                $notification->descricao = $data['description'];
                $notification->fiscal_id = $data['id2'];
                $notification->fiscal_nome = $agent->nome;
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

                    $email = new Email();
                    $email->add(
                        "Notificação",
                        "<p style='font-family: \"Dosis\", sans-serif;'>Olá " . $salesman->nome . ", você acaba de ter sua conta <span style='color: #ed2e54;'>SUSPENSA</span> do <span style='color: #ed2e54;'> ORDITI</span></p>
                        <p style='font-family: \"Dosis\", sans-serif;'>Título da suspensão: <span style='color: #ed2e54;'>" . $data['title'] . "</span></p>
                        <p style='font-family: \"Dosis\", sans-serif;'>Descrição: <span style='color: #ed2e54;'>" . $data['description'] . "</span></p>
                        <div> <img style='width: 20%' src='https://www.maceio.orditi.com/i/themes/assets/img/nav-logo.png'> </div>",
                        $salesman->nome,
                        $salesman->email
                    )->send();
                }

                if (!($data['penality'] == '' || $data['penality'] == ' ' || $data['penality'] == 0 || $data['penality'] == 00)) {
                    $paymentDate = date('Y-m-d', strtotime("+3 days"));

                    $payment = new Payment();
                    $payment->id_ambulante = $data['id1'];
                    $payment->valor = $data['penality'];
                    $payment->pagar_em = date('Y-m-d H:i:s', strtotime("+3 days"));
                    $payment->tipo = 0;
                    $payment->status = 0;
                    $payment->cod_referencia = null;
                    $payment->cod_pagamento = null;
                    $payment->save();

                    $extCode = 'ODT' . $payment->id;

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
                                           <e:EXTvlrvvt>' . $data['penality'] . '</e:EXTvlrvvt>
                                           <e:EXTvlrvvtdesconto>0.00</e:EXTvlrvvtdesconto>
                                           <e:EXTvencimento>' . $paymentDate . '</e:EXTvencimento>
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

                    $payment->cod_pagamento = $extCode;
                    $payment->cod_referencia = $code;
                    $payment->save();

                    $email = new Email();
                    $email->add(
                        "Multa",
                        "<p style='font-family: \"Dosis\", sans-serif;'>Olá " . $salesman->nome . ", você acaba de receber uma <span style='color: #ed2e54;'>MULTA</span> no valor de  <span style='color: #157881;'>R$" . $data['penality'] . "</span> que deverá ser paga até o dia <span style='color: #ed2e54;'>" . date('d-m-Y', strtotime($paymentDate)) . "</span></p>
                    <p style='font-family: \"Dosis\", sans-serif;'>Título da multa: <span style='color: #ed2e54;'>" . $data['title'] . "</span></p>
                    <p style='font-family: \"Dosis\", sans-serif;'>Descrição: <span style='color: #ed2e54;'>" . $data['description'] . "</span></p>
                    <div> <img style='width: 20%' src='https://www.maceio.orditi.com/i/themes/assets/img/nav-logo.png'> </div>",
                        $salesman->nome,
                        $salesman->email
                    )->send();
                }

                if ($notification->fail()) {
                    echo 0;
                } else {
                    echo 1;
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

        if ($payments) {
            foreach ($payments as $payment) {
                $salesmanName = (new Salesman())->findById($payment->id_ambulante, 'nome');
                if ($salesmanName) {
                    $payment->name = $salesmanName->nome;
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
        } else {
            $paymentArray = null;
        }

        echo $this->view->render('paymentList', [
            'title' => 'Pagamentos | ' . SITE,
            'payments' => $paymentArray,
            'amount' => count($payments),
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
        $agents = (new Agent)->find('', '', 'id, matricula, cpf, email, nome, status')->fetch(true);
        $apporved = 0;
        $blocked = 0;
        $pendding = 0;
        foreach ($agents as $agent) {
            if ($agent->status == 1) {
                $apporved++;
            } else if ($agent->status == 0) {
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
            if ($agent->status == 1) {
                $agent->status = 2;
            } else {
                $agent->status = 1;
            }

            $agent->save();
            $this->router->redirect('web.agentList');
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

        if ($data['fileType'] == 1) {
            // File Name
            $file_name = 'pagamentos.xls';

            // File Head
            $html = '';
            $html .= '<table>';
            $html .= '<tr>';
            $html .= '<td colspan="5">Planilha de pagamentos - ORDITI</td>';
            $html .= '</tr>';

            // File Fields
            $html .= '<tr>';
            $html .= '<td><b>Status</b></td>';
            $html .= '<td><b>Valor</b></td>';
            $html .= '<td><b>Vencimento</b></td>';
            $html .= '<td><b>Pagamento</b></td>';
            $html .= '<td><b>Ambulante</b></td>';
            $html .= '</tr>';

            // File Body
            $payments = (new Payment())->find->fetch(true);
            if ($payments) {
                foreach ($payments as $payment) {
                    $salesman = (new Salesman())->findById($payment->id_ambulante, 'nome');
                    if ($payment->status == 3 || $payment->status == 0) {
                        $status = "Pendente";
                    } elseif ($payment->status == 1) {
                        $status = "Pago";
                    } else {
                        $status = "Vencido";
                    }

                    $html .= '<tr>';
                    $html .= '<td>' . $status . '</td>';
                    $html .= '<td>R$ ' . $payment->valor . ',00</td>';
                    $html .= '<td>' . date('d-m-Y', strtotime($payment->pagar_em)) . '</td>';
                    $html .= '<td>' . date('d-m-Y', strtotime($payment->pago_em)) . '</td>';
                    $html .= '<td>' . $salesman->nome . '</td>';
                    $html .= '</tr>';
                }
            }
        } else if ($data['fileType'] == 2) {
            // File Name
            $file_name = 'ambulantes.xls';

            // File Head
            $html = '';
            $html .= '<table>';
            $html .= '<tr>';
            $html .= '<td colspan="5">Planilha de ambulantes - ORDITI</td>';
            $html .= '</tr>';

            // File Fields
            $html .= '<tr>';
            $html .= '<td><b>Cpf</b></td>';
            $html .= '<td><b>Nome</b></td>';
            $html .= '<td><b>Situação</b></td>';
            $html .= '<td><b>Empresa</b></td>';
            $html .= '<td><b>Localização</b></td>';
            $html .= '</tr>';

            $salesmans = (new Salesman())->find()->fetch(true);
            if ($salesmans) {
                foreach ($salesmans as $salesman) {
                    if ($salesman->stituacao == 0 || $salesman->stituacao == 3) {
                        $status = 'Pendente';
                    } else if ($salesman->stituacao == 1) {
                        $status = 'Ativo';
                    } else {
                        $status = 'Inadimplente';
                    }

                    if ($salesman->id_empresa != null) {
                        $company = (new Company())->findById($salesman->id_empresa, 'nome_fantasia');
                        $company = $company->nome_fantasia;
                    } else {
                        $company = 'Não possui';
                    }

                    $html .= '<tr>';
                    $html .= '<td>' . $salesman->identidade . '</td>';
                    $html .= '<td>' . $salesman->nome . '</td>';
                    $html .= '<td>' . $status . '</td>';
                    $html .= '<td>' . $company . '</td>';
                    $html .= '<td>' . $salesman->end_local . '</td>';
                    $html .= '</tr>';
                }
            }
        } else if ($data['fileType'] == 3) {
            $companys = (new Company())->find()->fetch(true);
            if ($companys) {
                // File Name
                $file_name = 'empresas.xls';

                // File Head
                $html = '';
                $html .= '<table>';
                $html .= '<tr>';
                $html .= '<td colspan="5">Planilha de empresas - ORDITI</td>';
                $html .= '</tr>';

                // File Fields
                $html .= '<tr>';
                $html .= '<td><b>Cnpj</b></td>';
                $html .= '<td><b>Nome</b></td>';
                $html .= '<td><b>Ambulantes</b></td>';
                $html .= '<td><b>Equipamentos</b></td>';
                $html .= '<td><b>Localização</b></td>';
                $html .= '</tr>';
                foreach ($companys as $company) {
                    $html .= '<tr>';
                    $html .= '<td>' . $company->cnpj . '</td>';
                    $html .= '<td>' . $company->nome_fantasia . '</td>';
                    $html .= '<td>' . $company->contador_ambulantes . '</td>';
                    $html .= '<td>' . $company->quantidade_equipamentos . '</td>';
                    $html .= '<td>' . $company->endereco . ', ' . $company->numero . ', ' . $company->bairro . ', ' . $company->cidade . ', ' . $company->cep . '</td>';
                    $html .= '</tr>';
                }
            }
        }

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=\"{$file_name}\"");
        header("Content-Description: PHP Generated Data");
        // Envia o conteúdo do arquivo

        echo $html;
    }

    /**
     * @return void
     */
    public function salesmanList(): void
    {
        $this->checkAgent();

        $salesmans = (new Salesman())
            ->find('', '', 'id, identidade, rg, nome, end_local, email, fone, situacao')
            ->fetch(true);
        $companys = (new Company())
            ->find('', '', 'id, cnpj, rg, nome_fantasia, email, fone, endereco, cidade, 
            bairro, numero, cep, situacao')
            ->fetch(true);

        $auxPaid = 0;
        $auxPending = 0;
        $auxBlocked = 0;
        foreach ($salesmans as $salesman) {
            if ($salesman->situacao == 1) {
                $auxPaid++;
            } else {
                $auxPending++;
            }

            if ($salesman->suspenso == 1) {
                $auxBlocked++;
            }
        }

        echo $this->view->render('salesmanList', [
            'title' => 'Usuários | ' . SITE,
            'salesmans' => $salesmans,
            'companys' => $companys,
            'registered' => count($salesmans),
            'paid' => $auxPaid,
            'pending' => $auxPending,
            'blocked' => $auxBlocked
        ]);
    }

    /**
     * @return void
     */
    public function salesmanMap(): void
    {
        $zoneData = array();
        $reports = (new Report())->find()->fetch(true);
        $zones = (new Zone())->find('', '', 'id, ST_AsText(coordenadas) as poligono, ST_AsText(ST_Centroid(coordenadas)) as centroide, nome, limite_ambulantes, quantidade_ambulantes')->fetch(true);

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

        $salesmans = (new Salesman())->find('', '', 'id, latitude, longitude, nome, area_equipamento, identidade, situacao')->fetch(true);
        if (!isset($_SESSION['user']['login']) || (isset($_SESSION['user']['login']) && ($_SESSION['user']['login'] === 1 || $_SESSION['user']['login'] == 2))) {
            echo $this->view->render('salesmanMap', [
                'title' => 'Mapa',
                'salesmans' => null,
                'reports' => $reports,
                'zones' => $zoneData
            ]);
        } else {
            echo $this->view->render('salesmanMap', [
                'title' => 'Mapa',
                'salesmans' => $salesmans,
                'reports' => $reports,
                'zones' => $zoneData
            ]);
        }
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
    public function validateNewAgent($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $image = null;
        if (is_uploaded_file($_FILES['localImage']['tmp_name'])) {
            $target_file = basename($_FILES['localImage']['name']);

            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $extensions_arr = array("jpg", "jpeg", "png");

            if (in_array($imageFileType, $extensions_arr)) {
                $image_base64 = base64_encode(file_get_contents($_FILES['localImage']['tmp_name']));
                $image = 'data:image/' . $imageFileType . ';base64,' . $image_base64;
            }
        }

        $psw = substr(md5(date('Y-m-d H:i:s')), 1, 5);

        $agent = new Agent();
        $agent->matricula = $data['registration'];
        $agent->cpf = $data['identity'];
        $agent->email = $data['email'];
        $agent->nome = $data['name'];
        $agent->senha = md5($psw);
        $agent->tipo = 1;
        $agent->foto = $image;
        $agent->save();

        if ($agent->fail()) {
            var_dump($agent->fail()->getMessage());
        } else {
            $email = new Email();
            $email->add(
                "Cadastro Orditi",
                "<p style='font-family: \"Dosis\", sans-serif;'>Olá " . $data['name'] . ", você teve sua conta cadastrada no </span><span style='color: #ed2e54;'> ORDITI</span></p>
                        <p style='font-family: \"Dosis\", sans-serif;'>Estamos felizes em te-lo conosco.</p>
                        <br>
                        <p style='font-family: \"Dosis\", sans-serif;'>
                            Para acessar sua conta basta <a href='https://www.maceio.orditi.com/i'>clicar aqui</a> e
                            informar seu CPF e a seguinte senha: " . $psw . "
                        </p>
                        <div> <img style='width: 20%' src='https://www.maceio.orditi.com/i/themes/assets/img/nav-logo.png'> </div>",
                $data['name'],
                $data['email']
            )->send();

            if ($email->error()) {
                var_dump($email->error()->getMessage());
            } else {
                echo 1;
            }
        }
    }

    /**
     * @return void
     */
    public function validateZone($data)
    {
        $image = null;
        if (is_uploaded_file($_FILES['localImage']['tmp_name'])) {
            $target_file = basename($_FILES['localImage']['name']);

            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $extensions_arr = array("jpg", "jpeg", "png");

            if (in_array($imageFileType, $extensions_arr)) {
                $image_base64 = base64_encode(file_get_contents($_FILES['localImage']['tmp_name']));
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
                $array_point[] = $coodinate->lat . " " . $coodinate->lng;
            }

            $str = implode(',', $array_point);
            $polygon = 'POLYGON((' . $str . '))';

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $zone = new Zone();
            $zone->nome = $data['zoneName'];
            if ($image !== null) {
                $zone->foto = $image;
            }
            $zone->detalhes = $description;
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
    public function zone(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if (is_numeric($data['id'])) {
            $zone = (new Zone())->find('id = :zoneId', 'zoneId=' . $data['id'], 'id, ST_AsText(coordenadas) as poligono, ST_AsText(ST_Centroid(coordenadas)) as centroide, nome, limite_ambulantes, quantidade_ambulantes, foto, detalhes')->fetch();
            $salesmans = (new Salesman())->find('regiao = :zoneId', 'zoneId=' . $data['id'], 'id, identidade, nome, situacao, fone, email')->fetch(true);
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

                if (!$zone->foto) {
                    $image_base64 = base64_encode(file_get_contents(THEMES . '/assets/img/zone.jpg'));
                    $zone->foto = 'data:image/jpg;base64,' . $image_base64;
                }

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
                        'salesmans' => $salesmans
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
    public function checkLogin(): void
    {
        if (!isset($_SESSION['user']['login'])) {
            $this->router->redirect('web.home');
        }
    }

    /**
     * Check if an agent
     * @return data
     */
    public function checkAgent(): void
    {
        if (!isset($_SESSION['user']['login']) || (isset($_SESSION['user']['login']) && !($_SESSION['user']['login'] === 3))) {
            $this->router->redirect('web.home');
        }
    }

    /**
     * Check if an agent/company
     * @return data
     */
    public function checkuser(): void
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
     */
    public function salesmanProfile(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $this->checkuser();

        if (is_numeric($data['id'])) {
            if ($_SESSION['user']['login'] == 2) {
                $salesman = (new Salesman())->find('id = :dataId AND id_empresa = :company', 'dataId=' . $data['id'] . '&company=' . $_SESSION['user']['id'])->fetch();
            } else {
                $salesman = (new Salesman())->findById($data['id']);
            }
            if ($salesman !== null) {
                $notification = (new Notification())->find('ambulante_id = :id', 'id=' . $salesman->id)->fetch(true);
                $payments = (new Payment())->find('id_ambulante = :id', 'id=' . $salesman->id)->fetch(true);
                $agents = (new Agent())->find('', '', 'id, nome')->fetch(true);

                if ($salesman->regiao !== null) {
                    $zone = (new Zone())->findById($salesman->regiao, 'id, nome, ST_AsText(coordenadas) as poligono, limite_ambulantes, quantidade_ambulantes');
                } else {
                    $zone = null;
                }

                if ($salesman->id_empresa !== NULL) {
                    $company = (new Company())->findById($salesman->id_empresa, 'nome_fantasia');
                } else {
                    $company = null;
                }

                if ($zone) {
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
                }

                if ($salesman->suspenso == 0 && ($salesman->latitude == null || $salesman->longitude == null)) {
                    $zoneData = array();
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
                    } else {
                        $zoneData = null;
                    }
                } else {
                    $zoneData = null;
                }

                $folder = ROOT . '/themes/assets/uploads';
                $uploads = array();
                $aux = 1;
                $attachments = (new Attach())->find('id_usuario = :id AND tipo_usuario = 1', 'id=' . $salesman->id)->fetch(true);
                if ($attachments) {
                    foreach ($attachments as $attach) {
                        $attachName = explode('.', $attach->file_name);
                        if ($attachName[0] == 'userImage') {
                            $userImage = ROOT . '/themes/assets/uploads/salesmans/' . $attach->id_usuario
                                . '/' . $attach->file_name;
                        }

                        $uploads[] = [
                            'fileName' => $attach->file_name,
                            'groupName' => 'salesmans',
                            'userId' => $salesman->id
                        ];
                        $aux++;
                    }
                }

                echo $this->view->render('profile', [
                    'title' => 'Ambulante | ' . SITE,
                    'salesman' => $salesman,
                    'notifications' => $notification,
                    'payments' => $payments,
                    'agents' => $agents,
                    'zone' => $zone,
                    'company' => $company,
                    'zones' => $zoneData,
                    'uploads' => $uploads,
                    'userImage' => $userImage
                ]);
            } else {
                $this->router->redirect('web.salesmanList');
            }
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

        $file = (new Attach())->find('file_name = :fileName', 'fileName=' . $data['fileName'])
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

            header("Content-Disposition: attachment; filename=\"{$file->file_name}\"");
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
            $email->add(
                "Notificação",
                "<p style='font-family: \"Dosis\", sans-serif;'>Olá " . $salesman->nome . ", sua suspensão foi removida do </span><span style='color: #ed2e54;'> ORDITI</span></p>
                        <p style='font-family: \"Dosis\", sans-serif;'>Estamos felizes em te-lo de volta.</p>
                        <br>
                        <p style='font-family: \"Dosis\", sans-serif;'>Acesse <a href='https://www.maceio.orditi.com/i'>https://www.maceio.orditi.com/i</a></p>
                        <div> <img style='width: 20%' src='https://www.maceio.orditi.com/i/themes/assets/img/nav-logo.png'> </div>",
                $salesman->nome,
                $salesman->email
            )->send();

            echo 1;
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

    /**
     * @param array $data
     * @return void
     */
    public function companyInfo(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $this->checkAgent();

        if (is_numeric($data['id'])) {
            $company = (new Company())->findById($data['id']);
            if ($company !== null) {
                $payments = (new Payment())->find('id_empresa = :id', 'id=' . $company->id)->fetch(true);
                $salesmans = (new Salesman())->find('id_empresa = :id', 'id=' . $company->id)->fetch(true);
                $zones = (new Zone())->find('', '', 'id, ST_AsText(coordenadas) as poligono, ST_AsText(ST_Centroid(coordenadas)) as centroide, nome, limite_ambulantes, quantidade_ambulantes')->fetch(true);

                $paymentArray = array();
                if ($payments) {
                    foreach ($payments as $payment) {
                        $salesmanName = (new Salesman())->findById($payment->id_ambulante, 'nome');
                        if ($salesmanName) {
                            $payment->name = $salesmanName->nome;
                            $paymentArray[] = $payment;
                        }
                    }
                } else {
                    $paymentArray == null;
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

                $folder = ROOT . '/themes/assets/uploads';
                $uploads = array();
                $aux = 1;
                $attachments = (new Attach())->find('id_usuario = :id AND tipo_usuario = 2', 'id=' . $company->id)->fetch(true);
                if ($attachments) {
                    foreach ($attachments as $attach) {
                        $attachName = explode('.', $attach->file_name);
                        if ($attachName[0] == 'userImage') {
                            $userImage = ROOT . '/themes/assets/uploads/companys/' . $attach->id_usuario
                                . '/' . $attach->file_name;
                        }

                        $uploads[] = [
                            'fileName' => $attach->file_name,
                            'groupName' => 'companys',
                            'userId' => $company->id
                        ];
                        $aux++;
                    }
                }

                if ($salesmans) {
                    $count = count($salesmans);
                } else {
                    $count = 0;
                }

                echo $this->view->render("companyProfile", [
                    'title' => 'Empresa | ' . SITE,
                    'company' => $company,
                    'salesmans' => $salesmans,
                    'salesmansCount' => $count,
                    'zones' => $zoneData,
                    'payments' => $paymentArray,
                    'userImage' => $userImage,
                    'uploads' => $uploads
                ]);
            } else {
                $this->router->redirect('web.salesmanList');
            }
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
        $email->add(
            "Alguém enviou uma mensagem",
            "<div style='font-family: \"Dosis\", sans-serif;'>
                        <p>Olá, recebemos uma mensagem de <span style='color: #157881;'>" . $_SESSION['user']['name'] . "</span></p>
                        <p>Email: <span style='color: #157881;'>" . $_SESSION['user']['email'] . "</span></p>
                        <p>Telefone: <span style='color: #157881;'>" . $data['phone'] . "</span></p>
                        <p>Descrição: <span style='color: #157881;'>" . $data['description'] . "</span></p>
                    </div>",
            COMPANY,
            EMAIL
        )->send();

        if ($email->error()) {
            var_dump($email->error()->getMessage());
        } else {
            echo 1;
        }
    }
}

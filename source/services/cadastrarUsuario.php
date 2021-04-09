<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Accept: application/json');

use Stonks\Router\Router;
use Source\Models\Email;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../Config.php';
use SimpleXMLElement;

function sendEmail($user, $id): void
{
    $email = new Email();
                $message = file_get_contents(THEMES . "/assets/emails/confirmRegisterEmail.php");

                $url = ROOT . "/confirmAccount/" . md5($id);

                $template = array("%title", "%textBody", "%button", "%link", "%name");
                $dataReplace = array("Confirmação de Cadastro", "Para confirmar seu cadastro", "Confirmar", $url, $user['nome']);
                $message = str_replace($template, $dataReplace, $message);

                $email->add(
                    "Confirmação de cadastro",
                    $message,
                    $user['nome'],
                    $user['email']
                )->send();
}

function base64_to_jpeg($base64_string, $imagePlace, $imageName, $id)
{
    
    $output_file = $imagePlace . $imageName;
    require "connection.php";
    // open the output file for writing
    $ifp = fopen($output_file, 'wb');

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode(',', $base64_string);

    // we could add validation here with ensuring count( $data ) > 1
    fwrite($ifp, base64_decode($data[1]));

    // clean up the file resource
    fclose($ifp);
    $result = mysqli_query($connection, "
            INSERT INTO anexos(               
                `nome`,
                `tipo_usuario`,
                `id_usuario`)
            values(
                '" . $imageName . "',
                0,
                " . $id . "
    
            )");
    $data["mysqlamb"] = mysqli_error($connection);
    return $output_file;
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    require "connection.php";

    $psw = md5(date('Y-m-d H:i:s'));
    $psw = substr($psw, 1, 5);

    $input_json = file_get_contents("php://input");
    $input_array = json_decode($input_json, true);

    $dataTeste = array();

    $result = mysqli_query($connection, "SELECT * FROM usuarios WHERE cpf = '" . $input_array['identidade'] . "'");
    while ($row = mysqli_fetch_assoc($result)) {
        $dataTeste[] = $row;
    }

    if (!isset($dataTeste[0])) {

        $cpf = preg_replace('/[^0-9]/is', '', $input_array['identidade']);

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

        curl_setopt($curl, CURLOPT_URL, "http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/apwsretornopertences");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $soap_input);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $soap_response = curl_exec($curl);

        $xml_response = str_ireplace(['SOAP-ENV:', 'SOAP:', '.executeresponse', '.SDTRetornoPertences'], '', $soap_response);

        //@$xml = new SimpleXMLElement($xml_response, NULL, FALSE);

        $companys = $xml->Body->PWSRetornoPertences->Sdtretornopertences->SDTRetornoPertencesItem->SDTRetornoPertencesEmpresa->SDTRetornoPertencesEmpresaItem;

        if ($companys != "") {
            //Possui CMC
            $companyAux = 0;
            foreach ($companys as $company) {
                if ($company->SRPAutonomo == "A") {
                    $cmc = $company->SRPInscricaoEmpresa;
                    $companyAux = 1;  // Que é isso?
                }
            }

            if ($companyAux != 0) {
                $result = mysqli_query($connection, "
                INSERT INTO usuarios(               
                    `cpf`,
                    `cmc`,
                    `nome`,
                    `endereco`,
                    `telefone`,
                    `email`,
                    `rg`,
                    `nome_mae`)
                values(
                    '" . $input_array['identidade'] . "',
                    '" . $cmc . "',
                    '" . $input_array['nome'] . "',
                    '" . $input_array['rua'] . ', ' . $input_array['cidade'] . ', ' . $input_array['bairro'] . ', ' . $input_array['numero'] . "',
                    '" . $input_array['fone'] . "',
                    '" . $input_array['email'] . "',
                    '" . $input_array['identidade'] . "',
                    '" . $input_array['nome_materno'] . "'
                )");
                $id = mysqli_insert_id($connection);
                $data["mysqlamb"] = mysqli_error($connection);
            }
        } else {
            //Não possui CMC
            $cmc = null;

            $result = mysqli_query($connection, "
            INSERT INTO usuarios(               
                `cpf`,
                `cmc`,
                `nome`,
                `endereco`,
                `telefone`,
                `email`,
                `rg`,
                `nome_mae`)
            values(
                '" . $input_array['identidade'] . "',
                '" . $cmc . "',
                '" . $input_array['nome'] . "',
                '" . $input_array['rua'] . ', ' . $input_array['cidade'] . ', ' . $input_array['bairro'] . ', ' . $input_array['numero'] . "',
                '" . $input_array['fone'] . "',
                '" . $input_array['email'] . "',
                '" . $input_array['identidade'] . "',
                '" . $input_array['nome_materno'] . "'
    
            )");
            $id = mysqli_insert_id($connection);
            
            $data["mysqlamb"] = mysqli_error($connection);
        }
        
        if($data["mysqlamb2"] == "" && $data["mysqlamb"]==""){
                $data['retorno'] = 1;
                sendEmail($input_array, $id);
                $data['message'] = "Cadastro realizado com sucesso!";
            } else {
                $data['retorno'] = 2;
                $data['message'] = "Ocorreu algum erro no cadastro!";
            }

        //Onde a imagem será armazenada
        $imagePlace = __DIR__ . '../../themes/assets/uploads/users/' . $licenca_id . '/';
        //cria a pasta
        mkdir($imagePlace, 0777, true);
        //Cria as imagens
        base64_to_jpeg($input_array['foto_cliente'], $imagePlace, 'userImage.jpg', $licenca_id );

        if (isset($input_array['foto_identidade'])) {
            base64_to_jpeg($input_array['foto_identidade'], $imagePlace, 'identityImage.jpg', $licenca_id );
        }
        if (isset($input_array['foto_comprovante_residencia'])) {
            base64_to_jpeg($input_array['foto_comprovante_residencia'], $imagePlace, 'proofAddress.jpg', $licenca_id );
        }
        
        $data['id'] = $id;
        
        echo json_encode($data);
    } else {
        $data['retorno'] = 2;
        $data['message'] = "Já existe alguém cadastrado com esse CPF";
        echo json_encode($data);
    }
}

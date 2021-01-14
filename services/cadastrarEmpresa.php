<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Accept: application/json');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    require "connection.php";

    $input_json = file_get_contents("php://input");
    $input_array = json_decode($input_json, true);

    $psw = md5(date('Y-m-d H:i:s'));
    $psw = substr($psw, 1, 5);

    $dataTeste = array();

    $result = mysqli_query($connection, "SELECT * FROM empresas WHERE cnpj = '" . $input_array['cnpj'] . "'");
    while ($row = mysqli_fetch_assoc($result)) {
        $dataTeste[] = $row;
    }

    if (!isset($dataTeste[0])) {

        $cpf = preg_replace('/[^0-9]/is', '', $input_array['cpf']);

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

        @$xml = new SimpleXMLElement($xml_response, NULL, FALSE);

        $companys = $xml->Body->PWSRetornoPertences->Sdtretornopertences->SDTRetornoPertencesItem->SDTRetornoPertencesEmpresa->SDTRetornoPertencesEmpresaItem;

        if ($companys != "") {
            $companyAux = 0;
            foreach ($companys as $company) {
                $cmc = $company->SRPInscricaoEmpresa;
                if ($input_array['cmc'] == $cmc) {
                    $companyAux = 1;
                }
            }

            if ($companyAux != 0) {

                $result = mysqli_query(
                    $connection,
                    "
    INSERT INTO empresas(
        `senha_temporaria`,
        `nome`,
        `cpf`,
        `rg`,
        `fone`,
        `email`,
        `nome_materno`,
        `endereco`,
        `numero`,
        `bairro`,
        `cidade`,
        `cep`,
        `foto_cliente`,
        `foto_rg`,
        `foto_cnpj`,
        `foto_cpf`,
        `foto_contrato_social`,
        `foto_alvara`,
        `foto_outro`,
        `produto`,
        `relato_atividade`,
        `cnpj`,
        `cmc`,
        `nome_fantasia`,
        `fone_empresa`,
        `outro_produto`,
        `quantidade_equipamentos`)
    values(
        '" . md5($psw) . "',
        '" . $input_array['nome'] . "',
        '" . $input_array['cpf'] . "', 
        '" . $input_array['rg'] . "', 
        '" . $input_array['fone'] . "', 
        '" . $input_array['email'] . "', 
        '" . $input_array['nome_materno'] . "',
        '" . $input_array['endereco'] . "',
        " . $input_array['numero'] . ",
        '" . $input_array['bairro'] . "',
        '" . $input_array['cidade'] . "',
        '" . $input_array['cep'] . "',
        '" . $input_array['foto_cliente'] . "',
        '" . $input_array['foto_rg'] . "',
        '" . $input_array['foto_cnpj'] . "',
        '" . $input_array['foto_cpf'] . "',
        '" . $input_array['foto_contrato_social'] . "', 
        '" . $input_array['foto_alvara'] . "', 
        '" . $input_array['foto_outro'] . "', 
        '" . $input_array['produto'] . "', 
        '" . $input_array['relato_atividade'] . "', 
        '" . $input_array['cnpj'] . "', 
        '" . $input_array['cmc'] . "', 
        '" . $input_array['nome_fantasia'] . "',
        '" . $input_array['fone_empresa'] . "',
        '" . $input_array['outro_produto'] . "',
        " . $input_array['quantidade_equipamentos'] . "
    )"
                );

                $data['retorno'] = 1;
                echo json_encode($data);
            } else {
                $data['retorno'] = 4;
                echo json_encode($data);
            }
        } else {
            $data['retorno'] = 4;
            echo json_encode($data);
        }
    } else {
        $data['retorno'] = 3;
        echo json_encode($data);
    }
} else {
    $data['retorno'] = 0;
    echo json_encode($data);
}

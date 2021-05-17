<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
header('Accept: application/json');

use SimpleXMLElement;

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    require "connection.php";

    $input_json = file_get_contents("php://input");
    $input_array = json_decode($input_json, true);

    $dataTeste = array();

    $result = mysqli_query($connection, "SELECT cmc, id FROM usuarios WHERE cpf = '". $input_array['cpf'] ."'");
    while ($row = mysqli_fetch_assoc($result)) {
        $dataTeste[] = $row;
    }

    if (isset($dataTeste[0])) {
        $cmc = $dataTeste[0]['cmc'];
        //if ($cmc != '0' && $cmc != null) {
            //Cadastra a licença caso haja cmc

            $result = mysqli_query($connection, "
                INSERT INTO licencas(
                    `cmc`,
                    `tipo`,
                    `data_inicio`,
                    `data_fim`,
                    `status`,
                    `id_usuario`
                    )
                values(
                    '". $cmc . "',
                    '1',
                    '" . date('Y-m-d', strtotime("+3 days")) . "',
                    '" . date('Y-m-d', strtotime("+30 days")) . "',
                    0,
                    " . $dataTeste[0]['id']. "
                )");
            
            $data["mysqlamb"] = mysqli_error($connection);

            $licenca_id = mysqli_insert_id($connection);

            $result2 = mysqli_query($connection, "
            INSERT INTO ambulantes(
                `id_licenca`,
                `id_zona`,
                `local_endereco`,
                `produto`,
                `atendimento_dias`,
                `atendimento_hora_inicio`,
                `atendimento_hora_fim`,
                `relato_atividade`,
                `area_equipamento`,
                `tipo_equipamento`,
                `latitude`,
                `longitude`
                )
            values(
                " . $licenca_id . ",
                " . $input_array['regiao'] . ",
                '" . $input_array['local_endereco'] . "',
                '" . $input_array['produto'] . "',
                '" . $input_array['atendimento_dias'] . "',
                '" . $input_array['atendimento_hora_inicio'] . "',
                '" . $input_array['atendimento_hora_fim'] . "',
                '',
                '" . $input_array['area_equipamento'] .  "',
                '" . $input_array['tipo_equipamento'] . "',
                '" . $input_array['local_latitude'] . "',
                '" . $input_array['local_longitude'] . "'
            )");
            $data["mysqlamb2"] = mysqli_error($connection);
            $values = array();

            $area = 2;

            foreach (str_split($input_array['produto']) as $product) {
                if ($product == 0 || $product == 1) {
                    if ($area <= 1.50) {
                        $values[] = 40.00;
                    } else {
                        $values[] = 72.00;
                    }
                } else if ($product == 2 || $product == 3 || $product == 4 || $product == 5) {
                    if ($area <= 1.50) {
                        $values[] = 80.00;
                    } else {
                        $values[] = 144.00;
                    }
                } else if ($product == 6 || $product == 7) {
                    if ($area <= 1.50) {
                        $values[] = 72.00;
                    } else {
                        $values[] = 80.00;
                    }
                }
            }
            rsort($values);
            
            $ambulante_id = mysqli_insert_id($connection);
            $result3 = mysqli_query($connection, "INSERT INTO boletos (`id_licenca`, `id_usuario`, `valor`, `status`, `tipo`, `pagar_em` ) VALUES (" . $licenca_id . ", " . $dataTeste[0]['id']. ", " . $values[0] . ", '" . "0" . "', '" . "1" . "', '" . date('Y-m-d H:i:s', strtotime("+10 days")) . "' )");
            
            if($data["mysqlamb2"] == "" && $data["mysqlamb"]==""){
                $data['retorno'] = 1;
                $data['message'] = "Cadastro realizado com sucesso!";
            } else {
                $data['retorno'] = 2;
                $data['message'] = "Ocorreu algum erro no cadastro!";
            }
            echo json_encode($data);
            
            /*$soap_input = '
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
                                               <e:EXTInscricao>' . $cmc . '</e:EXTInscricao>
                                               <e:EXTvlrvvt>' . $values[0] . '</e:EXTvlrvvt>
                                               <e:EXTvlrvvtdesconto>0.00</e:EXTvlrvvtdesconto>
                                               <e:EXTvencimento>' .  date('Y-m-d', strtotime("+3 days")) . '</e:EXTvencimento>
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

            curl_setopt($curl, CURLOPT_URL, "http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/awstaxaexternas");
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $soap_input);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $soap_response = curl_exec($curl);

            $xml_response = str_ireplace(['SOAP-ENV:', 'SOAP:', '.executeresponse', '.SDTConsultaParcelamentoItem', '.SDTMensagem_TaxaExternaItem'], '', $soap_response);

            @$xml = new SimpleXMLElement($xml_response, NULL, FALSE);
            $code = $xml->Body->WSTaxaExternas->Mensagem->SDTMensagem_TaxaExterna->NossoNumero;

            $result3 = mysqli_query($connection, "UPDATE boletos SET cod_referencia='$code', cod_pagamento='$extCode' WHERE id=$lastId");
            //*/
        //} else {
        //    $data['retorno'] = 2;
        //    $data['message'] = "Cadastro realizado com sucesso!";
        //    echo json_encode($data);
        //}
    }
}

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

    $result = mysqli_query($connection, "SELECT * FROM ambulantes WHERE identidade = '" . $input_array['identidade'] . "'");
    while ($row = mysqli_fetch_assoc($result)) {
        $dataTeste[] = $row;
    }

    if (!isset($dataTeste[0])) {
        $psw = md5(date('Y-m-d H:i:s'));
        $psw = substr($psw, 1, 5);

        $area = $input_array['comprimento'] * $input_array['largura'];
        $area_equipamento = $input_array['comprimento'] . " x " . $input_array['largura'];
        if (isset($input_array['id_empresa'])) {
            $id_empresa =  $input_array['id_empresa'];
            $contador_ambulante =  $input_array['contador_ambulante'];
        } else {
            $id_empresa = 0;
        }
        ///
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

        @$xml = new SimpleXMLElement($xml_response, NULL, FALSE);

        $companys = $xml->Body->PWSRetornoPertences->Sdtretornopertences->SDTRetornoPertencesItem->SDTRetornoPertencesEmpresa->SDTRetornoPertencesEmpresaItem;

        if ($companys != "") {
            $companyAux = 0;
            foreach ($companys as $company) {
                if ($company->SRPAutonomo == "A") {
                    $cmc = $company->SRPInscricaoEmpresa;
                    $companyAux = 1;
                }
            }

            if ($companyAux != 0) {
                // continua o código
                ///
                $result = mysqli_query($connection, "
        INSERT INTO ambulantes(
            `id_empresa`,                
            `identidade`,
            `cmc`,
            `senha`,
            `endereco`,
            `numero`,
            `bairro`,
            `cidade`,
            `cep`,
            `fone`,
            `email`,
            `nome`,
            `rg`,
            `nome_materno`,
            `end_local`,
            `foto`,
            `foto_cpf`,
            `foto_rg`,
            `ponto_referencia`,
            `produto`,
            `atendimento_dias`,
            `atendimento_inicio`,
            `atendimento_fim`,
            `relato_atividade`,
            `regiao`,
            `latitude`,
            `longitude`,
            `foto_equipamento`,
            `situacao`,
            `area_equipamento`,
            `como_vende`)
        values(
            " . $id_empresa . ",
            '" . $input_array['identidade'] . "', 
            '" . $cmc . "', 
            '" . md5($psw) . "',
            '" . $input_array['endereco'] . "', 
            " . $input_array['numero'] . ", 
            '" . $input_array['bairro'] . "', 
            '" . $input_array['cidade'] . "', 
            '" . $input_array['cep'] . "', 
            '" . $input_array['fone'] . "',
            '" . $input_array['email'] . "',
            '" . $input_array['nome'] . "',
            '" . $input_array['rg'] . "',
            '" . $input_array['nome_materno'] . "',
            '" . $input_array['end_local'] . "',
            '" . $input_array['foto_cliente'] . "',
            '" . $input_array['foto_cpf'] . "',
            '" . $input_array['foto_rg'] . "',
            '" . $input_array['ponto_referencia'] . "',
            '" . $input_array['produto'] . "', 
            '" . $input_array['atendimento_dias'] . "', 
            '" . $input_array['atendimento_inicio'] . "', 
            '" . $input_array['atendimento_fim'] . "', 
            '" . $input_array['relato_atividade'] . "', 
            " . $input_array['regiao'] . ", 
            '" . $input_array['latitude'] . "', 
            '" . $input_array['longitude'] . "',
            '" . $input_array['foto_equipamento'] . "',
            '" . $input_array['situacao'] . "',
            '" . $area_equipamento . "',
            '" . $input_array['tipo_equipamento'] . "'
        )");
        $data["mysqlamb"] = mysqli_error($connection);


                $values = array();

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


                if ($id_empresa == "0") {
                    $ambulante_id = mysqli_insert_id($connection);
                    $result2 = mysqli_query($connection, "INSERT INTO boletos (id_ambulante, valor, pagar_em) VALUES (" . $ambulante_id . ", " . $values[0] . ", '" . date('Y-m-d H:i:s', strtotime("+3 days")) . "')");

                    //
                    $lastId = mysqli_insert_id($connection);
                    $extCode = 'ODT'. $lastId;

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
                                               <e:EXTCodigo>'. $extCode .'</e:EXTCodigo>
                                               <e:EXTDescricao>numero da licenca</e:EXTDescricao>
                                               <e:EXTTipoMulta></e:EXTTipoMulta>
                                               <e:EXTDescMulta></e:EXTDescMulta>
                                               <e:EXTanolct>2020</e:EXTanolct>
                                               <e:EXTtpoTaxaExternas>2</e:EXTtpoTaxaExternas>
                                               <e:EXTCTBid>1254</e:EXTCTBid>
                                               <e:EXTcpfcnpjpropr></e:EXTcpfcnpjpropr>
                                               <e:EXTInscricao>'. $cmc .'</e:EXTInscricao>
                                               <e:EXTvlrvvt>'. $values[0] .'</e:EXTvlrvvt>
                                               <e:EXTvlrvvtdesconto>0.00</e:EXTvlrvvtdesconto>
                                               <e:EXTvencimento>'.  date('Y-m-d', strtotime("+3 days")) .'</e:EXTvencimento>
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
                    //
                } else {
                    $result3 = mysqli_query($connection, "UPDATE empresas SET contador_ambulantes='$contador_ambulante' WHERE id=$id_empresa");
                }

                if ($input_array['regiao'] !== 0) {
                    $id_zona = $input_array['regiao'];
                    $result3 = mysqli_query($connection, "UPDATE zonas SET quantidade_ambulantes= quantidade_ambulantes+1 WHERE id=$id_zona");
                }

                $data["mysql"] = mysqli_error($connection);
                $data['teste3'] = (isset($dataTeste[0]));
                $data['teste'] = ($input_array['id_empresa'] == "0");
                $data['teste2'] = ($input_array['regiao'] !== 0);
                $data['retorno'] = 1;
                $data['senha'] = $psw;
                echo json_encode($data);
            } else {
                $data['teste3'] = $dataTeste[0];
                $data['retorno'] = 2;
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

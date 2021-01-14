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


    $result = mysqli_query($connection, "
            INSERT INTO notificacoes(`ambulante_id`, 
            `data_notificacao`, 
            `hora_notificacao`, 
            `titulo`, 
            `descricao`, 
            `fiscal_id`, 
            `fiscal_nome`,
            `multa`)
            values(
                " . $input_array['ambulante_id'] . ", 
                '" . $input_array['data_notificacao'] . "', 
                '" . $input_array['hora_notificacao'] . "', 
                '" . $input_array['titulo'] . "',
                '" . $input_array['descricao'] . "', 
                " . $input_array['fiscal_id'] . ", 
                '" . $input_array['fiscal_nome'] . "',
                '" . $input_array['multa'] . "'
            )");
    if ($input_array['multa'] != null) {
        $data['multa'] = "tem";

        $result = mysqli_query($connection, "SELECT * FROM ambulantes WHERE id = '" . $input_array['ambulante_id'] . "'");
        while ($row = mysqli_fetch_assoc($result)) {
            $data['cmc'] = $row['cmc'];
        }

        $result2 = mysqli_query($connection, "INSERT INTO boletos (id_ambulante, valor, pagar_em) VALUES (" . $input_array['ambulante_id'] . ", " . $input_array['multa'] . ", '" . date('Y-m-d H:i:s', strtotime("+3 days")) . "')");

        $lastId = mysqli_insert_id($connection); //Coleta o id do boleto registrado no banco
        $extCode = 'ODT' . $lastId;

        //
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
                                               <e:EXTInscricao>' . $row['cmc'] . '</e:EXTInscricao>
                                               <e:EXTvlrvvt>' . $input_array['multa'] . '</e:EXTvlrvvt>
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
        //
    }
    $data['retorno'] = 1;
    echo json_encode($data);
} else {
    $data['retorno'] = 0;
    echo json_encode($data);
}

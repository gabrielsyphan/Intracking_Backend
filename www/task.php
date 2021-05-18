<?php
    require __DIR__ . "/vendor/autoload.php";

    use Source\Models\Salesman;
    use Source\Models\Payment;
    use Source\Models\Email;
    use Source\Models\Company;

    // Consulta todos os pagamentos pendentes do banco
    $payments = (new Payment())->find('status = 0 OR status = 2 OR status = 3')->fetch(true);
    if($payments){
        foreach($payments as $payment){
            if($payment->id_ambulante != null){
                $user = (new Salesman())->findById($payment->id_ambulante);
                $name = $user->nome;
            }else{
                $user = (new Company())->findById($payment->id_empresa);
                $name = $user->nome_fantasia;
            }

            // Consulta o status do pagamento no Eagata
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
                           <e:TipoMode>DSP</e:TipoMode>
                           <e:EXTTipoContr>3</e:EXTTipoContr>
                           <e:EXTCodigo>'. $payment->cod_pagamento .'</e:EXTCodigo>
                           <e:EXTDescricao>numero da licenca</e:EXTDescricao>
                           <e:EXTTipoMulta></e:EXTTipoMulta>
                           <e:EXTDescMulta></e:EXTDescMulta>
                           <e:EXTanolct>2020</e:EXTanolct>
                           <e:EXTtpoTaxaExternas>2</e:EXTtpoTaxaExternas>
                           <e:EXTCTBid>1254</e:EXTCTBid>
                           <e:EXTcpfcnpjpropr></e:EXTcpfcnpjpropr>
                           <e:EXTInscricao>'. $user->cmc .'</e:EXTInscricao>
                           <e:EXTvlrvvt>'. $payment->valor .'</e:EXTvlrvvt>
                           <e:EXTvlrvvtdesconto>0.00</e:EXTvlrvvtdesconto>
                           <e:EXTvencimento>'. date('Y-m-d', strtotime($payment->pagar_em)) .'</e:EXTvencimento>
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
            $code = $xml->Body->WSTaxaExternas->Mensagem->SDTMensagem_TaxaExterna->StatusDebito;

            // Pagamento em análise
            if($code == "A"){
                // Verifica se o pagamento está vencido
                if(date('Y-m-d H:i:s') > $payment->pagar_em){
                    $nUser = (new Salesman())->findById($payment->id_ambulante);
                    if($nUser){
                        // Altera a situação do boleto para vencido
                        $payment->status = 2;
                        $payment->save();

                        // Altera a situação do ambulante para vencido
                        $nUser->situacao = 2;
                        $nUser->regiao = null;
                        $nUser->latitude = null;
                        $nUser->longitude = null;
                        $nUser->save();
                    }else{
                        echo "Ambulante não encontrado";
                    }
                }else{
                    // Caso esteja próximo do vencimento, é enviado um email para o ambulante/empresa vinculado ao pagamento
                    $result = (date('d', strtotime($payment->pagar_em)) - date('d'));
                    if($result === 1 || $result === 2 || $result === 3){
                        $email = new Email();
                        $email->add(
                            "Lembrete",
                            "<p style='font-family: \"Dosis\", sans-serif;'>Olá ". $name .", lembre-se de que você possui um pagamento pendente no valor de  <span style='color: #157881;'>R$". $payment->valor .",00</span> que deverá ser pago até o dia <span style='color: #ed2e54;'>". date('d-m-Y', strtotime($payment->pagar_em)) ."</span></p>
                            <br>
                            <p style='font-family: \"Dosis\", sans-serif;'>Atenciosamente,</p>
                            <br>
                            <p style='font-family: \"Dosis\", sans-serif;'>Equipe <a style='color: #157881;' href='www.syphan.com.br/orditi' target='_blank'>Orditi</a></p>
                            <div> <img style='width: 20%' src='https://www.syphan.com.br/orditi/themes/assets/img/nav-logo.png'> </div>",
                            $name,
                            $user->email
                        )->send();

                        if($email->error()){
                            var_dump($email->error()->getMessage());
                        }
                    }
                }
            // Pagamento realizado
            }else if($code == "P"){
                // Altera o status do pagamento para 1(Pago)
                $payment->status = 1;
                $payment->save();

                // Verifica se tem mais algum pagamento pendente
                $checkPayments = (new Payment())->find('id_ambulante = :id', 'id='. $payment->id_ambulante)->fetch(true);
                $checkAux = 0;
                foreach ($checkPayments as $checkPayment){
                    if($checkPayment->status != 1){
                        // Verifica se o pagamento pendente não é a mensalidade do mês
                        if(!($checkPayment->tipo == 1 && ($checkPayment->pagar_em > date('Y-m-d H:i:s')))){
                            $checkAux = 1;
                        }
                    }
                }

                // Traz todos os dados daquele ambulante
                $nUser = (new Salesman())->findById($payment->id_ambulante);
                if($nUser){
                    // Checa se o ambulante foi suspenso
                    if($nUser->suspenso == 0){
                        // Altera o status do ambulante para 1(Em dia) caso não tenha mais nenhum pagamento pendente
                        if($checkAux === 0){
                            $nUser->situacao = 1;
                            $nUser->save();
                        }

                        // Cria um novo pagamento para 1 mês depois do vencimento do pagamento atual caso ele seja do tipo 1 (mensalidade)
                        if($payment->tipo == 1){
                            $nPayment = new Payment();
                            $nPayment->id_ambulante = $payment->id_ambulante;
                            $nPayment->id_empresa = $payment->id_empresa;
                            $nPayment->cod_referencia = null;
                            $nPayment->cod_pagamento = null;
                            $nPayment->valor = $payment->valor;
                            $nPayment->tipo = 1;
                            $nPayment->pagar_em = date('Y-m-d H:i:s', strtotime("+1 month",strtotime($payment->pagar_em)));;
                            $nPayment->save();

                            $extCode = 'ODT'. $nPayment->id;

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
                                               <e:EXTCodigo>'. $extCode .'</e:EXTCodigo>
                                               <e:EXTDescricao>numero da licenca</e:EXTDescricao>
                                               <e:EXTTipoMulta></e:EXTTipoMulta>
                                               <e:EXTDescMulta></e:EXTDescMulta>
                                               <e:EXTanolct>2020</e:EXTanolct>
                                               <e:EXTtpoTaxaExternas>2</e:EXTtpoTaxaExternas>
                                               <e:EXTCTBid>1254</e:EXTCTBid>
                                               <e:EXTcpfcnpjpropr></e:EXTcpfcnpjpropr>
                                               <e:EXTInscricao>'. $user->cmc .'</e:EXTInscricao>
                                               <e:EXTvlrvvt>'. $payment->valor .'</e:EXTvlrvvt>
                                               <e:EXTvlrvvtdesconto>0.00</e:EXTvlrvvtdesconto>
                                               <e:EXTvencimento>'. date('Y-m-d', strtotime("+1 month",strtotime($payment->pagar_em))) .'</e:EXTvencimento>
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

                            // Salva os dados do novo boleto no banco de dados
                            $nPayment->cod_referencia = $code;
                            $nPayment->cod_pagamento = $extCode;
                            $nPayment->save();
                        }
                    }
                }
            }
        }
    }
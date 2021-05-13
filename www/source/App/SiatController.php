<?php


namespace Source\App;

use League\Plates\Engine;
use SimpleXMLElement;
use Source\Models\Payment;
use stdClass;
use Stonks\Router\Router;

class SiatController extends Payment
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

    public function insertUser(
        $identity, $companyName, $companyNameSummary,
        $deliveryAddressType, $birthPlace, $country,
        $addressType, $locationType, $streetType,
        $street, $number, $complement = null,
        $neighborhoodType, $neighborhood, $city,
        $federalState, $postcode, $zipcode,
        $areaCode, $phone, $areaCodeFax = null,
        $fax = null, $email = null
    ): bool
    {
        $response = false;
        $soap =
            '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:WebServiceGTM">
               <soapenv:Header/>
               <soapenv:Body>
                  <urn:gravaPessoa>
                     <!--Optional:-->
                     <entradaXML>         
                     <![CDATA[ <Entrada>
                            <EntradaGravaPessoa>
                            <cpfCnpj>' . $identity . '</cpfCnpj>
                            <nomeRazaoSocial>' . $companyName . '</nomeRazaoSocial>
                            <nomeRazaoSocialResumido>' . $companyNameSummary . '</nomeRazaoSocialResumido>
                            <tipoEnderecoEntregaPessoa>' . $deliveryAddressType . '</tipoEnderecoEntregaPessoa>
                            <tipoDocumento></tipoDocumento>
                            <numeroDocumento></numeroDocumento>
                            <orgaoExpedidor></orgaoExpedidor>
                            <ufOrgaoExpedidor></ufOrgaoExpedidor>
                            <dataExpedicao></dataExpedicao>
                            <dataNascimento></dataNascimento>
                            <paisNaturalidade>' . $birthPlace . '</paisNaturalidade>
                            <estadoCivil></estadoCivil>
                            <sexo></sexo>
                                <EntradaGravaEnderecoPessoa>
                                    <tipoEndereco>' . $addressType . '</tipoEndereco>
                                    <tipoLocalizacao>' . $locationType . '</tipoLocalizacao>
                                    <pais>' . $country . '</pais>
                                    <tipoLogradouro>' . $streetType . '</tipoLogradouro>
                                    <logradouro>' . $street . '</logradouro>
                                    <codigoLogradouro></codigoLogradouro>
                                    <numero>' . $number . '</numero>
                                    <complemento>' . $complement . '</complemento>
                                    <tipoBairro>' . $neighborhoodType . '</tipoBairro>
                                    <bairro>' . $neighborhood . '</bairro>
                                    <codigoBairro></codigoBairro>
                                    <distrito></distrito>
                                    <cidade>' . $city . '</cidade>
                                    <uf>' . $federalState . '</uf>
                                    <cep>' . $postcode . '</cep>
                                    <enderecoReferencia></enderecoReferencia>
                                    <zipCode>' . $zipcode . '</zipCode>
                                    <inscricaoImobiliaria></inscricaoImobiliaria>
                                    <povoado></povoado>
                                    <zonaRural></zonaRural>
                                    <ccir></ccir>
                                    <nirf></nirf>
                                    <datum></datum>
                                    <latitude></latitude>
                                    <longitude></longitude>   
                                    <dddTelefone>' . $areaCode . '</dddTelefone>
                                    <telefone>' . $phone . '</telefone>
                                    <dddCelular></dddCelular>
                                    <celular></celular>
                                    <dddFax>' . $areaCodeFax . '</dddFax>
                                    <fax>' . $fax . '</fax>
                                    <email>' . $email . '</email>  
                                </EntradaGravaEnderecoPessoa> 
                            </EntradaGravaPessoa>  
                        </Entrada> 
                     ]]>
                     </entradaXML>
                  </urn:gravaPessoa>
               </soapenv:Body>
            </soapenv:Envelope>';

        $xml = $this->postSoap($soap);

        if (isset($xml->Envelope->Body->gravaPessoaResponse->return->Saida->SaidaGravaPessoa->resposta) && $xml->Envelope->Body->gravaPessoaResponse->return->Saida->SaidaGravaPessoa->resposta == 0) {
            $response = true;
        }

        return $response;
    }

    public function selectUser($identity): object
    {
        $response = new stdClass();
        $soap =
            '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:WebServiceGTM">
              <soapenv:Header />
              <soapenv:Body>
                <urn:consultaPessoa>
                  <entradaXML>
                    <![CDATA[ <Entrada>
                       <EntradaConsultaPessoa>
                         <cpfCnpj>'. $identity .'</cpfCnpj>
                       </EntradaConsultaPessoa>
                     </Entrada>]]>
                  </entradaXML>
                </urn:consultaPessoa>
              </soapenv:Body>
            </soapenv:Envelope>';

        $xml = $this->postSoap($soap);

        if (
            isset($xml->Body->consultaPessoaResponse->return->Saida->SaidaConsultaPessoa->resposta) &&
            $xml->Body->consultaPessoaResponse->return->Saida->SaidaConsultaPessoa->resposta == 0
        ){
            $response = $xml;
        }

        return $response;
    }

    public function getPaymentData($identity, $date, $paymentNumber): string
    {
        $response = '';
        $soap =
'            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:WebServiceGTM">
                <soapenv:Header/>
                <soapenv:Body>
                <urn:solicitaGuiaDam>
                <!--Optional:-->
                <mensagem>
                        <![CDATA[
                            <Entrada>
                            <EntradaSolicitaGuiaDam>
                                <codigoCadastro>3</codigoCadastro>
                                <numeroCadastro>'. $identity .'</numeroCadastro>
                                <tipoProcedencia>WS</tipoProcedencia>
                                <emissorPortal>N</emissorPortal>
                                <guiaPorParcela>N</guiaPorParcela>
                                <dataCalculo>'. $date .'</dataCalculo>	
                                <entradaSolicitaGuiaDamParcela>
                                    <anoExercicio>2021</anoExercicio>
                                    <codigoLancamento>'. $paymentNumber .'</codigoLancamento>
                                    <codigoTributo></codigoTributo>				
                                    <entradaSolicitaGuiaDamParcelaNumero>
                                        <numeroParcela>1</numeroParcela>
                                    </entradaSolicitaGuiaDamParcelaNumero>							
                                </entradaSolicitaGuiaDamParcela>
                            </EntradaSolicitaGuiaDam>
                        </Entrada>
                        ]]>
                </mensagem>
                </urn:solicitaGuiaDam>
                </soapenv:Body>
            </soapenv:Envelope>';

        $xml = $this->postSoap($soap);

        if (isset($xml
                    ->Body->solicitaGuiaDamResponse
                    ->return->Saida->SaidaSolicitaGuiaDamArquivos->arquivo)
        ){
            $response = $xml
                        ->Body->solicitaGuiaDamResponse
                        ->return->Saida->SaidaSolicitaGuiaDamArquivos->arquivo;
        }

        return $response;
    }

    public function newPayment($licenseId, $userId, $identity, $value, $name): bool
    {
        $response = false;

        $date = date('d-m-Y');
        $date = explode('-', $date);
        $date = $date[0].$date[1].$date[2];

        $dateLimit = date('d-m-Y', strtotime('+3 days'));
        $dateLimit = explode('-', $dateLimit);
        $dateLimit = $dateLimit[0].$dateLimit[1].$dateLimit[2];

        $lastPayment = (new Payment())
            ->find('', '', 'id')
            ->limit(1)
            ->order("first_name DESC")
            ->fetch(false);

        if ($lastPayment) {
            $paymentCode = 'ORDT-'. ($lastPayment->id + 1);
        } else {
            $paymentCode = 'ORDT-1';
        }

        $soap =
            '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:WebServiceGTM">
               <soapenv:Header/>
               <soapenv:Body>
                  <urn:geraLancamentoEspecializadoOutrosSistemas>
                     <!--Optional:-->
                     <mensagem>
                     <![CDATA[
                            <Entrada>
                <EntradaIdentificacaoCadastral>
                    <sistemaLancamentoEspecializado>ORDITI</sistemaLancamentoEspecializado>
                        <tipoOperacao>IM</tipoOperacao>
                    <tipoCadastro>3</tipoCadastro>
                    <identificacaoCadastro>'. $identity .'</identificacaoCadastro>
                    <identificacaoOrigemLancamento>'. $paymentCode .'</identificacaoOrigemLancamento>
                        <grupoSistemaOrigem>0</grupoSistemaOrigem>
                    <processoAdministrativo>11112020</processoAdministrativo>
                        <volume/>
                        <folha/>
                        <EntradaLancamentoOutroSistema>
                            <tributo>12</tributo>
                        <anoExercicio>2021</anoExercicio>
                        <dataBaseLancamento>'. $date .'</dataBaseLancamento>
                        <dataNotificacao>'. $date .'</dataNotificacao>
                        <qtdeParcelas>1</qtdeParcelas>
                        <valorLancado>'. $value .'</valorLancado>
                            <valorLancadoMoeda>'. $value .'</valorLancadoMoeda>
                        <observacao>TESTE LANCAMENTO ESPECIALIZADO OUTROS SISTEMAS</observacao>
                        <estrutura>MEMORIA ITBIWEB</estrutura>
                        <EntradaParcelaOutroSistema>
                            <numero>1</numero>
                            <dataBaseLancamento>'. $date .'</dataBaseLancamento>
                            <dataVencimento>'. $dateLimit .'</dataVencimento>
                                <valorLancado>'. $value .'</valorLancado>
                                <valorLancadoMoeda>'. $value .'</valorLancadoMoeda>
                                <EntradaItemParcelaOutroSistema>
                                <itemTributo>17</itemTributo>
                                 <siglaIndicadorEconomico>R$</siglaIndicadorEconomico>
                                    <principal>S</principal>
                                    <valorImposto>'. $value .'</valorImposto>
                                    <valorBeneficio>0</valorBeneficio>
                                    <valorLancado>'. $value .'</valorLancado>
                                    <valorLancadoMoeda>'. $value .'</valorLancadoMoeda>
                                </EntradaItemParcelaOutroSistema>
                        </EntradaParcelaOutroSistema>
                        <EntradaMemoriaCalculo>
                            <atributo>ADQUIRENTE PRINCIPAL</atributo>
                                <valor>'. $name .'</valor>			        			        
                        </EntradaMemoriaCalculo>
                        <EntradaMemoriaCalculo>
                            <atributo>CODIGO ITBIWEB</atributo>
                              <valor>012021</valor>
                        </EntradaMemoriaCalculo>
                        <EntradaMemoriaCalculo>
                            <atributo>TRANSMITENTE PRINCIPAL</atributo>
                                <valor>VALDEJANE SOUZA</valor>
                        </EntradaMemoriaCalculo>
                        </EntradaLancamentoOutroSistema>
                </EntradaIdentificacaoCadastral>	
            </Entrada>
                     ]]>
                     </mensagem>
                  </urn:geraLancamentoEspecializadoOutrosSistemas>
               </soapenv:Body>
            </soapenv:Envelope>';

        $xml = $this->postSoap($soap);

        if (isset($xml
                ->Body->geraLancamentoEspecializadoOutrosSistemasResponse
                ->return->Saida->SaidaLancamentoOutroSistema->resposta)
        ){
            try {
                $payment = new Payment();
                $payment->id_licenca = $licenseId;
                $payment->id_usuario = $userId;
                $payment->cod_pagamento = $paymentCode;
                $payment->valor = $value;
                $payment->status = 0;
                $payment->tipo = 1;
                $payment->pagar_em = $date;
                $payment->pago_em = $dateLimit;
                $payment->save();

                if (!$payment->fail) {
                    $response = true;
                }
            } catch (\Exception $e) {
                var_dump($e->getMessage());
            }
        }

        return $response;
    }

    public function checkImmobile(): SimpleXMLElement
    {
        $customerService = 'WS';
        $inscricaoImobiliaria = '307';
        $possuiSaidaProprietario = 'S';
        $possuiSaidaCompromissario = 'S';
        $possuiSaidaEndCompromissario = 'S';
        $possuiSaidaEndEntImovel = 'S';
        $possuiSaidaEndLocImovel = 'S';
        $possuiSaidaEndProprietario = 'S';
        $possuiSaidaEnquadramento = 'N';
        $possuiSaidaBeneficioFiscal = 'N';
        $possuiSaidaTestadaVinculada = 'S';
        $possuiSaidaUnidadeAvalicao = 'S';
        $possuiSaidaZoneamento = 'N';

        $soap = '<?xml version="1.0" encoding="UTF-8"?>
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:WebServiceGTM">
                       <soapenv:Header/>
                       <soapenv:Body>
                          <urn:consultaImovel>
                             <!--Optional:-->
                             <entradaConsultaImovelXML><![CDATA[
                                  <Entrada>
                                    <EntradaConsultaImovel>
                                      <consumidorServico>' . $customerService . '</consumidorServico>
                                      <inscricaoCartografica></inscricaoCartografica>
                                      <inscricaoImobiliaria>' . $inscricaoImobiliaria . '</inscricaoImobiliaria>
                                      <possuiSaidaProprietario>' . $possuiSaidaProprietario . '</possuiSaidaProprietario>
                                      <possuiSaidaCompromissario>' . $possuiSaidaCompromissario . '</possuiSaidaCompromissario>
                                      <possuiSaidaEndCompromissario>' . $possuiSaidaEndCompromissario . '</possuiSaidaEndCompromissario>
                                      <possuiSaidaEndEntImovel>' . $possuiSaidaEndEntImovel . '</possuiSaidaEndEntImovel>
                                      <possuiSaidaEndLocImovel>' . $possuiSaidaEndLocImovel . '</possuiSaidaEndLocImovel>
                                      <possuiSaidaEndProprietario>' . $possuiSaidaEndProprietario . '</possuiSaidaEndProprietario>
                                      <possuiSaidaEnquadramento>' . $possuiSaidaEnquadramento . '</possuiSaidaEnquadramento>                  		   
                                      <possuiSaidaBeneficioFiscal>' . $possuiSaidaBeneficioFiscal . '</possuiSaidaBeneficioFiscal>                                    
                                      <possuiSaidaTestadaVinculada>' . $possuiSaidaTestadaVinculada . '</possuiSaidaTestadaVinculada>
                                      <possuiSaidaUnidadeAvalicao>' . $possuiSaidaUnidadeAvalicao . '</possuiSaidaUnidadeAvalicao>
                                      <possuiSaidaZoneamento>' . $possuiSaidaZoneamento . '</possuiSaidaZoneamento>
                                      <codigoImovel></codigoImovel>
                                    </EntradaConsultaImovel>
                                  </Entrada>
                             ]]></entradaConsultaImovelXML>
                          </urn:consultaImovel>
                       </soapenv:Body>
                    </soapenv:Envelope>';

        $xml = $this->postSoap($soap);

        return $xml;
    }

    private function postSoap($soap): SimpleXMLElement
    {
        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "http://192.168.10.7:8080/dsf_mcz_gtm/services/WebServiceGTM?wsdl");
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $soap);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $soapResponse = curl_exec($curl);

            $xmlResponse = str_ireplace(['&lt;'], '<', $soapResponse);
            $xmlResponse = str_ireplace(['soap:', 'ns2:'], '', $xmlResponse);

            $xml = new SimpleXMLElement($xmlResponse, NULL, FALSE);
            return $xml;
        } catch (\Exception $e){
            var_dump($e->getMessage());
        }
    }
}

<?php


namespace Source\App;

use SimpleXMLElement;
use League\Plates\Engine;
use Stonks\Router\Router;

class WebServiceSIAT
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

    public function varDump($objeto)
    {
        echo "<pre>";
        var_dump($objeto);
        echo "</pre>";
    }

    public function consultaImovel(): void
    {
        $identificacao = "05019701230012";

        $soap = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:WebServiceGTM">
                   <soapenv:Header/>
                   <soapenv:Body>
                      <urn:consultaImovel>
                         <!--Optional:-->
                         <entradaConsultaImovelXML>         
                         <![CDATA[ <Entrada>  
                                        <EntradaConsultaImovel>   
                                            <consumidorServico>WS</consumidorServico>   
                                            <inscricaoImobiliaria>' . $identificacao . '</inscricaoImobiliaria>   
                                            <codigoImovel></codigoImovel>  
                                        </EntradaConsultaImovel> 
                                    </Entrada> 
                         ]]>
                         </entradaConsultaImovelXML>
                      </urn:consultaImovel>
                   </soapenv:Body>
                </soapenv:Envelope>';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "http://192.168.10.7:8080/dsf_mcz_gtm/services/WebServiceGTM?wsdl");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $soap);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $soapResponse = curl_exec($curl);

        $xmlResponse = str_ireplace(['&lt;'], '<', $soapResponse);
        $xmlResponse = str_ireplace(['soap:', 'ns2:'], '', $xmlResponse);

        @$xml = new SimpleXMLElement($xmlResponse, NULL, FALSE);

        $this->varDump($xml->Body->consultaImovelResponse->return->Saida->SaidaConsultaImovel->SaidaImovel);
    }

    public function consultaPessoa(): void
    {
        $data = "24328700000178";

        $soap = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:WebServiceGTM">
                   <soapenv:Header/>
                   <soapenv:Body>
                      <urn:consultaPessoa>
                         <!--Optional:-->
                         <entradaXML>         
                         <![CDATA[ <Entrada>  
                                        <EntradaConsultaPessoa>   
                                            <consumidorServico>WS</consumidorServico>   
                                            <cpfCnpj>24328700000178</cpfCnpj>
                                        </EntradaConsultaPessoa>  
                                    </Entrada> 
                         ]]>
                         </entradaXML>
                      </urn:consultaPessoa>
                   </soapenv:Body>
                </soapenv:Envelope>';

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

        $this->varDump($xml);
    }

    public function gravaPessoa(): void
    {
        $soap = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:WebServiceGTM">
                   <soapenv:Header/>
                   <soapenv:Body>
                      <urn:gravaPessoa>
                         <!--Optional:-->
                         <entradaXML>         
                         <![CDATA[ <Entrada>
                                        <EntradaGravaPessoa>
                                        <cpfCnpj>30950841021</cpfCnpj>
                                        <nomeRazaoSocial>IZABEL APARECIDA GODINHO DA SILVA</nomeRazaoSocial>
                                        <nomeRazaoSocialResumido>IZABEL APARECIDA GODINHO DA SILVA</nomeRazaoSocialResumido>
                                        <tipoEnderecoEntregaPessoa>C</tipoEnderecoEntregaPessoa>
                                        <tipoDocumento></tipoDocumento>
                                        <numeroDocumento></numeroDocumento>
                                        <orgaoExpedidor></orgaoExpedidor>
                                        <ufOrgaoExpedidor></ufOrgaoExpedidor>
                                        <dataExpedicao></dataExpedicao>
                                        <dataNascimento></dataNascimento>
                                        <paisNaturalidade>Brasil</paisNaturalidade>
                                        <estadoCivil></estadoCivil>
                                        <sexo></sexo>
                                            <EntradaGravaEnderecoPessoa>
                                                <tipoEndereco>C</tipoEndereco>
                                                <tipoLocalizacao>NAC</tipoLocalizacao>
                                                <pais>Brasil</pais>
                                                <tipoLogradouro>AVENIDA</tipoLogradouro>
                                                <logradouro>REPUBLICA</logradouro>
                                                <codigoLogradouro></codigoLogradouro>
                                                <numero>2355</numero>
                                                <complemento>- DE 21322133 A 36803681</complemento>
                                                <tipoBairro>VILA</tipoBairro>
                                                <bairro>PALMITAL</bairro>
                                                <codigoBairro></codigoBairro>
                                                <distrito></distrito>
                                                <cidade>MARILIA</cidade>
                                                <uf>SP</uf>
                                                <cep>17510402</cep>
                                                <enderecoReferencia></enderecoReferencia>
                                                <zipCode>17510402</zipCode>
                                                <inscricaoImobiliaria></inscricaoImobiliaria>
                                                <povoado></povoado>
                                                <zonaRural></zonaRural>
                                                <ccir></ccir>
                                                <nirf></nirf>
                                                <datum></datum>
                                                <latitude></latitude>
                                                <longitude></longitude>   
                                                <dddTelefone>14</dddTelefone>
                                                <telefone>34021500</telefone>
                                                <dddCelular></dddCelular>
                                                <celular></celular>
                                                <dddFax>14</dddFax>
                                                <fax>34021508</fax>
                                                <email>contabil@tauste.com.br</email>  
                                            </EntradaGravaEnderecoPessoa> 
                                        </EntradaGravaPessoa>  
                                    </Entrada> 
                         ]]>
                         </entradaXML>
                      </urn:gravaPessoa>
                   </soapenv:Body>
                </soapenv:Envelope>';

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

        $this->varDump($xml);
    }

    public function consulta(): void
    {
        $soap = '{
          "anoProcesso": 2021,
          "codigoSecretariaProcesso": 0,
          "documentos": [
            {
              "bytes": "string",
              "conferido": true,
              "descricao": "string",
              "idOrigemDocumento": 0,
              "modificarDocumento": "string",
              "visibilidade": "string"
            }
          ],
          "numeroProcesso": 0
        }';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "http://integracao.homologacao.maceio.al.gov.br/protocolo-api/api/secretaria");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $soap);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $soapResponse = curl_exec($curl);

        $this->varDump($soapResponse);
    }
}

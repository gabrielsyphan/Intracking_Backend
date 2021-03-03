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

    public function consultaImovel(): void
    {
        $inscricao = "20";

        $soap = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:WebServiceGTM">
                        <soapenv:Header/>
                            <soapenv:Body>
                                <urn:consultaImovel>
                                    <entradaConsultaImovelXML>' . $inscricao . '</entradaConsultaImovelXML>
                                </urn:consultaImovel>
                            </soapenv:Body>
                 </soapenv:Envelope>';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "http://192.168.10.7:8080/dsf_mcz_gtm/services/WebServiceGTM");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $soap);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $soap_response = curl_exec($curl);
        var_dump($soap_response);
        exit();
        $xml_response = str_ireplace(['SOAP-ENV:', 'SOAP:', '.executeresponse', '.SDTConsultaParcelamentoItem', '.SDTMensagem_TaxaExternaItem'], '', $soap_response);

        @$xml = new SimpleXMLElement($xml_response, NULL, FALSE);
//        var_dump($xml);
//        exit();
    }

}
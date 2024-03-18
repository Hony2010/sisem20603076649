<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SoapClientSUNAT extends SoapClient {
  private $URL_OASIS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
  
  //POR DEFAULT TOMARA EL LINK
  private $URL_WSDL_SUNAT = '';//'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
  
  //LINK PARA CONSULTAR CDR
  private $URL_WSDL_SUNAT_CDR = '';//'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
  // private $URL_WSDL_SUNAT_ESTADO ='https://www.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl';
  /**PARA HACER CONSULTAS SOBRE XML A SUNAT */
  private $URL_WSDL_SUNAT_ESTADO ='https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl';
  private $URL_WSDL_SUNAT_VALIDAR ='https://e-factura.sunat.gob.pe/ol-it-wsconsvalidcpe/billValidService?wsdl';
  private $URL_WSDL_SUNAT_CONSULTA = "";
  private $indicadorCDR = false;

  public $indicadorGuiaRemisionRemitente = false;
  private $URL_WSDL_SUNAT_GUIA = '';//'https://e-beta.sunat.gob.pe/ol-ti-itemision-guia-gem-beta/billService?wsdl';

  private $URL_BETA = [];
  private $SUNAT_OSE = [];
  private $CABECERA_OSE = "";
  /**
   * WS-Security Username RUC+CLAVE SOL
   * @var string
   */
  private $username;

  /**
   * WS-Security Password Clave SOL
   * @var string
   */
  private $password;

  public function __construct()
  {
    //parent::__construct($this->URL_WSDL_SUNAT);
    if (!isset($this->CI))
    {
        $this->CI =& get_instance();
    }

    $this->CI->load->service('Configuracion/General/sConstanteSistema');
    //PARAMETRO PARA URL DE SUNAT U OSE, BETA O PRODUCCION
    $this->URL_BETA = $this->CI->sConstanteSistema->ObtenerParametroEnvioSunatBeta();
    //PARAMETRO PARA URL DE SUNAT U OSE EN RUTAS
    $this->SUNAT_OSE = $this->CI->sConstanteSistema->ObtenerParametroEnvioSunatOSE();

    if($this->URL_BETA == 1)
    {
      $this->URL_WSDL_SUNAT = ($this->SUNAT_OSE == 1) ? URL_ENVIO_OSE_BETA : URL_ENVIO_SUNAT_BETA;//'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
      $this->URL_WSDL_SUNAT_GUIA = ($this->SUNAT_OSE == 1) ? URL_ENVIO_OSE_GUIA_BETA : URL_ENVIO_SUNAT_GUIA_BETA;
    }
    else {
      $this->URL_WSDL_SUNAT = ($this->SUNAT_OSE == 1) ? URL_ENVIO_OSE_PRODUCCION : URL_ENVIO_SUNAT_PRODUCCION;//APP_PATH_URL."assets/plantillas/billService.xml?wsdl";
      $this->URL_WSDL_SUNAT_GUIA = ($this->SUNAT_OSE == 1) ? URL_ENVIO_OSE_GUIA_PRODUCCION : URL_ENVIO_SUNAT_GUIA_PRODUCCION;
    }

    if($this->SUNAT_OSE == 1)
    {
      $this->CABECERA_OSE = USUARIO_OSE;
    }

    $this->URL_WSDL_SUNAT_CDR=APP_PATH_URL."assets/plantillas/billConsultService.xml?wsdl";
  }

  public function __setUsernameToken($username, $password)
  {
    $this->username = $username;
    $this->password = $password;
  }

  public function sendBill($fileName,$contentFile)
  {
    if($this->SUNAT_OSE == 1)
    {
      $this->URL_OASIS = $this->CABECERA_OSE;
    }
    // print_r($this->URL_OASIS);exit;
    $argumentos=array("fileName"=>$fileName,"contentFile"=>$contentFile,"partyType"=>"");
    $resultado = $this->__soapCall("sendBill",array($argumentos));

    if($resultado == false)
    {
      return false;
    }
    else {
      // code...
      // print_r($resultado);exit;
      if($resultado["Estado"] == 1)
      {
        return $resultado["Message"]->applicationResponse;
      }
      else {
        $resultado["Message"]["Error"] = "Ha ocurrido un error.";
        return $resultado["Message"];
      }
    }
  }

  public function sendSummary($fileName,$contentFile)
  {
    if($this->SUNAT_OSE == 1)
    {
      $this->URL_OASIS = $this->CABECERA_OSE;
    }

    $argumentos=array("fileName"=>$fileName,"contentFile"=>$contentFile,"partyType"=>"");
    $resultado = $this->__soapCall("sendSummary",array($argumentos));
    if($resultado == false)
    {
      return false;
    }
    else {
      // print_r($resultado);exit;
      // code...
      if($resultado["Estado"] == 1)
      {
        return $resultado["Message"]->ticket;
      }
      else {
        $resultado["Message"]["Error"] = "Ha ocurrido un error.";
        return $resultado["Message"];
      }
    }

  }

  public function getStatus($ticket)
  {
    if($this->SUNAT_OSE == 1)
    {
      $this->URL_OASIS = $this->CABECERA_OSE;
    }
    
    $argumentos=array("ticket"=>$ticket);
    $resultado = $this->__soapCall("getStatus",array($argumentos));
    if($resultado == false)
    {
      return false;
    }
    else {
      // print_r($resultado);exit;
      // code...
      if($resultado["Estado"] == 1)
      {
        return $resultado["Message"]->status;
      }
      else {
        $resultado["Message"]["Error"] = "Ha ocurrido un error.";
        return $resultado["Message"];
      }
    }
  }

  public function getStatusCDR($data)
  {
    $argumentos=array("rucComprobante"=>$data["ruc"], "tipoComprobante"=>$data["tipo"],
    "serieComprobante"=>$data["serie"], "numeroComprobante"=>$data["numero"]);
    $this->indicadorCDR = true;
    $resultado = $this->__soapCall("getStatusCdr",array($argumentos));
    if($resultado == false)
    {
      return false;
    }
    else {
      // print_r($resultado);exit;
      // code...
      if($resultado["Estado"] == 1)
      {
        return $resultado["Message"]->statusCdr;
      }
      else {
        $resultado["Message"]["Error"] = "Ha ocurrido un error.";
        return $resultado["Message"];
      }
    }
  }

  public function __soapCall($function_name,$arguments,$options=array(),$input_headers = null,&$output_headers = null)
  {
    if($this->indicadorGuiaRemisionRemitente)
    {
      $URL = ($this->indicadorCDR == false) ? $this->URL_WSDL_SUNAT_GUIA : $this->URL_WSDL_SUNAT_CDR;
    }
    else
    {
      $URL = ($this->indicadorCDR == false) ? $this->URL_WSDL_SUNAT : $this->URL_WSDL_SUNAT_CDR;
      // ($this->indicadorCDR == false) ? $URL = $this->URL_WSDL_SUNAT : $URL = $this->URL_WSDL_SUNAT_CDR;
    }

    $handle = curl_init($URL);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

    if($httpCode == 200) {
      curl_close($handle);
    }
    else {
      return false;
    }

    try {
      parent::__construct($URL);
      $input_headers = $this->generarWSSeguridadHeader();

      $input["Estado"] = 1;
      $input["Message"] = parent::__soapCall($function_name,$arguments,$options,$input_headers,$output_headers);
      return $input;
    }
    catch (Exception $e) {
      // echo $e->getMessage();
      $input["Estado"] = 0;
      $input["Message"]["faultcode"] = $e->faultcode;
      $input["Message"]["faultstring"] = $e->faultstring;
      $input["Message"]["detail"] = ($this->SUNAT_OSE == 1) ? $e->detail->message : "";
      // print_r($e);exit;
      return $input;
    }
  }

  private function generarWSSeguridadHeader()
  {
    $WSHeader = '<wsse:Security xmlns:wsse="'.$this->URL_OASIS.'">
    <wsse:UsernameToken>
          <wsse:Username>'.$this->username.'</wsse:Username>
          <wsse:Password>'.$this->password.'</wsse:Password>
      </wsse:UsernameToken>
    </wsse:Security>';

    $header = new SoapHeader($this->URL_OASIS, 'Security', new SoapVar($WSHeader, XSD_ANYXML));
    return $header;
  }

  //PARA LAS CONSULTAS DE VALIDEZ 
  public function __soapCallConsulta($function_name,$arguments,$options=array(),$input_headers = null,&$output_headers = null)
  {
    $handle = curl_init($this->URL_WSDL_SUNAT_CONSULTA);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    if($httpCode == 200) {
      curl_close($handle);
    }
    else {
      return false;
    }

    try {
      $URL = $this->URL_WSDL_SUNAT_CONSULTA;
      parent::__construct($URL);
      $input_headers = $this->generarWSSeguridadHeader();

      $input["Estado"] = 1;

      $input["Message"] = parent::__soapCall($function_name,$arguments,$options,$input_headers,$output_headers);
      return $input;
    }
    catch (Exception $e) {
      // echo $e->getMessage();
      $input["Estado"] = 0;
      $input["Message"]["faultcode"] = $e->faultcode;
      $input["Message"]["faultstring"] = $e->faultstring;
      return $input;
    }
  }

  public function getStatusComprobante($data)
  {
    $this->URL_WSDL_SUNAT_CONSULTA = $this->URL_WSDL_SUNAT_ESTADO;
    $argumentos=array("rucComprobante"=>$data["ruc"], "tipoComprobante"=>$data["tipo"],
    "serieComprobante"=>$data["serie"], "numeroComprobante"=>$data["numero"]);
    $resultado = $this->__soapCallConsulta("getStatus",array($argumentos));
    
    if($resultado == false)
    {
      return false;
    }
    else {
      // code...
      if($resultado["Estado"] == 1)
      {
        return $resultado["Message"]->status;
      }
      else {
        $resultado["Message"]["Error"] = "Ha ocurrido un error.";
        return $resultado["Message"];
      }
    }
  }

  public function validarComprobanteElectronico($data)
  {
    $this->URL_WSDL_SUNAT_CONSULTA = $this->URL_WSDL_SUNAT_VALIDAR;
    $argumentos=array("rucEmisor"=>$data["rucEmisor"], "tipoCDP"=>$data["tipoCDP"],
    "serieCDP"=>$data["serieCDP"], "numeroCDP"=>$data["numeroCDP"], 
    "tipoDocIdReceptor"=>$data["tipoDocIdReceptor"], "numeroDocIdReceptor"=>$data["numeroDocIdReceptor"], 
    "fechaEmision"=>$data["fechaEmision"], "importeTotal"=>$data["importeTotal"]);
    $resultado = $this->__soapCallConsulta("validaCDPcriterios",array($argumentos));
    
    if($resultado == false)
    {
      return false;
    }
    else {
      // code...
      if($resultado["Estado"] == 1)
      {
        return $resultado["Message"]->cdpvalidado;
      }
      else {
        $resultado["Message"]["Error"] = "Ha ocurrido un error.";
        return $resultado["Message"];
      }
    }
  }

}

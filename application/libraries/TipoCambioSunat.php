<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TipoCambioSunat {
  private $URL_TIPOCAMBIO = 'https://www.deperu.com/api/rest/cotizaciondolar.json';

  function __construct()
  {
      //parent::__construct();
      /*Additional code which you want to run automatically in every function call */
      if (!isset($this->CI))
      {
          $this->CI =& get_instance();
      }
      // $this->CI->load->service('Configuracion/General/sEmpresa');
  }

  function ValidarURL()
  {
    try {
      $url = $this->URL_TIPOCAMBIO;
      $handle = curl_init($url);
      curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
      $response = curl_exec($handle);
      $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

      if($httpCode == 200) {
        curl_close($handle);
        return true;
      }
      else {
        return false;
        // throw new Exception("Error Processing Request", 1);
      }
    } catch (Exception $e) {
      return false;
      // return $e->getMessage();
    }

  }

  function ConsultarTipoCambioCompra(){
    try {
      $ValidarTipoCambio = $this->ValidarURL();
      if($ValidarTipoCambio)
      {
        $datos_tipocambio = file_get_contents($this->URL_TIPOCAMBIO);
        $json_tipocambio = json_decode($datos_tipocambio, true);
        if(is_array($json_tipocambio))
        {
          if(count($json_tipocambio["Cotizacion"]) > 0)
          {
            return $json_tipocambio["Cotizacion"][0]["Compra"];
          }
          else {
            return '';
          }
        }
      }
      else {
        return '';
      }
    } catch (Exception $e) {
      return '';
    }
  }

  function ConsultarTipoCambioVenta(){
    try {
      $ValidarTipoCambio = $this->ValidarURL();
      if($ValidarTipoCambio)
      {
        $datos_tipocambio = file_get_contents($this->URL_TIPOCAMBIO);
        $json_tipocambio = json_decode($datos_tipocambio, true);
        if(is_array($json_tipocambio))
        {
          if(count($json_tipocambio["Cotizacion"]) > 0)
          {
            return $json_tipocambio["Cotizacion"][0]["Venta"];
          }
          else {
            return '';
          }
        }
      }
      else {
        return '';
      }
    } catch (Exception $e) {
      return '';
    }
  }

  public function ConsultarTipoCambio($fecha)
  {
    try {
        $fechaES = DateTime::createFromFormat('d/m/Y', $fecha);
        $fechaEN = $fechaES->format('Y-m-d');

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha=$fechaEN",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));

        $result = curl_exec($curl);

        // Close cURL resource
        curl_close($curl);

        $resultado = json_decode($result, true); 

        return array('TipoCambioVenta' => $resultado["venta"], 'TipoCambioCompra' => $resultado["compra"]);

    } catch (Exception $e) {
      return '';
    }
  }


}

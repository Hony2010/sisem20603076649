<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sPendienteLetraCobrar extends MY_Service
{

  public $PendienteLetraCobrar = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->library('reporter');
    $this->load->library('imprimir');
    $this->load->helper("date");
    $this->load->model("Base");
    $this->load->model('Caja/mPendienteLetraCobrar');
    $this->load->service("Caja/sDetallePendienteLetraCobrar");  
    $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
    $this->load->service("Caja/sMovimientoCaja");
    $this->load->service("Configuracion/General/sMoneda");
    $this->load->service("Configuracion/General/sTipoDocumento");
    $this->PendienteLetraCobrar = $this->mPendienteLetraCobrar->PendienteLetraCobrar;
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $this->PendienteLetraCobrar["FechaGiro"] = $hoy;
    $this->PendienteLetraCobrar["FechaVencimiento"] = $hoy;

    $this->PendienteLetraCobrar['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $this->PendienteLetraCobrar["IdTipoDocumento"] = ID_TIPO_DOCUMENTO_PENDIENTE_LETRA_COBRAR;

    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($this->PendienteLetraCobrar);
    $this->PendienteLetraCobrar["SerieDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : "";
    $this->PendienteLetraCobrar["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : "";
    $this->PendienteLetraCobrar["IdTipoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdTipoDocumento"] : "";

    $this->PendienteLetraCobrar["ImporteLetra"] = "0.00";
    $this->PendienteLetraCobrar["ImporteCobrado"] = 0;
    $this->PendienteLetraCobrar["InteresCobrado"] = 0;

    $this->PendienteLetraCobrar["NumeroDocumento"] = "";
    $this->PendienteLetraCobrar["Documento"] = "";
    $this->PendienteLetraCobrar["Item"] = "";

    $this->PendienteLetraCobrar["LugarGiro"] = "";
    $this->PendienteLetraCobrar["NombreAval"] = "";
    $this->PendienteLetraCobrar["RUCAval"] = "";

    $data = array(
      'SeriesDocumento' => $SeriesDocumento
    );

    $resultado = array_merge($this->PendienteLetraCobrar, $data);

    return $resultado;
  }

  //VALIDACION CORRELATIVO
  function ValidarCorrelativoDocumento($data)
  {
    $resultado = "";

    if (strlen($data["IdPendienteLetraCobrar"]) == 0 && strlen($data["NumeroDocumento"]) == 0) {
      return $resultado;
    }

    if (strlen($data["NumeroDocumento"]) > 0 && !is_numeric($data["NumeroDocumento"])) {
      $resultado = $resultado . "El numero de documento debe ser mayor a cero y numérico." . "\n";
    } else {
      $output = $this->mPendienteLetraCobrar->ObtenerPendienteLetraCobrar($data);

      if (count($output) > 0) //existe y es modificacion
      {
        $resultado2 = $output[0];
        if ($resultado2["IdTipoDocumento"] == $data["IdTipoDocumento"] && $resultado2["SerieDocumento"] == $data["SerieDocumento"]  && $resultado2["NumeroDocumento"] == $data["NumeroDocumento"]  && $resultado2["FechaGiro"] == $data["FechaGiro"]) {
          //$resultado = $resultado."NO hay cambios \n";
          return $resultado;
        }
      } else {
        $resultado3 = $this->mPendienteLetraCobrar->ObtenerPendienteLetraCobrarPorSerieDocumento($data);
        if ($resultado3 != null) {
          $resultado = $resultado . "El número de documento ya existe en otro comprobante de venta" . "\n";
          return $resultado;
        }
      }
    }

    $objeto1 = $this->mPendienteLetraCobrar->ObtenerFechaMayor($data);
    $objeto2 = $this->mPendienteLetraCobrar->ObtenerFechaMenor($data);
    $fechamayor = $objeto1->FechaGiroMayor;
    $fechamenor = $objeto2->FechaGiroMenor;

    if (strlen($fechamayor) != 0 && strlen($fechamenor) != 0) {
      if (!($data["FechaGiro"] >= $fechamenor && $data["FechaGiro"] <= $fechamayor))
        $resultado = $resultado . "La fecha emisión debe ser entre " . $fechamenor . " al " . $fechamayor . " \n";
    } elseif (strlen($fechamayor) != 0) {
      if (!($data["FechaGiro"] <= $fechamayor))
        $resultado = $resultado . "La fecha emisión debe ser menor o igual al " . $fechamayor . " \n";
    } elseif (strlen($fechamenor) != 0) {
      // if(!($data["FechaGiro"]>=$fechamenor))
      // $resultado = $resultado."La fecha emisión debe ser mayor o igual al ".$fechamenor." \n";
    } else {
      //$resultado = $resultado."La fecha emisión debe ser mayor o igual al ".$fechamenor." \n";
    }

    return $resultado;
  }

  function ActualizarSerieDocumentoPendienteLetraCobrar($data)
  {
    $resultado = $this->mPendienteLetraCobrar->ActualizarPendienteLetraCobrar($data);
    return $resultado;
  }

  function InsertarPendienteLetraCobrar($data)
  {
    try {
      $resultadoValidacion = "";

      if (!$this->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)) {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      } else if ($resultadoValidacion == "") {
        $resultado = $this->mPendienteLetraCobrar->InsertarPendienteLetraCobrar($data);

        if (strlen($data["IdPendienteLetraCobrar"]) == 0 && strlen($data["NumeroDocumento"]) == 0) {
          $dataCorrelativo["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
          $UltimoDocumento = $this->sCorrelativoDocumento->IncrementarCorrelativoDocumento($dataCorrelativo);
          $input = $data;
          $input["NumeroDocumento"] = $UltimoDocumento;
          $resultadoValidacionCorrelativo = $this->ValidarCorrelativoDocumento($input);
          if ($resultadoValidacionCorrelativo != "") return $resultadoValidacionCorrelativo;
          $resultado["NumeroDocumento"] = $UltimoDocumento;

          $this->ActualizarSerieDocumentoPendienteLetraCobrar($resultado);
          $resultado["NumeroDocumento"] = str_pad($UltimoDocumento, CANTIDAD_LETRA_NUMERO_DOCUMENTO, '0', STR_PAD_LEFT);
        } else {
          $resultadoCorrelativo = $this->sCorrelativoDocumento->ObtenerNuevoCorrelativoDocumento($data);

          if ($resultadoCorrelativo->UltimoDocumento < $data["NumeroDocumento"]) {
            $input["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
            $input["UltimoDocumento"] = $data["NumeroDocumento"];
            $input["SerieDocumento"] = $data["SerieDocumento"];
            $input["IdTipoDocumento"] = $data["IdTipoDocumento"];
            $this->sCorrelativoDocumento->ActualizarCorrelativoDocumento($input);
          }
          //$resultado["NumeroDocumento"] =str_pad($data["NumeroDocumento"], CANTIDAD_LETRA_NUMERO_DOCUMENTO, '0', STR_PAD_LEFT);
        }
        // $data["IdPendienteLetraCobrar"] = $resultado["IdPendienteLetraCobrar"];
        $resultado["DetallesPendienteLetraCobrar"] = $data["DetallesPendienteLetraCobrar"];

        $resultado["DetallesPendienteLetraCobrar"] = $this->sDetallePendienteLetraCobrar->InsertarDetallesPendienteLetraCobrar($resultado);
        return $resultado;
      } else {
        $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
        return $resultado;
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function ActualizarPendienteLetraCobrar($data)
  {
    try {
      // $data["FechaGiro"]=$data["FechaGiro"];
      $resultadoValidacion = "";

      if (!$this->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)) {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      } else if ($resultadoValidacion == "") {
        // $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];
        $resultado = $this->mPendienteLetraCobrar->ActualizarPendienteLetraCobrar($data);

        $resultadoCorrelativo = $this->sCorrelativoDocumento->ObtenerNuevoCorrelativoDocumento($data);
        if ($resultadoCorrelativo->UltimoDocumento < $data["NumeroDocumento"]) {
          $input["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
          $input["UltimoDocumento"] = $data["NumeroDocumento"];
          $input["SerieDocumento"] = $data["SerieDocumento"];
          $input["IdTipoDocumento"] = $data["IdTipoDocumento"];
          $this->sCorrelativoDocumento->ActualizarCorrelativoDocumento($input);
        }
        
        return $resultado;
      } else {
        throw new Exception(nl2br($resultadoValidacion));
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  //VALIDACION DE COBRANZAS REALIZAS CON EL PENDIENTE
  function BorrarPendienteLetraCobrar($data)
  {
    // $data["FechaGiro"]=convertToDate($data["FechaGiro"]);
    $resultado = $this->ValidarComprobanteVentaEnCobranzaCliente($data);
    // print_r($resultado);exit;
    if ($resultado == "") {
      // $pendiente = $this->ObtenerPendienteLetraCobrarPorIdComprobanteVenta($data);
      // // print_r($pendiente);exit;
      // if(count($pendiente) > 0)
      // {
      $resultado = $this->mPendienteLetraCobrar->BorrarPendienteLetraCobrar($data);
      return $resultado;
      // }
      // else{
      //   return $data;
      // }
    }
    return $resultado;
  }

  function ObtenerPendientesLetraCobrarPorIdCanjeLetraCobrar($data)
  {
    $resultado = $this->mPendienteLetraCobrar->ObtenerPendientesLetraCobrarPorIdCanjeLetraCobrar($data);
    $i = 1;
    foreach ($resultado as $key => $value) {
      $resultado[$key]["Item"] = $i;
      $resultado[$key]["FechaGiro"] = convertirFechaES($value["FechaGiro"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($value["FechaVencimiento"]);
      $i++;
    }
    return $resultado;
  }

  function BorrarPendientesLetraCobrarPorCanjeLetraCobrar($data)
  {
    $resultado = $this->ObtenerPendientesLetraCobrarPorIdCanjeLetraCobrar($data);
    foreach ($resultado as $key => $value) {
      $response = $this->sDetallePendienteLetraCobrar->BorrarDetallesPendienteLetraCobrarPorIdPendienteLetraCobrar($value);
    }
    return $resultado;
  }

  function InsertarPendientesLetraCobrar($data)
  {
    $pendientesletra = $data["PendientesLetraCobrar"];
    $i = 1;
    foreach ($pendientesletra as $key => $value) {
      $value["IdPendienteLetraCobrar"] = "";
      $value["IdCanjeLetraCobrar"] = $data["IdCanjeLetraCobrar"];
      $value["IdCliente"] = $data["IdCliente"];
      $value["IdMoneda"] = $data["IdMoneda"];
      $value["LugarGiro"] = $data["LugarGiro"];
      $value["NombreAval"] = $data["NombreAval"];
      $value["RUCAval"] = $data["RUCAval"];
      $value["FechaGiro"] = convertToDate($value["FechaGiro"]);
      $value["FechaVencimiento"] = convertToDate($value["FechaVencimiento"]);
      $pendientesletra[$key] = $this->InsertarPendienteLetraCobrar($value);
      $pendientesletra[$key]["Item"] = $i;
      $i++;
    }
    return $pendientesletra;
  }

  function PrepararDetallesPendienteLetraCobrar($data, $item)
  {
    $detalles = array();
    $pendientes = $data["PendientesCobranzaCliente"];

    // $saldo = 0;
    $importe = 0;
    // $primero = true;
    $importeLetra = $item["ImporteLetra"];
    foreach ($pendientes as $key => $value) {
      $resultado = $this->sDetallePendienteLetraCobrar->Cargar();
      // print_r($resultado);exit;
      // print_r($value);exit;
      // if($primero)
      // {
      $SaldoPendiente = $value["SaldoPendiente"] - $importeLetra;
      if ($SaldoPendiente >= 0) {
        $resultado["ImporteComprobanteVenta"] = $importeLetra;
        $importeLetra = 0;
      } else {
        $resultado["ImporteComprobanteVenta"] = $importeLetra + $SaldoPendiente;
        $importeLetra = abs($SaldoPendiente);
        $SaldoPendiente = 0;
      }

      $value["SaldoPendiente"] = $SaldoPendiente;

      $resultado["SaldoPendiente"] = $SaldoPendiente;

      //OTROS CAMPOS
      if(array_key_exists("IdComprobanteVenta", $value))
      {
        $resultado["IdComprobanteVenta"] = $value["IdComprobanteVenta"];
      }

      $resultado["SaldoPendienteComprobanteVenta"] = $resultado["ImporteComprobanteVenta"];
      $resultado["NuevoImporteComprobanteVenta"] = 0;
      // $primero = false;
      // }
      // else
      // {

      // }
      // $resultado["ImporteComprobanteVenta"] = $importeLetra;

      array_push($detalles, $resultado);
      $pendientes[$key] = $value;
    }
    

    $response["DetallesPendienteLetraCobrar"] = $detalles;
    $response["PendientesCobranzaCliente"] = $pendientes;
    return $response;
  }

  //PARA COBRANZACLIENTE
  function ConsultarPendientesLetraCobrarPorIdClienteYFiltro($data)
  {
    $resultado = $this->mPendienteLetraCobrar->ConsultarPendientesLetraCobrarPorIdClienteYFiltro($data);
    return $resultado;
  }

  //OBTENER PCC
  function ObtenerPendienteLetraCobrarPorIdComprobanteVenta($data)
  {
    $resultado = $this->mPendienteLetraCobrar->ObtenerPendienteLetraCobrarPorIdComprobanteVenta($data);
    return $resultado;
  }

  function ConsultarPendienteLetraCobrar($data)
  {
    $resultado = $this->mPendienteLetraCobrar->ObtenerPendienteLetraCobrar($data);
    if(count($resultado) > 0)
    {
      $detalles = $this->sDetallePendienteLetraCobrar->ConsultarDetallesPendienteLetraCobrarPorPendienteLetraCobrar($resultado[0]);
      $resultado[0]["DetallesPendienteLetraCobrar"] = $detalles;
    }
    return $resultado;
  }

  function ConsultarPendientesLetraCobrarParaCobranza($data)
  {
    $resultado = $this->mPendienteLetraCobrar->ConsultarPendientesLetraCobrarParaCobranza($data);
    return $resultado;
  }

  //PARA DESCONTAR PENDIENTE COBRANZA CLIENTE
  function DescontarSaldosPendienteLetraCobrar($data) //UPDATE
  {
    // print_r($data);exit;
    foreach ($data as $key => $value) {
      $response = array();
      if(array_key_exists("IdPendienteLetraCobrar", $value))
      {
        if(is_numeric($value["IdPendienteLetraCobrar"]))
        {
          $response = $this->mPendienteLetraCobrar->ObtenerPendienteLetraCobrar($data);
        }
      }
      
      // print_r($response);exit;
      if(count($response) > 0)
      {
        $response[0]["ImporteCobrado"] = 0;
        $data[$key] = $this->ActualizarPendienteLetraCobrar($response[0]);
      }
    }
    return $data;
  }

  //PARA REVERTIR PENDIENTE COBRANZA CLIENTE
  function RevertirSaldosPendienteLetraCobrar($data) //UPDATE
  {
    // print_r($data);exit;
    foreach ($data as $key => $value) {
      $response = array();
      if(array_key_exists("IdPendienteLetraCobrar", $value))
      {
        if(is_numeric($value["IdPendienteLetraCobrar"]))
        {
          $response = $this->mPendienteLetraCobrar->ObtenerPendienteLetraCobrar($data);
        }
      }

      // print_r($response);exit;
      if(count($response) > 0)
      {
        $response[0]["ImporteCobrado"] = $response[0]["ImporteLetra"];
        $data[$key] = $this->ActualizarPendienteLetraCobrar($response[0]);
        // print_r($resultado);exit;
      }
    }
    return $data;
  }

  //ACTUALIZACIOND DE SALDOS EN REVERSA
  function ActualizarMovimientosCajaParaSaldoPendienteLetraCobrar($data)
  {
    foreach ($data as $key => $value) {
      $response = $this->ActualizarMovimientosCajaParaCobranzaCliente($value);
    }
    return $data;
  }

}

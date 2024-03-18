<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sCanjeLetraCobrar extends MY_Service
{

  public $CanjeLetraCobrar = array();

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
    $this->load->model('Caja/mCanjeLetraCobrar');
    $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
    $this->load->service("Configuracion/General/sTipoDocumento");
    $this->load->service("Configuracion/General/sMoneda");
    $this->load->service("Caja/sPendienteLetraCobrar");
    $this->load->service("Caja/sMovimientoCaja");
    $this->CanjeLetraCobrar = $this->mCanjeLetraCobrar->CanjeLetraCobrar;
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $this->CanjeLetraCobrar["FechaDocumento"] = $hoy;

    $this->CanjeLetraCobrar['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $this->CanjeLetraCobrar["IdTipoDocumento"] = ID_TIPO_DOCUMENTO_CANJE_LETRA_COBRAR;

    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($this->CanjeLetraCobrar);
    $this->CanjeLetraCobrar["SerieDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : "";
    $this->CanjeLetraCobrar["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : "";
    $this->CanjeLetraCobrar["IdTipoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdTipoDocumento"] : "";
    
    $this->CanjeLetraCobrar["NumeroLetra"] = "0";
    $this->CanjeLetraCobrar["ImporteTotalCanje"] = "0.00";

    $this->CanjeLetraCobrar["LugarGiro"] = "";
    $this->CanjeLetraCobrar["NombreAval"] = "";
    $this->CanjeLetraCobrar["RUCAval"] = "";

    $Monedas = $this->sMoneda->ListarMonedas();

    $Filtro["IdMoneda"] = ID_MONEDA_SOLES;
    $Filtro["IdCliente"] = "";
    $Filtro["RazonSocialCliente"] = "";
    $Filtro["NumeroDocumentoIdentidad"] = "";
    $Filtro["FechaInicio"] = $hoy;
    $Filtro["FechaFin"] = $hoy;

    $data = array(
      'SeriesDocumento' => $SeriesDocumento,
      'Filtro' => $Filtro,
      'Monedas' => $Monedas,
      'NuevoPendienteLetraCobrar' => $this->sPendienteLetraCobrar->Cargar(),
      'PendientesCobranzaCliente' => array(),
      'PendientesLetraCobrar' => array()
    );

    $resultado = array_merge($this->CanjeLetraCobrar, $data);

    $this->CanjeLetraCobrar["NuevoCanjeLetraCobrar"] = $resultado;
    return $resultado;
  }

  //VALIDACION CORRELATIVO
  function ValidarCorrelativoDocumento($data)
  {
    $resultado = "";

    if (strlen($data["IdCanjeLetraCobrar"]) == 0 && strlen($data["NumeroDocumento"]) == 0) {
      return $resultado;
    }

    if (strlen($data["NumeroDocumento"]) > 0 && !is_numeric($data["NumeroDocumento"])) {
      $resultado = $resultado . "El numero de documento debe ser mayor a cero y numérico." . "\n";
    } else {
      $output = $this->mCanjeLetraCobrar->ObtenerCanjeLetraCobrar($data);

      if (count($output) > 0) //existe y es modificacion
      {
        $resultado2 = $output[0];
        if ($resultado2["IdTipoDocumento"] == $data["IdTipoDocumento"] && $resultado2["SerieDocumento"] == $data["SerieDocumento"]  && $resultado2["NumeroDocumento"] == $data["NumeroDocumento"]  && $resultado2["FechaDocumento"] == $data["FechaDocumento"]) {
          //$resultado = $resultado."NO hay cambios \n";
          return $resultado;
        }
      } else {
        $resultado3 = $this->mCanjeLetraCobrar->ObtenerCanjeLetraCobrarPorSerieDocumento($data);
        if ($resultado3 != null) {
          $resultado = $resultado . "El número de documento ya existe en otro comprobante de venta" . "\n";
          return $resultado;
        }
      }
    }

    $objeto1 = $this->mCanjeLetraCobrar->ObtenerFechaMayor($data);
    $objeto2 = $this->mCanjeLetraCobrar->ObtenerFechaMenor($data);
    $fechamayor = $objeto1->FechaDocumentoMayor;
    $fechamenor = $objeto2->FechaDocumentoMenor;

    if (strlen($fechamayor) != 0 && strlen($fechamenor) != 0) {
      if (!($data["FechaDocumento"] >= $fechamenor && $data["FechaDocumento"] <= $fechamayor))
        $resultado = $resultado . "La fecha emisión debe ser entre " . $fechamenor . " al " . $fechamayor . " \n";
    } elseif (strlen($fechamayor) != 0) {
      if (!($data["FechaDocumento"] <= $fechamayor))
        $resultado = $resultado . "La fecha emisión debe ser menor o igual al " . $fechamayor . " \n";
    } elseif (strlen($fechamenor) != 0) {
      // if(!($data["FechaDocumento"]>=$fechamenor))
      // $resultado = $resultado."La fecha emisión debe ser mayor o igual al ".$fechamenor." \n";
    } else {
      //$resultado = $resultado."La fecha emisión debe ser mayor o igual al ".$fechamenor." \n";
    }

    return $resultado;
  }

  function ActualizarSerieDocumentoCanjeLetraCobrar($data)
  {
    $resultado = $this->mCanjeLetraCobrar->ActualizarCanjeLetraCobrar($data);
    return $resultado;
  }

  //VALIDACIONES
  function ValidarComprobantesEnMovimientos($data)
  {
    $texto = "";
    $comprobantes = $this->mCanjeLetraCobrar->ObtenerComprobanteVentaPorIdCanjeLetraCobrar($data);
    foreach ($comprobantes as $key => $value) {
      $movimientos = $this->sMovimientoCaja->ObtenerDocumentosPorIdComprobanteVenta($value);
      if (count($movimientos)) {
        $texto = "Los comprobantes ya tienen movimientos.";
        break;
      }
    }
    return $texto;
  }

  function InsertarCanjeLetraCobrar($data)
  {
    try {
      $data["FechaDocumento"] = convertToDate($data["FechaDocumento"]);
      $resultadoValidacion = "";

      if (!$this->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)) {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      } else if ($resultadoValidacion == "") {
        $data["IdCanjeLetraCobrar"] = "";
        $resultado = $this->mCanjeLetraCobrar->InsertarCanjeLetraCobrar($data);

        $resultado["PendientesCobranzaCliente"] = $data["PendientesCobranzaCliente"];
        $resultado["PendientesLetraCobrar"] = $data["PendientesLetraCobrar"];
        // print_r($resultado);exit;
        if (strlen($data["IdCanjeLetraCobrar"]) == 0 && strlen($data["NumeroDocumento"]) == 0) {
          // echo "One";
          $dataCorrelativo["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
          $UltimoDocumento = $this->sCorrelativoDocumento->IncrementarCorrelativoDocumento($dataCorrelativo);
          $input = $data;
          $input["NumeroDocumento"] = $UltimoDocumento;
          $resultadoValidacionCorrelativo = $this->ValidarCorrelativoDocumento($input);
          if ($resultadoValidacionCorrelativo != "") return $resultadoValidacionCorrelativo;
          $resultado["NumeroDocumento"] = $UltimoDocumento;

          $this->ActualizarSerieDocumentoCanjeLetraCobrar($resultado);
          $resultado["NumeroDocumento"] = str_pad($UltimoDocumento, CANTIDAD_LETRA_NUMERO_DOCUMENTO, '0', STR_PAD_LEFT);
        } else {
          // echo "Two";
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
        // $data["IdCanjeLetraCobrar"] = $resultado["IdCanjeLetraCobrar"];
        // print_r($resultado);exit;
        //PREDATA
        $resultado["PendientesLetraCobrar"] = $this->PrepararDataPendientesLetraCobrar($resultado);
        // return $resultado;
        //INSERCION
        $resultado["PendientesLetraCobrar"] = $this->sPendienteLetraCobrar->InsertarPendientesLetraCobrar($resultado);

        $resultado["ComprobantesVentaPendiente"] = $this->DescontarSaldosPendienteCobranzaCliente($resultado);

        return $resultado;
      } else {
        $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
        return $resultado;
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function ActualizarCanjeLetraCobrar($data)
  {
    try {
      $data["FechaDocumento"] = convertToDate($data["FechaDocumento"]);
      $resultadoValidacion = "";
      $validacion = $this->ValidarComprobantesEnMovimientos($data);

      if (!$this->session->userdata("Usuario_" . LICENCIA_EMPRESA_RUC)) {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      } elseif ($validacion != "") {
        return $validacion;
      } elseif ($resultadoValidacion == "") {
        // $data["ImporteTotalCanje"] = (is_string($data["ImporteTotalCanje"])) ? str_replace(',',"",$data["ImporteTotalCanje"]) : $data["ImporteTotalCanje"];
        $resultado = $this->mCanjeLetraCobrar->ActualizarCanjeLetraCobrar($data);

        $resultadoCorrelativo = $this->sCorrelativoDocumento->ObtenerNuevoCorrelativoDocumento($data);
        if ($resultadoCorrelativo->UltimoDocumento < $data["NumeroDocumento"]) {
          $input["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
          $input["UltimoDocumento"] = $data["NumeroDocumento"];
          $input["SerieDocumento"] = $data["SerieDocumento"];
          $input["IdTipoDocumento"] = $data["IdTipoDocumento"];
          $this->sCorrelativoDocumento->ActualizarCorrelativoDocumento($input);
        }

        $revertirPendientes = $this->RevertirSaldosPendienteCobranzaCliente($data);

        //BORRAMOS DATOS
        $this->sPendienteLetraCobrar->BorrarPendientesLetraCobrarPorCanjeLetraCobrar($data);

        //PREDATA
        $data["PendientesLetraCobrar"] = $this->PrepararDataPendientesLetraCobrar($data);
        //INSERCION
        $resultado["PendientesLetraCobrar"] = $this->sPendienteLetraCobrar->InsertarPendientesLetraCobrar($data);

        $descontarPendientes = $this->DescontarSaldosPendienteCobranzaCliente($resultado);
        $resultado["ComprobantesVentaPendiente"] = array_merge($descontarPendientes, $revertirPendientes);
        return $resultado;
      } else {
        throw new Exception(nl2br($resultadoValidacion));
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  //VALIDACION DE COBRANZAS REALIZAS CON EL PENDIENTE
  function BorrarCanjeLetraCobrar($data)
  {
    // $data["FechaDocumento"]=convertToDate($data["FechaDocumento"]);
    // $resultado = $this->ValidarComprobanteVentaEnCobranzaCliente($data);
    $validacion = $this->ValidarComprobantesEnMovimientos($data);
    // print_r($resultado);exit;
    if ($validacion == "") {
      // $pendiente = $this->ObtenerCanjeLetraCobrarPorIdComprobanteVenta($data);
      // // print_r($pendiente);exit;
      // if(count($pendiente) > 0)
      // {
      $resultado = $this->mCanjeLetraCobrar->BorrarCanjeLetraCobrar($data);

      //BORRANDO PENDIENTES LETRA COBRAR
      $this->sPendienteLetraCobrar->BorrarPendientesLetraCobrarPorCanjeLetraCobrar($data);
      

      $resultado["ComprobantesVentaPendiente"] = $this->RevertirSaldosPendienteCobranzaCliente($resultado);

      return $resultado;
      // }
      // else{
      //   return $data;
      // }
    }
    return $resultado;
  }

  function PrepararDataPendientesLetraCobrar($data)
  {
    $pendientesletra = $data["PendientesLetraCobrar"];
    foreach ($pendientesletra as $key => $value) {
      // print_r($value);exit;
      $preData = $this->sPendienteLetraCobrar->PrepararDetallesPendienteLetraCobrar($data, $value);
      $data["PendientesCobranzaCliente"] = $preData["PendientesCobranzaCliente"];

      $value["DetallesPendienteLetraCobrar"] = $preData["DetallesPendienteLetraCobrar"];
      $pendientesletra[$key] = $value;
    }
    return $pendientesletra;
  }

  //CONSULTAS
  function ConsultarCanjesLetraCobrar($data, $numeropagina, $numerofilasporpagina)
  {
    $numerofilainicio = $numerofilasporpagina * ($numeropagina - 1);
    $resultado = $this->mCanjeLetraCobrar->ConsultarCanjesLetraCobrar($data, $numerofilainicio, $numerofilasporpagina);
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    foreach ($resultado as $key => $item) {
      $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
      $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
      $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;
      $resultado[$key]["FechaDocumento"] = convertirFechaES($item["FechaDocumento"]);
    }

    return $resultado;
  }

  function ObtenerNumeroFilasPorPagina()
  {
    $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_COMPROBANTEVENTA;
    $parametro = $this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
    $numerofilasporpagina = $parametro->ValorParametroSistema;
    return $numerofilasporpagina;
  }

  function ObtenerNumeroTotalCanjesLetraCobrar($data)
  {
    $resultado = $this->mCanjeLetraCobrar->ObtenerNumeroTotalCanjesLetraCobrar($data);
    return $resultado;
  }

  function ObtenerPendientesCobranzaClientePorCanje($data)
  {
    $resultado = array();
    $comprobantes = $this->mCanjeLetraCobrar->ObtenerComprobanteVentaPorIdCanjeLetraCobrar($data);
    foreach ($comprobantes as $key => $value) {
      if(array_key_exists("IdComprobanteVenta", $value))
      {
        if(is_numeric($value["IdComprobanteVenta"]))
        {
          $response = $this->sPendienteCobranzaCliente->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($value)[0];
          $response["FechaEmision"] = convertirFechaES($response["FechaEmision"]);
          $response["ComprobanteSeleccionado"] = true;
          $response["SaldoPendiente"] = $response["MontoOriginal"];
          array_push($resultado, $response);
        }
      }
    }
    return $resultado;
  }

  function DescontarSaldosPendienteCobranzaCliente($data) //UPDATE
  {
    // print_r($data);exit;
    $comprobantes = $this->mCanjeLetraCobrar->ObtenerComprobanteVentaPorIdCanjeLetraCobrar($data);
    // print_r($comprobantes);exit;
    foreach ($comprobantes as $key => $value) {
      $response = array();
      if(array_key_exists("IdComprobanteVenta", $value))
      {
        if(is_numeric($value["IdComprobanteVenta"]))
        {
          $response = $this->sPendienteCobranzaCliente->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($value);
        }
      }
      
      if(count($response) > 0)
      {
        $response[0]["SaldoPendiente"] = 0;
        $response[0]["MontoCobrado"] = $response[0]["MontoOriginal"];
        $data[$key] = $this->sPendienteCobranzaCliente->ActualizarPendienteCobranzaCliente($response[0]);
      }
    }
    return $comprobantes;
  }

  //PARA REVERTIR PENDIENTE COBRANZA CLIENTE
  function RevertirSaldosPendienteCobranzaCliente($data) //UPDATE
  {
    $comprobantes = $this->mCanjeLetraCobrar->ObtenerComprobanteVentaPorIdCanjeLetraCobrar($data);
    // print_r($comprobantes);exit;
    foreach ($comprobantes as $key => $value) {
      $response = array();
      if(array_key_exists("IdComprobanteVenta", $value))
      {
        if(is_numeric($value["IdComprobanteVenta"]))
        {
          $response = $this->sPendienteCobranzaCliente->ObtenerPendienteCobranzaClientePorIdComprobanteVenta($value);
        }
      }
      
      if(count($response) > 0)
      {
        $response[0]["SaldoPendiente"] = $response[0]["MontoOriginal"];
        $response[0]["MontoCobrado"] = 0;
        $data[$key] = $this->sPendienteCobranzaCliente->ActualizarPendienteCobranzaCliente($response[0]);
      }
    }
    return $comprobantes;
  }
}

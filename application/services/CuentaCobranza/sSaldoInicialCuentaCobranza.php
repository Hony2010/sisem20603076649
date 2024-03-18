<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sSaldoInicialCuentaCobranza extends MY_Service {

  public $SaldoInicialCuentaCobranza = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->helper("date");
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->library('reporter');
    $this->load->library('imprimir');
    $this->load->model("Base");
    $this->load->model('CuentaCobranza/mSaldoInicialCuentaCobranza');
    $this->load->model('Venta/mComprobanteVenta');
    $this->load->service('CuentaCobranza/sDetalleSaldoInicialCuentaCobranza');
		$this->load->service("Configuracion/General/sMoneda");
		$this->load->service("Configuracion/General/sTipoDocumento");
    $this->load->service('Caja/sPendienteCobranzaCliente');

    $this->SaldoInicialCuentaCobranza = $this->mSaldoInicialCuentaCobranza->SaldoInicialCuentaCobranza;
  }

  function CargarSaldoInicialCuentaCobranza($parametro)
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $this->SaldoInicialCuentaCobranza["RazonSocial"] = "";
    $this->SaldoInicialCuentaCobranza["NumeroDocumentoIdentidad"] = "";
    $this->SaldoInicialCuentaCobranza["IdSaldoInicialCuentaCobranza"] = "";
    $this->SaldoInicialCuentaCobranza["IdTipoDocumento"] = "";
    $this->SaldoInicialCuentaCobranza["IdMoneda"] = "";
    $this->SaldoInicialCuentaCobranza["TipoDocumento"] = "";
    $this->SaldoInicialCuentaCobranza["SerieDocumento"] = "";
    $this->SaldoInicialCuentaCobranza["NumeroDocumento"] = "";
    $this->SaldoInicialCuentaCobranza["NombreMoneda"] = "";
    $this->SaldoInicialCuentaCobranza["TipoCambioVenta"] = "";
    $this->SaldoInicialCuentaCobranza["FechaEmision"] = $hoy;
    $this->SaldoInicialCuentaCobranza["FechaVencimiento"] = $hoy;
    $this->SaldoInicialCuentaCobranza["MontoOriginal"] = "";
    $this->SaldoInicialCuentaCobranza["SaldoInicial"] = "";
    $this->SaldoInicialCuentaCobranza["DetalleSaldoInicialCuentaCobranza"] = $this->sDetalleSaldoInicialCuentaCobranza->DetalleSaldoInicialCuentaCobranza;

    $TiposDocumento = $this->sTipoDocumento->ListarTiposDocumento();
    $Monedas = $this->sMoneda->ListarMonedas();

    $data = array(
      "TiposDocumento" => $TiposDocumento,
      "Monedas" => $Monedas,
      "DetallesSaldoInicialCuentaCobranza" => array()
    );

    $resultado = array_merge($this->SaldoInicialCuentaCobranza, $data);

    return $resultado;
  }

  /**CONSULTAS SOBRE COMPROBANTE DE CAJA */
  function ConsultarSaldosInicialCuentaCobranza($data, $numeropagina, $numerofilasporpagina)
  {
    $numerofilainicio = $numerofilasporpagina * ($numeropagina - 1);
    $resultado = $this->mSaldoInicialCuentaCobranza->ConsultarSaldosInicialCuentaCobranza($data, $numerofilainicio, $numerofilasporpagina);
    foreach ($resultado as $key => $item) {
      $resultado[$key]["FechaEmision"] = convertirFechaES($item["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($item["FechaVencimiento"]);
      $resultado[$key]["DetallesSaldoInicialCuentaCobranza"] = $this->sDetalleSaldoInicialCuentaCobranza->ConsultarDetallesSaldoInicialCuentaCobranza($item);
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

  function ObtenerNumeroTotalSaldosInicialCuentaCobranza($data)
  {
    $resultado = $this->mSaldoInicialCuentaCobranza->ObtenerNumeroTotalSaldosInicialCuentaCobranza($data);
    return $resultado;
  }
  /**FIN DE CONSULTAS */

  function InsertarSaldoInicialCuentaCobranza($data)
  {
    try {
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
      $data["MontoOriginal"] = (is_string($data["MontoOriginal"])) ? str_replace(',',"",$data["MontoOriginal"]) : $data["MontoOriginal"];
      $data["SaldoInicial"] = (is_string($data["SaldoInicial"])) ? str_replace(',',"",$data["SaldoInicial"]) : $data["SaldoInicial"];
      $data["TipoCambioVenta"] = (is_string($data["TipoCambioVenta"])) ? str_replace(',',"",$data["TipoCambioVenta"]) : $data["TipoCambioVenta"];

      $resultadoValidacion = $this->ValidarComprobanteVenta($data);

      if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
      {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      }
      else if($resultadoValidacion == "")
      {
        $resultado = $this->mSaldoInicialCuentaCobranza->InsertarSaldoInicialCuentaCobranza($data);
        $resultado["DetallesSaldoInicialCuentaCobranza"] = $this->sDetalleSaldoInicialCuentaCobranza->InsertarDetallesSaldoInicialCuentaCobranza($resultado["IdSaldoInicialCuentaCobranza"], $data["DetallesSaldoInicialCuentaCobranza"]);

        //INSERTAMOS PENDIENTE SALDO INICIAL
        $response = $this->sPendienteCobranzaCliente->AgregarPendienteCobranzaCliente($resultado, true);
        // print_r($response);exit;
        $resultado["FechaEmision"] =convertirFechaES($resultado["FechaEmision"]);
        $resultado["FechaVencimiento"] =convertirFechaES($resultado["FechaVencimiento"]);
        return $resultado;
      }
      else
      {
        $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
        return $resultado;
      }
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function ActualizarSaldoInicialCuentaCobranza($data)
  {
    try {
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
      $data["MontoOriginal"] = (is_string($data["MontoOriginal"])) ? str_replace(',',"",$data["MontoOriginal"]) : $data["MontoOriginal"];
      $data["SaldoInicial"] = (is_string($data["SaldoInicial"])) ? str_replace(',',"",$data["SaldoInicial"]) : $data["SaldoInicial"];
      $data["TipoCambioVenta"] = (is_string($data["TipoCambioVenta"])) ? str_replace(',',"",$data["TipoCambioVenta"]) : $data["TipoCambioVenta"];

      $resultadoValidacion = $this->ValidarComprobanteVenta($data, true);

      if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
      {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      }
      else if($resultadoValidacion == "")
      {
        $resultado = $this->mSaldoInicialCuentaCobranza->ActualizarSaldoInicialCuentaCobranza($data);
        $resultado["DetallesSaldoInicialCuentaCobranza"] = $this->sDetalleSaldoInicialCuentaCobranza->ActualizarDetallesSaldoInicialCuentaCobranza($resultado["IdSaldoInicialCuentaCobranza"], $data["DetallesSaldoInicialCuentaCobranza"]);
        
        //ACTUALIZAMOS PENDIENTE SALDO INICIAL
        $response = $this->sPendienteCobranzaCliente->AgregarPendienteCobranzaCliente($resultado, true);
        // print_r($response);exit;
        $resultado["FechaEmision"] =convertirFechaES($resultado["FechaEmision"]);
        $resultado["FechaVencimiento"] =convertirFechaES($resultado["FechaVencimiento"]);
        return $resultado;
      }
      else
      {
        throw new Exception(nl2br($resultadoValidacion));
      }
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function BorrarSaldoInicialCuentaCobranza($data) {
    // $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
    $resultado = $this->mSaldoInicialCuentaCobranza->BorrarSaldoInicialCuentaCobranza($data);
    if (is_array($resultado)) {
      $detalle = $this->sDetalleSaldoInicialCuentaCobranza->BorrarDetallesPorIdSaldoInicialCuentaCobranza($data);
    }

    //BORRAMOS PENDIENTE SALDO INICIAL
    $response = $this->sPendienteCobranzaCliente->BorrarPendienteCobranzaCliente($resultado, true);
    // print_r("XDDDD");print_r($response);exit;
    if(is_string($response))
    {
      return $response;
    }

    return $resultado;
  }

  function ObtenerSaldoInicialCuentaCobranzaPorSerieDocumento($data)
  {
    $resultado = $this->mSaldoInicialCuentaCobranza->ObtenerSaldoInicialCuentaCobranzaPorSerieDocumentoInsertar($data);
    return $resultado;
  }

  //VALIDACIONES
  function ValidarCorrelativoDocumentoEnVenta($data)
  {
    $resultado = $this->mComprobanteVenta->ObtenerComprobanteVentaPorSerieDocumento($data);
    return (!empty($resultado)) ? "Ya esta registrado la serie y numero de documento en  ventas, favor de revisar." : "";
  }

  function ValidarCorrelativoDocumentoInsertar($data)
  {
    $resultado = $this->mSaldoInicialCuentaCobranza->ObtenerSaldoInicialCuentaCobranzaPorSerieDocumentoInsertar($data);
    return (count($resultado) > 0) ? "La Serie con el Numero de Documento, ya fueron registrados para este Tipo Documento." : "";
  }

  function ValidarCorrelativoDocumentoActualizar($data)
  {
    $resultado = $this->mSaldoInicialCuentaCobranza->ObtenerSaldoInicialCuentaCobranzaPorSerieDocumentoActualizar($data);
    return (count($resultado) > 0) ? "La Serie con el Numero de Documento, ya fueron registrados para este Tipo Documento." : "";
  }

  function ValidarComprobanteVenta($data, $option = false)
  {
    $resultado = "";

    if ($data["IdMoneda"] != ID_MONEDA_SOLES)
    {
      if($data["TipoCambioVenta"] <= 0 || !is_numeric($data["TipoCambioVenta"]))
      {
        $resultado = $resultado."El tipo de cambio de documento debe ser mayor a cero y numérico."."\n";
      }
    }
    $correlativoventa = $this->ValidarCorrelativoDocumentoEnVenta($data);
    if($correlativoventa == "")
    {
      $correlativo = ($option) ? $this->ValidarCorrelativoDocumentoActualizar($data) : $this->ValidarCorrelativoDocumentoInsertar($data);
      $resultado = $resultado.$correlativo;
    }
    else
    {
      $resultado = $resultado.$correlativoventa;
    }

    if(strlen($data["FechaEmision"]) <= 0 || !validateDate($data["FechaEmision"],"Y-m-d"))
    {
      $resultado = $resultado."La fecha de emision es incorrecta."."\n";
    }

    if(strlen($data["IdCliente"]) == 0)
    {
      $resultado = $resultado."El Cliente no se encuentra disponible en el sistema."."\n";
    }

    // if($data["IdFormaPago"] == ID_FORMA_PAGO_CREDITO)
    // {
    //   if( strlen($data["FechaVencimiento"]) <= 0 || !validateDate($data["FechaVencimiento"],"Y-m-d") )
    //   {
    //     $resultado = $resultado."La fecha de vencimiento es incorrecta y obligatoria en cuando la forma de pago es al crédito."."\n";
    //   }
    // }
    // else
    // {
    //   if(strlen($data["FechaVencimiento"])> 0)
    //   {
    //     if(!validateDate($data["FechaVencimiento"],"Y-m-d"))
    //     {
    //       $resultado = $resultado."La fecha de vencimiento es incorrecta."."\n";
    //     }
    //   }
    // }

    if(!is_numeric($data["MontoOriginal"]))
    {
      $resultado = $resultado."El Monto Original no es numerico."."\n";
    }
    else
    {
      if(!($data["MontoOriginal"] > 0))
      {
        $resultado = $resultado."El Monto Original no es mayor a cero."."\n";
      }
    }

    if(!is_numeric($data["TipoCambioVenta"]))
    {
      $resultado = $resultado."El Tipo Cambio no es numerico."."\n";
    }
    else
    {
      if(!($data["TipoCambioVenta"] > 0))
      {
        $resultado = $resultado."El Tipo Cambio no es mayor a cero."."\n";
      }
    }

    if(!is_numeric($data["SaldoInicial"]))
    {
      $resultado = $resultado."El Saldo Inicial no es numerico."."\n";
    }

    $resultado_detalle = "";//$this->sDetalleComprobanteVenta->ValidarDetallesComprobanteVenta($data["DetallesComprobanteVenta"], $data["IdAsignacionSede"]);
    $resultado = $resultado.$resultado_detalle;
    return $resultado;
  }

}

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sSaldoInicialCuentaPago extends MY_Service {

  public $SaldoInicialCuentaPago = array();

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
    $this->load->model('CuentaPago/mSaldoInicialCuentaPago');
    $this->load->model('Compra/mComprobanteCompra');
    $this->load->service('CuentaPago/sDetalleSaldoInicialCuentaPago');
		$this->load->service("Configuracion/General/sMoneda");
		$this->load->service("Configuracion/General/sTipoDocumento");
    $this->load->service('Caja/sPendientePagoProveedor');

    $this->SaldoInicialCuentaPago = $this->mSaldoInicialCuentaPago->SaldoInicialCuentaPago;
  }

  function CargarSaldoInicialCuentaPago($parametro)
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $this->SaldoInicialCuentaPago["RazonSocial"] = "";
    $this->SaldoInicialCuentaPago["NumeroDocumentoIdentidad"] = "";
    $this->SaldoInicialCuentaPago["IdSaldoInicialCuentaPago"] = "";
    $this->SaldoInicialCuentaPago["IdTipoDocumento"] = "";
    $this->SaldoInicialCuentaPago["IdMoneda"] = "";
    $this->SaldoInicialCuentaPago["TipoDocumento"] = "";
    $this->SaldoInicialCuentaPago["SerieDocumento"] = "";
    $this->SaldoInicialCuentaPago["NumeroDocumento"] = "";
    $this->SaldoInicialCuentaPago["NombreMoneda"] = "";
    $this->SaldoInicialCuentaPago["TipoCambioCompra"] = "";
    $this->SaldoInicialCuentaPago["FechaEmision"] = $hoy;
    $this->SaldoInicialCuentaPago["FechaVencimiento"] = $hoy;
    $this->SaldoInicialCuentaPago["MontoOriginal"] = "";
    $this->SaldoInicialCuentaPago["SaldoInicial"] = "";
    $this->SaldoInicialCuentaPago["DetalleSaldoInicialCuentaPago"] = $this->sDetalleSaldoInicialCuentaPago->DetalleSaldoInicialCuentaPago;

    $TiposDocumento = $this->sTipoDocumento->ListarTiposDocumento();
    $Monedas = $this->sMoneda->ListarMonedas();

    $data = array(
      "TiposDocumento" => $TiposDocumento,
      "Monedas" => $Monedas,
      "DetallesSaldoInicialCuentaPago" => array()
    );

    $resultado = array_merge($this->SaldoInicialCuentaPago, $data);

    return $resultado;
  }

  /**CONSULTAS SOBRE COMPROBANTE DE CAJA */
  function ConsultarSaldosInicialCuentaPago($data, $numeropagina, $numerofilasporpagina)
  {
    $numerofilainicio = $numerofilasporpagina * ($numeropagina - 1);
    $resultado = $this->mSaldoInicialCuentaPago->ConsultarSaldosInicialCuentaPago($data, $numerofilainicio, $numerofilasporpagina);
    foreach ($resultado as $key => $item) {
      $resultado[$key]["FechaEmision"] = convertirFechaES($item["FechaEmision"]);
      $resultado[$key]["FechaVencimiento"] = convertirFechaES($item["FechaVencimiento"]);
      $resultado[$key]["DetallesSaldoInicialCuentaPago"] = $this->sDetalleSaldoInicialCuentaPago->ConsultarDetallesSaldoInicialCuentaPago($item);
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

  function ObtenerNumeroTotalSaldosInicialCuentaPago($data)
  {
    $resultado = $this->mSaldoInicialCuentaPago->ObtenerNumeroTotalSaldosInicialCuentaPago($data);
    return $resultado;
  }
  /**FIN DE CONSULTAS */

  function InsertarSaldoInicialCuentaPago($data)
  {
    try {
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
      $data["MontoOriginal"] = (is_string($data["MontoOriginal"])) ? str_replace(',',"",$data["MontoOriginal"]) : $data["MontoOriginal"];
      $data["SaldoInicial"] = (is_string($data["SaldoInicial"])) ? str_replace(',',"",$data["SaldoInicial"]) : $data["SaldoInicial"];
      $data["TipoCambioCompra"] = (is_string($data["TipoCambioCompra"])) ? str_replace(',',"",$data["TipoCambioCompra"]) : $data["TipoCambioCompra"];

      $resultadoValidacion = $this->ValidarComprobanteCompra($data);

      if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
      {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      }
      else if($resultadoValidacion == "")
      {
        $resultado = $this->mSaldoInicialCuentaPago->InsertarSaldoInicialCuentaPago($data);
        $resultado["DetallesSaldoInicialCuentaPago"] = $this->sDetalleSaldoInicialCuentaPago->InsertarDetallesSaldoInicialCuentaPago($resultado["IdSaldoInicialCuentaPago"], $data["DetallesSaldoInicialCuentaPago"]);

        //INSERTAMOS PENDIENTE SALDO INICIAL
        $response = $this->sPendientePagoProveedor->AgregarPendientePagoProveedor($resultado, true);
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

  function ActualizarSaldoInicialCuentaPago($data)
  {
    try {
      $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
      $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
      $data["MontoOriginal"] = (is_string($data["MontoOriginal"])) ? str_replace(',',"",$data["MontoOriginal"]) : $data["MontoOriginal"];
      $data["SaldoInicial"] = (is_string($data["SaldoInicial"])) ? str_replace(',',"",$data["SaldoInicial"]) : $data["SaldoInicial"];
      $data["TipoCambioCompra"] = (is_string($data["TipoCambioCompra"])) ? str_replace(',',"",$data["TipoCambioCompra"]) : $data["TipoCambioCompra"];

      $resultadoValidacion = $this->ValidarComprobanteCompra($data, true);

      if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
      {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      }
      else if($resultadoValidacion == "")
      {
        $resultado = $this->mSaldoInicialCuentaPago->ActualizarSaldoInicialCuentaPago($data);
        $resultado["DetallesSaldoInicialCuentaPago"] = $this->sDetalleSaldoInicialCuentaPago->ActualizarDetallesSaldoInicialCuentaPago($resultado["IdSaldoInicialCuentaPago"], $data["DetallesSaldoInicialCuentaPago"]);
        
        //ACTUALIZAMOS PENDIENTE SALDO INICIAL
        $response = $this->sPendientePagoProveedor->AgregarPendientePagoProveedor($resultado, true);
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

  function BorrarSaldoInicialCuentaPago($data) {
    // $data["FechaEmision"] = convertToDate($data["FechaEmision"]);
    $resultado = $this->mSaldoInicialCuentaPago->BorrarSaldoInicialCuentaPago($data);
    if (is_array($resultado)) {
      $detalle = $this->sDetalleSaldoInicialCuentaPago->BorrarDetallesPorIdSaldoInicialCuentaPago($data);
    }

    //BORRAMOS PENDIENTE SALDO INICIAL
    $response = $this->sPendientePagoProveedor->BorrarPendientePagoProveedor($resultado, true);
    // print_r("XDDDD");print_r($response);exit;
    if(is_string($response))
    {
      return $response;
    }

    return $resultado;
  }

  function ObtenerSaldoInicialCuentaPagoPorSerieDocumento($data)
  {
    $resultado = $this->mSaldoInicialCuentaPago->ObtenerSaldoInicialCuentaPagoPorSerieDocumentoInsertar($data);
    return $resultado;
  }

  //VALIDACIONES
  function ValidarCorrelativoDocumentoEnCompra($data)
  {
    $resultado = $this->mComprobanteCompra->ObtenerComprobanteCompraPorSerieDocumento($data);
    return (!empty($resultado)) ? "Ya esta registrado la serie y numero de documento en  compras, favor de revisar ." : "";
  }

  function ValidarCorrelativoDocumentoInsertar($data)
  {
    $resultado = $this->mSaldoInicialCuentaPago->ObtenerSaldoInicialCuentaPagoPorSerieDocumentoInsertar($data);
    return (count($resultado) > 0) ? "La Serie con el Numero de Documento, ya fueron registrados para este Tipo Documento." : "";
  }

  function ValidarCorrelativoDocumentoActualizar($data)
  {
    $resultado = $this->mSaldoInicialCuentaPago->ObtenerSaldoInicialCuentaPagoPorSerieDocumentoActualizar($data);
    return (count($resultado) > 0) ? "La Serie con el Numero de Documento, ya fueron registrados para este Tipo Documento." : "";
  }

  function ValidarComprobanteCompra($data, $option = false)
  {
    $resultado = "";

    if ($data["IdMoneda"] != ID_MONEDA_SOLES)
    {
      if($data["TipoCambioCompra"] <= 0 || !is_numeric($data["TipoCambioCompra"]))
      {
        $resultado = $resultado."El tipo de cambio de documento debe ser mayor a cero y numérico."."\n";
      }
    }
    $correlativoventa = $this->ValidarCorrelativoDocumentoEnCompra($data);
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

    if(strlen($data["IdProveedor"]) == 0)
    {
      $resultado = $resultado."El Proveedor no se encuentra disponible en el sistema."."\n";
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

    if(!is_numeric($data["TipoCambioCompra"]))
    {
      $resultado = $resultado."El Tipo Cambio no es numerico."."\n";
    }
    else
    {
      if(!($data["TipoCambioCompra"] > 0))
      {
        $resultado = $resultado."El Tipo Cambio no es mayor a cero."."\n";
      }
    }

    if(!is_numeric($data["SaldoInicial"]))
    {
      $resultado = $resultado."El Saldo Inicial no es numerico."."\n";
    }

    $resultado_detalle = "";//$this->sDetalleComprobanteCompra->ValidarDetallesComprobanteCompra($data["DetallesComprobanteCompra"], $data["IdAsignacionSede"]);
    $resultado = $resultado.$resultado_detalle;
    return $resultado;
  }

}

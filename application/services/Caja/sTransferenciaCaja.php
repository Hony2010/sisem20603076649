<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');
require_once(APPPATH.'services\Caja\sComprobanteCaja.php');

class sTransferenciaCaja extends sComprobanteCaja {

  public function __construct()
  {
    parent::__construct();
    $this->load->library('sesionusuario');
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service('Configuracion/General/sMoneda');
    $this->load->service('Seguridad/sAccesoCajaUsuario');
    $this->load->service('Seguridad/sUsuario');
    $this->load->service('Caja/sSaldoCajaTurno');
    $this->load->service('Caja/sMovimientoCaja');
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("Y-m-d");

    // return $resultado;
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_TRANSFERENCIA_CAJA;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    
    $resultado = parent::CargarComprobanteCaja($parametro);
    // $resultado["FechaTranferencia"] = $hoy;
    //Cajas de Origen
    $CajasOrigen = $this->sAccesoCajaUsuario->ListarAccesosCajaUsuarioPorIdUsuario(); //Se listaran las cajas por usuario
    $resultado["CajasOrigen"] = $CajasOrigen;
    //Cajas de Destino
    $filtroCaja["FechaCaja"] = $hoy;
    $CajasDestino = $this->sSaldoCajaTurno->ObtenerCajasAperturadasParaTransferencias($filtroCaja); //Se listaran las cajas por usuario
    $resultado["CajasDestino"] = $CajasDestino;

    // $resultado["IdCajaOrigen"] = "";
    // $resultado["IdCajaDestino"] = "";
    $resultado["CajeroTransferencia"] = "";
    $resultado["SaldoActual"] = "";
    $resultado["MontoTransferencia"] = "";
    $resultado["IdTipoOperacionCaja"] = ID_TIPO_OPERACION_CAJA_TRANSFERENCIA_CAJA;

    $resultado["IdCajaOrigen"] = "";
    $resultado["IdTurnoOrigen"] = "";
    $resultado["IdUsuarioOrigen"] = "";//$this->sesionusuario->obtener_sesion_id_usuario();
    $resultado["IdCajaDestino"] = "";
    $resultado["IdTurnoDestino"] = "";
    $resultado["IdUsuarioDestino"] = "";
    $resultado["AliasUsuarioVenta"] = "";

    $resultado["NuevaTransferencia"] = $resultado;
    
    return $resultado;
  }

  function ValidarTransferenciaOrigen($data)
  {
    $resultado = $this->BuscarSaldoCajaTurnoOrigen($data);
    if(is_array($resultado))
    {
      return "";
    }
    else
    {
      return $resultado;
    }
  }

  function ValidarTransferenciaDestino($data)
  {
    $resultado = $this->BuscarSaldoCajaTurnoDestino($data);
    if(is_array($resultado))
    {
      return "";
    }
    else
    {
      return $resultado;
    }
  }

  function ValidarTransferenciaMonto($data)
  {
    $saldoActual = $data["SaldoActual"];
    $montoTransferencia = $data["MontoTransferencia"];

    $diferenciaSaldo = $saldoActual - $montoTransferencia;
    if($montoTransferencia > $saldoActual)
    {
      return "El Monto de Transferencia para la Caja Destino no puede ser mayor al Saldo Actual de la Caja Origen.";
    }
    else if($saldoActual <= 0)
    {
      return "El Saldo Actual de la Caja Origen restante es igual o menor a cero.";
    }
    else if($diferenciaSaldo < 0)
    {
      return "La diferencia entre el Saldo Actual de la Caja Origen y El Monto de Transferencia para la Caja Destino es menor a cero.";
    }
    else
    {
      return "";
    }
  }

  function ValidarTransferenciaCaja($data)
  {
    $resultado1 = $this->ValidarTransferenciaOrigen($data);
    $resultado2 = $this->ValidarTransferenciaDestino($data);
    $resultado3 = $this->ValidarTransferenciaMonto($data);
    if($resultado1 != "")
    {
      return $resultado1;
    }
    else if($resultado2 != "")
    {
      return $resultado2;
    }
    else if($resultado3 != "")
    {
      return $resultado3;
    }
    else
    {
      return "";
    }
  }

  // function ValidarCierreParaAperturarCajaParaInsertar($data)
  // {
  //   $resultado = $this->sSaldoCajaTurno->ValidarExistenciaDeCierreSaldoCajaTurnoParaInsertar($data);
  //   // $resultado = $this->sSaldoCajaTurno->ObtenerCajasAperturadasParaInsertar($data);
  //   if (count($resultado)>0)
  //   {
  //     return "La caja aun no fue cerrada. Para aperturar una caja debe cerrar previamente la anterior.";
  //   }
  //   else
  //   {
  //     return "";
  //   }
  // }

  // function ValidarFechaTransferenciaCajaParaInsertar($data)
  // {
  //   $hoy = $this->Base->ObtenerFechaServidor("Y-m-d");
  //   $fechaComprobante = $data["FechaComprobante"];
  //   if ($hoy < $fechaComprobante)
  //   {
  //     return "La fecha de apertura no puede ser mayor a la actual.";
  //   }
  //   else
  //   {
  //     return "";
  //   }
  // }

  // function ValidarTransferenciaCajaParaInsertar($data)
  // {
  //   $validacion = "";//$this->ValidarDuplicadoTransferenciaCajaParaInsertar($data);
  //   $validacion2 = $this->ValidarCierreParaAperturarCajaParaInsertar($data);
  //   $validacion3 = $this->ValidarFechaTransferenciaCajaParaInsertar($data);
  //   if($validacion != "")
  //   {
  //     return $validacion;
  //   }
  //   else if($validacion2 != "")
  //   {
  //     return $validacion2;
  //   }
  //   else if($validacion3 != "")
  //   {
  //     return $validacion3;
  //   }
  //   else
  //   {
  //     return "";
  //   }
  // }

  /**FUNCIONES BASE DE INSERTADO Y ACTUALIZACION */
  function InsertarTransferenciaCaja($data)
  {
    try {
      $resultadoValidacion = "";
      if($resultadoValidacion == "")
      {
        $resultado= parent::InsertarComprobanteCaja($data);
        if(is_array($resultado))
        {
          return $resultado;
        }
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
  /****FIN DE FUNCIONES BASE */
  
  function AgregarTransferenciaCaja($data)
  {
    // $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
    // $data["CodigoSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["CodigoSede"] : '';
    // $data["NombreSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["NombreSede"] : '';
    $data["MontoTransferencia"] = (is_string($data["MontoTransferencia"])) ? str_replace(',',"",$data["MontoTransferencia"]) : $data["MontoTransferencia"];
    $data["SaldoActual"] = (is_string($data["SaldoActual"])) ? str_replace(',',"",$data["SaldoActual"]) : $data["SaldoActual"];
    $data["MontoComprobante"] = $data["MontoTransferencia"];
    // $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
    // $data["CodigoTipoDocumento"] = (count($tipodocumento)>0) ? $tipodocumento[0]["CodigoTipoDocumento"] : '';
    $data["FechaComprobante"] = convertToDate($data["FechaComprobante"]);
    $data["FechaTurno"] = convertToDate($data["FechaTurno"]);
    // print_r($data);exit;
    $data["IdCaja"] = $data["IdCajaOrigen"];
    $data["IdTurno"] = $data["IdTurnoOrigen"];
    $data["IdUsuario"] = $data["IdUsuarioOrigen"];

    //VALIDACION DE TURNO PARA EL USUARIO
    // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    // $data["FechaCaja"] = $data["FechaComprobante"];
    $turno = $this->sSaldoCajaTurno->ValidarTurno($data);
    if(!is_array($turno))
    {
      return $turno;
    }

    $validacion = $this->ValidarTransferenciaCaja($data);
    if($validacion != "")
    {
      return $validacion;
    }

    $resultado = $this->InsertarTransferenciaCaja($data);
    // print_r("TTTT");print_r($resultado);exit;
    if(is_array($resultado)) {
      $data["IdComprobanteCaja"] = $resultado["IdComprobanteCaja"];
      $response = $this->InsertarMovimientosTransferencia($data);
      // print_r("TTTT");print_r($response);exit;
      return $resultado;
    }
    else {
      return "";
    }
  }

  public function InsertarMovimientosTransferencia($data)
  {
    //DATA PARA TODOS
    //NONE

    $dataSalida = $data;
    $dataSalida["MontoComprobante"] = $data["MontoTransferencia"];
    $dataSalida["IdCaja"] = $data["IdCajaOrigen"];
    $dataSalida["IdTurno"] = $data["IdTurnoOrigen"];
    $dataSalida["IdUsuario"] = $data["IdUsuarioOrigen"];

    //CAMPOS NO USADOS
    $dataSalida["IdMovimientoCajaReferencia"] = "";
    $dataSalida["Observador"] = "";
    //HACEMOS UN MOVIMIENTO DE SALIDA DE CAJA AL CAJERO DE SALIDA
    $responseSalida = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoEgreso($dataSalida);
    // print_r("TTTT");print_r($responseSalida);exit;
    $dataEntrada = $data;
    $dataEntrada["MontoComprobante"] = $data["MontoTransferencia"];
    $dataEntrada["IdCaja"] = $data["IdCajaDestino"];
    $dataEntrada["IdTurno"] = $data["IdTurnoDestino"];
    $dataEntrada["IdUsuario"] = $data["IdUsuarioDestino"];
    
    //CAMPOS NO USADOS
    $dataEntrada["IdMovimientoCajaReferencia"] = "";
    $dataEntrada["Observador"] = "";
    //HACEMOS UN MOVIMIENTO DE ENRADA DE CAJA AL CAJERO RECIBIDO
    $responseEntrada = $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoIngreso($dataEntrada);
    // print_r("TTTT");print_r($responseEntrada);exit;
    return $data;
  }
  
  function ActualizarMovimientosTransferencia($data)
  {
    $response = $this->sMovimientoCaja->BorrarMovimientosCajaTransferenciaCaja($data);
    $resultado = $this->InsertarMovimientosTransferencia($data);
    return $resultado;
  }

  // function BorrarTransferenciaCajaDesdeTransferencia($data)
  // {
  //   $resultado = $this->mComprobanteCaja->ObtenerDocumentosPorIdComprobanteCompra($data);
  //   if(count($resultado) > 0)
  //   {
  //     foreach ($resultado as $key => $value) {
  //       $this->BorrarDocumentosIngreso($value);
  //       //borramos movimiento de almacen de esas notas entrada
  //       $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoIngreso($value);
  //     }
  //   }
  //   return $resultado;
  // }
  function BuscarSaldoCajaTurnoOrigen($data)
  {
    $dataBusqueda["IdCaja"] = $data["IdCajaOrigen"];
    $dataBusqueda["IdUsuario"] = $data["IdUsuarioOrigen"];

    $dataBusqueda["FechaCaja"] = convertToDate($data["FechaComprobante"]);

    $resultado = $this->BuscarSaldoCajaTurno($dataBusqueda);
    return $resultado;
  }

  function BuscarSaldoCajaTurnoDestino($data)
  {
    $dataBusqueda["IdCaja"] = $data["IdCajaDestino"];
    $dataBusqueda["IdUsuario"] = $data["IdUsuarioDestino"];

    $dataBusqueda["FechaCaja"] = convertToDate($data["FechaComprobante"]);

    $resultado = $this->BuscarSaldoCajaTurno($dataBusqueda);
    return $resultado;
  }


  function BuscarSaldoCajaTurno($data)
  {
    $turno = $this->sUsuario->ValidarTurnoUsuario($data);

    if(is_array($turno))
    {
      $data["IdTurno"] = $turno["IdTurno"];
      $data["IdHorario"] = $turno["IdHorario"];
    }
    else
    {
      return $turno;
    }
    
    $resultado = $this->sSaldoCajaTurno->ObtenerSaldoCajaTurnoPorUsuario($data);
    // print_r($data);print_r("aaaaaaaaa");print_r($resultado);exit;
    if(count($resultado) > 0)
    {
      return $resultado;
    }
    else{
      return "La caja no esta aperturada.";
    }
  }
}

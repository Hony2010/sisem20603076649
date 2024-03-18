<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sSaldoCajaTurno extends MY_Service {

  public $SaldoCajaTurno = array();

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
    $this->load->model('Caja/mSaldoCajaTurno');
    $this->load->service('Seguridad/sUsuario');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->service('Configuracion/General/sMoneda');

    $this->SaldoCajaTurno = $this->mSaldoCajaTurno->SaldoCajaTurno;
  }

  //#INICIO DE FUNCIONES DE VALIDACION
  function ValidarDuplicadoDeAperturaSaldoCajaTurnoParaInsertar($data)
  {
    $response = $this->mSaldoCajaTurno->ValidarDuplicadoDeAperturaSaldoCajaTurnoParaInsertar($data);
    return $response;
  }

  function ValidarAperturaSaldoCajaTurnoPorTurnoYCaja($data)
  {
    $response = $this->mSaldoCajaTurno->ValidarAperturaSaldoCajaTurnoPorTurnoYCaja($data);
    return $response;
  }

  function ValidarExistenciaDeCierreSaldoCajaTurnoParaInsertar($data)
  {
    $response = $this->mSaldoCajaTurno->ValidarExistenciaDeCierreSaldoCajaTurnoParaInsertar($data);
    return $response;
  }

  function ValidarDuplicadoDeCierreSaldoCajaTurnoParaInsertar($data)
  {
    $response = $this->mSaldoCajaTurno->ValidarDuplicadoDeCierreSaldoCajaTurnoParaInsertar($data);
    return $response;
  }

  function ValidarExistenciaDeAperturaSaldoCajaTurnoParaInsertar($data)
  {
    $response = $this->mSaldoCajaTurno->ValidarExistenciaDeAperturaSaldoCajaTurnoParaInsertar($data);
    return $response;
  }

  //#FIN DE LAS FUNCIONES DE VALIDACION
  function ObtenerSaldoCajaTurnoParaInsertarOActualizar($data)
  {
    $response = $this->mSaldoCajaTurno->ObtenerSaldoCajaTurnoParaInsertarOActualizar($data);
    return $response;
  }

  function ObtenerUltimaAperturaPorUsuarioYCaja($data)
  {
    $response = $this->mSaldoCajaTurno->ObtenerUltimaAperturaPorUsuarioYCaja($data);
    return $response;
  }
    
  function AgregarSaldoCajaTurnoDocumentoEgreso($data)
  {
    $response = $this->ObtenerSaldoCajaTurnoParaInsertarOActualizar($data);

    if(count($response)>0)
    {
      $nuevacantidad = $response[0]["SaldoActual"] - $data["MontoEgresoEfectivo"];
      $nueva_data["IdSaldoCajaTurno"] = $response[0]["IdSaldoCajaTurno"];
      $nueva_data["SaldoActual"] = $nuevacantidad;
      $resultado = $this->ActualizarSaldoCajaTurno($nueva_data);
      return $resultado;
    }
    else {
      $nuevacantidad = 0 - $data["MontoEgresoEfectivo"];
      $data["SaldoActual"] = $nuevacantidad;

      //UN ESTADO CERRADO AL INICIAR
      $data["EstadoCaja"] = INDICADOR_ESTADO_CAJA_PENDIENTE;
      $data["FechaCaja"] = convertToDate($data["FechaTurno"]);
      
      $resultado = $this->InsertarSaldoCajaTurno($data);
      return $resultado;
    }
  }

  function AgregarSaldoCajaTurnoDocumentoIngreso($data)
  {
    $response = $this->ObtenerSaldoCajaTurnoParaInsertarOActualizar($data);

    if(count($response)>0)
    {
      $nuevacantidad = $response[0]["SaldoActual"] + $data["MontoIngresoEfectivo"];
      $nueva_data["IdSaldoCajaTurno"] = $response[0]["IdSaldoCajaTurno"];
      $nueva_data["SaldoActual"] = $nuevacantidad;
      $resultado = $this->ActualizarSaldoCajaTurno($nueva_data);
      return $resultado;
    }
    else {
      $nuevacantidad = 0 + $data["MontoIngresoEfectivo"];
      $data["SaldoActual"] = $nuevacantidad;
      
      //UN ESTADO CERRADO AL INICIAR
      $data["EstadoCaja"] = INDICADOR_ESTADO_CAJA_PENDIENTE;
      $data["FechaCaja"] = convertToDate($data["FechaTurno"]);

      $resultado = $this->InsertarSaldoCajaTurno($data);
      return $resultado;
    }
  }

  function ActualizarSaldoCajaTurnoDocumentoIngreso($data)  {
    $response = $this->ObtenerSaldoCajaTurnoParaInsertarOActualizar($data);
    if(count($response)>0)
    {
      $nuevacantidad = $response[0]["SaldoActual"] - $data["MontoIngresoEfectivo"];
      $nueva_data["IdSaldoCajaTurno"] = $response[0]["IdSaldoCajaTurno"];
      $nueva_data["SaldoActual"] = $nuevacantidad;
      $resultado = $this->ActualizarSaldoCajaTurno($nueva_data);
    }
  }

  function ActualizarSaldoCajaTurnoDocumentoEgreso($data)
  {
    $response = $this->ObtenerSaldoCajaTurnoParaInsertarOActualizar($data);
    if(count($response)>0)
    {
      $nuevacantidad = $response[0]["SaldoActual"] + $data["MontoEgresoEfectivo"];
      $nueva_data["IdSaldoCajaTurno"] = $response[0]["IdSaldoCajaTurno"];
      $nueva_data["SaldoActual"] = $nuevacantidad;
      $resultado = $this->ActualizarSaldoCajaTurno($nueva_data);
    }
  }

  function InsertarSaldoCajaTurno($data)
  {
    try {
      $resultadoValidacion = "";
      if($resultadoValidacion == "")
      {
        $resultado= $this->mSaldoCajaTurno->InsertarSaldoCajaTurno($data);
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

  function ActualizarSaldoCajaTurno($data)
  {
    try {
      $resultadoValidacion = "";
      if($resultadoValidacion == "")
      {
        $resultado=$this->mSaldoCajaTurno->ActualizarSaldoCajaTurno($data);
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

  function BorrarSaldoCajaTurno($data)
  {
    $this->mSaldoCajaTurno->BorrarSaldoCajaTurno($data);
    return "";
  }

  function ObtenerCajasAperturadasParaInsertar($data)
  {
    $resultado = $this->mSaldoCajaTurno->ObtenerCajasAperturadasParaInsertar($data);
    return $resultado;
  }

  //FUNCIONES PARA APERTURA Y CIERRE CAJA
  function InsertarCierreCajaEnSaldoCajaTurno($data)
  {
    $dataCierre["IdComprobanteCaja"] = $data["IdAperturaCaja"];
    $dataCierre["EstadoCaja"] = INDICADOR_ESTADO_CAJA_CERRADO;
    $this->ActualizarSaldoCajaTurno($dataCierre);
  }

  function ObtenerCajasAperturadasParaTransferencias($data)
  {
    $resultado = $this->mSaldoCajaTurno->ObtenerCajasAperturadasParaTransferencias($data);
    return $resultado;
  }

  function ObtenerSaldoCajaTurnoPorUsuario($data)
  {
    $resultado = $this->mSaldoCajaTurno->ObtenerSaldoCajaTurnoPorUsuario($data);
    return $resultado;
  }

  //AQUI HACEMOS LAS VALIDACIONES DE TURNO Y APERTURADO
  function ValidarTurno($data)
  {
    //AQUI VALIDAMOS SI EL TURNO DE USUARIO ES EL CORRECTO
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $turno = $this->sUsuario->ValidarTurnoUsuario($data);
    if(is_array($turno))
    {
      $response["IdTurno"] = $turno["IdTurno"];
      $response["IdHorario"] = $turno["IdHorario"];
      $data = array_merge($data, $response);
      return $data;
    }
    else
    {
      return $turno;
    }
  }

  function ValidarCajaAperturadaParaVentaYCompraInsercion($data)
  {
    $resultado = $this->mSaldoCajaTurno->ObtenerUltimaCajaAperturadaPorTurnoYCaja($data);
    if(count($resultado) > 0)
    {
      // $response["FechaCaja"] = $resultado[0]["FechaCaja"];
      $response["FechaTurno"] = $resultado[0]["FechaCaja"];
      $data = array_merge($data, $response);
      return $data;
    }
    else
    {
      $texto = "";
      if(array_key_exists("IdMoneda", $data))
      {
        if(is_numeric($data["IdMoneda"]))
        {
          $moneda = $this->sMoneda->ObtenerMonedaPorId($data);
          $texto = " de ".$moneda[0]["NombreMoneda"];
        }
      }
      return "No existe caja aperturada".$texto.".";
    }
  }

  function ValidarCajaAperturadaParaVentaYCompraActualizacion($data) {
    $resultado = $this->ObtenerSaldoCajaTurnoPorUsuario($data);
    
    if(count($resultado) > 0) {
      if($resultado[0]["EstadoCaja"] == INDICADOR_ESTADO_CAJA_CERRADO) {
        return "La caja ya esta cerrada.";
      }
      else {
        $response["FechaTurno"] = $resultado[0]["FechaCaja"];
        $data = array_merge($data, $response);
        return $data;
      }
    }
    else {
      return "La caja del movimiento ya esta cerrada.";
    }
  }

  //SE VALIDA LA APERTURA DE CAJA PARA TURNO
  function ValidarAperturaCajaParaVentaYCompraApertura($data)
  {
    $resultado = $this->ValidarDuplicadoDeAperturaSaldoCajaTurnoParaInsertar($data);
    if(count($resultado) > 0)
    {
      if($resultado[0]["EstadoCaja"] == INDICADOR_ESTADO_CAJA_CERRADO)
      {
        return "La caja de esta fecha ya esta cerrada.";
      }
      else{
        return $resultado;
      }
    }
    else
    {
      $texto = " ";
      if(array_key_exists("IdMoneda", $data))
      {
        if(is_numeric($data["IdMoneda"]))
        {
          $moneda = $this->sMoneda->ObtenerMonedaPorId($data);
          $texto = " de ".$moneda[0]["NombreMoneda"]." ";
        }
      }
      return "No existe caja aperturada".$texto."para la fecha.";
    }
  }

  function ValidarTurnoYAperturaCajaApertura($data)
  {
    //AQUI VALIDAMOS SI EL TURNO DE USUARIO ES EL CORRECTO
    $response = $this->ValidarTurno($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else
    {
      return $response;
    }

    //AQUI VALIDAMOS SI SE HA APERTURADO UNA CAJA O NO
    $respuesta = $this->ValidarAperturaCajaParaVentaYCompraApertura($data);
    // print_r($respuesta);exit;    
    if(is_array($respuesta))
    {
      // print_r($respuesta);exit;
      // $data = $respuesta;
    }
    else
    {
      return $respuesta;
    }

    return $data;
  }

  function ValidarTurnoYAperturaCajaInsercion($data)
  {
    //AQUI VALIDAMOS SI EL TURNO DE USUARIO ES EL CORRECTO
    $response = $this->ValidarTurno($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else
    {
      return $response;
    }

    //AQUI VALIDAMOS SI SE HA APERTURADO UNA CAJA O NO
    $respuesta = $this->ValidarCajaAperturadaParaVentaYCompraInsercion($data);
    // print_r($respuesta);exit;    
    if(is_array($respuesta))
    {
      // print_r($respuesta);exit;
      $data = $respuesta;
    }
    else
    {
      return $respuesta;
    }

    return $data;
  }

  function ValidarTurnoYAperturaCajaActualizacion($data)
  {
    //AQUI VALIDAMOS SI EL TURNO DE USUARIO ES EL CORRECTO
    $response = $this->ValidarTurno($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else
    {
      return $response;
    }

    //AQUI VALIDAMOS SI SE HA APERTURADO UNA CAJA O NO
    $respuesta = $this->ValidarCajaAperturadaParaVentaYCompraActualizacion($data);
    // print_r($respuesta);exit;    
    if(is_array($respuesta))
    {
      // print_r($respuesta);exit;
      $data = $respuesta;
    }
    else
    {
      return $respuesta;
    }

    return $data;
  }

  //SALDO CAJA TURNO NEGATIVO
  function ValidarSaldoCajaTurnoNegativo($data)
  {
    $parametroCajaNegativo = $this->sConstanteSistema->ObtenerParametroCajaSaldoNegativo();
    $saldoCajaNegativo = $this->ObtenerSaldoCajaTurnoPorUsuario($data);
    if ($parametroCajaNegativo == 0)
    {
      if((count($saldoCajaNegativo) > 0) && ($saldoCajaNegativo[0]["SaldoActual"] < 0))
      {
        return "Los saldos no pueden ser negativos.";
      }
    }
    return array();
  }

}

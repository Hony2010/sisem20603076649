<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Caja\sComprobanteCaja.php');

class sAperturaCaja extends sComprobanteCaja {

  public function __construct()
  {
    parent::__construct();
    $this->load->library('sesionusuario');
    $this->load->service('Caja/sTipoOperacionCaja');
    $this->load->service('Caja/sMovimientoCaja');
    $this->load->service('Caja/sSaldoCajaTurno');
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service('Configuracion/General/sMoneda');
    $this->load->service('Seguridad/sAccesoCajaUsuario');
    $this->load->service('Seguridad/sUsuario');
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_VOUCHER_INGRESO;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $resultado = parent::CargarComprobanteCaja($parametro);

    // $resultado['IdSede'] = $this->sesionusuario->obtener_sesion_id_sede();
    $resultado["IdTipoOperacionCaja"] = ID_TIPO_OPERACION_CAJA_SALDO_INICIAL;
    $resultado["EstadoCaja"] = INDICADOR_ESTADO_CAJA_PENDIENTE;
    $Monedas = $this->sMoneda->ListarMonedas(); //Se listan las monedas
    $resultado["Monedas"] = json_decode(json_encode($Monedas), true);//$Monedas;
    $resultado["IdMoneda"] = (count($Monedas) > 0) ? $Monedas[0]->IdMoneda : "";
    $Cajas = $this->sAccesoCajaUsuario->ListarAccesosCajaUsuarioPorIdUsuario(); //Se listaran las cajas por usuario
    $resultado["Cajas"] = $Cajas;
    $resultado["IdCaja"] = (count($Cajas) > 0) ? $Cajas[0]["IdCaja"] : "";
    $resultado["IdMedioPago"] = ID_MEDIO_PAGO_EFECTIVO;
    $resultado["FechaTurno"] = $resultado["FechaComprobante"];

    $resultado["NuevoComprobanteCaja"] = $resultado;

    //OBTENER MONTO SI LA CAJA FUE APERTURADA
    $resultadoApertura = $this->ConsultarAperturaCaja($resultado);
    if(is_array($resultadoApertura))
    {
      $resultado["MontoComprobante"] = (count($resultadoApertura) > 0) ? $resultadoApertura["MontoComprobante"] : "";
    }
    
    return $resultado;
  }

  function ConsultarAperturaCaja($data)
  {
    $data["FechaComprobante"] = convertToDate($data["FechaComprobante"]);
    $data["FechaTurno"] = convertToDate($data["FechaTurno"]);
    $data["FechaCaja"] = $data["FechaTurno"];
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();

    //AQUI HACEMOS LAS VALIDACIONES DE TURNO Y APERTURADO
    $response = $this->sSaldoCajaTurno->ValidarTurnoYAperturaCajaApertura($data);
    if(is_array($response))
    {
      $data = $response;
      $resultado = parent::ObtenerComprobanteCajaApertura($data);
      if(count($resultado) > 0)
      {
        $resultado[0]["FechaTurno"] = convertirFechaES($resultado[0]["FechaTurno"]);
        $resultado[0]["FechaComprobante"] = convertirFechaES($resultado[0]["FechaComprobante"]);
        return $resultado[0];
      }
      else
      {
        return "No se encontro Apertura para la Caja y Fecha";
      }
    }
    else {
      return $response;
    }
  }

  function ValidarFechaAperturaCajaParaInsertar($data)
  {
    $hoy = $this->Base->ObtenerFechaServidor("Y-m-d");
    $fechaComprobante = $data["FechaComprobante"];
    if ($hoy < $fechaComprobante)
    {
      return "La fecha de apertura no puede ser mayor a la actual.";
    }
    else
    {
      return "";
    }
  }

  function ValidarAperturaCajaParaInsertar($data)
  {
    $validacion = $this->ValidarFechaAperturaCajaParaInsertar($data);
    if($validacion != "")
    {
      return $validacion;
    }
    else
    {
      return "";
    }
  }

  //SE HACE EL AÑADIDO DEL REGISTRO PARA LA APERTURA
  function AgregarAperturaCaja($data)
  {
    $data["FechaComprobante"] = convertToDate($data["FechaComprobante"]);
    $data["FechaTurno"] = convertToDate($data["FechaTurno"]);
    // $data["FechaCaja"] = $data["FechaComprobante"];

    $response = $this->sSaldoCajaTurno->ValidarTurno($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else {
      return $response;
    }

    //VALIDANDO SI EXISTE CAJA APERTURADA PREVIAMENTE
    $aperturaPorTurno = $this->sSaldoCajaTurno->ValidarAperturaSaldoCajaTurnoPorTurnoYCaja($data);
    if(count($aperturaPorTurno) > 0)
    {
      return "Hay una caja aperturada previamente para este turno, cierre la caja previa.";
    }

    $resultado = $this->sSaldoCajaTurno->ValidarDuplicadoDeAperturaSaldoCajaTurnoParaInsertar($data);
    if(count($resultado) > 0)
    {
      if($resultado[0]["EstadoCaja"] == INDICADOR_ESTADO_CAJA_CERRADO)
      {
        return "La apertura de caja para esta fecha ya fue registrada y esta cerrada.";
      }
      else{        
        $resultado2 = $this->sSaldoCajaTurno->BorrarSaldoCajaTurno($resultado[0]);
        $resultado = parent::ObtenerComprobanteCajaApertura($data);        
        $borrado = $this->BorrarAperturaCaja($resultado[0]);

        $data["IdComprobanteCaja"] = "";
        // $data["NumeroDocumento"] = "";
        $response = $this->InsertarAperturaCaja($data);
        return $response;
      }
    }
    else{
      $data["IdComprobanteCaja"] = "";
      $data["NumeroDocumento"] = "";
      $response = $this->InsertarAperturaCaja($data);
      return $response;
    }
  }

  /**FUNCIONES BASE DE INSERTADO Y ACTUALIZACION */
  function InsertarAperturaCaja($data)
  {
    try {
      $resultadoValidacion = $this->ValidarAperturaCajaParaInsertar($data);

      if($resultadoValidacion == "")
      {
        $resultado= parent::InsertarComprobanteCaja($data);
        if(is_array($resultado))
        {
          //AQUI DEBEN INSERTARSE LOS MOVIMIENTO
          $this->sMovimientoCaja->InsertarMovimientoCajaDocumentoIngreso($resultado);
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

  function BorrarAperturaCaja($data)
  {
    $resultado = $this->sMovimientoCaja->BorrarMovimientosCajaDocumentoIngreso($data);
    $response = parent::BorrarComprobanteCaja($data);
    return $data;
  }

}

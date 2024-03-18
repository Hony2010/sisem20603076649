<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Caja\sComprobanteCaja.php');

class sCierreCaja extends sComprobanteCaja {

  public function __construct()
  {
    parent::__construct();
    $this->load->service('Caja/sTipoOperacionCaja');
    $this->load->service('Caja/sMovimientoCaja');
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service('Configuracion/General/sMoneda');
    $this->load->service('Seguridad/sAccesoCajaUsuario');
    $this->load->service('Seguridad/sAccesoTurnoUsuario');
    $this->load->service('Caja/sSaldoCajaTurno');
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $Monedas = $this->sMoneda->ListarMonedas(); //Se listan las monedas
    $resultado["Monedas"] = json_decode(json_encode($Monedas), true);//$Monedas;
    $resultado["IdMoneda"] = (count($Monedas) > 0) ? $Monedas[0]->IdMoneda : "";
    $Cajas = $this->sAccesoCajaUsuario->ListarAccesosCajaUsuarioPorIdUsuario(); //Se listaran las cajas por usuario
    $resultado["Cajas"] = $Cajas;
    $resultado["IdCaja"] = (count($Cajas) > 0) ? $Cajas[0]["IdCaja"] : "";
    $resultado["IdTurno"] = $this->sesionusuario->obtener_sesion_turno_usuario()["IdTurno"];
    $resultado["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $resultado["AliasUsuarioVenta"] = $this->sesionusuario->obtener_alias_usuario();
    //PARA ACTUALIZAR EL COMPROBANTE DE APERTURA
    $resultado["IdAperturaCaja"] = "";
    $resultado["FechaAperturaCaja"] = "";
    $resultado["FechaCierreCaja"] = $hoy;
    $resultado["MontoAperturaCaja"] = "";
    $resultado["MontoCierreCaja"] = "";

    $resultado["NuevoCierreCaja"] = $resultado;
    return $resultado;
  }

  function ObtenerUltimaAperturaPorUsuarioYCaja($data)
  {
    $resultado = $this->sSaldoCajaTurno->ObtenerUltimaAperturaPorUsuarioYCaja($data);//1

    $response["MontoCierreCaja"] = 0;
    
    if(count($resultado) > 0) {
      //$response["IdSaldoCajaTurno"] = $resultado[0]["IdSaldoCajaTurno"];
      
      $resultado[0]["FechaComprobante"] = $resultado[0]["FechaCaja"];
      $resultado = parent::ObtenerComprobanteCajaApertura($resultado[0]);//?
      // $response = $resultado[0];
      // $response["IdAperturaCaja"] = $resultado[0]["IdComprobanteCaja"];      
      $response["FechaAperturaCaja"] = convertirFechaES($resultado[0]["FechaComprobante"]);
      $response["FechaTurno"] = convertirFechaES($resultado[0]["FechaTurno"]);
      $response["MontoAperturaCaja"] = $resultado[0]["MontoComprobante"];
      $response["MontoCierreCaja"] = 0;
      
      $cajaturno = $this->sSaldoCajaTurno->ObtenerSaldoCajaTurnoParaInsertarOActualizar($resultado[0]);
      
      if(count($cajaturno) > 0) {
        $response["MontoCierreCaja"] = $cajaturno[0]["SaldoActual"];        
        $response["IdSaldoCajaTurno"] = $cajaturno[0]["IdSaldoCajaTurno"];
      }
      return $response;
    }
    else {
      return array();
    }
  }

  //FUNCION DE CIERRE DE CAJA
  function AgregarCierreCaja($data)
  {
    $response = $this->sSaldoCajaTurno->ValidarTurno($data);
    if(is_array($response))
    {
      $data = $response;
    }
    else {
      return $response;
    }

    $data["FechaAperturaCaja"] = convertToDate($data["FechaAperturaCaja"]);
    $data["FechaCierreCaja"] = convertToDate($data["FechaCierreCaja"]);

    $data["FechaComprobante"] = $data["FechaCierreCaja"];
    $data["FechaTurno"] = convertToDate($data["FechaTurno"]);
    
    $resultado = $this->sSaldoCajaTurno->ValidarDuplicadoDeAperturaSaldoCajaTurnoParaInsertar($data);
      
    if(count($resultado) > 0) {

      foreach($resultado as $key => $value) {
        if ($value["IdSaldoCajaTurno"] != $data["IdSaldoCajaTurno"]) {
          $resultado2 = $this->sSaldoCajaTurno->BorrarSaldoCajaTurno($value);
        }
      }
      
      $comprobante["IdSaldoCajaTurno"] = $data["IdSaldoCajaTurno"];//$resultado[0]["IdSaldoCajaTurno"];
      $comprobante["FechaCierreCaja"] = $data["FechaCierreCaja"];
      $comprobante["EstadoCaja"] = INDICADOR_ESTADO_CAJA_CERRADO;
      $response = $this->sSaldoCajaTurno->ActualizarSaldoCajaTurno($comprobante);
      return $response;
    }
    else{
      return "";
    }
  }
}

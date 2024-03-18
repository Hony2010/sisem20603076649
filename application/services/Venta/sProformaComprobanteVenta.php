<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'services\Venta\sComprobanteVenta.php');

class sProformaComprobanteVenta extends MY_Service {

  public function __construct() {
    parent::__construct();    
    $this->load->database();
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->library('mapper');
    $this->load->library('herencia');    
    $this->load->library('json');
    $this->load->helper("date");
    $this->load->model('Venta/mProformaComprobanteVenta');
    $this->load->service('Venta/sGuiaRemisionRemitente');    
  }

  function Nuevo() {
        
  }

  
  function InsertarProformaComprobanteVenta($data) {
    $data["IndicadorEstado"]=ESTADO_ACTIVO;    
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    
    $resultado = $this->mProformaComprobanteVenta->InsertarProformaComprobanteVenta($data);
    return $resultado;
  }

  function ModificarProformaComprobanteVenta($data) {
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $resultado = $this->ActualizarProformaComprobanteVenta($data);
    return $resultado;
  }

  function ActualizarProformaComprobanteVenta($data) {    
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mProformaComprobanteVenta->ActualizarProformaComprobanteVenta($data);
    return $resultado;
  }

  function BorrarProformaComprobanteVenta($data) {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->ActualizarProformaComprobanteVenta($data);
    return $resultado;
  }

  function ListarProformasComprobanteVenta($data) {    
    $resultado = $this->mProformaComprobanteVenta->ListarProformasComprobanteVenta($data);
    return $resultado;
  }
  function ObtenerProformaComprobanteVenta($data) {
    
    $resultado = $this->mProformaComprobanteVenta->ObtenerProformaComprobanteVenta($data);
    return $resultado;
  }

  
  function GuardarProformasComprobanteVenta($data) {
    if (count($data) > 0 ) {
      $dataProformasComprobanteVenta=$this->ListarProformasComprobanteVenta($data[0]);
      //borrar todos
      foreach($dataProformasComprobanteVenta as $key => $value) {
        $this->BorrarProformaComprobanteVenta($value);
      }
      
      //actualizar o insertar
      foreach($data as $key => $value) {
        $encontrado = false;
        foreach($dataProformasComprobanteVenta as $key2 => $value2) {
          if ($value["IdProforma"] ==$value2["IdProforma"] ) {
            $this->ModificarProformaComprobanteVenta($value);            
            $encontrado = true;
            break;
          }
        }

        if (!$encontrado) {
          $this->InsertarProformaComprobanteVenta($value);
        }

      }

    }
    
  }
  

}

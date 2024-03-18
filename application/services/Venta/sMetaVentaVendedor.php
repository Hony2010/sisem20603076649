<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMetaVentaVendedor extends MY_Service {

  public $MetaVentaVendedor = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->helper("date");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->model("Base");
    $this->load->model('Venta/mMetaVentaVendedor');
    $this->MetaVentaVendedor = $this->mMetaVentaVendedor->MetaVentaVendedor;
  }

  function Cargar()
  {
    $resultado = $this->MetaVentaVendedor;
    return $resultado;
  }

  /**INICIO B. FUNC. */
  function InsertarMetaVentaVendedor($data)
  {
    $resultado = $this->mMetaVentaVendedor->InsertarMetaVentaVendedor($data);
    return $resultado;
  }

  function ActualizarMetaVentaVendedor($data)
  {
    $resultado = $this->mMetaVentaVendedor->ActualizarMetaVentaVendedor($data);
    return $resultado;
  }

  function BorrarMetaVentaVendedor($data)
  {
    $resultado = $this->mMetaVentaVendedor->BorrarMetaVentaVendedor($data);
    return $resultado;
  }
  /**FIN B. FUNC. */

  function AgregarMetaVentaVendedor($data)
  {
    $data["Sueldo"] = (is_string($data["Sueldo"])) ? str_replace(',',"",$data["Sueldo"]) : $data["Sueldo"];
    $data["MetaVentaMensual"] = (is_string($data["MetaVentaMensual"])) ? str_replace(',',"",$data["MetaVentaMensual"]) : $data["MetaVentaMensual"];
    $data["BonificacionMetaCincuenta"] = (is_string($data["BonificacionMetaCincuenta"])) ? str_replace(',',"",$data["BonificacionMetaCincuenta"]) : $data["BonificacionMetaCincuenta"];
    $data["BonificacionMetaCien"] = (is_string($data["BonificacionMetaCien"])) ? str_replace(',',"",$data["BonificacionMetaCien"]) : $data["BonificacionMetaCien"];
    
    $resultado = $this->mMetaVentaVendedor->ObtenerMetaVentaVendedorPorIdPersona($data);
    if(count($resultado) > 0)
    {
      $data["IdMetaVentaVendedor"] = $resultado[0]["IdMetaVentaVendedor"];
      $response = $this->ActualizarMetaVentaVendedor($data);
      return $response;
    }
    else
    {
      $data["IdMetaVentaVendedor"] = "";
      $response = $this->InsertarMetaVentaVendedor($data);
      return $response;
    }
  }

  //INSERTAR CUOTA MENSUAL POR USUARIO
  function AgregarMetasVentaVendedor($data)
  {
    foreach ($data as $key => $value) {
      $data[$key] = $this->AgregarMetaVentaVendedor($value);
    }
    return $data;
  }

  //CONSULTAR CUOTAS MENSUALES POR USUARIO
  function ConsultarMetasVentaVendedor($data)
  {
    $resultado = $this->mMetaVentaVendedor->ConsultarMetasVentaVendedor($data);
    return $resultado;
  }
}

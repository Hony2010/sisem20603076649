<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sLineaProducto extends MY_Service {

  public $LineaProducto = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->model('Configuracion/Catalogo/mLineaProducto');
    $this->LineaProducto = $this->mLineaProducto->LineaProducto;
  }

  function ListarLineasProducto()
  {
    $resultado = $this->mLineaProducto->ListarLineasProducto();
    return($resultado);
  }

  function ValidarLineaProducto($data)
  {
    $Nombre=$data["NombreLineaProducto"];
    if ($Nombre == "")
    {
      return "Debe completar el registro";
    }
    else
    {
      return "";
    }
  }

  function InsertarLineaProducto($data) {
    $data["NombreLineaProducto"] = trim($data["NombreLineaProducto"]);
    $resultado = $this -> ValidarLineaProducto($data);
    if ($resultado != "") {
      return ($resultado);
    }
    else {
      $data["UsuarioRegistro"]=$this->sesionusuario->obtener_alias_usuario();
      $resultado = $this->mLineaProducto->InsertarLineaProducto($data);
      return $resultado;
    }
  }

  function ActualizarLineaProducto($data)
  {
    $data["NombreLineaProducto"] = trim($data["NombreLineaProducto"]);
    $resultado = $this -> ValidarLineaProducto($data);
    if ($resultado != "")
    {
      return ($resultado);
    }
    else
    {
      $this->mLineaProducto->ActualizarLineaProducto($data);
      return "";
    }
  }

  function ValidarExistenciaLineaProductoEnMercaderia($data)
  {
    $resultado = $this->mLineaProducto->ConsultarLineasProductoEnMercaderia($data);
    $contador = COUNT($resultado);
    if ($contador > 0)
    {
      return "No se puede eliminar porque tiene registros en Mercaderia";
    }
    else
    {
      return "";
    }
  }

  function BorrarLineaProducto($data)
  {
    $resultado=$this-> ValidarExistenciaLineaProductoEnMercaderia($data);
    if($resultado!="")
    {
      return $resultado;
    }
    $this->mLineaProducto->BorrarLineaProducto($data);
    return "";
  }

  //PARA EXPORTAR
  function ConsultarLineasJSON($data)
  {
    $resultado=$this->mLineaProducto->ConsultarLineasJSON($data);
    return $resultado;
  }

  function ObtenerLineaProductoPorNombreLineaProducto($data) {
    $resultado=$this->mLineaProducto->ObtenerLineaProductoPorNombreLineaProducto($data);
    return $resultado;    
  }
}

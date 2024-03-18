<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sSubFamiliaProducto extends MY_Service
{

  public $SubFamiliaProducto = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->library('mapper');
    $this->load->model('Configuracion/Catalogo/mSubFamiliaProducto');
    $this->SubFamiliaProducto = $this->mSubFamiliaProducto->SubFamiliaProducto;
  }

  function ListarSubFamiliasProducto($data)
  {
    $resultado = $this->mSubFamiliaProducto->ListarSubFamiliasProducto($data);
    return $resultado;
  }

  function ListarTodosSubFamiliasProducto()
  {
    $resultado = $this->mSubFamiliaProducto->ListarTodosSubFamiliasProducto();
    return $resultado;
  }

  function ValidarSubFamiliaProducto($data)
  {
    $Nombre = $data["NombreSubFamiliaProducto"];
    if ($Nombre == "") {
      return "Debe ingresar primero el nombre de la Sub Familia";
    } else {
      return "";
    }
  }

  function InsertarSubFamiliaProducto($data)
  {
    $resultado = $this->ValidarSubFamiliaProducto($data);
    if ($resultado != "") {
      return $resultado;
    } else {
      $data["UsuarioRegistro"]=$this->sesionusuario->obtener_alias_usuario();
      $resultado = $this->mSubFamiliaProducto->InsertarSubFamiliaProducto($data);
      return $resultado;
    }
  }

  function ActualizarSubFamiliaProducto($data)
  {
    $resultado = $this->ValidarSubFamiliaProducto($data);
    if ($resultado != "") {
      return $resultado;
    } else {
      $this->mSubFamiliaProducto->ActualizarSubFamiliaProducto($data);
      return "";
    }
  }

  function ValidarExistenciaSubFamiliasProductoEnMercaderia($data)
  {
    $resultado = $this->mSubFamiliaProducto->ConsultarSubFamiliasProductoEnMercaderia($data);
    $contador = COUNT($resultado);
    if ($contador > 0) {
      return "No se puede eliminar porque la Sub familia tiene movimientos en Mercaderia";
    } else {
      return "";
    }
  }

  function BorrarSubFamiliaProducto($data)
  {
    $resultado = $this->ValidarExistenciaSubFamiliasProductoEnMercaderia($data);
    if ($resultado != "") {
      return $resultado;
    } else {

      $this->mSubFamiliaProducto->BorrarSubFamiliaProducto($data);
      return "";
    }
  }

  function ConsultarSubFamiliasProducto($data)
  {
    $resultado = $this->mSubFamiliaProducto->ConsultarSubFamiliasProducto($data);
    return $resultado;
  }

  //PARA EXPORTAR
  function ConsultarSubFamiliasJSON($data)
  {
    $resultado = $this->mSubFamiliaProducto->ConsultarSubFamiliasJSON($data);
    return $resultado;
  }

  function ConsultarSubFamiliaNoEspefificadoPorFamilia($data)
  {
    $resultado = $this->mSubFamiliaProducto->ConsultarSubFamiliaNoEspefificadoPorFamilia($data);
    return $resultado;
  }

  function ObtenerSubFamiliaProductoPorNombreSubFamilia($data) {
    $resultado = $this->mSubFamiliaProducto->ObtenerSubFamiliaProductoPorNombreSubFamilia($data);
    return $resultado;
  }
}

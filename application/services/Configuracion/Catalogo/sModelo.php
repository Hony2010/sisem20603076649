<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sModelo extends MY_Service
{

  public $Modelo = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');    
    $this->load->model('Configuracion/Catalogo/mModelo');
    $this->Modelo = $this->mModelo->Modelo;
  }

  function ListarModelos($data)
  {
    $resultado = $this->mModelo->ListarModelos($data);
    return $resultado;
  }

  function ListarTodosModelos()
  {
    $resultado = $this->mModelo->ListarTodosModelos();
    return $resultado;
  }

  function ValidarModelo($data)
  {
    $Nombre = $data["NombreModelo"];
    if ($Nombre == "") {
      return "Debe completar el registro";
    } else {
      return "";
    }
  }

  function InsertarModelo($data)
  {
    $data["NombreModelo"] = trim($data["NombreModelo"]);
    $resultado = $this->ValidarModelo($data);
    if ($resultado != "") {
      return $resultado;
    } else {
      $data["UsuarioRegistro"]=$this->sesionusuario->obtener_alias_usuario();     
      $resultado = $this->mModelo->InsertarModelo($data);
      return $resultado;
    }
  }

  function ActualizarModelo($data)
  {
    $data["NombreModelo"] = trim($data["NombreModelo"]);
    $resultado = $this->ValidarModelo($data);
    if ($resultado != "") {
      return $resultado;
    } else {
      $this->mModelo->ActualizarModelo($data);
      return "";
    }
  }

  function ValidarExistenciaModeloEnMercaderia($data)
  {
    $resultado = $this->mModelo->ConsultarModeloEnMercaderia($data);
    $contador = COUNT($resultado);
    if ($contador > 0) {
      return "No se puede eliminar porque tiene registros en Mercaderia";
    } else {
      return "";
    }
  }

  function ValidarExistenciaModeloActivoFijo($data)
  {
    $resultado = $this->mModelo->ConsultarModeloEnActivoFijo($data);
    $contador = COUNT($resultado);
    if ($contador > 0) {
      return "No se puede eliminar porque tiene registros en Activo Fijo";
    } else {
      return "";
    }
  }

  function BorrarModelo($data)
  {
    $resultado1 = $this->ValidarExistenciaModeloEnMercaderia($data);
    $resultado2 = $this->ValidarExistenciaModeloActivoFijo($data);
    if ($resultado1 != "") {
      return $resultado1;
    } else if ($resultado2 != "") {
      return $resultado2;
    } else {
      $this->mModelo->BorrarModelo($data);
      return "";
    }
  }

  function ConsultarModelo($data)
  {
    $resultado = $this->mModelo->ConsultarModelo($data);
    return $resultado;
  }

  //PARA EXPORTAR
  function ConsultarModelosJSON($data)
  {
    $resultado = $this->mModelo->ConsultarModelosJSON($data);
    return $resultado;
  }

  function ConsultarModeloNoEspefificadoPorMarca($data)
  {
    $resultado = $this->mModelo->ConsultarModeloNoEspefificadoPorMarca($data);
    return $resultado;
  }

  function ObtenerModeloMarcaPorNombreModelo($data) {
    $resultado = $this->mModelo->ObtenerModeloMarcaPorNombreModelo($data);
    return $resultado;
  }

}

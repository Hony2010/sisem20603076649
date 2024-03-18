<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sFabricante extends MY_Service {

  public $Fabricante = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->model('Configuracion/Catalogo/mFabricante');
    $this->Fabricante = $this->mFabricante->Fabricante;
  }

  function ListarFabricantes()
  {
    $resultado = $this->mFabricante->ListarFabricantes();
    return $resultado;
  }

  function ValidarFabricante($data)
  {
    $nombre=$data["NombreFabricante"];
    if ($nombre == "")
    {
      return "Debe completar el Nombre";
    }
    else
    {
      return "";
    }
  }

  function InsertarFabricante($data)
  {
    $data["NombreFabricante"] = trim($data["NombreFabricante"]);
    $resultado = $this -> ValidarFabricante($data);
    if ($resultado != "")
    {
      return $resultado;
    }
    else
    {
      $resultado=$this->mFabricante->InsertarFabricante($data);
      return $resultado;
    }
  }

  function ActualizarFabricante($data)
  {
    $data["NombreFabricante"] = trim($data["NombreFabricante"]);
    $resultado = $this -> ValidarFabricante($data);
    if ($resultado != "")
    {
      return $resultado;
    }
    else
    {
      $this->mFabricante->ActualizarFabricante($data);
      return "";
    }
  }

  function ValidarExistenciaFabricanteEnMercaderia($data)
  {
    $resultado = $this->mFabricante->ConsultarFabricanteEnMercaderia($data);
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

  function BorrarFabricante($data)
  {
    $resultado1= $this -> ValidarExistenciaFabricanteEnMercaderia($data);

    if ($resultado1 != "")
    {
      return $resultado1;
    }
    else
    {
      $this->mFabricante->BorrarFabricante($data);
      return "";
    }
  }

  //PARA EXPORTAR
  function ConsultarFabricantesJSON($data)
  {
    $resultado=$this->mFabricante->ConsultarFabricantesJSON($data);
    return $resultado;
  }
  
}

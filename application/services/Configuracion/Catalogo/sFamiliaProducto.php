<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sFamiliaProducto extends MY_Service {

  public $FamiliaProducto = array();
  public $SubFamiliaProducto = array();

  public function __construct()
  {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('sesionusuario');
        $this->load->library('mapper');
        $this->load->model('Configuracion/Catalogo/mFamiliaProducto');
        $this->load->model('Configuracion/Catalogo/mSubFamiliaProducto');
        $this->FamiliaProducto = $this->mFamiliaProducto->FamiliaProducto;
        $this->SubFamiliaProducto = $this->mSubFamiliaProducto->SubFamiliaProducto;
  }

  function ListarFamiliasProducto()
  {
    $resultado = $this->mFamiliaProducto->ListarFamiliasProducto();
    return($resultado);
  }

  function ValidarFamiliaProducto($data)
  {
    $Nombre=$data["NombreFamiliaProducto"];
    if ($Nombre == "")
    {
      return "Debe ingresar primero el nombre de la Familia";
    }
    else
    {
      return "";
    }
  }

  function InsertarFamiliaProducto($data) {
    $data["NombreFamiliaProducto"] = trim($data["NombreFamiliaProducto"]);
    $resultado = $this->ValidarFamiliaProducto($data);
    if ($resultado != "") {
      return ($resultado);
    }
    else {
      $data["UsuarioRegistro"]=$this->sesionusuario->obtener_alias_usuario();
      $resultado = $this->mFamiliaProducto->InsertarFamiliaProducto($data);
      $data['IdFamiliaProducto']=$resultado["IdFamiliaProducto"];      
      $resultadoSubFamiliaProducto = $this->InsertarNoEspecificadoEnSubFamiliaProducto($data);
      $resultado["IdSubFamiliaProducto"]=$resultadoSubFamiliaProducto["IdSubFamiliaProducto"];
      return $resultado;
    }
  }

  function ActualizarFamiliaProducto($data)
  {
    $resultado = $this -> ValidarFamiliaProducto($data);
    if ($resultado != "")
    {
      return ($resultado);
    }
    else
    {
      $this->mFamiliaProducto->ActualizarFamiliaProducto($data);
      return "";
    }
  }

  function ValidarExistenciaFamiliaProductoEnMercaderia($data)
  {
    $resultado = $this->mFamiliaProducto->ConsultarFamiliasProductoEnMercaderia($data);
    $contador = COUNT($resultado);

    if ($contador > 0)
    {
      return "No se puede eliminar porque tiene registros en Mercadería";
    }
    else
    {
      return $resultado;
    }
  }

  function ValidarExistenciaFamiliaProductoEnSubFamiliaProducto($data)
  {
    $resultado = $this->mFamiliaProducto->ConsultarFamiliasProductoEnSubFamiliaProducto($data);
    $contador = COUNT($resultado);

    if ($contador > 1)
    {
      return "No se puede eliminar porque tiene registros en Sub Familia";
    }
    else
    {
      return $resultado;
    }
  }

  function BorrarFamiliaProducto($data)
  {
    $resultado1 = $this->ValidarExistenciaFamiliaProductoEnSubFamiliaProducto($data);
    $resultado2= $this->ValidarExistenciaFamiliaProductoEnMercaderia($data);

    if (!is_array($resultado1))
    {
      return($resultado1);
    }
    else
    {
      if (!is_array($resultado2))
      {
        return $resultado2;
      }
      else
      {
        $resultado3 = $resultado1[0];
        $resultado =(array)$resultado3;
        $this->mFamiliaProducto->BorrarFamiliaProducto($data);
        $this->mSubFamiliaProducto->BorrarSubFamiliaProducto($resultado);
        return $resultado2;
      }
    }
  }

  function ValidarExistenciaFamiliaProductoYNoEspecificadoEnSubFamiliaProducto($data)
  {
    $resultado = $this->mFamiliaProducto->ObtenerFamiliaProductoYNoEspecificadoEnSubFamiliaProducto($data);
    $contador = COUNT($resultado);
    return($contador);
  }

  function InsertarNoEspecificadoEnSubFamiliaProducto($data) {
    //$resultado = $this->ValidarExistenciaFamiliaProductoYNoEspecificadoEnSubFamiliaProducto($data);
    //if ($resultado == null)
    //{
      $resultadoSubFamiliaProducto=$this->mFamiliaProducto->InsertarNoEspecificadoEnSubFamiliaProducto($data);
      return $resultadoSubFamiliaProducto;
    //}
    //else
    //{
      //return ""
    //}
  }

  function ConsultarFamiliasProducto($data)
  {
    $resultado=$this->mFamiliaProducto->ConsultarFamiliasProducto($data);
    return ($resultado);
  }

  //PARA EXPORTAR
  function ConsultarFamiliasJSON($data)
  {
    $resultado=$this->mFamiliaProducto->ConsultarFamiliasJSON($data);
    return $resultado;
  }

  function ObtenerFamiliaProductoPorNombreFamilia($data) {
    $resultado=$this->mFamiliaProducto->ObtenerFamiliaProductoPorNombreFamilia($data);
    return $resultado;
  }

} 

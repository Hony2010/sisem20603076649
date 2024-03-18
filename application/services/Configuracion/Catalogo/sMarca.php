<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMarca extends MY_Service {

  public $Marca = array();
  public $Modelo = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->library('mapper');
    $this->load->model('Configuracion/Catalogo/mMarca');
    $this->load->model('Configuracion/Catalogo/mModelo');
    $this->Marca = $this->mMarca->Marca;
    $this->Modelo = $this->mModelo->Modelo;
  }

  function ListarMarcas()
  {
    $resultado = $this->mMarca->ListarMarcas();
    return $resultado;
  }

  function ValidarMarca($data)
  {
    $Nombre=$data["NombreMarca"];
    if ($Nombre == "")
    {
      return "Debe ingresar primero el nombre de la Marca";
    }
    else
    {
      return "";
    }
  }

  function InsertarMarca($data) {
    $data["NombreMarca"] = trim($data["NombreMarca"]);
    $resultado = $this -> ValidarMarca($data);
    if ($resultado != "") {
      return $resultado;
    }
    else {
      $data["UsuarioRegistro"]=$this->sesionusuario->obtener_alias_usuario();  
      $resultado = $this->mMarca->InsertarMarca($data);      
      $data['IdMarca']=$resultado["IdMarca"];    
      $resultadoModelo = $this->InsertarNoEspecificadoEnModelo($data);
      $resultado["IdModelo"] = $resultadoModelo["IdModelo"];
      return $resultado;
    }
  }

  function ActualizarMarca($data)
  {
    $data["NombreMarca"] = trim($data["NombreMarca"]);
    $resultado = $this -> ValidarMarca($data);
    if ($resultado != "")
    {
      return $resultado;
    }
    else
    {
      $this->mMarca->ActualizarMarca($data);
      return "";
    }
  }

  function ValidarExistenciaMarcaEnMercaderia($data)
  {
    $resultado = $this->mMarca->ConsultarMarcaEnMercaderia($data);
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

  function ValidarExistenciaMarcaEnModelo($data)
  {
    $resultado = $this->mMarca->ConsultarMarcaEnModelo($data);
    $contador = COUNT($resultado);
    if ($contador > 1)
    {
      return "No se puede eliminar porque tiene registros en Modelo";
    }
    else
    {
      return $resultado;
    }
  }

  function ValidarExistenciaMarcaEnActivoFijo($data)
  {
    $resultado = $this->mMarca->ConsultarMarcaEnActivoFijo($data);
    $contador = COUNT($resultado);
    if ($contador > 0)
    {
      return "No se puede eliminar porque tiene registros en Activo Fijo";
    }
    else
    {
      return $resultado;
    }
  }

  function BorrarMarca($data)
  {
    $resultado1= $this -> ValidarExistenciaMarcaEnModelo($data);
    $resultado2 = $this -> ValidarExistenciaMarcaEnMercaderia($data);
    $resultado3= $this -> ValidarExistenciaMarcaEnActivoFijo($data);

    if (!is_array($resultado1))
    {
      return $resultado1;
    }
    else
    {
      if (!is_array($resultado2))
      {
        return $resultado2;
      }
      else
      {
        if (!is_array($resultado3))
        {
          return $resultado3;
        }
          else
          {
            $resultado4 = $resultado1[0];
            $resultado =(array)$resultado4;
            $this->mMarca->BorrarMarca($data);
            $this->mModelo->BorrarModelo($resultado);
            return $resultado3;
          }
        }
      }
    }

  function ValidarExistenciaMarcaYNoEspecificadoEnModelo($data)
  {
    $resultado = $this->mMarca->ObtenerMarcaYNoEspecificadoEnModelo($data);
    $contador = COUNT($resultado);
    return($contador);
  }

  function InsertarNoEspecificadoEnModelo($data)
  {
    //$resultado = $this->ValidarExistenciaMarcaYNoEspecificadoEnModelo($data);
    //if ($resultado == "")
    //{      
      $resultadoModelo=$this->mMarca->InsertarNoEspecificadoEnModelo($data);
      return $resultadoModelo;
    //}
    //else
    //{
      //return "";
    //}
  }

  function ConsultarMarca($data)
  {
    $resultado=$this->mMarca->ConsultarMarca($data);
    return $resultado;
  }

  //PARA EXPORTAR
  function ConsultarMarcasJSON($data)
  {
    $resultado=$this->mMarca->ConsultarMarcasJSON($data);
    return $resultado;
  }

  function ObtenerMarcaPorNombreMarca($data) {
    $resultado=$this->mMarca->ObtenerMarcaPorNombreMarca($data);
    return $resultado;
  }

}

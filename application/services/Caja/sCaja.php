<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCaja extends MY_Service {

  public $Caja = array();

  public function __construct()
  {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->model('Caja/mCaja');
        $this->Caja = $this->mCaja->Caja;
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $this->Caja["NombreCaja"] = "";
    $this->Caja["NombreMoneda"] = "";
    $this->Caja["NumeroCaja"] = "";

    $data = array();

    $resultado = array_merge($this->Caja, $data);

    return $resultado;
  }

  function ListarCajas()
  {
    $resultado = $this->mCaja->ListarCajas();
    return $resultado;
  }

  function ListarCajasPorNumeroCaja()
  {
    $data["NumeroCaja"] = NUMERO_CAJA;
    $resultado = $this->mCaja->ListarCajasPorNumeroCaja($data);
    return $resultado;
  }

  function ValidarNombreCaja($data)
  {
    $nombre=$data["NombreCaja"];
    if ($nombre == "")
    {
      return "Debe completar el Nombre de Caja.";
    }
    else
    {
      return "";
    }
  }

  function ValidarNumeroCaja($data)
  {
    $nombre=$data["NumeroCaja"];
    if ($nombre == "")
    {
      return "Debe completar el Numero de Caja.";
    }
    else
    {
      return "";
    }
  }

  function ValidarCaja($data)
  {
    $nombre= $this->ValidarNombreCaja($data);
    $numero= $this->ValidarNumeroCaja($data);
    if ($nombre !="")
    {
      return $nombre;
    }
    else if ($numero !="")
    {
      return $numero;
    }
    else
    {
      return "";
    }
  }

  function ValidarExistenciaNombreCajaParaInsertar($data)
  {
    $resultado = $this->mCaja->ObtenerNombreCajaParaInsertar($data);
    if (count($resultado)>0)
    {
      return "Esta caja ya fue registrada.";
    }
    else
    {
      return "";
    }
  }

  function ValidarExistenciaNombreCajaParaActualizar($data)
  {
    $resultado = $this->mCaja->ObtenerNombreCajaParaActualizar($data);
    if (count($resultado)>0)
    {
      return "Esta caja ya fue registrada.";
    }
    else
    {
      return "";
    }
  }

  function ValidarExistenciaNumeroCajaParaInsertar($data)
  {
    $resultado = $this->mCaja->ObtenerNumeroCajaParaInsertar($data);
    if (count($resultado)>0)
    {
      return "Este número de caja ya fue registrada.";
    }
    else
    {
      return "";
    }
  }

  function ValidarExistenciaNumeroCajaParaActualizar($data)
  {
    $resultado = $this->mCaja->ObtenerNumeroCajaParaActualizar($data);
    if (count($resultado)>0)
    {
      return "Este número de caja ya fue registrada.";
    }
    else
    {
      return "";
    }
  }

  function InsertarCaja($data)
  {
    $data["NombreCaja"] = trim($data["NombreCaja"]);
    $resultado1 = $this->ValidarCaja($data);
    $duplicadonombrecaja = $this->ValidarExistenciaNombreCajaParaInsertar($data);
    $duplicadonumerocaja = $this->ValidarExistenciaNumeroCajaParaInsertar($data);

    if ($resultado1 != "")
    {
      return $resultado1;
    }
    else if ($duplicadonombrecaja != "")
    {
      return $duplicadonombrecaja;
    }
    else if ($duplicadonumerocaja != "")
    {
      return $duplicadonumerocaja;
    }
    else
    {
      $resultado = $this->mCaja->InsertarCaja($data);
      return $resultado;
    }
  }

  function ActualizarCaja($data)
  {
    $data["NombreCaja"] = trim($data["NombreCaja"]);
    $resultado1 = $this->ValidarCaja($data);
    $duplicadonombrecaja = $this->ValidarExistenciaNombreCajaParaActualizar($data);
    $duplicadonumerocaja = $this->ValidarExistenciaNumeroCajaParaActualizar($data);

    if ($resultado1 != "")
    {
      return $resultado1;
    }
    else if ($duplicadonombrecaja != "")
    {
      return $duplicadonombrecaja;
    }
    else if ($duplicadonumerocaja != "")
    {
      return $duplicadonumerocaja;
    }
    else
    {
      $resultado = $this->mCaja->ActualizarCaja($data);
      return $resultado;
    }
  }

  function ValidarExistenciaCajaEnVenta($data)
  {
    $resultado = $this->mCaja->ConsultarCajaEnVenta($data);
    $contador = count($resultado);
    if ($contador > 0)
    {
      return "No se puede eliminar porque tiene registros en Venta";
    }
    else
    {
      return "";
    }
  }

  function BorrarCaja($data)
  {
    $resultado1= "";//$this -> ValidarExistenciaCajaEnVenta($data);
    if ($resultado1 != "")
    {
      return $resultado1;
    }
    else
    {
      $resultado = $this->mCaja->BorrarCaja($data);
      return $resultado;
    }
  }
}

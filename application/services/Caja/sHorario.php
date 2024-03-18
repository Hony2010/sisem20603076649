<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sHorario extends MY_Service {

  public $Horario = array();

  public function __construct()
  {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->model('Caja/mHorario');
        $this->Horario = $this->mHorario->Horario;
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $this->Horario["IdHorario"] = "";
    $this->Horario["HoraInicio"] = "";
    $this->Horario["HoraFin"] = "";
    $this->Horario["HorasHolgura"] = "";
    $this->Horario["HoraInicioHolgura"] = "";
    $this->Horario["HoraFinHolgura"] = "";

    $data =array();

    $resultado = array_merge($this->Horario, $data);

    return $resultado;
  }

  function ListarHorarios()
  {
    $resultado = $this->mHorario->ListarHorarios();
    return $resultado;
  }

  //Se añade o devuelve el resultado encontrado
  function AgregarHorario($data)
  {
    $resultado = $this->mHorario->ObtenerHorarioPorHoraInicioYFin($data);
    if(count($resultado) > 0)
    {
      return $resultado[0];
    }
    else
    {
      $data["IdHorario"] = "";
      
      //PARAHOLGURA
      $HoraInicio = new DateTime($data["HoraInicio"]);
      $HoraFin = new DateTime($data["HoraFin"]);
      $HorasHolgura = (string) $data["HorasHolgura"];
      $HoraInicioHolgura = $HoraInicio->modify("-".$HorasHolgura." hour");
      $HoraFinHolgura = $HoraFin->modify("+".$HorasHolgura." hour");
      $data["HoraInicioHolgura"] = $HoraInicioHolgura->format('H:i:s');
      $data["HoraFinHolgura"] = $HoraFinHolgura->format('H:i:s');
      
      $response = $this->InsertarHorario($data);
      return $response;
    }
  }

  function InsertarHorario($data)
  {
    $resultado = $this->mHorario->InsertarHorario($data);
    return $resultado;
  }

  function ActualizarHorario($data)
  {
    $resultado = $this->mHorario->ActualizarHorario($data);
    return $resultado;
  }

  function BorrarHorario($data)
  {
    $resultado = $this->mHorario->BorrarHorario($data);
    return $resultado;
  }
}

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTurno extends MY_Service {

  public $Turno = array();

  public function __construct()
  {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->model('Caja/mTurno');
        $this->load->service('Caja/sHorario');
        $this->Turno = $this->mTurno->Turno;
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");
    
    $this->Turno["IdTurno"] = "";
    $this->Turno["NombreTurno"] = "";
    $horario = $this->sHorario->Cargar();

    $turno = array_merge($this->Turno, $horario);
    $data =array(
      'TurnoNuevo' => $turno
    );
    
    $resultado = array_merge($turno, $data);

    return $resultado;
  }

  function ListarTurnos()
  {
    $resultado = $this->mTurno->ListarTurnos();

    return $resultado;
  }

  function ValidarNombreTurno($data)
  {
    $nombre=$data["NombreTurno"];
    if ($nombre == "")
    {
      return "Debe completar el Nombre";
    }
    else
    {
      return "";
    }
  }

  function ValidarHoraInicio($data)
  {
    $nombre=$data["HoraInicio"];
    if ($nombre == "")
    {
      return "Debe completar la Hora de Inicio";
    }
    else
    {
      return "";
    }
  }

  function ValidarHoraFin($data)
  {
    $nombre=$data["HoraFin"];
    if ($nombre == "")
    {
      return "Debe completar la Hora Fin";
    }
    else
    {
      return "";
    }
  }

  function ValidarTurno($data)
  {
    $nombre= $this->ValidarNombreTurno($data);
    $horainicio= $this->ValidarHoraInicio($data);
    $horafinal= $this->ValidarHoraFin($data);
    if ($nombre != "")
    {
      return $nombre;
    }
    else if ($horainicio != "")
    {
      return $horainicio;
    }
    else if ($horafinal != "")
    {
      return $horafinal;
    }
    else
    {
      return "";
    }
  }

  function ValidarExistenciaTurnoParaInsertar($data)
  {
    $resultado = $this->mTurno->ObtenerTurnoParaInsertar($data);
    if (count($resultado)>0)
    {
      return "Este turno ya fue registrado";
    }
    else
    {
      return "";
    }
  }

  function ValidarExistenciaTurnoParaActualizar($data)
  {
    $resultado = $this->mTurno->ObtenerTurnoParaActualizar($data);
    if (count($resultado)>0)
    {
      return "Este turno ya fue registrado";
    }
    else
    {
      return "";
    }
  }

  function InsertarTurno($data)
  {
    $data["NombreTurno"] = trim($data["NombreTurno"]);
    $resultado1 = $this->ValidarTurno($data);
    $resultado2 = $this->ValidarExistenciaTurnoParaInsertar($data);

    if ($resultado1 != "")
    {
      return $resultado1;
    }
    else if ($resultado2 != "")
    {
      return $resultado2;
    }
    else
    {
      //INSERTAMOS U OBTENEMOS EL HORARIO
      $horario = $this->sHorario->AgregarHorario($data);
      $data["IdHorario"] = $horario["IdHorario"];
      $resultado = $this->mTurno->InsertarTurno($data);

      $resultado = array_merge($resultado, $horario);
      return $resultado;
    }
  }

  function ActualizarTurno($data)
  {
    $data["NombreTurno"] = trim($data["NombreTurno"]);
    $resultado1 = $this->ValidarTurno($data);
    $resultado2 = $this->ValidarExistenciaTurnoParaActualizar($data);

    if ($resultado1 != "")
    {
      return $resultado1;
    }
    else if ($resultado2 != "")
    {
      return $resultado2;
    }
    else
    {
      //INSERTAMOS U OBTENEMOS EL HORARIO
      $horario = $this->sHorario->AgregarHorario($data);
      $data["IdHorario"] = $horario["IdHorario"];
      $resultado = $this->mTurno->ActualizarTurno($data);

      $resultado = array_merge($resultado, $horario);
      return $resultado;
    }
  }

  function BorrarTurno($data)
  {
    $resultado1= "";
    if ($resultado1 != "")
    {
      return $resultado1;
    }
    else
    {
      $resultado = $this->mTurno->BorrarTurno($data);
      return $resultado;
    }
  }
}

<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sPersona extends MY_Service
{

  public $Persona = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->service("Configuracion/Catalogo/sTipoDocumentoIdentidad");
    $this->load->service("Configuracion/General/sTipoPersona");
    $this->load->model('Catalogo/mPersona');
    $this->Persona = $this->mPersona->Persona;
  }


  function ValidarNumeroDocumentoIdentidad($data)
  {

    $numero = $data["NumeroDocumentoIdentidad"];
    $tipodoc = $data["IdTipoDocumentoIdentidad"];
    if ($tipodoc == ID_TIPO_DOCUMENTO_IDENTIDAD_OTROS) {
      return "";
    }
    if ($numero == "") {
      return "Debe ingresar el Número del Documento";
    } else if ($tipodoc == 2) {
      if (strlen($numero) != 8 or !is_numeric($numero)) {
        return "El DNI debe ser de 8 dígitos y tipo numérico";
      } else {
        return "";
      }
    } else if ($tipodoc == 4) {
      if (strlen($numero) < 11 or !is_numeric($numero)) {
        return "El RUC debe ser de 11 dígitos y tipo numérico";
      } else {
        return "";
      }
    } else {
      return "";
    }
  }

  function ValidarNombreCompleto($data)
  {
    $nombre = $data["NombreCompleto"];
    if ($nombre == "") {
      return "Debe ingresar el Nombre";
    } else {
      return "";
    }
  }

  function ValidarApellidoCompleto($data)
  {
    $apellido = $data["ApellidoCompleto"];
    if ($apellido == "") {
      return "Debe ingresar el Apellido";
    } else {
      return "";
    }
  }

  function ValidarRazonSocial($data)
  {
    $razon = $data["RazonSocial"];
    if ($razon == "") {
      return "Debe ingresar la Razon Social";
    } else {
      return "";
    }
  }

  function ValidarPersonaComoPersonaJuridica($data)
  {
    $data["NumeroDocumentoIdentidad"] = trim($data["NumeroDocumentoIdentidad"]);
    $data["RazonSocial"] = trim($data["RazonSocial"]);
    $numero = $this->ValidarNumeroDocumentoIdentidad($data);
    $razon = $this->ValidarRazonSocial($data);
    if ($numero != "") {
      return $numero;
    } else if ($razon != "") {
      return $razon;
    } else {
      return "";
    }
  }

  function ValidarPersonaComoPersonaNatural($data)
  {
    $data["NumeroDocumentoIdentidad"] = trim($data["NumeroDocumentoIdentidad"]);
    $data["RazonSocial"] = trim($data["RazonSocial"]);
    $numero = $this->ValidarNumeroDocumentoIdentidad($data);
    $nombre = $this->ValidarNombreCompleto($data);
    $apellido = $this->ValidarApellidoCompleto($data);
    $razon = $this->ValidarRazonSocial($data);

    if ($numero != "") {
      return $numero;
    } else if ($nombre != "") {
      return $nombre;
    } else if ($apellido != "") {
      return $apellido;
    } else if ($razon != "") {
      return $razon;
    } else {
      return "";
    }
  }

  function ValidarPersonaComoNoDomiciliado($data)
  {
    $data["NumeroDocumentoIdentidad"] = trim($data["NumeroDocumentoIdentidad"]);
    $data["RazonSocial"] = trim($data["RazonSocial"]);
    $numero = $this->ValidarNumeroDocumentoIdentidad($data);
    $nombre = $this->ValidarNombreCompleto($data);
    $apellido = $this->ValidarApellidoCompleto($data);
    $razon = $this->ValidarRazonSocial($data);
    if ($numero != "") {
      return $numero;
    } else if ($nombre != "") {
      return $nombre;
    } else if ($apellido != "") {
      return $apellido;
    } else if ($razon != "") {
      return $razon;
    } else {
      return "";
    }
  }

  function InsertarPersonaComoPersonaJuridica($data)
  {
    $resultado = $this->ValidarPersonaComoPersonaJuridica($data);

    if ($resultado != "") {
      return $resultado;
    } else {
      $persona = $this->mPersona->InsertarPersona($data);
      return $persona;
    }
  }

  function InsertarPersonaComoPersonaNatural($data)
  {
    $resultado = $this->ValidarPersonaComoPersonaNatural($data);

    if ($resultado != "") {
      return $resultado;
    } else {
      $persona = $this->mPersona->InsertarPersona($data);
      return $persona;
    }
  }

  function InsertarPersonaComoNoDomiciliado($data)
  {
    $resultado = $this->ValidarPersonaComoNoDomiciliado($data);

    if ($resultado != "") {
      return $resultado;
    } else {
      $persona = $this->mPersona->InsertarPersona($data);
      return $persona;
    }
  }

  function ActualizarPersonaComoPersonaJuridica($data)
  {
    $resultado = $this->ValidarPersonaComoPersonaJuridica($data);

    if ($resultado != "") {
      return $resultado;
    } else {
      $resultado = $this->mPersona->ActualizarPersona($data);
      return $resultado;
    }
  }

  function ActualizarPersonaComoPersonaNatural($data)
  {
    $resultado = $this->ValidarPersonaComoPersonaNatural($data);

    if ($resultado != "") {
      return $resultado;
    } else {
      $resultado = $this->mPersona->ActualizarPersona($data);
      return $resultado;
    }
  }

  function ActualizarPersonaComoNoDomiciliado($data)
  {
    $resultado = $this->ValidarPersonaComoNoDomiciliado($data);

    if ($resultado != "") {
      return $resultado;
    } else {
      $resultado = $this->mPersona->ActualizarPersona($data);
      return $resultado;
    }
  }
  function ActualizarEmailPersona($data)
  {
    $resultado = $this->mPersona->ActualizarPersona($data);
    return "";
  }

  function BorrarPersona($data)
  {
    // $resultado = $this->ValidarExistenciaPersonaEnVenta($data);
    // if ($resultado !="")
    // {
    //   return $resultado;
    // }
    // else
    // {
    $resultado = $this->mPersona->BorrarPersona($data);
    return "";
    // }
  }
}

<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sSeguridad extends MY_Service
{

  public $Usuario = array();
  public $Seguridad = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->model('Seguridad/mUsuario');
    $this->load->service('Seguridad/sUsuario');
    $this->load->service('Configuracion/General/sEmpresa');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->Usuario = $this->mUsuario->Usuario;
  }

  function ValidarInicioSesion($data)
  {
    $nombre = $this->sUsuario->ValidarNombreUsuario($data);
    $clave = $this->sUsuario->ValidarClaveUsuario($data);

    if ($nombre != "") {
      return $nombre;
    } else if ($clave != "") {
      return $clave;
    } else {
      return "";
    }
  }

  function ValidarCuenta($data)
  {

    $resultado = $this->mUsuario->ObtenerCuenta($data);
    // print_r($resultado);
    // exit;
    if ($resultado == null) //si existe cuenta
    {
      return "El Nombre de Usuario o la Clave es Incorrecta.";
    } else if ($resultado[0]["IndicadorEstado"] == "B") //si la cuenta esta bloqueada
    {
      return "La cuenta esta bloqueada";
    } else if ($resultado[0]["IndicadorEstado"] == "E") //si la cuenta esta Eliminada
    {
      return "La cuenta no esta activa";
    }
    // else if ($resultado["IndicadorSesionActivo"] =="S") //si la cuenta esta vigente de administrador no tiene sesion activo
    // {
    //   return "La cuenta esta siendo usada en otra sesion.";
    // }
    else {
      return $resultado;
    }
  }

  function ValidarHorario($data)
  {

    $resultado = $this->sUsuario->ValidarTurnoUsuario($data);
    return $resultado;
  }

  function AutenticarCuenta($data)
  {
    $resultado1 = $this->ValidarInicioSesion($data);
    $resultado2 = $this->ValidarCuenta($data);
    if ($resultado1 != "") {
      return $resultado1;
    } else if (!is_array($resultado2)) //si la cuenta no es valida
    {
      return $resultado2;
    } else {

      //$this->mUsuario->MarcarSesionActivo($resultado2);
      $parametroCaja = $this->sConstanteSistema->ObtenerParametroCaja();
      if ($parametroCaja == "1") {
        $resultado3 = $this->ValidarHorario($resultado2[0]);
        if (!is_array($resultado3)) //si la cuenta no es valida
        {
          return $resultado3;
        } else {
          return $resultado2;
        }
      } else {
        return $resultado2;
      }
    }
  }

  public function Login($data)
  {
    try {
      //$data = $this->input->post("Data");
      $resultado = $this->AutenticarCuenta($data);
      //$usuario = array("Usuario" => $resultado);
      //$this->session->set_userdata($usuario);
      return $resultado;
    } catch (Exception $e) {
      echo $this->json->json_response_error($e);
    }
  }

  public function CerrarSesion($data)
  {
    try {
      $this->mUsuario->DesmarcarSesionActivo($data);
      return "";
    } catch (Exception $e) {
      echo $this->json->json_response_error($e);
    }
  }

  public function CrearSesionUsuario($data)
  {
    $turno = $this->sUsuario->ObtenerTurnoActualUsuario($data);
    if ($data["Foto"] != "") {
      $data["RutaFoto"] = RUTA_IMAGENES . "Empleado/" . $data["IdPersona"] . "/" . $data["Foto"];
    } else {
      $data["RutaFoto"] = RUTA_IMAGENES . "usuario.png";
    }

    $input["IdEmpresa"] = LICENCIA_EMPRESA_ID;
    $datos_empresa = $this->sEmpresa->ListarEmpresas($input)[0];

    if ($turno != null) {
      $data['Turno'] = $turno;
    }

    $empresa = array("Empresa_" . LICENCIA_EMPRESA_RUC => $datos_empresa);
    $usuario = array("Usuario_" . LICENCIA_EMPRESA_RUC => $data);
    $parametroCaja = $this->sConstanteSistema->ObtenerParametroCaja();
    $datosParametro = array(
      "ParametroCaja" => $parametroCaja
    );
    $parametro = array("Parametro_" . LICENCIA_EMPRESA_RUC => $datosParametro);
    $this->session->set_userdata($usuario);
    $this->session->set_userdata($empresa);
    $this->session->set_userdata($parametro);
  }

  public function IniciarSesion($data)
  {
    $resultado = $this->Login($data);
    $datos_usuario = $resultado[0];

    if (is_array($datos_usuario)) {
      $this->CrearSesionUsuario($datos_usuario);
    }

    return $resultado;
  }

  public function IniciarSesionApi($data)
  {
    $data["NombreUsuario"] = $data["UsuarioRegistro"];
    $resultado = $this->LoginApi($data);    

    if(is_array($resultado)) {
      $datos_usuario = $resultado[0];
      $this->CrearSesionUsuario($datos_usuario);
    }

    return $resultado; //$resultado;
  }

  public function LoginApi($data)
  {
    $resultado = $this->mUsuario->ObtenerCuentaPorNombreUsuario($data);
    // print_r($resultado);
    // exit;
    if ($resultado == null) //si existe cuenta
    {
      return "El Nombre de Usuario o la Clave es Incorrecta.";
    } else if ($resultado[0]["IndicadorEstado"] == "B") //si la cuenta esta bloqueada
    {
      return "La cuenta esta bloqueada";
    } else if ($resultado[0]["IndicadorEstado"] == "E") //si la cuenta esta Eliminada
    {
      return "La cuenta no esta activa";
    }
    // else if ($resultado["IndicadorSesionActivo"] =="S") //si la cuenta esta vigente de administrador no tiene sesion activo
    // {
    //   return "La cuenta esta siendo usada en otra sesion.";
    // }
    else {
      return $resultado;
    }
  }
}

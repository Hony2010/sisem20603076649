<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sEmpleado extends MY_Service
{

  public $Empleado = array();
  public $Persona = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->library('jsonconverter');
    $this->load->library('servicessearch');
    $this->load->library('consultadatosreniec');
    $this->load->service("Configuracion/General/sRol");
    $this->load->service("Configuracion/General/sSede");
    $this->load->service("Configuracion/Catalogo/sTipoDocumentoIdentidad");
    $this->load->service("Configuracion/General/sTipoPersona");
    $this->load->model('Catalogo/mEmpleado');
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->model('Catalogo/mPersona');
    $this->Empleado = $this->mEmpleado->Empleado;
    $this->Persona = $this->mPersona->Persona;
    $this->Empleado = $this->herencia->Heredar($this->Persona, $this->Empleado);
  }

  function Inicializar()
  {
    $input["textofiltro"] = '';
    $input["pagina"] = 1;
    $input["numerofilasporpagina"] = $this->ObtenerNumeroFilasPorPagina();
    $input["paginadefecto"] = 1;
    $input["totalfilas"] = $this->ObtenerNumeroTotalEmpleados($input);

    $this->Empleado["Foto"] = "";
    $this->Empleado["NombreAbreviado"] = "";
    $this->Empleado["IdPersona"] = "";
    $this->Empleado["IndicadorEstado"] = "A";
    $this->Empleado["Celular"] = "";
    $this->Empleado["TelefonoFijo"] = "";
    $this->Empleado["NombreRol"] = "";
    $this->Empleado["NombreSede"] = "";
    $this->Empleado["FechaIngreso"] = "";
    $this->Empleado["Sueldo"] = "";
    $this->Empleado["IndicadorEstadoEmpleado"] = true;
    $this->Empleado["ClienteNuevo"] = $this->Empleado;

    $Empleados = $this->sEmpleado->ListarEmpleados(1);
    $Roles = $this->sRol->ListarRoles();
    $Sedes = $this->sSede->ListarSedes();
    $TiposDocumentoIdentidad = $this->sTipoDocumentoIdentidad->ListarTiposDocumentoIdentidad();
    $TiposPersona = $this->sTipoPersona->ListarTiposPersona();
    $ImageURLEmpleado = $this->ObtenerUrlCarpetaImagenes();

    $data = array(
      "data" =>
      array(
        'Filtros' => $input,
        'Empleado' => $this->Empleado,
        'NuevoEmpleado' => $this->Empleado,
        'Empleados' => $Empleados,
        'Roles' => $Roles,
        'Sedes' => $Sedes,
        'TiposDocumentoIdentidad' => $TiposDocumentoIdentidad,
        'TiposPersona' => $TiposPersona,
        'ImageURLEmpleado' => $ImageURLEmpleado
      )
    );

    return $data;
  }

  function ObtenerNumeroFila()
  {
    $resultado = $this->mEmpleado->ObtenerNumeroFila();
    $total = $resultado[0]['NumeroFila'];
    return $total;
  }

  function ListadoDeEmpleados()
  {
    $resultado = $this->mEmpleado->ListadoDeEmpleados();
    return $resultado;
  }

  function ListarEmpleadosPorId($data)
  {
    $resultado = $this->mEmpleado->ListarEmpleadosPorId($data);
    return $resultado;
  }

  function ObtenerNumeroFilaPorConsultaEmpleado($data)
  {
    $resultado = $this->mEmpleado->ObtenerNumeroFilaPorConsultaEmpleado($data);
    $total = $resultado[0]['NumeroFila'];
    return $total;
  }

  function ObtenerNumeroPagina()
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_PERSONA;
    $total = $this->ObtenerNumeroFila();
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      if (($total % $ValorParametroSistema) > 0) {
        $numeropagina = ($total / $ValorParametroSistema) + 1;
        return intval($numeropagina);
      } else {
        $numeropagina = ($total / $ValorParametroSistema);
        return intval($numeropagina);
      }
    }
  }

  function ObtenerNumeroPaginaPorConsultaEmpleado($data)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_EMPLEADO;
    $total = $this->ObtenerNumeroFilaPorConsultaEmpleado($data);
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      if (($total % $ValorParametroSistema) > 0) {
        $numeropagina = ($total / $ValorParametroSistema) + 1;
        return intval($numeropagina);
      } else {
        $numeropagina = ($total / $ValorParametroSistema);
        return intval($numeropagina);
      }
    }
  }

  function ListarEmpleados($pagina)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_EMPLEADO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      $inicio = ($pagina * $ValorParametroSistema) - $ValorParametroSistema;
      $resultado = $this->mEmpleado->ListarEmpleados($inicio, $ValorParametroSistema);
      foreach ($resultado as $key => $value) {
        $resultado[$key]["FechaIngreso"] = ($value["FechaIngreso"] == "") ? "" : convertirFechaES($value["FechaIngreso"]);
        $resultado[$key]["IndicadorEstadoEmpleado"] = ($value["EstadoEmpleado"] == 0) ? false : true;
      }
      return ($resultado);
    }
  }

  function ValidarNumeroDocumento($data)
  {
    $numero = $data["NumeroDocumentoIdentidad"];
    if ($numero == "") {
      return "Debe ingresar el Numero del Documento";
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
    $razon2 = strpos($data['RazonSocial'], '"');
    if ($razon == "") {
      return "Debe ingresar la Razon Social";
    } else if (is_numeric($razon2)) {
      return "No se puedes utilizar comillas dobles, porfavor utilizar comillas simples";
    } else {
      return "";
    }
  }

  function ValidarDireccion($data)
  {
    $direccion = strpos($data['Direccion'], '"');
    if (is_numeric($direccion)) {
      return "No se puedes utilizar comillas dobles, porfavor utilizar comillas simples";
    } else {
      return "";
    }
  }

  function ValidarPersona($data)
  {
    $direccion = $this->ValidarDireccion($data);
    $numero = $this->ValidarNumeroDocumento($data);
    $razon = $this->ValidarRazonSocial($data);
    if ($numero != "") {
      return $numero;
    } else if ($direccion != "") {
      return $direccion;
    } else if ($razon != "") {
      return $razon;
    } else {
      return "";
    }
  }

  function ValidarExistenciaNumeroDocumentoIdentidadParaInsertar($data)
  {
    $numero = $data['NumeroDocumentoIdentidad'];
    $resultado = $this->mEmpleado->ObtenerNumeroDocumentoIdentidadParaInsertar($data);
    if (Count($resultado) > 0) {
      return "Este número de documento ya fue registrado.";
    } else {
      return "";
    }
  }

  function ValidarExistenciaNumeroDocumentoIdentidadParaActualizar($data)
  {
    $resultado = $this->mEmpleado->ObtenerNumeroDocumentoIdentidadParaActualizar($data);
    if (Count($resultado) > 0) {
      return "Este número de documento ya fue registrado.";
    } else {
      return "";
    }
  }

  function InsertarEnPersonaYEmpleado($data)
  {
    $data["FechaIngreso"] = ($data["FechaIngreso"] == "") ? NULL : convertToDate($data["FechaIngreso"]);
    $data["Sueldo"] = (is_string($data["Sueldo"])) ? str_replace(',', "", $data["Sueldo"]) : $data["Sueldo"];
    $data["EstadoEmpleado"] = ($data["IndicadorEstadoEmpleado"] == true) ? "1" : "0";
    $resultado = $this->ValidarPersona($data);
    $validar = $this->ValidarExistenciaNumeroDocumentoIdentidadParaInsertar($data);

    if ($resultado != "") {
      return $resultado;
    } else if ($validar != "") {
      return $validar;
    } else {
      
      $persona = $this->mPersona->InsertarPersona($data);
      $data["IdPersona"] = $persona["IdPersona"];
      $empleado = $this->mEmpleado->InsertarEmpleado($data);
      $data["IdEmpleado"] = $empleado;
      $data["FechaIngreso"] = ($data["FechaIngreso"] == "") ? NULL : convertirFechaES($data["FechaIngreso"]);
      return $data;
    }
  }

  function ActualizarEnPersonaYEmpleado($data)
  {
    $data["FechaIngreso"] = ($data["FechaIngreso"] == "") ? NULL : convertToDate($data["FechaIngreso"]);
    $data["Sueldo"] = (is_string($data["Sueldo"])) ? str_replace(',', "", $data["Sueldo"]) : $data["Sueldo"];
    $resultado = $this->ValidarPersona($data);
    $validar = $this->ValidarExistenciaNumeroDocumentoIdentidadParaActualizar($data);

    if ($resultado != "") {
      return $resultado;
    } else if ($validar != "") {
      return $validar;
    } else {
      $persona = $this->mPersona->ActualizarPersona($data);
      $empleado = $this->mEmpleado->ActualizarEmpleado($data);
      return "";
    }
  }

  function ConsultarEmpleados($data, $pagina)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_EMPLEADO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      $inicio = ($pagina * $ValorParametroSistema) - $ValorParametroSistema;
      $resultado = $this->mEmpleado->ConsultarEmpleados($inicio, $ValorParametroSistema, $data);
      foreach ($resultado as $key => $value) {
        $resultado[$key]["FechaIngreso"] = ($value["FechaIngreso"] == "") ? "" : convertirFechaES($value["FechaIngreso"]);
        $resultado[$key]["IndicadorEstadoEmpleado"] = ($value["EstadoEmpleado"] == 0) ? false : true;
      }
      return $resultado;
    }
  }

  function ObtenerUrlCarpetaImagenes()
  {
    $data['IdParametroSistema'] = ID_URL_CARPETA_IMAGENES_EMPLEADO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function DarBajaEmpleado($data)
  {
    $dataEmpleado["IdPersona"] = $data["IdPersona"];
    $dataEmpleado["IdEmpleado"] = $data["IdEmpleado"];
    $dataEmpleado["IndicadorEstado"] = ESTADO_INACTIVO;
    $resultado = $this->mEmpleado->ActualizarEmpleado($dataEmpleado);
    $resultado = $this->mPersona->ActualizarPersona($dataEmpleado);
    return "";
  }

  function ReactivarEmpleado($data)
  {
    $dataEmpleado["IdPersona"] = $data["IdPersona"];
    $dataEmpleado["IdEmpleado"] = $data["IdEmpleado"];
    $dataEmpleado["IndicadorEstado"] = ESTADO_ACTIVO;
    $resultado = $this->mEmpleado->ActualizarEmpleado($dataEmpleado);
    $resultado = $this->mPersona->ActualizarPersona($dataEmpleado);
    return "";
  }

  function ConsultarReniec($data)
  {
    $resultado = $this->consultadatosreniec->BuscarPorNumeroDocumentoIdentidad($data);
    return $resultado;
  }

  function ObtenerNumeroFilasPorPagina()
  {
    $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_EMPLEADO;
    $parametro = $this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
    $numerofilasporpagina = $parametro->ValorParametroSistema;
    return $numerofilasporpagina;
  }

  function ObtenerNumeroTotalEmpleados($data)
  {
    $resultado = $this->mEmpleado->ObtenerNumeroTotalEmpleados($data);
    return $resultado;
  }

  function PrepararDataJSONEmpleado()
  {
    $response = array();
    $empleados = $this->mEmpleado->ConsultarEmpleadoParaJSON();
    foreach ($empleados as $key => $value) {
      $nueva_fila = array(
        "IdEmpleado" => $value["IdEmpleado"],
        "IdPersona" => $value["IdPersona"],
        "Foto" => $value["Foto"],
        "NombrePersona" => $value["NombreCompleto"] . ' ' . $value["ApellidoCompleto"],
        "NumeroDocumentoIdentidad" => $value["NumeroDocumentoIdentidad"],
        "EstadoEmpleado" => $value["EstadoEmpleado"]
      );

      array_push($response, $nueva_fila);
    }

    return $response;
  }

  function CrearJSONEmpleadoTodos()
  {
    $url = DIR_ROOT_ASSETS . '/data/empleado/empleados.json';
    $data_json = $this->PrepararDataJSONEmpleado();

    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);
    return $resultado;
  }

  //PARA EL TRADADO DEL JSONH
  function PreparaDataFilaEmpleado($data)
  {
    $nueva_fila = array(
      "IdEmpleado" => $data["IdEmpleado"],
      "IdPersona" => $data["IdPersona"],
      "Foto" => $data["Foto"],
      "NombrePersona" => $data["NombreCompleto"] . ' ' . $data["ApellidoCompleto"],
      "NumeroDocumentoIdentidad" => $data["NumeroDocumentoIdentidad"],
      "EstadoEmpleado" => $data["EstadoEmpleado"]
    );

    return $nueva_fila;
  }

  function InsertarJSONDesdeEmpleado($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/empleado/empleados.json';
    $nueva_fila = $this->PreparaDataFilaEmpleado($data);
    $resultado2 = $this->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $nueva_fila);
    return $resultado2;
  }

  function ActualizarJSONDesdeEmpleado($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/empleado/empleados.json';
    $nueva_fila = $this->PreparaDataFilaEmpleado($data);
    $resultado2 = $this->jsonconverter->ActualizarFilaEnArchivoJSON($url, $nueva_fila, "IdEmpleado");

    return $resultado2;
  }

  function BorrarJSONDesdeEmpleado($data)
  {
    $url = DIR_ROOT_ASSETS . '/data/empleado/empleados.json';
    $resultado = $this->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdEmpleado");

    return $resultado;
  }
}

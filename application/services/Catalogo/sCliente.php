<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sCliente extends MY_Service
{

  public $Cliente = array();
  public $Persona = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->helper("date");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('jsonconverter');
    $this->load->library('consultadatosreniec');
    $this->load->library('servicessearch');
    $this->load->library('herencia');
    $this->load->model('Catalogo/mCliente');
    $this->load->model('Catalogo/mPersona');
    $this->load->service('Configuracion/General/sGradoAlumno');
    $this->load->service("Configuracion/General/sTipoPersona");
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->service("Configuracion/Catalogo/sTipoDocumentoIdentidad");
    $this->load->service('Configuracion/Catalogo/sDireccionCliente');
    $this->load->service('Catalogo/sVehiculoCliente');
    $this->load->service('Catalogo/sPersona');
    $this->Cliente = $this->mCliente->Cliente;
    $this->Persona = $this->sPersona->Persona;
    $this->Cliente = $this->herencia->Heredar($this->Persona, $this->Cliente);
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $this->Cliente["Foto"] = "";
    $this->Cliente["ApellidoCompleto"] = "";
    $this->Cliente["NombreCompleto"] = "";
    $this->Cliente["NombreAbreviado"] = "";
    $this->Cliente["IdPersona"] = "";
    $this->Cliente["NombreTipoPersona"] = "";
    $this->Cliente["NombreGradoAlumno"] = "";
    $this->Cliente["CodigoDocumentoIdentidad"] = "";
    $this->Cliente["EstadoContribuyente"] = "";
    $this->Cliente["CondicionContribuyente"] = "";
    $this->Cliente["Celular"] = "";
    $this->Cliente["TelefonoFijo"] = "";
    $this->Cliente["IdRol"] = ID_ROL_CLIENTE;
    $this->Cliente["RazonSocial"] = "";
    $this->Cliente["Direccion"] = "";
    $this->Cliente["NombreComercial"] = "";
    $this->Cliente["RepresentanteLegal"] = "";
    $this->Cliente["Email"] = "";

    $this->Cliente["DireccionesCliente"] = array();
    $this->Cliente["DireccionesClienteBorrado"] = array();
    $this->Cliente["DireccionCliente"] = $this->sDireccionCliente->DireccionCliente;
    $this->Cliente["NuevaDireccionCliente"] = $this->sDireccionCliente->DireccionCliente;

    $this->Cliente["VehiculosCliente"] = array();
    $this->Cliente["VehiculoCliente"] = $this->sVehiculoCliente->Inicializar();
    $this->Cliente["NuevoVehiculoCliente"] = $this->sVehiculoCliente->Inicializar();
    $this->Cliente["IndicadorEstadoCliente"] = true;

    //PARA RESTAURANT
    $this->Cliente["IndicadorAfiliacionTarjeta"] = 0;
    $this->Cliente["FechaInicioAfiliacionTarjeta"] = $hoy;

    $intervaloTarjeta = INTERVALO_TIEMPO_TARJETA_SIETE;
    $this->Cliente["FechaFinAfiliacionTarjeta"] = date("d/m/Y", strtotime(convertToDate($hoy) . "+ " . $intervaloTarjeta . " year"));

    $TiposDocumentoIdentidad = $this->sTipoDocumentoIdentidad->ListarTiposDocumentoIdentidad();
    $TiposPersona = $this->sTipoPersona->ListarTiposPersona();
    $GradosAlumno = $this->sGradoAlumno->ListarGradosAlumno();
    $parametros = $this->ObtenerParametrosParaVista();
    $ImageURL = $this->ObtenerUrlCarpetaImagenes();

    $parametros["ParametroRestaurante"] = $this->sConstanteSistema->ObtenerParametroRestaurante();
    $parametros["ParametroMostrarAfiliacionTarjetaSiete"] = $this->sConstanteSistema->ObtenerParametroMostrarAfiliacionTarjetaSiete();
    $parametros["ParametroRubroLubricante"] = $this->sConstanteSistema->ObtenerParametroRubroLubricante();

    $data = array(
      'TiposDocumentoIdentidad' => $TiposDocumentoIdentidad,
      'TiposPersona' => $TiposPersona,
      'Parametros' => $parametros,
      'GradosAlumno' => $GradosAlumno,
      'ImageURL' => $ImageURL
    );

    $resultado = array_merge($this->Cliente, $data);

    $resultado["ClienteNuevo"] = $resultado;

    return $resultado;
  }

  function ObtenerNumeroFila()
  {
    $resultado = $this->mCliente->ObtenerNumeroFila();
    $total = $resultado[0]['NumeroFila'];
    return $total;
  }

  function ObtenerNumeroFilaPorConsultaCliente($data)
  {
    $resultado = $this->mCliente->ObtenerNumeroFilaPorConsultaCliente($data);
    $total = $resultado[0]['NumeroFila'];
    return $total;
  }

  function ObtenerUrlCarpetaImagenes()
  {
    $data['IdParametroSistema'] = ID_URL_CARPETA_IMAGENES_CLIENTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerNumeroPagina()
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_CLIENTE;
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

  function ObtenerNumeroFilasPorPagina()
  {
    $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_CLIENTE;
    $parametro = $this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
    $numerofilasporpagina = $parametro->ValorParametroSistema;
    return $numerofilasporpagina;
  }

  function ObtenerNumeroTotalClientes($data)
  {
    $resultado = $this->mCliente->ObtenerNumeroTotalClientes($data)[0]['cantidad'];
    return $resultado;
  }

  function ObtenerNumeroPaginaPorConsultaCliente($data)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_CLIENTE;
    $total = $this->ObtenerNumeroFilaPorConsultaCliente($data);
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

  function ListarClientes($pagina)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_CLIENTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      $inicio = ($pagina * $ValorParametroSistema) - $ValorParametroSistema;
      $resultado = $this->mCliente->ListarClientes($inicio, $ValorParametroSistema);
      foreach ($resultado as $key => $value) {
        $resultado[$key]['FechaNacimiento'] = convertirFechaES($value['FechaNacimiento']);
        $resultado[$key]["FechaFinAfiliacionTarjeta"] = convertirFechaES($value["FechaFinAfiliacionTarjeta"]);
        $resultado[$key]["FechaInicioAfiliacionTarjeta"] = convertirFechaES($value["FechaInicioAfiliacionTarjeta"]);
        $resultado[$key]["IndicadorEstadoCliente"] = ($value["EstadoCliente"] == 0) ? false : true;
        $resultado[$key]["DireccionesCliente"] = $this->sDireccionCliente->ConsultarDireccionesCliente($value);
        $resultado[$key]["DireccionesClienteBorrado"] = array();
        $resultado[$key]["VehiculosCliente"] = $this->sVehiculoCliente->ConsultarVehiculosClientePorIdCliente($value);
      }
      return ($resultado);
    }
  }

  function ValidarRazonSocial($data)
  {
    $razonsocial = strpos($data['RazonSocial'], '"');
    if (is_numeric($razonsocial)) {
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

  function ValidarDatosClientes($data)
  {
    $razonsocial = $this->ValidarRazonSocial($data);
    $direccion = $this->ValidarDireccion($data);
    if ($razonsocial != "") {
      return $razonsocial;
    } else if ($direccion != "") {
      return $direccion;
    } else {
      return "";
    }
  }

  function ValidarExistenciaNumeroDocumentoIdentidadParaInsertar($data)
  {
    if ($data["IdTipoDocumentoIdentidad"] == ID_TIPO_DOCUMENTO_IDENTIDAD_OTROS) {
      $resultado = array();
    } else {
      $resultado = $this->mCliente->ObtenerNumeroDocumentoIdentidadParaInsertar($data);
    }

    if (Count($resultado) > 0) {
      return "Este número de documento ya fue registrado.";
    } else {
      return "";
    }
  }

  function ValidarExistenciaNumeroDocumentoIdentidadParaActualizar($data)
  {

    if ($data["IdTipoDocumentoIdentidad"] == ID_TIPO_DOCUMENTO_IDENTIDAD_OTROS) {
      $resultado = array();
    } else {
      $resultado = $this->mCliente->ObtenerNumeroDocumentoIdentidadParaActualizar($data);
    }

    if (Count($resultado) > 0) {
      return "Este número de documento ya fue registrado.";
    } else {
      return "";
    }
  }

  //PARA TARJETA SIETE - RESTAURANT
  function ValidarAfiliacionTarjetaSiete($data)
  {
    if ($data["FechaFinAfiliacionTarjeta"] < $data["FechaInicioAfiliacionTarjeta"]) {
      return "La Fecha Final de Afiliacion no puede ser menor a la Fecha Inicial de Afiliacion.";
    } else {
      return "";
    }
  }

  function InsertarCliente($data)
  {
    try {
      $validaciondatos = $this->ValidarDatosClientes($data);
      $validacion = $this->ValidarExistenciaNumeroDocumentoIdentidadParaInsertar($data);

      $ParametroMostrarAfiliacionTarjetaSiete = $this->sConstanteSistema->ObtenerParametroMostrarAfiliacionTarjetaSiete();
      if ($ParametroMostrarAfiliacionTarjetaSiete == '1') {
        if (array_key_exists("FechaFinAfiliacionTarjeta", $data)) {
          $data["FechaFinAfiliacionTarjeta"] = convertToDate($data["FechaFinAfiliacionTarjeta"]);
        }

        if (array_key_exists("FechaInicioAfiliacionTarjeta", $data)) {
          $data["FechaInicioAfiliacionTarjeta"] = convertToDate($data["FechaInicioAfiliacionTarjeta"]);
        }
      }

      $resultadoAfiliacion = ($ParametroMostrarAfiliacionTarjetaSiete == '1') ? $this->ValidarAfiliacionTarjetaSiete($data) : "";

      if ($validacion != "") {
        return $validacion;
      } else if ($validaciondatos != "") {
        return $validaciondatos;
      } else if ($resultadoAfiliacion != "") {
        return $resultadoAfiliacion;
      } else {
        $data["FechaNacimiento"] = convertToDate($data['FechaNacimiento']);
        switch ($data["IdTipoPersona"]) {
          case ID_TIPO_PERSONA_NATURAL:
            $resultadoPersona = $this->sPersona->InsertarPersonaComoPersonaNatural($data);
            break;
          case ID_TIPO_PERSONA_JURIDICA:
            $resultadoPersona = $this->sPersona->InsertarPersonaComoPersonaJuridica($data);
            break;
          case ID_TIPO_PERSONA_NO_DOMICILIADO:
            $resultadoPersona = $this->sPersona->InsertarPersonaComoNoDomiciliado($data);
            break;
        }

        if (!is_array($resultadoPersona))
          return $resultadoPersona;
        else {
          $data["IdPersona"] = $resultadoPersona["IdPersona"];

          if (array_key_exists("IndicadorAfiliacionTarjeta", $data)) {
            $resultadoPersona["IndicadorAfiliacionTarjeta"] = $data["IndicadorAfiliacionTarjeta"];
          } else {
            $resultadoPersona["IndicadorAfiliacionTarjeta"] = 0;
          }

          if (array_key_exists("FechaInicioAfiliacionTarjeta", $data)) {
            $resultadoPersona["FechaInicioAfiliacionTarjeta"] = $data["FechaInicioAfiliacionTarjeta"];
          } else {
            $resultadoPersona["FechaInicioAfiliacionTarjeta"] = "01-01-1900";
          }
          if (array_key_exists("FechaFinAfiliacionTarjeta", $data)) {
            $resultadoPersona["FechaFinAfiliacionTarjeta"] = $data["FechaFinAfiliacionTarjeta"];
          } else {
            $resultadoPersona["FechaFinAfiliacionTarjeta"] = "01-01-1900";
          }


          $resultadoPersona["NombreZona"] = $data["NombreZona"];
          $resultadoPersona["EstadoCliente"] = ($data["IndicadorEstadoCliente"] == true) ? "1" : "0";
          $resultado = $this->mCliente->InsertarCliente($resultadoPersona);
          $resultadoPersona['FechaNacimiento'] = convertirFechaES($resultadoPersona['FechaNacimiento']);

          if(!array_key_exists("DireccionesCliente", $data)) {    
            if ($data["Direccion"]!="") {
              $dataDireccionCliente["Direccion"] =$data["Direccion"];
              $dataDireccionCliente["IdCliente"] =$resultadoPersona["IdPersona"];
              $data["DireccionesCliente"][0] =$this->CrearNuevaDireccionCliente($dataDireccionCliente);
            }
          }
            
          $resultadoDirecciones = $this->sDireccionCliente->InsertarDireccionesCliente($data);

          if ($resultadoDirecciones != "") {
            return $resultadoDirecciones; //throw new Exception(nl2br($resultadoDirecciones));
          }

          $resultadoPersona["VehiculosCliente"] = (array_key_exists("VehiculosCliente", $data)) ? $data["VehiculosCliente"] : array();
          $resultadoPersona["VehiculosCliente"] = $this->sVehiculoCliente->AgregarVehiculosClientes($data);

          return $resultadoPersona;
        }
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function ActualizarCliente($data)
  {
    try {
      $validaciondatos = $this->ValidarDatosClientes($data);
      $validacion = $this->ValidarExistenciaNumeroDocumentoIdentidadParaActualizar($data);

      $ParametroMostrarAfiliacionTarjetaSiete = $this->sConstanteSistema->ObtenerParametroMostrarAfiliacionTarjetaSiete();
      if ($ParametroMostrarAfiliacionTarjetaSiete == '1') {
        if (array_key_exists("FechaFinAfiliacionTarjeta", $data)) {
          $data["FechaFinAfiliacionTarjeta"] = convertToDate($data["FechaFinAfiliacionTarjeta"]);
        }

        if (array_key_exists("FechaInicioAfiliacionTarjeta", $data)) {
          $data["FechaInicioAfiliacionTarjeta"] = convertToDate($data["FechaInicioAfiliacionTarjeta"]);
        }
      }

      $resultadoAfiliacion = ($ParametroMostrarAfiliacionTarjetaSiete == '1') ? $this->ValidarAfiliacionTarjetaSiete($data) : "";
      if ($validacion != "") {
        return $validacion;
      } else if ($validaciondatos != "") {
        return $validaciondatos;
      } else if ($resultadoAfiliacion != "") {
        return $resultadoAfiliacion;
      } else {
        $data["FechaNacimiento"] = convertToDate($data['FechaNacimiento']);
        switch ($data["IdTipoPersona"]) {
          case ID_TIPO_PERSONA_NATURAL:
            $resultadoPersona = $this->sPersona->ActualizarPersonaComoPersonaNatural($data);
            break;
          case ID_TIPO_PERSONA_JURIDICA:
            $resultadoPersona = $this->sPersona->ActualizarPersonaComoPersonaJuridica($data);
            break;
          case ID_TIPO_PERSONA_NO_DOMICILIADO:
            $resultadoPersona = $this->sPersona->ActualizarPersonaComoNoDomiciliado($data);
            break;
        }

        if (!is_array($resultadoPersona))
          return $resultadoPersona;
        else {
          $resultado = $this->mCliente->ActualizarCliente($data); //ESPACIOS PARA DIRECCIONES CLIENTE
          $resultadoDirecciones = $this->sDireccionCliente->ActualizarDireccionesCliente($data);
          if ($resultadoDirecciones != "") { //throw new Exception(nl2br($resultadoDirecciones));
            return $resultadoDirecciones; //throw new Exception(nl2br($resultadoDirecciones));
          }

          $data["VehiculosCliente"] = (array_key_exists("VehiculosCliente", $data)) ? $data["VehiculosCliente"] : array();
          $data["VehiculosCliente"] = $this->sVehiculoCliente->AgregarVehiculosClientes($data);

          return $data;
        }
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode(), $e);
    }
  }

  function ActualizarEmailCliente($data)
  {
    $resultadoPersona = $this->sPersona->ActualizarEmailPersona($data);
    return $resultadoPersona;
  }

  function ValidarExistenciaPersonaEnComprobanteVenta($data)
  {
    $resultado = $this->mPersona->ConsultarClienteEnComprobanteVenta($data);
    $contador = count($resultado);
    if ($contador > 0) {
      return "No se puede eliminar porque tiene registros en comprobante de venta";
    } else {
      return "";
    }
  }

  function BorrarCliente($data)
  {
    $existencia = $this->ValidarExistenciaPersonaEnComprobanteVenta($data);
    if ($existencia != "") {
      return $existencia;
    } else {
      $input["IdPersona"] = $data["IdPersona"];
      $resultado = $this->sPersona->BorrarPersona($input);
      return $resultado;
    }
  }

  function ConsultarClientes($data, $pagina)
  {
    $data['IdParametroSistema'] = ID_NUM_POR_PAGINA_CLIENTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      $inicio = ($pagina * $ValorParametroSistema) - $ValorParametroSistema;
      $resultado = $this->mCliente->ConsultarClientes($inicio, $ValorParametroSistema, $data);
      foreach ($resultado as $key => $value) {
        $resultado[$key]['FechaNacimiento'] = convertirFechaES($value['FechaNacimiento']);
        $resultado[$key]["FechaFinAfiliacionTarjeta"] = convertirFechaES($value["FechaFinAfiliacionTarjeta"]);
        $resultado[$key]["FechaInicioAfiliacionTarjeta"] = convertirFechaES($value["FechaInicioAfiliacionTarjeta"]);
        $resultado[$key]["IndicadorEstadoCliente"] = ($value["EstadoCliente"] == 0) ? false : true;
        $resultado[$key]["DireccionesCliente"] = $this->sDireccionCliente->ConsultarDireccionesCliente($value);
        $resultado[$key]["DireccionesClienteBorrado"] = array();
        $resultado[$key]["VehiculosCliente"] = $this->sVehiculoCliente->ConsultarVehiculosClientePorIdCliente($value);
      }
      return $resultado;
    }
  }

  function ConsultarSugerenciaClientesPorRuc($data)
  {
    $resultado = $this->mCliente->ConsultarSugerenciaClientesPorRuc($data);
    $persona = [];
    foreach ($resultado as $item) {
      $persona[] = $item["NumeroDocumentoIdentidad"];
    }
    return $persona;
  }

  function ConsultarClientesPorIdPersona($data)
  {
    $resultado = $this->mCliente->ConsultarClientesPorIdPersona($data);
    return $resultado;
  }

  function ObtenerClientePorIdPersona($data)
  {
    $resultado = $this->mCliente->ObtenerClientePorIdPersona($data);
    return $resultado;
  }

  function ConsultarSunat($data)
  {
    $number = $data["NumeroDocumentoIdentidad"];
    $resultado = $this->servicessearch->ruc($number);
    return $resultado;
  }

  function ConsultarReniec($data)
  {
    $resultado = $this->consultadatosreniec->BuscarPorNumeroDocumentoIdentidad($data);
    return $resultado;
  }

  //PARA EL TRADADO DEL JSONH
  function PreparaDataFilaCliente($data)
  {
    $direcciones = $this->sDireccionCliente->ConsultarDireccionesClienteParaJSON($data);
    $vehiculos = $this->sVehiculoCliente->ConsultarVehiculosClientePorIdCliente($data);
    $nueva_fila = array(
      "IdPersona" => $data["IdPersona"],
      "CodigoDocumentoIdentidad" => $data["CodigoDocumentoIdentidad"],
      "NumeroDocumentoIdentidad" => $data["NumeroDocumentoIdentidad"],
      "RazonSocial" => $data["RazonSocial"],
      "Direccion" => $data["Direccion"],
      "Email" => $data["Email"],
      "Celular" => $data["Celular"],
      "NombreGradoAlumno" => $data["NombreGradoAlumno"],
      "IdTipoPersona" => $data["IdTipoPersona"],
      "IndicadorAfiliacionTarjeta" => $data["IndicadorAfiliacionTarjeta"],
      "FechaInicioAfiliacionTarjeta" => $data["FechaInicioAfiliacionTarjeta"],
      "FechaFinAfiliacionTarjeta" => $data["FechaFinAfiliacionTarjeta"],
      "DireccionesCliente" => $direcciones,
      "Vehiculos" => $vehiculos,
      "EstadoCliente" => $data["EstadoCliente"]
    );

    return $nueva_fila;
  }

  
  function PrepararDataJSONCliente()
  {
    $response = array();
    $clientes = $this->mCliente->ConsultarClienteParaJSON();
    foreach ($clientes as $key => $value) {
      $nueva_fila = $this->PreparaDataFilaCliente($value);
      array_push($response, $nueva_fila);
    }

    return $response;
  }

 
  function CrearJSONClienteTodos() {
   
    $url = DIR_ROOT_ASSETS . '/data/cliente/clientes.json';
    $data_json = $this->PrepararDataJSONCliente();    
    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);
    return $resultado;
  }
  

  function InsertarJSONDesdeCliente($data) {
    $cliente =  $this->mCliente->ConsultarClienteParaJSONPorIdCliente($data);
    $url = DIR_ROOT_ASSETS . '/data/cliente/clientes.json';
    $nueva_fila = $this->PreparaDataFilaCliente($cliente);
    $resultado2 = $this->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $nueva_fila);

    return $resultado2;    
  }

  function ActualizarJSONDesdeCliente($data) {
    $cliente =  $this->mCliente->ConsultarClienteParaJSONPorIdCliente($data);
    $url = DIR_ROOT_ASSETS . '/data/cliente/clientes.json';
    $nueva_fila = $this->PreparaDataFilaCliente($cliente);
    $resultado2 = $this->jsonconverter->ActualizarFilaEnArchivoJSON($url, $nueva_fila, "IdPersona");
    return $resultado2;
  }

  function BorrarJSONDesdeCliente($data) {    
    $url = DIR_ROOT_ASSETS . '/data/cliente/clientes.json';
    $resultado = $this->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdPersona");
    return $resultado;   
  }

  function ObtenerParametrosParaVista()
  {
    $data['IdGrupoParametro'] = ID_GRUPO_PARAMTRO_VISTA_CLIENTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupo($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $response = array();
      foreach ($resultado as $key => $value) {
        $response[$value->NombreParametroSistema] = $value->ValorParametroSistema;
      }
      return $response;
    }
  }

  function ValidarDataJSONClienteRucRazonSocial($data)
  {
    $razonsocial = $data['RazonSocial'];
    $response = array();
    //buscamos codigo en la base principal
    $cliente = (array) $this->mCliente->ConsultarRucEnVentasJSON($data);
    // print_r($cliente);
    // print_r($data);exit;
    //comparamos si hay o no
    $response["Codigo"] = 3;
    if (!empty($cliente)) {
      // El producto existe
      //Mismo Codigo - buscamos nombre producto en la base principal
      // $cliente = $this->mCliente->ConsultarRazonSocialEnVentasJSON($data);
      if ($cliente['RazonSocial'] == $razonsocial) {
      } else {
        //MODIFICADO - CUANDO EL RAZON SOCIAL ES IGUAL
        $response["Codigo"] = 2;
        $cliente["CodigoEstado"] = 2;
        $response["Data"] = $cliente;
      }
    } else {
      //no se encontro - buscamos nombre producto en la base principal
      $cliente = (array) $this->mCliente->ConsultarRazonSocialEnVentasJSON($data);
      // print_r($cliente);exit;
      if (empty($cliente)) {
        //NUEVO
        $response["Codigo"] = 0;
      } else {
        //MODIFICADO - CUANDO LA RUC ES IGUAL
        $response["Codigo"] = 1;
        $cliente["CodigoEstado"] = 1;
        $response["Data"] = $cliente;
      }
    }
    return $response;
  }

  function ValidarDataJSONCliente($data)
  {
    $razonsocial = $data['RazonSocial'];
    $response = array();
    //buscamos codigo en la base principal
    $cliente = (array) $this->mCliente->ConsultarRucEnVentasJSON($data);
    // print_r($cliente);
    // print_r($data);exit;
    //comparamos si hay o no
    $response["Codigo"] = 3;
    if (!empty($cliente)) {
      // El producto existe
      //Mismo Codigo - buscamos nombre producto en la base principal
      // $cliente = $this->mCliente->ConsultarRazonSocialEnVentasJSON($data);
    } else {
      $response["Codigo"] = 0;
    }
    return $response;
  }

  function ConsultarClienteEnVentasJSON($data)
  {
    $resultado = $this->mCliente->ConsultarClienteEnVentasJSON($data);
    return $resultado;
  }

  //PARA EXPORTAR DATOS
  function ConsultarClienteParaJSONExportacion($data)
  {
    $data["IdPersona"] = $data["IdCliente"];
    $resultado = $this->mCliente->ConsultarClienteParaJSONExportacion($data);
    return $resultado;
  }

  function ConsultarClientesEnVentasJSON($data)
  {
    $resultado = $this->mCliente->ConsultarClientesEnVentasJSON($data);
    return $resultado;
  }

  function ObtenerDetalleCliente($data)
  {
    //$resultado[$key]['FechaNacimiento'] = convertirFechaES($data['FechaNacimiento']);
    //$resultado[$key]["FechaFinAfiliacionTarjeta"] = convertirFechaES($value["FechaFinAfiliacionTarjeta"]);
    //$resultado[$key]["FechaInicioAfiliacionTarjeta"] = convertirFechaES($value["FechaInicioAfiliacionTarjeta"]);
    $resultado["DireccionesCliente"] = $this->sDireccionCliente->ConsultarDireccionesCliente($data);
    $resultado["DireccionesClienteBorrado"] = array();
    $resultado["VehiculosCliente"] = $this->sVehiculoCliente->ConsultarVehiculosClientePorIdCliente($data);
    //}
    return $resultado;
    //}
  }

  function CrearNuevaDireccionCliente($data = null) {
    $dataNuevaDireccionCliente = $this->sDireccionCliente->NuevaDireccionCliente();
    if ($data != null) {
      $resultado = array_merge($dataNuevaDireccionCliente,$data);
    }
    else {
      $resultado = $dataNuevaDireccionCliente;
    }

    return $resultado;
  }

  function ObtenerClientePorIdCliente($data) {
    $dataCliente = $this->mCliente->ObtenerClientePorIdCliente($data);

    if (count($dataCliente)>0) {
      $dataCliente[0]["DireccionesCliente"] = $this->sDireccionCliente->ConsultarDireccionesClientePorIdCliente($data);
      $dataCliente[0]["DireccionesClienteBorrado"] = array();
      $dataCliente[0]["VehiculosCliente"] = $this->sVehiculoCliente->ConsultarVehiculosClientePorIdCliente($dataCliente[0]);               
      return $dataCliente[0];
    }
    else {
      return "";
    }
  }

  function ModificarCliente($data) {
    $dataCliente=$this->ObtenerClientePorIdCliente($data);
    
    if(is_array($dataCliente)) {
      $dataClienteModificado = array_merge($dataCliente,$data);      
     
      $resultado =$this->ActualizarCliente($dataClienteModificado);
      $dataJSON = $data;
      $dataJSON["IdPersona"] = $data["IdCliente"];
      $this->ActualizarJSONDesdeCliente($dataJSON);
      return $resultado;
    }
    else 
      return "";

  }
}

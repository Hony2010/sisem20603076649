<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTransportista extends MY_Service {

  public $Transportista = array();
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
    $this->load->model('Catalogo/mTransportista');
    $this->load->model('Catalogo/mPersona');
    $this->load->service("Configuracion/General/sTipoPersona");
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->service("Configuracion/Catalogo/sTipoDocumentoIdentidad");
    $this->load->service('Catalogo/sPersona');
    $this->Transportista = $this->mTransportista->Transportista;
    $this->Persona = $this->sPersona->Persona;
    $this->Transportista = $this->herencia->Heredar($this->Persona,$this->Transportista);
  }

  function Cargar()
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $this->Transportista["Foto"] = "";
    $this->Transportista["ApellidoCompleto"] = "";
    $this->Transportista["NombreCompleto"] = "";
    $this->Transportista["NombreAbreviado"] = "";
    $this->Transportista["IdPersona"] = "";
    $this->Transportista["NombreTipoPersona"] = "";
    $this->Transportista["CodigoDocumentoIdentidad"] = "";
    $this->Transportista["EstadoContribuyente"] = "";
    $this->Transportista["CondicionContribuyente"] = "";
    $this->Transportista["Celular"] = "";
    $this->Transportista["TelefonoFijo"] = "";
    $this->Transportista["IdRol"] = ID_ROL_CLIENTE;
    $this->Transportista["RazonSocial"] = "";
    $this->Transportista["Direccion"] = "";
    $this->Transportista["NombreComercial"] = "";
    $this->Transportista["RepresentanteLegal"] = "";
    $this->Transportista["Email"] = "";
    $this->Transportista["EstadoTransportista"] = true;

    $TiposDocumentoIdentidad = $this->sTipoDocumentoIdentidad->ListarTiposDocumentoIdentidad();
    $TiposPersona = $this->sTipoPersona->ListarTiposPersona();
    // $parametros = $this->ObtenerParametrosParaVista();
    $ImageURL = $this->ObtenerUrlCarpetaImagenes();

    $data = array(
      'TiposDocumentoIdentidad'=>$TiposDocumentoIdentidad,
      'TiposPersona'=>$TiposPersona,
      // 'Parametros'=>$parametros,
      'ImageURL' =>$ImageURL
    );

    $resultado = array_merge($this->Transportista,$data);

    $resultado["TransportistaNuevo"] = $resultado;
    
    return $resultado;
  }

  function ObtenerNumeroFila()
  {
    $resultado=$this->mTransportista->ObtenerNumeroFila();
    $total=$resultado[0]['NumeroFila'];
    return $total;
  }

  function ObtenerNumeroFilaPorConsultaTransportista($data)
  {
    $resultado=$this->mTransportista->ObtenerNumeroFilaPorConsultaTransportista($data);
    $total=$resultado[0]['NumeroFila'];
    return $total;
  }

  function ObtenerUrlCarpetaImagenes()
  {
    $data['IdParametroSistema']= ID_URL_CARPETA_IMAGENES_CLIENTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerNumeroPagina()
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_CLIENTE;
    $total = $this->ObtenerNumeroFila();
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      if (($total%$ValorParametroSistema)>0)
      {
        $numeropagina = ($total/$ValorParametroSistema)+1;
        return intval($numeropagina);
      }
      else
      {
        $numeropagina = ($total/$ValorParametroSistema);
        return intval($numeropagina);
      }
    }
  }

  function ObtenerNumeroFilasPorPagina()
  {
    $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_CLIENTE;
    $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
    $numerofilasporpagina=$parametro->ValorParametroSistema;
    return $numerofilasporpagina;
  }

  function ObtenerNumeroTotalTransportistas($data)
  {
      $resultado = $this->mTransportista->ObtenerNumeroTotalTransportistas($data)[0]['cantidad'];
      return $resultado;
  }

  function ObtenerNumeroPaginaPorConsultaTransportista($data)
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_CLIENTE;
    $total = $this->ObtenerNumeroFilaPorConsultaTransportista($data);
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      if (($total%$ValorParametroSistema)>0)
      {
        $numeropagina = ($total/$ValorParametroSistema)+1;
        return intval($numeropagina);
      }
      else
      {
        $numeropagina = ($total/$ValorParametroSistema);
        return intval($numeropagina);
      }
    }
  }

  function ListarTransportistas($pagina)
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_CLIENTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;
      $resultado = $this->mTransportista->ListarTransportistas($inicio,$ValorParametroSistema);
      foreach ($resultado as $key => $value) {
        $resultado[$key]["EstadoTransportista"] = ($resultado[$key]["EstadoTransportista"] =="1") ? true : false;
        $resultado[$key]['FechaNacimiento'] = convertirFechaES($value['FechaNacimiento']);
      }
      return($resultado);
    }
  }

  function ValidarRazonSocial($data)
  {
    $razonsocial = strpos($data['RazonSocial'], '"');
    if (is_numeric($razonsocial)) {
      return "No se puedes utilizar comillas dobles, porfavor utilizar comillas simples";
    }
    else {
      return "";
    }
  }

  function ValidarDireccion($data)
  {
    $direccion = strpos($data['Direccion'], '"');
    if (is_numeric($direccion)) {
      return "No se puedes utilizar comillas dobles, porfavor utilizar comillas simples";
    }
    else {
      return "";
    }
  }

  function ValidarDatosTransportistas($data)
  {
    $razonsocial = $this->ValidarRazonSocial($data);
    $direccion = $this->ValidarDireccion($data);
    if ($razonsocial != "") {
      return $razonsocial;
    }
    else if ($direccion != "") {
      return $direccion;
    }
    else {
      return "";
    }
  }

  function ValidarExistenciaNumeroDocumentoIdentidadParaInsertar($data)
  {
    if ($data["IdTipoDocumentoIdentidad"] == ID_TIPO_DOCUMENTO_IDENTIDAD_OTROS) {
      $resultado = array();
    } else {
      $resultado = $this->mTransportista->ObtenerNumeroDocumentoIdentidadParaInsertar($data);
    }

    if (count($resultado)>0)
    {
      return "Este número de documento ya fue registrado.";
    }
    else
    {
      return "";
    }
  }

  function ValidarExistenciaNumeroDocumentoIdentidadParaActualizar($data)
  {

    if ($data["IdTipoDocumentoIdentidad"] == ID_TIPO_DOCUMENTO_IDENTIDAD_OTROS) {
      $resultado = array();
    } else {
      $resultado = $this->mTransportista->ObtenerNumeroDocumentoIdentidadParaActualizar($data);
    }

    if (count($resultado)>0)
    {
      return "Este número de documento ya fue registrado.";
    }
    else
    {
      return "";
    }
  }

  function InsertarTransportista($data)
  {
    $validaciondatos = $this->ValidarDatosTransportistas($data);
    $validacion = $this->ValidarExistenciaNumeroDocumentoIdentidadParaInsertar($data);

    if ($validacion != "") {
      return $validacion;
    }
    else if ($validaciondatos != "") {
      return $validaciondatos;
    }
    else {
      $data["FechaNacimiento"] = convertToDate($data['FechaNacimiento']);
      switch($data["IdTipoPersona"])
      {
        case ID_TIPO_PERSONA_NATURAL: $resultadoPersona=$this->sPersona->InsertarPersonaComoPersonaNatural($data); break;
        case ID_TIPO_PERSONA_JURIDICA: $resultadoPersona=$this->sPersona->InsertarPersonaComoPersonaJuridica($data); break;
        case ID_TIPO_PERSONA_NO_DOMICILIADO: $resultadoPersona=$this->sPersona->InsertarPersonaComoNoDomiciliado($data); break;
      }

      if (!is_array($resultadoPersona))
        return $resultadoPersona;
      else
      {
        $resultadoPersona["IdTransportista"] = $resultadoPersona["IdPersona"];
        $resultadoPersona["NumeroConstanciaInscripcion"] = $data["NumeroConstanciaInscripcion"];
        $resultadoPersona["NumeroLicenciaConducir"] = $data["NumeroLicenciaConducir"];
        $resultadoPersona["EstadoTransportista"] = ($data["EstadoTransportista"] == true) ? "1" : "0";
        $resultado = $this->mTransportista->InsertarTransportista($resultadoPersona);
        $resultadoPersona['FechaNacimiento'] = convertirFechaES($resultadoPersona['FechaNacimiento']);

        return $resultadoPersona;
      }
    }
  }

  function ActualizarTransportista($data)
  {
    $validaciondatos = $this->ValidarDatosTransportistas($data);
    $validacion = $this->ValidarExistenciaNumeroDocumentoIdentidadParaActualizar($data);

    if ($validacion!="") {
      return $validacion;
    }
    else if($validaciondatos != "") {
      return $validaciondatos;
    }
    else {
      $data["FechaNacimiento"] = convertToDate($data['FechaNacimiento']);
      switch($data["IdTipoPersona"])
      {
        case ID_TIPO_PERSONA_NATURAL: $resultadoPersona=$this->sPersona->ActualizarPersonaComoPersonaNatural($data); break;
        case ID_TIPO_PERSONA_JURIDICA: $resultadoPersona=$this->sPersona->ActualizarPersonaComoPersonaJuridica($data); break;
        case ID_TIPO_PERSONA_NO_DOMICILIADO: $resultadoPersona=$this->sPersona->ActualizarPersonaComoNoDomiciliado($data); break;
      }
      
      if (!is_array($resultadoPersona))
        return $resultadoPersona;
      else {
        $resultado = $this->mTransportista->ActualizarTransportista($data);
        return $data;  
      }      
    }
  }

  // function ActualizarEmailTransportista($data)
  // {
  //   $resultadoPersona = $this->sPersona->ActualizarEmailPersona($data);
  //   return $resultadoPersona;
  // }

  // function ValidarExistenciaPersonaEnComprobanteVenta($data)
  // {
  //   $resultado = $this->mPersona->ConsultarTransportistaEnComprobanteVenta($data);
  //   $contador = count($resultado);
  //   if ($contador > 0)
  //   {
  //     return "No se puede eliminar porque tiene registros en comprobante de venta";
  //   }
  //   else
  //   {
  //     return "";
  //   }
  // }

  function BorrarTransportista($data)
  {
    $existencia = "";//$this->ValidarExistenciaPersonaEnComprobanteVenta($data);
    if ($existencia != "")
    {
      return $existencia;
    }
    else {
      $input["IdPersona"] = $data["IdPersona"];
      $input["IdTransportista"] = $data["IdPersona"];
      $resultado = $this->mTransportista->BorrarTransportista($input);

      $resultado = $this->sPersona->BorrarPersona($input);
      return $resultado;
    }
  }

  function ConsultarTransportistas($data,$pagina)
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_CLIENTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
        $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
        $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;
        $resultado=$this->mTransportista->ConsultarTransportistas($inicio,$ValorParametroSistema,$data);
        foreach ($resultado as $key => $value) {
          $resultado[$key]['FechaNacimiento'] = convertirFechaES($value['FechaNacimiento']);
        }
        return $resultado;
    }
  }

  function ConsultarSugerenciaTransportistasPorRuc($data)
  {
    $resultado=$this->mTransportista->ConsultarSugerenciaTransportistasPorRuc($data);
    $persona = [];
    foreach ($resultado as $item )
    {
      $persona[] = $item["NumeroDocumentoIdentidad"];
    }
    return $persona;
  }

  // function ConsultarTransportistasPorIdPersona($data)
  // {
  //   $resultado=$this->mTransportista->ConsultarTransportistasPorIdPersona($data);
  //   return $resultado;
  // }

  // function ObtenerTransportistaPorIdPersona($data)
  // {
  //   $resultado = $this->mTransportista->ObtenerTransportistaPorIdPersona($data);
  //   return $resultado;
  // }

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

  function PrepararDataJSONTransportista()
  {
    $response = array();
    $transportistas = $this->mTransportista->ConsultarTransportistaParaJSON();
    foreach ($transportistas as $key => $value) {
      $nueva_fila = $this->PreparaDataFilaTransportista($value);
      array_push($response, $nueva_fila);
    }

    return $response;
  }

  function CrearJSONTransportistaTodos()
  {
    $url = DIR_ROOT_ASSETS.'/data/transportista/transportistas.json';
    $data_json = $this->PrepararDataJSONTransportista();

    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);
    return $resultado;
  }

  //PARA EL TRADADO DEL JSONH
  function PreparaDataFilaTransportista($data)
  {
    $nueva_fila = Array (
      "IdPersona" => $data["IdPersona"],
      "CodigoDocumentoIdentidad" => $data["CodigoDocumentoIdentidad"],
      "NumeroDocumentoIdentidad" => $data["NumeroDocumentoIdentidad"],
      "RazonSocial" => $data["RazonSocial"],
      "Direccion" => $data["Direccion"],
      "Email" => $data["Email"],
      "Celular" => $data["Celular"],
      "IdTipoPersona" => $data["IdTipoPersona"],
      "NumeroConstanciaInscripcion" => $data["NumeroConstanciaInscripcion"],
      "NumeroLicenciaConducir" => $data["NumeroLicenciaConducir"],
      "EstadoTransportista" => $data["EstadoTransportista"]
    );

    return $nueva_fila;
  }

  function InsertarJSONDesdeTransportista($data)
  {
    $url = DIR_ROOT_ASSETS.'/data/transportista/transportistas.json';
    $nueva_fila = $this->PreparaDataFilaTransportista($data);
    $resultado2 = $this->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $nueva_fila);
    return $resultado2;
  }

  function ActualizarJSONDesdeTransportista($data)
  {
    $url = DIR_ROOT_ASSETS.'/data/transportista/transportistas.json';
    $nueva_fila = $this->PreparaDataFilaTransportista($data);
    $resultado2 = $this->jsonconverter->ActualizarFilaEnArchivoJSON($url, $nueva_fila, "IdPersona");

    return $resultado2;
  }

  function BorrarJSONDesdeTransportista($data)
  {
    $url = DIR_ROOT_ASSETS.'/data/transportista/transportistas.json';
    $resultado = $this->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdPersona");

    return $resultado;
  }

  // function ObtenerParametrosParaVista()
  // {
  //   $data['IdGrupoParametro']= ID_GRUPO_PARAMTRO_VISTA_CLIENTE;
  //   $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupo($data);
  //   if (is_string($resultado)) {
  //     return $resultado;
  //   }
  //   else {
  //     $response = array();
  //     foreach ($resultado as $key => $value) {
  //       $response[$value->NombreParametroSistema] = $value->ValorParametroSistema;
  //     }
  //     return $response;
  //   }
  // }

}

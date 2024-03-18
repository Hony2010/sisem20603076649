<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMercaderia extends MY_Service {

  public $Mercaderia = array();
  public $Producto = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->helper("date");
    $this->load->model('Catalogo/mMercaderia');
    $this->load->service("Configuracion/General/sUnidadMedida");
    $this->load->service("Configuracion/General/sTipoSistemaISC");
    $this->load->service("Configuracion/General/sTipoAfectacionIGV");
    $this->load->service("Configuracion/General/sTipoPrecio");
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->service("Configuracion/General/sOrigenMercaderia");
    $this->load->service("Configuracion/General/sAsignacionSede");    
    $this->load->service('Catalogo/sProducto');
    $this->load->service("Catalogo/sListaPrecioMercaderia");
    $this->load->service("Catalogo/sListaRaleoMercaderia");
    $this->load->service("Catalogo/sProductoProveedor");
    $this->load->service("Catalogo/sAnotacionPlatoProducto");
    $this->load->service("Inventario/sAlmacenProductoLote");
    $this->load->service("Inventario/sDocumentoSalidaZofraProducto");
    $this->load->service("Inventario/sDuaProducto");
    $this->load->service("Inventario/sAlmacenMercaderia");
    $this->load->service("Catalogo/sBonificacion");
    $this->load->library('reporter');
    $this->load->library('imprimir');
    
    $this->Mercaderia = $this->mMercaderia->Mercaderia;
    $this->Producto = $this->sProducto->Producto;
    $this->Mercaderia = $this->herencia->Heredar($this->Producto,$this->Mercaderia);
  }

  function ObtenerNumeroFila()
  {
    $resultado=$this->mMercaderia->ObtenerNumeroFila();
    $total=$resultado[0]['NumeroFila'];
    return $total;
  }

  function Cargar()
  {
    $UnidadesMedida = $this->sUnidadMedida->ListarUnidadesMedida();
    $TiposSistemaISC = $this->sTipoSistemaISC->ListarTiposSistemaISC();
    $TiposAfectacionIGV = $this->sTipoAfectacionIGV->ListarTiposAfectacionIGV();
    $TiposPrecio = $this->sTipoPrecio->ListarTiposPrecio();
    $OrigenMercaderia = $this->sOrigenMercaderia->ListarOrigenMercaderia();

    $this->Mercaderia["Color"] = "";
    $this->Mercaderia["Foto"] = "";
    $this->Mercaderia["CodigoBarras"] = "";
    $this->Mercaderia["SujetoPercepcionVenta"] = "0";
    $this->Mercaderia["IdMarca"] = 0;
    $this->Mercaderia["IdFamiliaProducto"] = 0;
    $this->Mercaderia["IdLineaProducto"] = LINEA_PRODUCTO;
    $this->Mercaderia["NombreFabricante"] = "";
    $this->Mercaderia["NombreFamiliaProducto"] = "";
    $this->Mercaderia["NombreLineaProducto"] = "";
    $this->Mercaderia["CodigoAutomatico"] = 0;
    $this->Mercaderia["IdUnidadMedida"] = ID_UNIDAD_MEDIDA_UNIDAD;
    $this->Mercaderia["IdMoneda"] = 1;
    $this->Mercaderia["IdTipoExistencia"] = 1;
    $this->Mercaderia["IdSubFamiliaProducto"] = 0;
    $this->Mercaderia["IdModelo"] = 0;
    $this->Mercaderia["IdTipoPrecio"] = ID_TIPO_PRECIO_UNITARIO;
    $this->Mercaderia["IdTipoSistemaISC"] = ID_TIPO_SISTEMA_ISC_NO_AFECTO;
    $this->Mercaderia["CodigoTipoSistemaISC"] = CODIGO_TIPO_SISTEMA_ISC_NO_AFECTO;
    $this->Mercaderia["IdTipoAfectacionIGV"] = ID_AFECTACION_IGV_GRAVADO;
    $this->Mercaderia["CodigoTipoAfectacionIGV"] = CODIGO_AFECTACION_IGV_GRAVADO;
    $this->Mercaderia["CodigoAutomatico"] = 0;
    $this->Mercaderia["CodigoTipoPrecio"] = "";
    $this->Mercaderia["BloquearTipoMercaderia"] = "0";
    $this->Mercaderia["IdTipoTributo"] = ID_TIPO_TRIBUTO_IGV;
    $this->Mercaderia["IdFabricante"] = ID_FABRICANTE_NO_ESPECIFICADO;
    $this->Mercaderia["IdOrigenMercaderia"] = ID_ORIGEN_MERCADERIA_GENERAL;
    $this->Mercaderia["ParametroCodigoBarras"] = $this->sConstanteSistema->ObtenerParametroCodigoBarras();

    //PARA TARJETA SIETE - RESTAURANT
    $this->Mercaderia["ParametroRestaurante"] = $this->sConstanteSistema->ObtenerParametroRestaurante();
    $this->Mercaderia["ParametroMostrarAfiliacionTarjetaSiete"] = $this->sConstanteSistema->ObtenerParametroMostrarAfiliacionTarjetaSiete();
    $this->Mercaderia["TipoDescuento"] = TIPO_DESCUENTO_PORCENTUAL;
    $this->Mercaderia["ValorDescuento"] = 0;

    //PARA ANOTACIONES PLATO
    $this->Mercaderia["AnotacionesPlatoProducto"] = $this->sAnotacionPlatoProducto->ListarAnotacionesPlatoProductoInicial();
    $this->Mercaderia["SeleccionarTodosAnotacionesPlato"] = false;
    $this->Mercaderia["TotalAnotacionesPlatoSeleccionados"] = 0;

    $this->Mercaderia["AfectoICBPER"] = 0; //IMPUESTO BOLSAS 0:NO AFECTA - 1:SI AFECTA
    $this->Mercaderia["IndicadorAfectoICBPER"] = false;

    $this->Mercaderia["NumeroDocumentoIdentidad"] = "";
    $this->Mercaderia["RazonSocial"] = "";
    $this->Mercaderia["ParametroRubroRepuesto"] = $this->sConstanteSistema->ObtenerParametroRubroRepuesto();
    $this->Mercaderia["CodigoAlterno"] = "";
    $this->Mercaderia["CodigoMercaderia2"] = "";
    $this->Mercaderia["Referencia"] = "";
    $this->Mercaderia["Aplicacion"] = "";
    $this->Mercaderia["NumeroMotor"] = "";
    $this->Mercaderia["ReferenciaProveedor"] = "";

    //BONIFICACIONES
    $this->Mercaderia["ParametroBonificacion"] = $this->sConstanteSistema->ObtenerParametroBonificacion();
    $this->Mercaderia["Bonificaciones"] = array();
    $this->Mercaderia["Bonificacion"] = $this->sBonificacion->Cargar();
    $this->Mercaderia["AfectoBonificacion"] = 0;
    $this->Mercaderia["CantidadBonificaciones"] = 0;
    $this->Mercaderia["IndicadorAfectoBonificacion"] = false;
    $this->Mercaderia["EstadoProducto"] = 1; //1: SE MUESTRA JSON | 0: NO SE MUESTRA JSON
    $this->Mercaderia["IndicadorEstadoProducto"] = true;
    $this->Mercaderia["IdMonedaCompra"] = ID_MONEDA_SOLES;
    $this->Mercaderia["ParametroCodigoBarrasAutomatico"] = $this->sConstanteSistema->ObtenerParametroCodigoBarraAutomatico();
    $this->Mercaderia["ParametroVistaPreviaImpresion"] =   $this->sesionusuario->obtener_sesion_indicador_vista_previa_impresion();

    // forma de calculo
    $this->Mercaderia["EstadoCampoCalculo"] = '0';

    $data = array(
      'UnidadesMedida'=>$UnidadesMedida,
      'TiposSistemaISC' =>$TiposSistemaISC,
      'OrigenMercaderia'=>$OrigenMercaderia,
      'TiposPrecio'=>$TiposPrecio,
      'TiposAfectacionIGV'=>$TiposAfectacionIGV
    );

    $resultado = array_merge($this->Mercaderia,$data);

    $resultado["MercaderiaNueva"] = $resultado;

    return $resultado;
  }

  function Nuevo() {
    $this->Mercaderia["CodigoMercaderia"] = "";
    $this->Mercaderia["CodigoMercaderia2"] = "";
    $this->Mercaderia["CodigoAlterno"] = "";
    $this->Mercaderia["IdTipoExistencia"] = 1;
    $this->Mercaderia["IdMarca"] = 0;
    $this->Mercaderia["IdModelo"] = 0;
    $this->Mercaderia["IdFamiliaProducto"] = 0;
    $this->Mercaderia["IdSubFamiliaProducto"] = 0;
    $this->Mercaderia["IdLineaProducto"] = LINEA_PRODUCTO;
    $this->Mercaderia["IdUnidadMedida"] = ID_UNIDAD_MEDIDA_UNIDAD;
    $this->Mercaderia["IdTipoSistemaISC"] = ID_TIPO_SISTEMA_ISC_NO_AFECTO;
    $this->Mercaderia["IdTipoAfectacionIGV"] = ID_AFECTACION_IGV_GRAVADO;
    $this->Mercaderia["IdTipoPrecio"] = ID_TIPO_PRECIO_UNITARIO;
    $this->Mercaderia["IdFabricante"] = ID_FABRICANTE_NO_ESPECIFICADO;
    $this->Mercaderia["IdMoneda"] = 1;
    $this->Mercaderia["IdTipoTributo"] = ID_TIPO_TRIBUTO_IGV;
    $this->Mercaderia["IdOrigenMercaderia"] = ID_ORIGEN_MERCADERIA_GENERAL;
    $this->Mercaderia["AfectoICBPER"] = 0; //IMPUESTO BOLSAS 0:NO AFECTA - 1:SI AFECTA
    $this->Mercaderia["Referencia"] = "";
    $this->Mercaderia["SaldoFisico"] = 0.00;
    $this->Mercaderia["Foto"] = "";
    $this->Mercaderia["NumeroMotor"] = "";
    $this->Mercaderia["Color"] = "";
    $this->Mercaderia["CodigoBarras"] = "";
    $this->Mercaderia["AfectoICBPER"] = 0; //IMPUESTO BOLSAS 0:NO AFECTA - 1:SI AFECTA
    $this->Mercaderia["SujetoPercepcionVenta"] = "0";
    $this->Mercaderia["TipoDescuento"] = TIPO_DESCUENTO_PORCENTUAL;
    $this->Mercaderia["ValorDescuento"] = 0;
    $this->Mercaderia["AfectoBonificacion"] = 0;

    $this->Mercaderia["EstadoProducto"] = 1; //1: SE MUESTRA JSON | 0: NO SE MUESTRA JSON
    $this->Mercaderia["IdMonedaCompra"] = ID_MONEDA_SOLES;
    $this->Mercaderia["ReferenciaProveedor"] = "";
    $this->Mercaderia["Aplicacion"] = "";
    $this->Mercaderia["CodigoCorrelativoAutomatico"] = 0;        
    $this->Mercaderia["CodigoAutomatico"] = 0;
    //$this->Mercaderia["NombreFabricante"] = "";
    //$this->Mercaderia["NombreFamiliaProducto"] = "";
    //$this->Mercaderia["NombreLineaProducto"] = "";    
    //$this->Mercaderia["CodigoTipoSistemaISC"] = CODIGO_TIPO_SISTEMA_ISC_NO_AFECTO;
    $this->Mercaderia["CodigoTipoAfectacionIGV"] = CODIGO_AFECTACION_IGV_GRAVADO;
    $this->Mercaderia["IndicadorCodigoPropio"] = "1";
    //$this->Mercaderia["CodigoTipoPrecio"] = "";
    //$this->Mercaderia["BloquearTipoMercaderia"] = "0";

    return $this->Mercaderia;
  }

  function ObtenerRangoPagina()
  {
    $data['IdParametroSistema']= ID_NUM_POR_RANGO_PAGINA_MERCADERIA;
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

  function ObtenerNumeroFilasPorPagina()
  {
    $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_MERCADERIA;
    $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
    $numerofilasporpagina=$parametro->ValorParametroSistema;
    return $numerofilasporpagina;
  }

  function ObtenerNumeroTotalMercaderias($data)
  {
    $parametroRubroRepuesto = $this->sConstanteSistema->ObtenerParametroRubroRepuesto();
    
    if($parametroRubroRepuesto == 1) { 
      $resultado = $this->mMercaderia->ObtenerNumeroTotalMercaderiasAvanzada($data)[0]["cantidad"];
    }
    else {
      $resultado = $this->mMercaderia->ObtenerNumeroTotalMercaderias($data)[0]["cantidad"];
    }
      
    return $resultado;
  }

  function ObtenerUrlCarpetaImagenes()
  {
    $data['IdParametroSistema']= ID_URL_CARPETA_IMAGENES;
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

  function ObtenerAtributosMercaderia()
  {
    $data['IdGrupoParametro']= ID_ATRIBUTO_MERCADERIA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupo($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      return $resultado;
    }
  }

  function ObtenerLinkDeBusqueda()
  {
    $data['IdGrupoParametro']= ID_LINK_DE_BUSQUEDA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupo($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      return $resultado;
    }
  }

  function ObtenerNumeroFilaPorConsultaMercaderia($data)
  {
    $resultado=$this->mMercaderia->ObtenerNumeroFilaPorConsultaMercaderia($data);
    $total=$resultado[0]['NumeroFila'];
    return $total;
  }

  function ObtenerNumeroPagina()
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_MERCADERIA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $total = $this->ObtenerNumeroFila();
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
  
  function ObtenerNumeroPaginaPorConsultaMercaderia($data)
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_MERCADERIA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $total = $this->ObtenerNumeroFilaPorConsultaMercaderia($data);
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

  function ListarMercaderias($pagina)
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_MERCADERIA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
        $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
        $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;
        $resultado = $this->mMercaderia->ListarMercaderias($inicio,$ValorParametroSistema);
        foreach ($resultado as $key => $value) {
          $validacion = $this->ValidarProductoEnMovimientoDuaOZofra($value);
          $resultado[$key]["BloquearTipoMercaderia"] = ($validacion == "") ? 0 : 1;
          $resultado[$key]["IndicadorAfectoICBPER"] = ($value["AfectoICBPER"] == 0) ? false : true;
          $resultado[$key]["IndicadorEstadoProducto"] = ($value["EstadoProducto"] == 0) ? false : true;
          $resultado[$key]["AnotacionesPlatoProducto"] = $this->sAnotacionPlatoProducto->ListarAnotacionesPlatoProductoPorProducto($value);
          $resultado[$key]["IndicadorAfectoBonificacion"] = ($value["AfectoBonificacion"] == 0) ? false : true;
          $Bonificaciones = $this->sBonificacion->ListarBonificacionesPorIdProducto($value);
          $resultado[$key]["CantidadBonificaciones"] = count($Bonificaciones);
          $resultado[$key]["Bonificaciones"] = array();
        }
        return $resultado ;
    }
  }

  function ListarBonificacionesPorIdProducto($data)
  {
    $resultado = $this->sBonificacion->ListarBonificacionesPorIdProducto($data);
    return $resultado;
  }

  function ValidarCodigoMercaderia($data)
  {
    $cantidad = $this->ObtenerParametroCantidadCodigo();
    $codigo=$data["CodigoMercaderia"];
    if ($codigo == "")
    {
      return "Debe ingresar el código de la Mercaderia";
    }
    else if (strlen($codigo)>$cantidad)
    {
      return "El código debe tener como máximo ".$cantidad." caracteres";
    }
    else
    {
      return "";
    }
  }

  function ValidarMercaderia($data)
  {
    $codigo = $this->ValidarCodigoMercaderia($data);
    $nombre = $this->sProducto->ValidarNombreProducto($data);
    if ($codigo != "")
    {
      return $codigo;
    }
    else if ($nombre != "")
    {
      return $nombre;
    }
    else
    {
        return "";
    }
  }

  function ValidarExistenciaCodigoMercaderiaParaInsertar($data) {
      $resultado = $this->mMercaderia->ObtenerCodigoMercaderiaParaInsertar($data);      
      //print_r($data);
      if (Count($resultado) > 0)
      {        
        return 'Este código "'.strtoupper($data["CodigoMercaderia"]).'" ya fue registrado';
      }
      else
      {
        return "";
      }       
  }


  function ValidarExistenciaCodigoMercaderiaParaActualizar($data)
  {
    $resultado = $this->mMercaderia->ObtenerCodigoMercaderiaParaActualizar($data);
    if (Count($resultado)>0)
    {
      return 'Este código "'.strtoupper($data["CodigoMercaderia"]).'" ya fue registrado';
    }
    else
    {
      return "";
    }
  }

  function ValidarExistenciaNombreProductoParaInsertar($data)
  {
    $comillasimple = (int) substr_count($data["NombreProducto"],"'");
    $comillasimple = $comillasimple%2;
    /*if($comillasimple != 0)
    {
      return "En Nombre del Producto no se puede utilizar comillas simples en cantidad impar."; 
    }*/

    $resultado = $this->mMercaderia->ObtenerNombreProductoParaInsertar($data);
    if (count($resultado)>0)
    {
      return 'El producto  "'.strtoupper($data["CodigoMercaderia"]).' - '.strtoupper($data["NombreProducto"]).'" ya fue registrado';
    }
    else
    {
      return "";
    }
  }

  function ValidarExistenciaNombreProductoParaActualizar($data)
  {
    $comillasimple = (int) substr_count($data["NombreProducto"],"'");
    $comillasimple = $comillasimple%2;
    /*if($comillasimple != 0)
    {
      return "En Nombre del Producto no se puede utilizar comillas simples en cantidad impar."; 
    }*/

    $resultado = $this->mMercaderia->ObtenerNombreProductoParaActualizar($data);
    if (count($resultado)>0)
    {
      return 'El producto  "'.strtoupper($data["CodigoMercaderia"]).' - '.strtoupper($data["NombreProducto"]).'" ya fue registrado';
    }
    else
    {
      return "";
    }
  }

  function ValidarProductoEnMovimientoDuaOZofra($data)
  {
    $resultado1 = $this->sDocumentoSalidaZofraProducto->ValidarProductoEnDocumentoSalidaZofraProductoParaMercaderia($data);
    $resultado2 = $this->sDuaProducto->ValidarProductoEnDuaProductoParaMercaderia($data);
    if ($resultado1>0 ||$resultado2>0)
    {
      return "El producto esta siendo usado.";
    }
    else {
      return "";
    }
  }

  //PARA TARJETA SIETE - RESTAURANT -
  function ValidarAfiliacionTarjetaSiete($data)
  {
    if ($data["TipoDescuento"] == "")
    {
      return "Debe Agregar un Tipo de Descuento.";
    }

    if (!is_numeric($data["ValorDescuento"]))
    {
      return "El Valor de Descuento debe ser numerico.";
    }

    if ($data["TipoDescuento"] == TIPO_DESCUENTO_PORCENTUAL)
    {
      return ($data["ValorDescuento"] >= 0 && $data["ValorDescuento"] <= 100) ? "" : "Debe insertar un Valor de Descuento entre 0 a 100.";
    }
    elseif ($data["TipoDescuento"] == TIPO_DESCUENTO_MONTO)
    {
      return ($data["ValorDescuento"] >= 0) ? "" : "Debe insertar un Valor de Descuento mayor o igual a 0.";
    }
    else {
      return "";
    }
  }

  function ObtenerTributoParaMercaderia($data)
  {
    $idtributo = "";
    switch ($data["CodigoTipoAfectacionIGV"]) {
      case '10':
        // code...
        $idtributo = ID_TIPO_TRIBUTO_IGV;
        break;
      case '20':
        // code...
        $idtributo = ID_TIPO_TRIBUTO_EXONERADO;
        break;
      case '30':
        // code...
        $idtributo = ID_TIPO_TRIBUTO_INAFECTO;
        break;
      case '40':
        // code...
        $idtributo = ID_TIPO_TRIBUTO_EXPORTACION;
        break;
      default:
        // code...
        $idtributo = ID_TIPO_TRIBUTO_IGV;
        break;
    }

    return $idtributo;
  }

  function GenerarCodigoBarraAleatorio($Contador = 1){
    $hoy = new DateTime($this->Base->ObtenerFechaServidor("Y-m-d h:i:s"));
    $hoy->add(new DateInterval('PT'.$Contador.'S'));
    $nuevafecha = $hoy->format('Y-m-d h:i:s');
    $variableTimestamp = strtotime($nuevafecha);    
    $year = date("Y", $variableTimestamp);
    $year2 = substr($year, 2);
    $formato1=$hoy->format("mdhis");
    $nuevafechahora=$year2.$formato1;
    /*
    $month = date("m", $variableTimestamp);
    $day = date("d", $variableTimestamp);

    $time = getdate();
    $hour = $time["hours"];
    $minute = $time["minutes"];
    $second = intval($time["seconds"])+intval($Contador);
    */
    $codigo = (String)$nuevafechahora;//$year2.$month.$day.$hour.$minute.$second;
    
    // $codigo = (string) $codigo;
    
    $codigos = str_split($codigo);
  
    $impar = 0;
    $par = 0;
    // print_r($codigos);exit;
    foreach ($codigos as $key => $value) {
      // $value = (int) $value;
      if($key % 2 == 0){
        $par += intval($value);
      }
      else{
        $impar += intval($value);
      }
    }
  
    $sum_imp = $impar * 3;
    $total = $sum_imp + $par;
    //$dec_cer = total % 10;
    $dec_cer = $this->roundUpToTen($total);  
    $last_digito =(String)($dec_cer - $total);
  
    $resultado=$codigo.$last_digito;
    //echo $last_digito."<br>";
    return $resultado;
  }

  function roundUpToTen($roundee) {
    $r = intval($roundee % 10);

    if ($r == 0) {
      $resultado = $roundee;
    }
    else {
      $resultado = $roundee + 10 - $r;
    }

    return $resultado; //return $roundee + 10 - $r;    
  }

  function InsertarMercaderia($data) {
    $parametroCodigoBarraAutomatico = $this->sConstanteSistema->ObtenerParametroCodigoBarraAutomatico();
    
    if($parametroCodigoBarraAutomatico == 1) {
      /*if ($data['CodigoAutomatico'] == '0') {
        $data["CodigoMercaderia"] = trim($this->ObtenerUltimoCodigoMercaderiaAlternativo());
      }*/
      //else {
      if($data['CodigoAutomatico'] == '0') {        
        $data["CodigoCorrelativoAutomatico"] = trim($this->ObtenerUltimoCodigoMercaderiaAlternativo());
        
        if (array_key_exists("Contador",$data)) {
          $Contador = $data["Contador"];
        }
        else {
          $Contador = 1;
        }

        $data["CodigoMercaderia"] = $this->GenerarCodigoBarraAleatorio($Contador);
      }
      //}
    }
    else {
      if ($data['CodigoAutomatico'] == '0') {
        $data["CodigoMercaderia"] = trim($this->ObtenerUltimoCodigoMercaderia());
      }
      //$data["CodigoCorrelativoAutomatico"] = $data["CodigoMercaderia"];
    }
    
    if (array_key_exists("NombreLargoProducto",$data)!=true) {
      $data["NombreLargoProducto"] ="";
    }

    $data["NombreLargoProducto"] = trim($data["NombreLargoProducto"]);
    $resultado1 = $this->ValidarMercaderia($data);
    $resultado2 = $this->ValidarExistenciaCodigoMercaderiaParaInsertar($data);
    $resultado3 = $this->ValidarExistenciaNombreProductoParaInsertar($data);

    $ParametroRestaurante = $this->sConstanteSistema->ObtenerParametroRestaurante();
    $ParametroMostrarAfiliacionTarjetaSiete = $this->sConstanteSistema->ObtenerParametroMostrarAfiliacionTarjetaSiete();
    $resultado4 = ($ParametroRestaurante == '1' && $ParametroMostrarAfiliacionTarjetaSiete == '1') ? $this->ValidarAfiliacionTarjetaSiete($data) : "";

    if ($resultado1 != "") {
      return $resultado1;
    }
    else if ($resultado2 != "") {
      return $resultado2;
    }
    else if ($resultado3 != "") {
      return $resultado3;
    }
    else if ($resultado4 != "") {
      return $resultado4;
    }
    else {
      $data["IdTipoTributo"] = $this->ObtenerTributoParaMercaderia($data);
      $producto = $this->sProducto->InsertarProducto($data, true);

      if(is_string($producto) && $producto != "") {
        return $producto;
      }
      else {
        $data["IdProducto"] = $producto["IdProducto"];
        $data["NombreProducto"] = $producto["NombreProducto"];
        $resultado = $this->mMercaderia->InsertarMercaderia($data);        
        $resultado = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($data);

        $dataAsignacionSede = $this->sAsignacionSede->ConsultarAsignacionesSedesPorIdTipoSedeAlmacen();
        
        foreach($dataAsignacionSede as $key => $value) {
          $dataAlmacenMercaderia["IdProducto"] = $data["IdProducto"];
          $dataAlmacenMercaderia["IdAsignacionSede"] = $value["IdAsignacionSede"];
          $dataAlmacenMercaderia["Cantidad"] =0;
          $resultadoAlmacenMercaderia = $this->sAlmacenMercaderia->AperturarStockAlmacenMercaderia($dataAlmacenMercaderia);  
        }            

        $parametroRestaurante = $this->sConstanteSistema->ObtenerParametroRestaurante();
        if($parametroRestaurante == 1)
        {
          $resultado["AnotacionesPlatoProducto"] = $this->sAnotacionPlatoProducto->AgregarAnotacionesPlatoProducto($data);
        }

        //BONIFICACIONES
        if(array_key_exists("Bonificaciones", $data))
        {
          if($data["AfectoBonificacion"] == 1)
          {
            $resultado["Bonificaciones"] = $this->sBonificacion->AgregarBonificaciones($data);
          }
        }

        // $resultado["IndicadorEstado"] = $producto["IndicadorEstado"];
        return $resultado;
      }
    }
  }

  function ActualizarMercaderia($data)
  {
    $parametroCodigoBarraAutomatico = $this->sConstanteSistema->ObtenerParametroCodigoBarraAutomatico();
    if($parametroCodigoBarraAutomatico == 1)
    {
      if(strlen($data["CodigoMercaderia"]) < 13)
      {
        $data["CodigoCorrelativoAutomatico"] = $data["CodigoMercaderia"];
        if (array_key_exists("Contador",$data)) {
          $Contador = $data["Contador"];
        }
        else {
          $Contador = 1;
        }

        $data["CodigoMercaderia"] = $this->GenerarCodigoBarraAleatorio($Contador);
      }
    }
    else
    {
      $data["CodigoCorrelativoAutomatico"] = $data["CodigoMercaderia"];
    }

    $data["CodigoMercaderia"] = trim($data["CodigoMercaderia"]);
    if (array_key_exists("NombreLargoProducto",$data)!=true) {
      $data["NombreLargoProducto"] ="";
    }
    $data["NombreLargoProducto"] = trim($data["NombreLargoProducto"]);
    $resultado1 = $this->ValidarMercaderia($data);
    $resultado2 = $this->ValidarExistenciaCodigoMercaderiaParaActualizar($data);
    $resultado3 = $this->ValidarExistenciaNombreProductoParaActualizar($data);

    $ParametroRestaurante = $this->sConstanteSistema->ObtenerParametroRestaurante();
    $ParametroMostrarAfiliacionTarjetaSiete = $this->sConstanteSistema->ObtenerParametroMostrarAfiliacionTarjetaSiete();
    $resultado4 = ($ParametroRestaurante == '1' && $ParametroMostrarAfiliacionTarjetaSiete == '1') ? $this->ValidarAfiliacionTarjetaSiete($data) : "";

    if ($resultado1 != "")
    {
      return $resultado1;
    }
    else if ($resultado2 != "")
    {
      return $resultado2;
    }
    else if ($resultado3 != "")
    {
      return $resultado3;
    }
    else if ($resultado4 != "")
    {
      return $resultado4;
    }
    else
    {
      $data["IdTipoTributo"] = $this->ObtenerTributoParaMercaderia($data);
      $producto = $this->sProducto->ActualizarProducto($data, true);

      if(is_string($producto) && $producto != "")
      {
        return $producto;
      }
      else
      {
        $resultado = $this->mMercaderia->ActualizarMercaderia($data);
        
        $dataAsignacionSede = $this->sAsignacionSede->ConsultarAsignacionesSedesPorIdTipoSedeAlmacen();
        
        foreach($dataAsignacionSede as $key => $value) {
          $dataAlmacenMercaderia["IdProducto"] = $data["IdProducto"];
          $dataAlmacenMercaderia["IdAsignacionSede"] = $value["IdAsignacionSede"];
          $dataAlmacenMercaderia["Cantidad"] = 0;
          $resultadoAlmacenMercaderia = $this->sAlmacenMercaderia->AperturarStockAlmacenMercaderia($dataAlmacenMercaderia);
        }

        $parametroRestaurante = $this->sConstanteSistema->ObtenerParametroRestaurante();
        if($parametroRestaurante == 1)
        {
          $resultado["AnotacionesPlatoProducto"] = $this->sAnotacionPlatoProducto->AgregarAnotacionesPlatoProducto($data);
        }
        
        //BONIFICACIONES
        if(array_key_exists("Bonificaciones", $data))
        {
          if($data["AfectoBonificacion"] == 1)
          {
            $resultado["Bonificaciones"] = $this->sBonificacion->AgregarBonificaciones($data);
          }
          else
          {
            $this->sBonificacion->BorrarBonificacionesPorIdProducto($data);
            $resultado["Bonificaciones"] = array();
          }
        }

        return $resultado;
      }
    }
  }

  function ActualizarMercaderiaSinValidacion($data) {
    if($data) {
      $resultado = $this->mMercaderia->ActualizarMercaderia($data);

      return $resultado;
    }
  }

  function ActualizarMercaderiaDesdeInventario($data)
  {
    $resultado = (array) $this->ConsultarMercaderiaPorIdProducto($data);
    
    $parametroCodigoBarraAutomatico = $this->sConstanteSistema->ObtenerParametroCodigoBarraAutomatico();
    if($parametroCodigoBarraAutomatico == 1)
    {
      if(strlen($resultado["CodigoMercaderia"]) < 13)
      {
        $resultado["CodigoCorrelativoAutomatico"] = $resultado["CodigoMercaderia"];
        if (array_key_exists("Contador",$data)) {
          $Contador = $data["Contador"];
        }
        else {
          $Contador = 1;
        
        }
        $resultado["CodigoMercaderia"] = $this->GenerarCodigoBarraAleatorio($Contador);
      }
    }
    else
    {
      $resultado["CodigoCorrelativoAutomatico"] = $resultado["CodigoMercaderia"];
    }

    $resultado["CodigoMercaderia"] = trim($resultado["CodigoMercaderia"]);
    
    $response = $this->mMercaderia->ActualizarMercaderia($resultado);
    return $response;
  }

  function BorrarMercaderia($data)
  {
    $resultadoventa= $this->sProducto->ValidarExistenciaProductoEnDetalleComprobanteVenta($data);
    $resultadocompra = $this->sProducto->ValidarExistenciaProductoEnDetalleComprobanteCompra($data);
    $resultadoinventario = $this->sProducto->ValidarExistenciaProductoEnInventarioInicial($data);
    if ($resultadoventa !="")
    {
      return $resultadoventa;
    }
    else if ($resultadocompra !="")
    {
      return $resultadocompra;
    }
    else if ($resultadoinventario !="")
    {
      return $resultadoinventario;
    }
    else
    {
      $resultado= $this->sProducto->BorrarProducto($data);

      $this->sAlmacenMercaderia->BorrarAlmacenMercaderiaPorIdProducto($data);
      $this->sAnotacionPlatoProducto->BorrarAnotacionesPlatoProductoPorIdProducto($data);
      $this->sBonificacion->BorrarBonificacionesPorIdProducto($data);

      return "";
    }
  }

  function ConsultarMercaderia($data,$pagina)
  {
    $data['IdParametroSistema']= ID_NUM_POR_PAGINA_MERCADERIA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
        $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
        $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;
        $resultado=$this->mMercaderia->ConsultarMercaderia($inicio,$ValorParametroSistema,$data);

        return $resultado;
    }
  }

  function ConsultarMercaderiaPorIdProducto($data)
  {
    $resultado=$this->mMercaderia->ConsultarMercaderiaPorIdProducto($data);
    return $resultado;
  }

  function ConsultarListasPrecioPorMercaderia($data)
  {
    $output = (array) $this->ConsultarMercaderiaPorIdProducto($data);
    $parametros["Lote"] = $this->sConstanteSistema->ObtenerParametroLote();
    $parametros["Zofra"] = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
    $parametros["Dua"] = $this->sConstanteSistema->ObtenerParametroDua();
    $listas = $this->ObtenerDataLista($data, $parametros);

    $output["Listas"] = $listas;
    return $output;
  }

  function ConsultarSugerenciaMercaderiaPorNombreProducto($data)
  {
    $resultado=$this->mMercaderia->ConsultarSugerenciaMercaderiaPorNombreProducto($data);
    $producto = [];
    foreach ($resultado as $item )
    {
      $producto[] = $item["NombreProducto"];
    }
    return $producto;
  }


  function ObtenerMercaderiaPorIdProducto($data)
  {
        $resultado=$this->mMercaderia->ObtenerMercaderiaPorIdProducto($data);
        return $resultado;
  }

  function ObtenerMercaderiaPorCodigoMercaderia($data)
  {
    $resultado=$this->mMercaderia->ObtenerMercaderiaPorCodigoMercaderia($data);
    return $resultado;
  }

  function ConsultarMercaderias($data,$numeropagina,$numerofilasporpagina)
  {
    $numerofilainicio=$numerofilasporpagina * ($numeropagina - 1);

    $parametroRubroRepuesto = $this->sConstanteSistema->ObtenerParametroRubroRepuesto();
    $data["EstadoProducto"]="%";
    if($parametroRubroRepuesto == 1) {
      $resultado = $this->mMercaderia->ConsultarMercaderiaAvanzada($data,$numerofilainicio,$numerofilasporpagina);
    }
    else {
      $resultado = $this->mMercaderia->ConsultarMercaderia($data,$numerofilainicio,$numerofilasporpagina);
    }

    foreach ($resultado as $key => $value) {
      $validacion = $this->ValidarProductoEnMovimientoDuaOZofra($value);
      $resultado[$key]["BloquearTipoMercaderia"] = ($validacion == "") ? 0 : 1;
      $resultado[$key]["IndicadorAfectoICBPER"] = ($value["AfectoICBPER"] == 0) ? false : true;
      $resultado[$key]["IndicadorEstadoProducto"] = ($value["EstadoProducto"] == 0) ? false : true;
      $resultado[$key]["AnotacionesPlatoProducto"] = $this->sAnotacionPlatoProducto->ListarAnotacionesPlatoProductoPorProducto($value);
      $resultado[$key]["IndicadorAfectoBonificacion"] = ($value["AfectoBonificacion"] == 0) ? false : true;
      $Bonificaciones = $this->sBonificacion->ListarBonificacionesPorIdProducto($value);
      $resultado[$key]["CantidadBonificaciones"] = count($Bonificaciones);
      $resultado[$key]["Bonificaciones"] = array();
    }
    return $resultado;
  }

  function ObtenerParametroCantidadCodigo()
  {
    $data['IdParametroSistema']= ID_CANTIDAD_CODIGO_MERCADERIA;
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

  function ObtenerUltimoCodigoMercaderia()
  {
    $resultado = $this->mMercaderia->ObtenerUltimoCodigoMercaderia();
    $valor = (int)$resultado[0]['MaximoValor'];
    if ($valor > 0) {
      $nuevoCodigo =$valor + 1;
      return $nuevoCodigo;
    }
    else {
      return '1';
    }
  }

  function ObtenerUltimoCodigoMercaderiaAlternativo()
  {
    $resultado = $this->mMercaderia->ObtenerUltimoCodigoMercaderiaAlternativo();
    $valor = (int)$resultado[0]['MaximoValor'];
    if ($valor > 0) {
      $nuevoCodigo =$valor + 1;
      return $nuevoCodigo;
    }
    else {
      return '1';
    }
  }
  /**Se parametriza la creacion del JSON por tipos de documentos
  ** 0: Se crea json con todas las listas
  ** 1: Se crea json solo con ListaZofra
  ** 2: Se crea json solo con ListaDua
  **/
  function ObtenerDataLista($data, $parametros)
  {
    $listas["ListaPrecios"] = $this->sListaPrecioMercaderia->ConsultarListasPrecioMercaderiaPorIdProducto($data);
    $listas["ListaRaleos"] = $this->sListaRaleoMercaderia->ConsultarListasRaleoMercaderiaPorIdProducto($data);
    $listas["ListaProveedores"] = $this->sProductoProveedor->ConsultarProductoProveedorPorIdProducto($data);

    $parametroLote = $parametros["Lote"];
    $parametroZofra = $parametros["Zofra"];
    $parametroDua = $parametros["Dua"];
    if($parametroLote == 1)
    {
      $listas["ListaLotes"] = $this->sAlmacenProductoLote->ConsultarListasLoteProductoPorIdProducto($data);
    }
    if($parametroZofra == 1)
    {
      $listas["ListaZofra"] = $this->sDocumentoSalidaZofraProducto->ConsultarListasDocumentoSalidaZofraPorIdProducto($data);
    }
    if($parametroDua == 1)
    {
      $listas["ListaDua"] = $this->sDuaProducto->ConsultarListasDuaPorIdProducto($data);
    }

    $listas["ListaStock"] = $this->sAlmacenMercaderia->ConsultarListasStockPorIdProducto($data);
    $listas["ListaAnotacionesPlato"] = $this->sAnotacionPlatoProducto->ListarAnotacionesPlatoProductoPorIdProducto($data);
    $listas["ListaBonificaciones"] = $this->sBonificacion->ListarBonificacionesPorIdProducto($data);

    return $listas;
  }

  function PreparaDataProductoParaJSON($data)
  {
    $preparacion = Array (
      "IdProducto" => $data["IdProducto"],
      "NombreProducto" => $data["NombreProducto"],
      "NombreLargoProducto" => $data["NombreLargoProducto"],
      "Foto" => $data["Foto"],
      "CodigoMercaderia" => $data["CodigoMercaderia"],      
      "IdUnidadMedida" => $data["IdUnidadMedida"],
      "AbreviaturaUnidadMedida" => $data["AbreviaturaUnidadMedida"],
      "IdTipoAfectacionIGV" => $data["IdTipoAfectacionIGV"],
      "CodigoTipoAfectacionIGV" => $data["CodigoTipoAfectacionIGV"],
      "IdTipoSistemaISC" => $data["IdTipoSistemaISC"],
      "CodigoTipoSistemaISC" => $data["CodigoTipoSistemaISC"],
      "IdTipoPrecio" => $data["IdTipoPrecio"],
      "CodigoTipoPrecio" => $data["CodigoTipoPrecio"],
      "PrecioUnitario" => $data["PrecioUnitario"],
      "IdOrigenMercaderia" => $data["IdOrigenMercaderia"],
      "IdTipoTributo" => $data["IdTipoTributo"],
      "IdFamiliaProducto" => $data["IdFamiliaProducto"],
      "IdSubFamiliaProducto" => $data["IdSubFamiliaProducto"],
      "PesoUnitario" => $data["PesoUnitario"],
      "TipoDescuento" => $data["TipoDescuento"],
      "ValorDescuento" => $data["ValorDescuento"],
      "AfectoICBPER" => $data["AfectoICBPER"],
      "AfectoBonificacion" => $data["AfectoBonificacion"],
      "Aplicacion" => $data["Aplicacion"],
      
      "NombreMarca" => $data["NombreMarca"],
      "CodigoMercaderia2" => $data["CodigoMercaderia2"],
      "CodigoAlterno" => $data["CodigoAlterno"],
      "RazonSocialProveedor" => $data["ReferenciaProveedor"],
      "Referencia" => $data["Referencia"],
      "NombreLineaProducto" => $data["NombreLineaProducto"],
      "UltimoPrecio" => $data["UltimoPrecio"],
      "PrecioUnitarioCompra" => $data["PrecioUnitarioCompra"],
      "CostoUnitarioCompra" => $data["CostoUnitarioCompra"],
      "FechaIngresoCompra" => $data["FechaIngresoCompra"],
      "IdMonedaCompra" => $data["IdMonedaCompra"],
      "NombreFamiliaProducto"=>$data["NombreFamiliaProducto"],
      "Aplicacion"=>$data["Aplicacion"],
      "OtroDato"=>$data["OtroDato"],
      "NumeroPiezas"=>$data["NumeroPiezas"],
      "NombreUnidadMedida" => $data["NombreUnidadMedida"],   
      "Peso" => $data["Peso"],
      "EstadoCampoCalculo" => $data["EstadoCampoCalculo"]
    );

    return $preparacion;
  }

  function ObtenerFilaMercaderiaParaJSON($data, $parametros)
  {
    $mercaderias = (array) $this->mMercaderia->ConsultarMercaderiaParaJSONPorIdProducto($data);

    $nueva_fila = $this->PreparaDataProductoParaJSON($mercaderias);

    $listas = $this->ObtenerDataLista($mercaderias, $parametros);
    $response = array_merge($nueva_fila, $listas);
    return $response;
  }

  /**
  ** AQUI SE REALIZAN LAS CONSULTAS PARA MOVIMIENTOS EN
  ** COMPRA - VENTA
  ** INVENTARIOS
  **/
  function ObtenerDataListaMovimientos($data, $parametros)
  {
    $parametroLote = $parametros["Lote"];
    $parametroZofra = $parametros["Zofra"];
    $parametroDua = $parametros["Dua"];
    $parametroLista = $parametros["Listas"];
    if($parametroLista)
    {
      $listas["ListaPrecios"] = $this->sListaPrecioMercaderia->ConsultarListasPrecioMercaderiaPorIdProducto($data);
      $listas["ListaRaleos"] = $this->sListaRaleoMercaderia->ConsultarListasRaleoMercaderiaPorIdProducto($data);
      $listas["ListaProveedores"] = $this->sProductoProveedor->ConsultarProductoProveedorPorIdProducto($data);
    }

    if($parametroLote == 1)
    {
      $listas["ListaLotes"] = $this->sAlmacenProductoLote->ConsultarListasLoteProductoPorIdProducto($data);
    }
    if($parametroZofra == 1)
    {
      $listas["ListaZofra"] = $this->sDocumentoSalidaZofraProducto->ConsultarListasDocumentoSalidaZofraPorIdProducto($data);
    }
    if($parametroDua == 1)
    {
      $listas["ListaDua"] = $this->sDuaProducto->ConsultarListasDuaPorIdProducto($data);
    }

    $listas["ListaStock"] = $this->sAlmacenMercaderia->ConsultarListasStockPorIdProducto($data);
    $listas["ListaAnotacionesPlato"] = $this->sAnotacionPlatoProducto->ListarAnotacionesPlatoProductoPorIdProducto($data);
    $listas["ListaBonificaciones"] = $this->sBonificacion->ListarBonificacionesPorIdProducto($data);
    return $listas;
  }

  /**
  ** AQUI SE PREPARA LA DATA PARA TODO JSON
  ** SE EMPIEZA A CREAR LAS FUNCIONES
  **/
  function PrepararDatosParaJSONMercaderia($data) //AQUI SOLO PONEMOS LOS DATOS NECESARIOS PARA MERCADERIAS.JSON
  {
    $ParametroRubroRepuesto = $this->sConstanteSistema->ObtenerParametroRubroRepuesto();
    if($ParametroRubroRepuesto == 0) {
      $listaStock = array();
      $listaPrecios = array();
    }
    else {
      $listaStock = $this->sAlmacenMercaderia->ConsultarListasStockPorIdProducto($data);
      $listaPrecios = $this->sListaPrecioMercaderia->ConsultarListasPrecioMercaderiaPorIdProducto($data);
    }
    
    
    $preparacion =  Array (
      "IdProducto" => $data["IdProducto"],
      "NombreProducto" => $data["NombreProducto"],
      "NombreLargoProducto" => $data["NombreLargoProducto"],
      "CodigoMercaderia" => $data["CodigoMercaderia"],
      "IdFamiliaProducto" => $data["IdFamiliaProducto"],
      "IdSubFamiliaProducto" => $data["IdSubFamiliaProducto"],
      "IdOrigenMercaderia" => $data["IdOrigenMercaderia"],
      "EstadoProducto" => $data["EstadoProducto"],
      "Aplicacion" => $data["Aplicacion"],
      "NombreMarca" => $data["NombreMarca"],
      "CodigoMercaderia2" => $data["CodigoMercaderia2"],
      "CodigoAlterno" => $data["CodigoAlterno"],
      "RazonSocialProveedor" => $data["ReferenciaProveedor"],
      "Referencia" => $data["Referencia"],
      "NombreLineaProducto" => $data["NombreLineaProducto"],
      "NombreFamiliaProducto" => $data["NombreFamiliaProducto"],
      "PrecioUnitario" => $data["PrecioUnitario"],
      "UltimoPrecio" => $data["UltimoPrecio"],
      "PrecioUnitarioCompra" => $data["PrecioUnitarioCompra"],
      "CostoUnitarioCompra" => $data["CostoUnitarioCompra"],
      "FechaIngresoCompra" => $data["FechaIngresoCompra"],
      "IdMonedaCompra" => $data["IdMonedaCompra"],
      "StockMercaderia" => (count($listaStock) > 0) ? $listaStock[0]["StockMercaderia"] : 0,
      "PrecioSugerido" => (count($listaPrecios) > 0) ? $listaPrecios[0]["Precio"] : 0,
      "Peso" => $data["Peso"]
    );
    
    return $preparacion;
  }

  function ObtenerDataJSONFilaMercaderia($data)
  {
    $mercaderias = (array) $this->mMercaderia->ConsultarMercaderiaParaJSONPorIdProducto($data);
    $nueva_fila = $this->PrepararDatosParaJSONMercaderia($mercaderias);
    return $nueva_fila;
  }

  function PrepararDataJSONMercaderias()
  {
    $response = array();
    $mercaderias = $this->mMercaderia->ConsultarMercaderiaParaJSON();
    
    foreach ($mercaderias as $key => $value) {
      $nueva_fila = $this->PrepararDatosParaJSONMercaderia($value);      
      array_push($response, $nueva_fila);
    }
    
    return $response;
  }

  //VALIDACION POR CODIGO Y NOMBRE PRODUCTO
  function ValidarDataJSONProductoCodigoNombreProducto($data)
  {
    $nombreproducto = $data['NombreProducto'];
    $response = array();
    //buscamos codigo en la base principal
    $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaEnVentasJSON($data);
    //comparamos si hay o no
    $response["Codigo"] = 3;
    if(!empty($mercaderia)) {
        // El producto existe
      //Mismo Codigo - buscamos nombre producto en la base principal
      // $mercaderia = $this->mCliente->ConsultarRazonSocialEnVentasJSON($data);
      if($mercaderia['NombreProducto'] == $nombreproducto)
      {
      }
      else
      {
        //MODIFICADO - CUANDO EL RAZON SOCIAL ES IGUAL
        $response["Codigo"] = 2;
        $mercaderia["CodigoEstado"] = 2;
        $response["Data"] = $mercaderia;
      }
    }
    else {
      //no se encontro - buscamos nombre producto en la base principal
      $mercaderia = (array) $this->mMercaderia->ConsultarNombreProductoEnVentasJSON($data);
      if(empty($mercaderia))
      {
        //NUEVO
        $response["Codigo"] = 0;
      }
      else
      {
        //MODIFICADO - CUANDO LA RUC ES IGUAL
        $response["Codigo"] = 1;
        $mercaderia["CodigoEstado"] = 1;
        $response["Data"] = $mercaderia;
      }
    }
    return $response;
  }

  //VALIDACION SOLO POR NOMBRE PRODUCTO
  function ValidarDataJSONProducto($data)
  {
    $nombreproducto = $data['NombreProducto'];
    $response = array();
    //buscamos codigo en la base principal
    $mercaderia = (array) $this->mMercaderia->ConsultarNombreProductoEnVentasJSON($data);
    //comparamos si hay o no
    $response["Codigo"] = 3;
    if(!empty($mercaderia)) {
        // El producto existe
      //Mismo Codigo - buscamos nombre producto en la base principal
      // $mercaderia = $this->mCliente->ConsultarRazonSocialEnVentasJSON($data);
    }
    else {
      $response["Codigo"] = 0;
    }
    return $response;
  }

  function ConsultarProductoEnVentasJSONParaImportacion($data)
  {
    $resultado=$this->mMercaderia->ConsultarProductoEnVentasJSONParaImportacion($data);
    return $resultado;
  }

  //PARA EXPORTAR MERCADERIAS
  public function ConsultarMercaderiaPorIdProductoParaJSON($data)
  {
    $resultado = $this->mMercaderia->ConsultarMercaderiaPorIdProductoParaJSON($data);
    return $resultado;
  }

  public function ConsultarMercaderiasEnVentasJSON($data)
  {
    $resultado = $this->mMercaderia->ConsultarMercaderiasEnVentasJSON($data);
    return $resultado;
  }

  public function ConsultarMercaderiaParaJSONAvanzado($data)
  {
    $resultado = $this->mMercaderia->ConsultarMercaderiaParaJSONAvanzado($data);
    return $resultado;
  }
  
  function ImprimirCodigoBarra($data) {
    try {      
      $parametros["IdProducto"] = $data["IdProducto"];
      $indicadorImpresion = INDICADOR_FORMATO_CODIGO_BARRAS;
      $dataConfig = $this->json->ObtenerConfigImpresion($indicadorImpresion);
  
      if($dataConfig != false) {
        $printer = $dataConfig["Printer"];
        $rutaFormato = RUTA_CARPETA_REPORTES.$dataConfig["Jasper"];
        $this->reporter->RutaReporte = $rutaFormato;
        $this->reporter->SetearParametros($parametros);  
        $this->reporter->Imprimir($printer);    
      }

      return "";
    }
    catch (Exception $e) {
      throw new Exception($e);
    }
  }

  function GenerarVistaPreviaPDF($data)
  {
    $parametros["IdProducto"] = $data["IdProducto"];
    $name_archive = $data["CodigoMercaderia"];      
    
    //$parametros["IdComprobanteVenta"] = $data["IdComprobanteVenta"];
    //$name_archive = $data["SerieDocumento"]."-".$data["NumeroDocumento"];
    
    $ruta_pdf = APP_PATH."assets/reportes/codigobarras/".$name_archive.".pdf";    
    $indicadorImpresion = INDICADOR_FORMATO_CODIGO_BARRAS;
    
    $dataConfig = $this->json->ObtenerConfigImpresion($indicadorImpresion);
    
    if($dataConfig != false) {
      $rutaFormato = RUTA_CARPETA_REPORTES.$dataConfig["Jasper"];
    }

    $this->reporter->RutaReporte = $rutaFormato;
    $this->reporter->RutaPDF = $ruta_pdf;
    $this->reporter->SetearParametros($parametros);
    $resultado = $this->reporter->ExportarReporteComoPDF();

    $output["APP_RUTA"] =APP_PATH_URL."assets/reportes/codigobarras/".$name_archive.".pdf";
    $output["BASE_RUTA"] =$ruta_pdf;// APP_PATH."assets/reportes/codigobarras/".$name_archive.".pdf";

    return $output;
  }

  function ObtenerSaldoFisicoMercaderiaPorIdProducto($data) {
    $mercaderia = (array) $this->ConsultarMercaderiaPorIdProducto($data);
    $saldofisico = (array_key_exists("SaldoFisico", $mercaderia) == false || strlen($mercaderia["SaldoFisico"]) == 0) ? 0 : $mercaderia["SaldoFisico"];
    
    if (is_string($saldofisico)) {
      $saldofisico = str_replace(',', "", $saldofisico);
    }        
    
    return $saldofisico;
  }
  
  function ConsultarMercaderiasPorFiltro($data) {
    $resultado = $this->mMercaderia->ConsultarMercaderias($data);    
    return $resultado;
  }

  

}

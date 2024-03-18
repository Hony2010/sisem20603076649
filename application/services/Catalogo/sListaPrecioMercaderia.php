<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sListaPrecioMercaderia extends MY_Service {

  public $ListaPrecioMercaderia = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->model('Catalogo/mListaPrecioMercaderia');
    $this->load->model('Catalogo/mMercaderia');
    $this->load->model('Catalogo/mServicio');
    
    $this->load->helper("date");
    $this->load->service("Configuracion/Catalogo/sTipoListaPrecio");
    $this->load->service("Configuracion/Catalogo/sFamiliaProducto");
    $this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
    $this->load->service("Configuracion/Catalogo/sMarca");
    $this->load->service("Configuracion/Catalogo/sModelo");
    $this->load->service("Configuracion/Catalogo/sLineaProducto");
    $this->load->service("Inventario/sMovimientoAlmacen");
    $this->load->service("Configuracion/General/sConstanteSistema");
    $this->load->service("Configuracion/General/sSede");
    $this->load->service('Catalogo/sMercaderia');

    $this->ListaPrecioMercaderia = $this->mListaPrecioMercaderia->ListaPrecioMercaderia;
  }

  function Inicializar() {
    $this->ListaPrecioMercaderia["IdSede"] = $this->sesionusuario->obtener_sesion_id_sede();
    $this->ListaPrecioMercaderia["IdFamiliaProducto"] = "";
    $this->ListaPrecioMercaderia["IdSubFamiliaProducto"] = "";
    $this->ListaPrecioMercaderia["IdLineaProducto"] = "";
    $this->ListaPrecioMercaderia["IdTipoListaPrecio"] = "";
    $this->ListaPrecioMercaderia["IdMarca"] = "";
    $this->ListaPrecioMercaderia["IdModelo"] = "";
    $this->ListaPrecioMercaderia["Descripcion"] = "";
    $this->ListaPrecioMercaderia["MargenUtilidadPrincipal"] = "0.00";
    $this->ListaPrecioMercaderia["PrecioBase"] = 0;
    $this->ListaPrecioMercaderia["InputPrecioBase"] = false;

    $this->ListaPrecioMercaderia["CostoPromedioPonderado"] = "";
    $this->ListaPrecioMercaderia["MargenPorcentaje"] = "";
    $this->ListaPrecioMercaderia["MargenUtilidad"] = "";
    $this->ListaPrecioMercaderia["ValorVenta"] = "";
    $this->ListaPrecioMercaderia["ValorIGV"] = "";

    $this->ListaPrecioMercaderia["PrecioPromedioCompra"] = "";
    $this->ListaPrecioMercaderia["UltimoPrecio"] = "";
    $this->ListaPrecioMercaderia["FechaUltimoPrecio"] = "";

    //PARA DESCUENTOS
    $this->ListaPrecioMercaderia["TipoDescuento"] = TIPO_DESCUENTO_PORCENTUAL;
    $this->ListaPrecioMercaderia["ValorDescuento"] = 0;
    
    $indicadorvistapreciominimo=$this->sesionusuario->obtener_sesion_indicador_vista_precio_minimo();
    if ($indicadorvistapreciominimo=='1') {
      $this->ListaPrecioMercaderia["TiposListaPrecio"] = $this->sTipoListaPrecio->ListarTiposListaPrecio();
    }
    else{
      $this->ListaPrecioMercaderia["TiposListaPrecio"] = $this->sTipoListaPrecio->ListarTiposListaPrecioSinTipoPrecioMinimo();
    }

    $this->ListaPrecioMercaderia["FamiliasProducto"] = $this->sFamiliaProducto->ListarFamiliasProducto();
    $this->ListaPrecioMercaderia["SubFamiliasProducto"] = $this->sSubFamiliaProducto->ListarTodosSubFamiliasProducto();
    $this->ListaPrecioMercaderia["Modelos"] = $this->sModelo->ListarTodosModelos();
    $this->ListaPrecioMercaderia["Marcas"] = $this->sMarca->ListarMarcas();
    $this->ListaPrecioMercaderia["LineasProducto"] = $this->sLineaProducto->ListarLineasProducto();
    $this->ListaPrecioMercaderia["Sedes"]=$this->sSede->ListarSedes();
    $this->ListaPrecioMercaderia["DetallesListaPrecio"] = array();
    $this->ListaPrecioMercaderia["CopiaIdProductosDetalle"] = array();
    
    $this->ListaPrecioMercaderia["ParametroCostoOPrecioPromedio"] = $this->sConstanteSistema->ObtenerParametroCostoOPrecioPromedio();
    $this->ListaPrecioMercaderia["ParametroListaPrecioAvanzado"] = $this->sConstanteSistema->ObtenerParametroAvanzadoListaPrecios();
    $this->ListaPrecioMercaderia["FiltroPrecio"] = '1';
    $this->ListaPrecioMercaderia["FiltroCosto"] = '1';
    $this->ListaPrecioMercaderia["CheckAplicaMismoPrecio"]=false;
    return $this->ListaPrecioMercaderia;
  }

  function ListarPreciosMercaderiasPorIdTipoPrecio($data) {
    $resultado=$this->mListaPrecioMercaderia->ListarPreciosMercaderiasPorIdTipoPrecio($data);
    return $resultado;
  }

  function ConsultarListasPrecioMercaderia($data)
  {
    $resultado = $this->mListaPrecioMercaderia->ConsultarListasPrecioMercaderia($data);
    foreach ($resultado as $key => $value) {
      if($value["FechaUltimoPrecio"] != "")
      {
        $resultado[$key]["FechaUltimoPrecio"] = convertirFechaES($value["FechaUltimoPrecio"]);
      }
    }
    return $resultado;
  }

  function ConsultarMercaderiasParaPrecio($data)
  {

    $resultado = $this->mListaPrecioMercaderia->ConsultarMercaderiasParaPrecio($data);
    
    foreach ($resultado as $key => $value) {
      if($value["FechaUltimoPrecio"] != "")
      {
        $resultado[$key]["FechaUltimoPrecio"] = convertirFechaES($value["FechaUltimoPrecio"]);
      }
    }

 
    //uso de parametroserviciolistaprecio
    $parametroclinica = $this->sConstanteSistema->ObtenerParametroRubroClinica();
    if($parametroclinica == 1) {
      $resultado2 = $this->mListaPrecioMercaderia->ConsultarServicioParaPrecioBase($data);
    
      foreach ($resultado2 as $key => $value) {
        $resultado2[$key]["FechaUltimoPrecio"] = convertirFechaES($this->Base->ObtenerFechaServidor());
        array_push($resultado, $resultado2[$key]);
      }
    }    

    return $resultado;
  }

  //PARA DESCUENTOS
  function ConsultarMercaderiasParaDescuento($data)
  {
    $resultado = $this->mListaPrecioMercaderia->ConsultarMercaderiasParaPrecio($data);    
    foreach ($resultado as $key => $value) {
      
    }
    return $resultado;
  }

  function ActualizarMercaderiasParaDescuento($data) {

    foreach ($data["DetallesListaPrecio"] as $key => $value) {
      if(is_numeric($value["IdProducto"])) {
        $dataProducto["IdProducto"] = $value["IdProducto"];
        $dataProducto["TipoDescuento"] = $value["TipoDescuento"];
        $dataProducto["ValorDescuento"] = $value["ValorDescuento"];
        if(is_string($dataProducto["ValorDescuento"])){$dataProducto["ValorDescuento"] = str_replace(',',"",$dataProducto["ValorDescuento"]);}
        $resultado = $this->mMercaderia->ActualizarMercaderia($dataProducto);
      }
    }
    return $data;
  }
  //FIN DESCUENTOS

  function ConsultarListasPrecioMercaderiaPorIdProducto($data)
  {
    $resultado = $this->mListaPrecioMercaderia->ConsultarListasPrecioMercaderiaPorIdProducto($data);
    $response = array();
    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $lista["NombreTipoListaPrecio"] = $value["NombreTipoListaPrecio"];
        $lista["Precio"] = $value["Precio"];
        $lista["IdTipoListaPrecio"] = $value["IdTipoListaPrecio"];
        $lista["IndicadorPrecioMinimo"] = $value["IndicadorPrecioMinimo"];
        $lista["IdSede"] = $value["IdSede"];
        $lista["IndicadorPrecioVentaPorDefecto"] = $value["IndicadorPrecioVentaPorDefecto"];
        array_push($response, $lista);
      }
    }
    return $response;
  }

  function ActualizarListaPreciosMercaderias($data) {
    //ListarTiposListaPrecio
    foreach ($data["DetallesListaPrecio"] as $key => $value) {
      $value["IdTipoListaPrecio"] = $data["IdTipoListaPrecio"];

      if(is_string($value["Precio"])){$value["Precio"] = str_replace(',',"",$value["Precio"]);}
      if(is_string($value["CostoPromedioPonderado"])){$value["CostoPromedioPonderado"] = str_replace(',',"",$value["CostoPromedioPonderado"]);}
      if(is_string($value["MargenPorcentaje"])){$value["MargenPorcentaje"] = str_replace(',',"",$value["MargenPorcentaje"]);}
      if(is_string($value["MargenUtilidad"])){$value["MargenUtilidad"] = str_replace(',',"",$value["MargenUtilidad"]);}
      if(is_string($value["ValorVenta"])){$value["ValorVenta"] = str_replace(',',"",$value["ValorVenta"]);}
      if(is_string($value["ValorIGV"])){$value["ValorIGV"] = str_replace(',',"",$value["ValorIGV"]);}
      if(is_string($value["PrecioPromedioCompra"])){$value["PrecioPromedioCompra"] = str_replace(',',"",$value["PrecioPromedioCompra"]);}
      $value["UltimoPrecio"] = $value["Precio"];
      $value["FechaUltimoPrecio"] = $this->Base->ObtenerFechaServidor();
      $value["IdSede"]=$data["IdSede"];
      $response = $this->mListaPrecioMercaderia->ObtenerListaPrecioMercaderiaPorIdProductoYIdTipoListaPrecio($value);
      if(count($response) > 0)
      {
        $value["IdListaPrecioMercaderia"] = $response[0]["IdListaPrecioMercaderia"];
        $resultado = $this->ActualizarListaPrecioMercaderia($value);
      }
      else
      {
        $value["IdListaPrecioMercaderia"] = "";
        $resultado = $this->InsertarListaPrecioMercaderia($value);
      }
    }
    return $data;
  }

  function ActualizarMercaderiasParaPrecioBase($data) {

    foreach ($data["DetallesListaPrecio"] as $key => $value) {
      if(is_numeric($value["IdProducto"])) {
        $indicador = $value["IndicadorProducto"];//$this->sConstanteSistema->ObtenerParametroRubroClinica();        
        if($indicador == 'S') {
          $dataProducto["IdProducto"] = $value["IdProducto"];  
          $dataProducto["PrecioUnitario"] = $value["Precio"];
          if(is_string($dataProducto["PrecioUnitario"])){$dataProducto["PrecioUnitario"] = str_replace(',',"",$dataProducto["PrecioUnitario"]);}          
          $resultado = $this->mServicio->ActualizarServicio($dataProducto);
        }
        else {        
          $dataProducto["IdProducto"] = $value["IdProducto"];
          $dataProducto["PrecioUnitario"] = $value["Precio"];
          $dataProducto["CostoPromedioPonderado"] = $value["CostoPromedioPonderado"];
          $dataProducto["MargenPorcentaje"] = $value["MargenPorcentaje"];
          $dataProducto["MargenUtilidad"] = $value["MargenUtilidad"];
          $dataProducto["ValorVenta"] = $value["ValorVenta"];
          $dataProducto["ValorIGV"] = $value["ValorVenta"];
          $dataProducto["PrecioPromedioCompra"] = $value["PrecioPromedioCompra"];

          if(is_string($dataProducto["PrecioUnitario"])){$dataProducto["PrecioUnitario"] = str_replace(',',"",$dataProducto["PrecioUnitario"]);}
          
          if(is_string($dataProducto["CostoPromedioPonderado"])){$dataProducto["CostoPromedioPonderado"] = str_replace(',',"",$dataProducto["CostoPromedioPonderado"]);}
          if(is_string($dataProducto["MargenPorcentaje"])){$dataProducto["MargenPorcentaje"] = str_replace(',',"",$dataProducto["MargenPorcentaje"]);}
          if(is_string($dataProducto["MargenUtilidad"])){$dataProducto["MargenUtilidad"] = str_replace(',',"",$dataProducto["MargenUtilidad"]);}
          if(is_string($dataProducto["ValorVenta"])){$dataProducto["ValorVenta"] = str_replace(',',"",$dataProducto["ValorVenta"]);}
          if(is_string($dataProducto["ValorIGV"])){$dataProducto["ValorIGV"] = str_replace(',',"",$dataProducto["ValorIGV"]);}
          if(is_string($dataProducto["PrecioPromedioCompra"])){$dataProducto["PrecioPromedioCompra"] = str_replace(',',"",$dataProducto["PrecioPromedioCompra"]);}

          $dataProducto["UltimoPrecio"] = $dataProducto["PrecioUnitario"];
          $dataProducto["FechaUltimoPrecio"] = $this->Base->ObtenerFechaServidor();
          
          $resultado = $this->mMercaderia->ActualizarMercaderia($dataProducto);      
        }
      }
    }
    return $data;
  }

  function InsertarListaPrecioMercaderia($data) {
    $resultado = $this->mListaPrecioMercaderia->InsertarListaPrecioMercaderia($data);
    return $resultado;
  }

  function ActualizarListaPrecioMercaderia($data) {
    $resultado = $this->mListaPrecioMercaderia->ActualizarListaPrecioMercaderia($data);
    return $resultado;
  }

  //FUNCIONES PARA LOS COSTOS PROMEDIOS
  function ObtenerCostosPromedios($data, $filtro)
  {
    if($data == null){return "Por lo menos debe tener una mercaderia";}
    if($filtro == 1)
    {
      $response = $this->ListarCostosPromedios($data);
    }
    else
    {
      $response = $this->ListarCostosPromediosPorUltimoEnCompra($data);
    }
    return $response;
  }

  function ListarCostosPromedios($data)
  {
    foreach ($data as $key => $value) {
      $resultado = $this->sMovimientoAlmacen->RecalcularCostoUnitarioPromedioPorProducto($value);
      $data[$key]["CostoPromedioPonderado"] = 0;
      if($resultado != null)
      {
        $data[$key]["CostoPromedioPonderado"] = $resultado["CostoUnitarioPromedio"];
      }
    }
    return $data;
  }

  function ListarCostosPromediosPorUltimoEnCompra($data)
  {
    foreach ($data as $key => $value) {
      $resultado = $this->mListaPrecioMercaderia->ConsultarUltimoCostoUnitarioCalculadoEnCompra($value);
      $data[$key]["CostoPromedioPonderado"] = 0;
      if(count($resultado)> 0)
      {
        $data[$key]["CostoPromedioPonderado"] = $resultado[0]["CostoUnitarioCalculado"];
      }
    }
    return $data;
  }

  //FUNCIONES PARA LOS PRECIOS PROMEDIOS
  function ObtenerPreciosPromedios($data, $filtro)
  {
    if($data == null){return "Por lo menos debe tener una mercaderia";}
    if($filtro == 1)
    {
      $response = $this->ListarPreciosPromedios($data);
    }
    else
    {
      $response = $this->ListarPreciosPromediosPorUltimoEnCompra($data);
    }
    return $response;
  }

  function ListarPreciosPromedios($data)
  {
    foreach ($data as $key => $value) {
      $resultado = $this->mListaPrecioMercaderia->ConsultarPromedioDePreciosUnitarioEnCompra($value);
      $data[$key]["PrecioPromedioCompra"] = 0;
      if(count($resultado)> 0)
      {
        $data[$key]["PrecioPromedioCompra"] = $resultado[0]["PrecioCompraPromedio"];
      }
    }
    return $data;
  }

  function ListarPreciosPromediosPorUltimoEnCompra($data)
  {
    foreach ($data as $key => $value) {
      $resultado = $this->mListaPrecioMercaderia->ConsultarUltimoPrecioUnitarioEnCompra($value);
      $data[$key]["PrecioPromedioCompra"] = 0;
      if(count($resultado)> 0)
      {
        $data[$key]["PrecioPromedioCompra"] = $resultado[0]["PrecioUnitario"];
      }
    }
    return $data;
  }

  function ObtenerPrecioMinimoPorIdProducto($data) {
      $resultado = $this->mListaPrecioMercaderia->ObtenerPrecioMinimoPorIdProducto($data);
      return $resultado;      
  }
        
  function ConsultarListaPreciosMercaderiaPorIdProducto($data) {
    $resultado = $this->mListaPrecioMercaderia->ConsultarListaPreciosMercaderiaPorIdProducto($data);
    return $resultado;      
  }

  function ConsultarPreciosMercaderia($data) {
    $resultadoMercaderias = $this->sMercaderia->ConsultarMercaderiasPorFiltro($data);
    $TiposListaPrecio = $this->sTipoListaPrecio->ListarTiposListaPrecio();
    
    foreach ($resultadoMercaderias as $key => $itemMercaderia) {
      $resultadoListaPreciosMercaderia=[];
      $itemMercaderia["IdSede"]=$data["IdSede"];
      $dataListaPreciosMercaderia  = $this->ConsultarListaPreciosMercaderiaPorIdProducto($itemMercaderia);
      
        foreach($TiposListaPrecio as $keyTipoListaPrecio => $itemTipoListaPrecio) {          
            $existeTipoListaPrecioEnListaPrecioMercaderia = false;      

            foreach($dataListaPreciosMercaderia as $keyListaPrecioMercaderia => $itemListaPrecioMercaderia) {
              if ($itemListaPrecioMercaderia["IdTipoListaPrecio"]==$itemTipoListaPrecio["IdTipoListaPrecio"] ) {
                $existeTipoListaPrecioEnListaPrecioMercaderia = true;
                $dataListaPrecioMercaderia = $itemListaPrecioMercaderia;
              }
            }
            
            if (!$existeTipoListaPrecioEnListaPrecioMercaderia) {
              $dataListaPrecioMercaderia = $this->ListaPrecioMercaderia;
              $dataListaPrecioMercaderia["IdProducto"]=$itemMercaderia["IdProducto"];
              $dataListaPrecioMercaderia["IdTipoListaPrecio"]=$itemTipoListaPrecio["IdTipoListaPrecio"];
              $dataListaPrecioMercaderia["NombreTipoListaPrecio"]=$itemTipoListaPrecio["NombreTipoListaPrecio"];
              $dataListaPrecioMercaderia["IndicadorPrecioMinimo"]=$itemTipoListaPrecio["IndicadorPrecioMinimo"];
              $dataListaPrecioMercaderia["Precio"]=0;
              $dataListaPrecioMercaderia["IndicadorProducto"]="";
            }
            
            $resultadoListaPreciosMercaderia[]=$dataListaPrecioMercaderia;              
        }

      $resultadoMercaderias[$key]["ListaDePrecios"] = $resultadoListaPreciosMercaderia;      
    }
    
    return $resultadoMercaderias;
  }

  function ObtenerListaPrecioMercaderiaPorIdProductoYIdTipoListaPrecio($data) {
    $resultado = $this->mListaPrecioMercaderia->ObtenerListaPrecioMercaderiaPorIdProductoYIdTipoListaPrecio($data);
    return $resultado;
  }

  function GuardarListaPreciosProducto($data) {
    
    if ($data["CheckAplicaMismoPrecio"] == 1) {      
      $dataSedes = $data["Sedes"];
    }
    else {
      $dataSedes = array(0=>array("IdSede"=>$data["IdSede"]));
    }
    
    foreach($dataSedes as $keySede => $valueSede) {
      foreach ($data["DetallesListaPrecio"] as $key => $input) {

        $dataMercaderia["IdProducto"]=$input["IdProducto"];
        $dataMercaderia["PrecioUnitario"]=$input["PrecioUnitario"];      
        $resultadoMercaderia=$this->mMercaderia->ActualizarMercaderia($dataMercaderia);
        
        foreach($input["ListaDePrecios"] as $key2 => $value) {
          
          if(is_string($value["Precio"])){$value["Precio"] = str_replace(',',"",$value["Precio"]);}      

          if(is_string($value["CostoPromedioPonderado"])){$value["CostoPromedioPonderado"] = str_replace(',',"",$value["CostoPromedioPonderado"]);}
          if(is_string($value["MargenPorcentaje"])){$value["MargenPorcentaje"] = str_replace(',',"",$value["MargenPorcentaje"]);}
          if(is_string($value["MargenUtilidad"])){$value["MargenUtilidad"] = str_replace(',',"",$value["MargenUtilidad"]);}
          if(is_string($value["ValorVenta"])){$value["ValorVenta"] = str_replace(',',"",$value["ValorVenta"]);}
          if(is_string($value["ValorIGV"])){$value["ValorIGV"] = str_replace(',',"",$value["ValorIGV"]);}
          if(is_string($value["PrecioPromedioCompra"])){$value["PrecioPromedioCompra"] = str_replace(',',"",$value["PrecioPromedioCompra"]);}
          $value["UltimoPrecio"] = $value["Precio"];
          $value["FechaUltimoPrecio"] = $this->Base->ObtenerFechaServidor();
          $value["IdSede"]=$valueSede["IdSede"];
          $response = $this->ObtenerListaPrecioMercaderiaPorIdProductoYIdTipoListaPrecio($value);
                  
          if(count($response) > 0) {
            $value["IdListaPrecioMercaderia"] = $response[0]["IdListaPrecioMercaderia"];
            $resultado = $this->ActualizarListaPrecioMercaderia($value);
          }
          else {
            $value["IdListaPrecioMercaderia"] = "";
            $resultado = $this->InsertarListaPrecioMercaderia($value);
          }
        }
      }
    }

    return $data;
  }
  
  
  function ConsultarTodosListasPrecioMercaderia($data) {
    $resultado = $this->mListaPrecioMercaderia->ConsultarTodosListasPrecioMercaderia($data);
    return $resultado;
  }
  
} 

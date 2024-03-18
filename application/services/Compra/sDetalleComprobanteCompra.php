<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleComprobanteCompra extends MY_Service {

  public $DetalleComprobanteCompra = array();
  public $ComprobanteCompra;

  public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('herencia');
        $this->load->helper("date");
        $this->load->model('Compra/mDetalleComprobanteCompra');
        $this->load->service('Catalogo/sProducto');
        $this->load->service('Catalogo/sMercaderia');
        $this->load->service('Catalogo/sProductoProveedor');
        $this->load->service('Configuracion/General/sConstanteSistema');
        $this->load->service('Configuracion/Catalogo/sTipoListaPrecio');        
        $this->load->service('Catalogo/sListaPrecioMercaderia');        
        $this->load->service("Inventario/sLoteProducto");
        $this->DetalleComprobanteCompra = $this->mDetalleComprobanteCompra->DetalleComprobanteCompra;
  }

  function Cargar() {
    $this->DetalleComprobanteCompra["IdDetalleComprobanteCompra"] = "0";
    $this->DetalleComprobanteCompra["CodigoMercaderia"] = "";
    $this->DetalleComprobanteCompra["AbreviaturaUnidadMedida"] = "";
    $this->DetalleComprobanteCompra["NombreProducto"] = "";
    $this->DetalleComprobanteCompra["AfectoIGV"] = "1";
    $this->DetalleComprobanteCompra["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleComprobanteCompra["ISCItem"] = 0.00;
    $this->DetalleComprobanteCompra["IGVItem"] = 0.00;
    $this->DetalleComprobanteCompra["DescuentoUnitario"] = "";
    $this->DetalleComprobanteCompra["CostoUnitario"] = "";
    $this->DetalleComprobanteCompra["PrecioUnitario"] = "";
    $this->DetalleComprobanteCompra["CostoItem"] = "";
    $this->DetalleComprobanteCompra["PrecioItem"] = "";
    $this->DetalleComprobanteCompra["Cantidad"] = "";
    $this->DetalleComprobanteCompra["CodigoProductoProveedor"] = "";
    $this->DetalleComprobanteCompra["CodigoProductoProveedorSave"] = 0;
    $this->DetalleComprobanteCompra["IdProductoProveedor"] = "";
    $this->DetalleComprobanteCompra["NumeroItem"] = "";
    $this->DetalleComprobanteCompra["NumeroItemDua"] = "";

    $this->DetalleComprobanteCompra["IdLoteProducto"] = "";
    $this->DetalleComprobanteCompra["NumeroLote"] = "";
    $this->DetalleComprobanteCompra["FechaVencimiento"] = "";
    $this->DetalleComprobanteCompra["FechaHoy"] = $this->Base->ObtenerFechaServidor("d/m/Y");

    $this->DetalleComprobanteCompra["AnteriorIdProducto"]="";
    $this->DetalleComprobanteCompra["AnteriorCantidad"]="";
    $this->DetalleComprobanteCompra["ParametroProductoDuplicado"]= $this->sConstanteSistema->ObtenerParametroProductoDuplicado();
    $this->DetalleComprobanteCompra["NombreLargoProducto"] = "";
    
    $this->DetalleComprobanteCompra["AfectoBonificacion"] = "";
    $this->DetalleComprobanteCompra["ParametroBonificacion"] = $this->sConstanteSistema->ObtenerParametroBonificacion();

    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleComprobanteCompra" =>$this->DetalleComprobanteCompra
    );

    $resultado = array_merge($this->DetalleComprobanteCompra,$data);

    return $resultado;
  }

  
  function CargarComprobanteCompra($data) {
    $this->ComprobanteCompra = $data;
  }

  function ActualizarDetallesComprobanteCompra($IdComprobanteCompra,$data) {//, $sede, $tipocompra = null, $dataCompra = array("FechaEmision" => ""))
  
    if (count($data["IndicadorReferenciaCostoAgregado"]) == '0' ) {
      //borrar todos los elementos
      $this->mDetalleComprobanteCompra->BorrarDetalleComprobanteCompraPorIdComprobanteCompra($IdComprobanteCompra);
      //insertar todos los elementos
      unset($data["IndicadorReferenciaCostoAgregado"]);
      $resultado=$this->InsertarDetallesComprobanteCompra($IdComprobanteCompra,$data);//, $sede, $tipocompra, $dataCompra);
      return $resultado;

    } else {
      //actualiza sin hacer un delete
      $dataResultado = [];
      unset($data["IndicadorReferenciaCostoAgregado"]);
      foreach ($data as $key => $value) {
        if ($value["IdProducto"] != null) {
          $resultado = $this->mDetalleComprobanteCompra->ActualizarDetalleComprobanteCompra($value);
          $resultado["NombreProducto"] = $value['NombreProducto'];
          $resultado["CodigoMercaderia"] = $value['CodigoMercaderia'];
          $resultado["CodigoProductoProveedor"] = $value['CodigoProductoProveedor'];
          array_push($dataResultado, $resultado);
        }
      }
      return $dataResultado;
    }
  }

  function ActualizarDetalleComprobanteCompra($data)
  {
      $resultado = $this->mDetalleComprobanteCompra->ActualizarDetalleComprobanteCompra($data);
      return $resultado;
  }

  function ConsultarDetalleComprobanteCompraPorId($data)
  {
      $resultado = $this->mDetalleComprobanteCompra->ConsultarDetalleComprobanteCompraPorId($data);
      return $resultado;
  }

  function BorrarDetallesComprobanteCompra($data)
  {
    foreach($data as $key => $value) {
      $IdDetalleComprobanteCompra=$value["IdDetalleComprobanteCompra"];
      $this->mDetalleComprobanteCompra->BorrarDetalleComprobanteCompra($IdDetalleComprobanteCompra);
    }
  }

  function EliminarDetallesPorIdComprobanteCompra($data) // cambiar a estado E
  {
    $id['IdComprobanteCompra'] = $data['IdComprobanteCompra'];
    $resultado = $this->mDetalleComprobanteCompra->EliminarDetallesPorIdComprobanteCompra($id);
    return $resultado;
  }

  function InsertarDetallesComprobanteCompra($IdComprobanteCompra,$data) //, $sede, $tipocompra, $dataCompra = array("FechaEmision" => ""))
  {
    $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();
    for($i=0; $i < count($data) ; $i++)
    {
      
      if ($data[$i]["IdProducto"] != null)
      {
        if($parametro_lote == 1 && $this->ComprobanteCompra["IdTipoCompra"] == ID_TIPOCOMPRA_MERCADERIA) //$tipocompra
        {
          $data[$i]["IdAsignacionSede"] = $this->ComprobanteCompra["IdAsignacionSede"];//$sede
          if ($data[$i]["FechaVencimiento"]!="")  {
            $data[$i]["FechaVencimiento"] =$data[$i]["FechaVencimiento"]== "" ? "" : convertToDate($data[$i]["FechaVencimiento"]);
          }

          $loteproducto = $this->sLoteProducto->AgregarLoteProducto($data[$i]);
          $data[$i]["IdLoteProducto"] = $loteproducto["IdLoteProducto"];
        }
      
        $data[$i]["IdComprobanteCompra"] = $IdComprobanteCompra;
        $data[$i]["IdDetalleComprobanteCompra"]="";
        $data[$i]["NumeroItem"] = $i+1;

        if(is_string($data[$i]["Cantidad"])){$data[$i]["Cantidad"] = str_replace(',',"",$data[$i]["Cantidad"]);}
        if(is_string($data[$i]["CostoUnitario"])){$data[$i]["CostoUnitario"] = str_replace(',',"",$data[$i]["CostoUnitario"]);}
        if(is_string($data[$i]["PrecioUnitario"])){$data[$i]["PrecioUnitario"] = str_replace(',',"",$data[$i]["PrecioUnitario"]);}
        if(is_string($data[$i]["DescuentoUnitario"])){$data[$i]["DescuentoUnitario"] = str_replace(',',"",$data[$i]["DescuentoUnitario"]);}
        if(is_string($data[$i]["CostoItem"])){$data[$i]["CostoItem"] = str_replace(',',"",$data[$i]["CostoItem"]);}
        if(is_string($data[$i]["PrecioItem"])){$data[$i]["PrecioItem"] = str_replace(',',"",$data[$i]["PrecioItem"]);}
        if(is_string($data[$i]["IGVItem"])){$data[$i]["IGVItem"] = str_replace(',',"",$data[$i]["IGVItem"]);}
        $data[$i]["SaldoPendienteNotaCredito"]=$data[$i]["CostoItem"];
        
        if($this->ComprobanteCompra["IdTipoCompra"] == ID_TIPOCOMPRA_MERCADERIA) //$dataCompra
        {
          $dataProducto["IdProducto"] = $data[$i]["IdProducto"];
          $dataProducto["CostoUnitarioCompra"] = $data[$i]["CostoUnitario"];
          $dataProducto["PrecioUnitarioCompra"] = $data[$i]["PrecioUnitario"];
          $dataProducto["FechaIngresoCompra"] = $this->ComprobanteCompra["FechaEmision"];
          $dataProducto["IdMonedaCompra"] = $this->ComprobanteCompra["IdMoneda"];
          $responseProducto = $this->sMercaderia->ActualizarMercaderiaSinValidacion($dataProducto);
        }

        $resultado = $this->mDetalleComprobanteCompra->InsertarDetalleComprobanteCompra($data[$i]);
        $data[$i]["IdSede"] = $this->ComprobanteCompra["IdSede"];//$sede
        $data[$i]["IdDetalleComprobanteCompra"] = $resultado;
        $data[$i]["IndicadorEstado"] = ESTADO_ACTIVO;
        
        $this->ActualizarPrecioMinimo($data[$i]);        
      }
    }

    return $data;
  }

  function ConsultarDetallesComprobanteCompra($data){
    $resultado = array();
    $data["IdTipoCompra"] = (array_key_exists("IdTipoCompra", $data)) ? $data["IdTipoCompra"] : ID_TIPOCOMPRA_MERCADERIA;
    if($data["IdTipoCompra"] == ID_TIPOCOMPRA_COSTOAGREGADO)
    {
      $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesComprobanteCompraCostoAgregado($data);
    }
    elseif($data["IdTipoCompra"] == ID_TIPOCOMPRA_GASTO)
    {
      $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesComprobanteCompraGasto($data);
    }
    else
    {
      $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($data);
    }

    $this->DetalleComprobanteCompra["IdDetalleComprobanteCompra"] = "0";
    $this->DetalleComprobanteCompra["CodigoMercaderia"] = "";
    $this->DetalleComprobanteCompra["AbreviaturaUnidadMedida"] = "";
    $this->DetalleComprobanteCompra["NombreProducto"] = "";
    $this->DetalleComprobanteCompra["ISCItem"] = 0.00;
    $this->DetalleComprobanteCompra["IGVItem"] = 0.00;
    $this->DetalleComprobanteCompra["DescuentoUnitario"] = "";
    $this->DetalleComprobanteCompra["CostoUnitario"] = "";
    $this->DetalleComprobanteCompra["Cantidad"] = "";
    $this->DetalleComprobanteCompra["IndicadorEstado"] = ESTADO_ACTIVO;
    $this->DetalleComprobanteCompra["CodigoProductoProveedor"] = "";
    $this->DetalleComprobanteCompra["IdProductoProveedor"] = "";
    $this->DetalleComprobanteCompra["CodigoProductoProveedorSave"] = 0;
    $this->DetalleComprobanteCompra["NumeroItem"] = "";
    $this->DetalleComprobanteCompra["NumeroItemDua"] = "";

    $this->DetalleComprobanteCompra["IdLoteProducto"] = "";
    $this->DetalleComprobanteCompra["NumeroLote"] = "";
    $this->DetalleComprobanteCompra["FechaVencimiento"] = "";
    $this->DetalleComprobanteCompra["FechaHoy"] = $this->Base->ObtenerFechaServidor("d/m/Y");

    $this->DetalleComprobanteCompra["AnteriorIdProducto"]="";
    $this->DetalleComprobanteCompra["AnteriorCantidad"]="";
    $this->DetalleComprobanteCompra["AfectoBonificacion"]="";
    $ParametroProductoDuplicado= $this->sConstanteSistema->ObtenerParametroProductoDuplicado();
    $this->DetalleComprobanteCompra["ParametroBonificacion"] = $this->sConstanteSistema->ObtenerParametroBonificacion();

    foreach ($resultado as $key => $value) {
      $filtro["IdProducto"] = $value["IdProducto"];
      $filtro["IdProveedor"] = $data["IdProveedor"];
      $codigoproductoproveedor = $this->sProductoProveedor->ConsultarProductoProveedorPorIdProductoAndIdProveedor($filtro);

      $resultado[$key]["ParametroProductoDuplicado"] = $ParametroProductoDuplicado;
      $resultado[$key]["CodigoProductoProveedor"] = count($codigoproductoproveedor) != 0 ? $codigoproductoproveedor[0]["CodigoProductoProveedor"] : "";
      $resultado[$key]["CodigoProductoProveedorSave"]=1;
      $resultado[$key]["IdProductoProveedor"]=count($codigoproductoproveedor) != 0 ? $codigoproductoproveedor[0]["IdProductoProveedor"] : "";
      $resultado[$key]["IdAsignacionSede"]=$data["IdAsignacionSede"];
      // $resultado[$key]["IdSede"]=$data["IdSede"];
      $resultado[$key]["ParametroBonificacion"]=$this->DetalleComprobanteCompra["ParametroBonificacion"];
      
      if(array_key_exists("FechaVencimiento", $value))
      {
        $resultado[$key]["FechaVencimiento"]= ($value["FechaVencimiento"] != "" || $value["FechaVencimiento"] != NULL) ? convertirFechaES($value["FechaVencimiento"]) : "";
      }


      $resultado[$key]["Producto"]=$this->sProducto->Producto;

      $resultado[$key]["AnteriorIdProducto"]=$value["IdProducto"];
      $resultado[$key]["AnteriorCantidad"]=$value["Cantidad"];

      $resultado[$key]["NuevoDetalleComprobanteCompra"]=$this->DetalleComprobanteCompra;
    }

    return $resultado;
  }

  function ValidarDetalleComprobanteCompra($data, $i = 0)
  {
    $resultado="";
    if(strlen($data["IdProducto"]) == 0)
    {
      $resultado = $resultado."En el ".($i)."° item del comprobante de compra, no se han encontrado resultados para tu búsqueda de cliente."."\n";
    }

    $cantidad = $data["Cantidad"];
    if(is_string($data["Cantidad"])){$cantidad = str_replace(',',"",$data["Cantidad"]);}
    if(floatval($cantidad) <= 0 || !is_numeric($cantidad) )
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de compra la cantidad debe ser mayor que cero y numérico."."\n";
    }

    $costounitario = $data["CostoUnitario"];
    if(is_string($data["CostoUnitario"])){$costounitario = str_replace(',',"",$data["CostoUnitario"]);}
    if(floatval($costounitario) < 0 || !is_numeric($costounitario) )
    {
      $resultado =$resultado ."En el ".($i)."° item del comprobante de compra el precio debe ser mayor que o igual que cero y numérico."."\n";
    }

    $descuentounitario = $data["DescuentoUnitario"];
    if(is_string($data["DescuentoUnitario"])){$descuentounitario = str_replace(',',"",$data["DescuentoUnitario"]);}
    if(floatval($descuentounitario) < 0 || !is_numeric($descuentounitario) )
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de compra el descuento debe ser mayor que o igual que cero y numérico."."\n";
    }

    $costoitem = $data["CostoItem"];
    if(is_string($data["CostoItem"])){$costoitem = str_replace(',',"",$data["CostoItem"]);}
    if(floatval($costoitem) < 0)
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de compra el descuento no debe ser mayor al importe."."\n";
    }

    $precioitem = $data["PrecioItem"];
    if(is_string($data["PrecioItem"])){$precioitem = str_replace(',',"",$data["PrecioItem"]);}
    if(floatval($precioitem) < 0)
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de compra el descuento no debe ser mayor al importe."."\n";
    }

    return $resultado;
  }

  function ValidarDetallesComprobanteCompra($data)
  {
    $resultado="";
    $total =count($data);

    if($total == 0)
      $resultado =$resultado."Ingresar por lo menos un item al comprobante."."\n";
    foreach ($data as $key => $value)
    {
      if($key < ($total - 1))//recorre hasta la penultima
      {
        $resultado = $resultado.$this->ValidarDetalleComprobanteCompra($value,$key+1);
      }
    }
    return $resultado;
  }

  function ActualizarPrecioMinimo($data) {
    
    $resultadoPrecio=$this->sListaPrecioMercaderia->ObtenerPrecioMinimoPorIdProducto($data);    
    if (count($resultadoPrecio)>0) {
      $resultadoPrecio[0]["Precio"] = $data["PrecioUnitario"];
      $this->sListaPrecioMercaderia->ActualizarListaPrecioMercaderia($resultadoPrecio[0]);
      
    }
    else {
      $resultadoTipoListaPrecio = $this->sTipoListaPrecio->ObtenerTipoListaPrecioMinimo();
      if (count($resultadoTipoListaPrecio) > 0) {
        $resultadoPrecio["IdTipoListaPrecio"]=$resultadoTipoListaPrecio[0]["IdTipoListaPrecio"];
        $resultadoPrecio["IdProducto"]=$data["IdProducto"];
        $resultadoPrecio["Precio"]=$data["PrecioUnitario"];
        $this->sListaPrecioMercaderia->InsertarListaPrecioMercaderia($resultadoPrecio);
      }
    }

  }

}

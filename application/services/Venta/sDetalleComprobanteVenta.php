<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleComprobanteVenta extends MY_Service {

  public $DetalleComprobanteVenta = array();
  public $EstadoPendienteNota = "";
  public $ComprobanteVenta;

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');    
    $this->load->model('Venta/mDetalleComprobanteVenta');
    $this->load->service('Catalogo/sProducto');
    $this->load->service('Catalogo/sMercaderia');
    $this->load->service('Inventario/sAlmacenMercaderia');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->service('Catalogo/sListaPrecioMercaderia');    
    $this->load->service('Seguridad/sUsuario');  
    $this->load->service('Venta/sComisionVentaProductoVendedor');      

    $this->DetalleComprobanteVenta = $this->mDetalleComprobanteVenta->DetalleComprobanteVenta;

    //DETALLE PARSEADA
    $this->DetalleComprobanteVenta["IdDetalleComprobanteVenta"] = "0";
    $this->DetalleComprobanteVenta["CodigoMercaderia"] = "";
    $this->DetalleComprobanteVenta["AbreviaturaUnidadMedida"] = "";
    $this->DetalleComprobanteVenta["NombreProducto"] = "";
    $this->DetalleComprobanteVenta["NombreLargoProducto"] = "";
    $this->DetalleComprobanteVenta["NombreMarca"] = "";
    $this->DetalleComprobanteVenta["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleComprobanteVenta["ISCItem"] = 0.00;
    $this->DetalleComprobanteVenta["IGVItem"] = 0.00;
    $this->DetalleComprobanteVenta["DescuentoItem"] = "";
    $this->DetalleComprobanteVenta["DescuentoUnitario"] = "";
    $this->DetalleComprobanteVenta["DescuentoValorUnitario"] = "";
    $this->DetalleComprobanteVenta["AfectoICBPER"] = 0;
    $this->DetalleComprobanteVenta["SumatoriaICBP"] = 0;
    $this->DetalleComprobanteVenta["PrecioUnitario"] = "";
    $this->DetalleComprobanteVenta["ValorUnitario"] = "";
    $this->DetalleComprobanteVenta["Cantidad"] = "";
    $this->DetalleComprobanteVenta["CantidadPrevia"] = 0;
    $this->DetalleComprobanteVenta["StockProducto"] = "";
    $this->DetalleComprobanteVenta["ListaPrecios"] = array();

    $this->DetalleComprobanteVenta["IdLoteProducto"] = null;
    $this->DetalleComprobanteVenta["NumeroLote"] = "";
    $this->DetalleComprobanteVenta["ListaLotes"] = array();

    $this->DetalleComprobanteVenta["IdDocumentoSalidaZofraProducto"] = null;
    $this->DetalleComprobanteVenta["NumeroDocumentoSalidaZofra"] = "";
    $this->DetalleComprobanteVenta["ListaZofra"] = array();

    $this->DetalleComprobanteVenta["IdDuaProducto"] = null;
    $this->DetalleComprobanteVenta["NumeroDua"] = "";
    $this->DetalleComprobanteVenta["ListaDua"] = array();
    $this->DetalleComprobanteVenta["AfectoBonificacion"] = "";
    $this->DetalleComprobanteVenta["ProductoBonificado"] = false;
    $this->DetalleComprobanteVenta["ListaBonificaciones"] = array();

    $this->DetalleComprobanteVenta["IdOrigenMercaderia"] = "";
    $this->DetalleComprobanteVenta["SaldoPendienteNotaCredito"] = 0.00;
    
    //PARA RESTAURANTE
    $this->DetalleComprobanteVenta["IndicadorImpresion"] = "0";
    $this->DetalleComprobanteVenta["IndicadorVistaPrecioMinimo"] = $this->sesionusuario->obtener_sesion_indicador_vista_precio_minimo();
    
    //PARA GRIFO
    $this->DetalleComprobanteVenta["EstadoCampoCalculo"] = "";

    //CALCULO SOLES A DOLARES
    $this->DetalleComprobanteVenta["PrecioUnitarioSolesDolares"] = '';
    $this->DetalleComprobanteVenta["EstadoCampoCalculo"] = "";      
  }

  function CargarComprobanteVenta($data) {
    $this->ComprobanteVenta = $data;
  }

  function Cargar() {
    $this->DetalleComprobanteVenta["ParametroVentaStockNegativo"] = $this->sConstanteSistema->ObtenerParametroVentaStockNegativo();
    $this->DetalleComprobanteVenta["ParametroCalcularPrecio"] = 1;
    $this->DetalleComprobanteVenta["SaldoPendienteNotaCredito"] = 0.00;
    $this->DetalleComprobanteVenta["IndicadorVistaPrecioMinimo"] = $this->sesionusuario->obtener_sesion_indicador_vista_precio_minimo();
    $this->DetalleComprobanteVenta["IndicadorOperacionGratuita"] = INDICADOR_NO_OPERACION_GRATUITA;
    $this->DetalleComprobanteVenta["IdTipoPrecio"] = ID_TIPO_PRECIO_UNITARIO_INCLUIDO_IGV;
    $this->DetalleComprobanteVenta["ValorReferencial"] = 0.00;//Unitario
    $this->DetalleComprobanteVenta["ValorReferencialItem"] = 0.00;
    $this->DetalleComprobanteVenta["IGVReferencialItem"] = 0.00;

    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleComprobanteVenta" =>$this->DetalleComprobanteVenta
    );

    $resultado = array_merge($this->DetalleComprobanteVenta, $data);
    return $resultado;
  }

  function ActualizarDetallesComprobanteVenta($IdComprobanteVenta,$data) {
    //borrar todos los elementos
    $parametroComisionMetaVentaProducto = $this->sConstanteSistema->ObtenerParametroComisionMetaVentaProducto();
    if ($parametroComisionMetaVentaProducto == 0 ) {
      $this->mDetalleComprobanteVenta->BorrarDetalleComprobanteVentaPorIdComprobanteVenta($IdComprobanteVenta);
    }
    else {      
      $dataDetallesComprobanteVenta=$this->ConsultarDetallesComprobanteVentaPorIdComprobanteVenta($this->ComprobanteVenta);
      foreach($dataDetallesComprobanteVenta as $key => $value) {        
        //Aplicamos Comision de Ventas
        $value["IdPeriodo"]=$this->ComprobanteVenta["IdPeriodoAnterior"];
        $value["IdUsuarioVendedor"]=$this->ComprobanteVenta["IdUsuarioVendedorAnterior"];
        
        if ($this->ComprobanteVenta["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $this->ComprobanteVenta["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION) {
          $this->sComisionVentaProductoVendedor->AumentarComisionVentaProductoVendedor($value);
        }
        else {
          $this->sComisionVentaProductoVendedor->DisminuirComisionVentaProductoVendedor($value);
        }
        $this->mDetalleComprobanteVenta->BorrarDetalleComprobanteVenta($value["IdDetalleComprobanteVenta"]);
      }

    }    
    
    //insertar todos los elementos
    $resultado=$this->InsertarDetallesComprobanteVenta($IdComprobanteVenta,$data);
    return $resultado;
  }

  function ActualizarDetalleComprobanteVenta($data)
  {
    $resultado = $this->mDetalleComprobanteVenta->ActualizarDetalleComprobanteVenta($data);
    return $resultado;
  }

  function ActualizarDetalleComprobanteVentaPorIdComprobanteVenta($data)
  {
    $resultado = $this->mDetalleComprobanteVenta->ActualizarDetalleComprobanteVentaPorIdComprobanteVenta($data);
    return $resultado;
  }

  function ConsultarDetalleComprobanteVentaPorId($data) {
      $resultado = $this->mDetalleComprobanteVenta->ConsultarDetalleComprobanteVentaPorId($data);
      return $resultado;
  }

  function BorrarDetallesComprobanteVenta($data)
  {
    foreach($data as $key => $value) {
      $IdDetalleComprobanteVenta = $value["IdDetalleComprobanteVenta"];
      $this->mDetalleComprobanteVenta->BorrarDetalleComprobanteVenta($IdDetalleComprobanteVenta);
    }
  }

  function AnularDetallesPorIdComprobanteVenta($data) {
    $parametroComisionMetaVentaProducto = $this->sConstanteSistema->ObtenerParametroComisionMetaVentaProducto();
    if ($parametroComisionMetaVentaProducto == 1 ) {    
      foreach($data["DetallesComprobanteVenta"] as $key => $value) {        
        if ($value["IdProducto"]!=null) {
          $value["IdPeriodo"]=$this->ComprobanteVenta["IdPeriodo"];
          $value["IdUsuarioVendedor"]=$this->ComprobanteVenta["IdUsuarioVendedor"];

          if ($this->ComprobanteVenta["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $this->ComprobanteVenta["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION) {
            $this->sComisionVentaProductoVendedor->AumentarComisionVentaProductoVendedor($value);
          }
          else {
            $this->sComisionVentaProductoVendedor->DisminuirComisionVentaProductoVendedor($value);
          }          
        }
      }      
    }
  }

  function EliminarDetallesPorIdComprobanteVenta($data) {
    $id['IdComprobanteVenta'] = $data['IdComprobanteVenta'];    
    
    $parametroComisionMetaVentaProducto = $this->sConstanteSistema->ObtenerParametroComisionMetaVentaProducto();
    if ($parametroComisionMetaVentaProducto == 1 ) {    
      foreach($data["DetallesComprobanteVenta"] as $key => $value) {        
        if ($value["IdProducto"]!=null) {
          $value["IdPeriodo"]=$this->ComprobanteVenta["IdPeriodo"];
          $value["IdUsuarioVendedor"]=$this->ComprobanteVenta["IdUsuarioVendedor"];
          
          if ($this->ComprobanteVenta["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $this->ComprobanteVenta["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION) {
            $this->sComisionVentaProductoVendedor->AumentarComisionVentaProductoVendedor($value);
          }
          else {
            $this->sComisionVentaProductoVendedor->DisminuirComisionVentaProductoVendedor($value);
          }
        }
      }      
    }

    $resultado = $this->mDetalleComprobanteVenta->EliminarDetallesPorIdComprobanteVenta($id);
    return $resultado;
  }

  function InsertarDetallesComprobanteVenta($IdComprobanteVenta,$data) {
    $parametroComisionMetaVentaProducto = $this->sConstanteSistema->ObtenerParametroComisionMetaVentaProducto();

    for($i=0; $i < count($data) ; $i++) {
      if ($data[$i]["IdProducto"] != null) {
        $data[$i]["IdComprobanteVenta"] = $IdComprobanteVenta;
        $data[$i]["IdDetalleComprobanteVenta"]="";
        $data[$i]["NumeroItem"] = $i+1;

        if(is_string($data[$i]["Cantidad"])){$data[$i]["Cantidad"] = str_replace(',',"",$data[$i]["Cantidad"]);}
        if(is_string($data[$i]["PrecioUnitario"])){$data[$i]["PrecioUnitario"] = str_replace(',',"",$data[$i]["PrecioUnitario"]);}
        if(is_string($data[$i]["DescuentoUnitario"])){$data[$i]["DescuentoUnitario"] = str_replace(',',"",$data[$i]["DescuentoUnitario"]);}
        if(is_string($data[$i]["SubTotal"])){$data[$i]["SubTotal"] = str_replace(',',"",$data[$i]["SubTotal"]);}
        $data[$i]["SaldoPendienteNotaCredito"] = $data[$i]["SubTotal"];
        $data[$i]["SaldoPendientePreVenta"] = $data[$i]["Cantidad"];
        $data[$i]["SaldoPendienteGuiaRemision"] = $data[$i]["Cantidad"];

        $data[$i]["IdDocumentoSalidaZofraProducto"] = (array_key_exists("IdDocumentoSalidaZofraProducto", $data[$i])) ? $data[$i]["IdDocumentoSalidaZofraProducto"] : NULL;
        $data[$i]["IdDuaProducto"] = (array_key_exists("IdDuaProducto", $data[$i])) ? $data[$i]["IdDuaProducto"] : NULL;

        $resultado = $this->mDetalleComprobanteVenta->InsertarDetalleComprobanteVenta($data[$i]);
        $data[$i]["IdDetalleComprobanteVenta"] = $resultado;
        $data[$i]["IndicadorEstado"] = ESTADO_ACTIVO;

        //Actualizamos Mercaderia
        $data_merca["IdProducto"] = $data[$i]["IdProducto"];
        $data_merca["UltimoPrecio"] = $data[$i]["PrecioUnitario"];
        $this->sMercaderia->ActualizarMercaderiaSinValidacion($data_merca);

        if ($parametroComisionMetaVentaProducto == 1 ) {
          //Aplicamos Comision de Ventas
          $data[$i]["IdPeriodo"]=$this->ComprobanteVenta["IdPeriodo"];
          $data[$i]["IdUsuarioVendedor"]=$this->ComprobanteVenta["IdUsuarioVendedor"];
          if ($this->ComprobanteVenta["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $this->ComprobanteVenta["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION) {
            $this->sComisionVentaProductoVendedor->DisminuirComisionVentaProductoVendedor($data[$i]);
          }
          else {
            $this->sComisionVentaProductoVendedor->AumentarComisionVentaProductoVendedor($data[$i]);
          }
        }
      }
    }

    return $data;
  }

  function ConsultarDetallesComprobanteVenta($data){
    $resultado = null;
    if($data["IdTipoVenta"] == TIPO_VENTA_MERCADERIA) {
      $resultado = $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVentaPorMercaderia($data);
    }
    else if($data["IdTipoVenta"] == TIPO_VENTA_SERVICIOS){
      $resultado = $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVentaPorServicio($data);
    }
    else if($data["IdTipoVenta"] == TIPO_VENTA_ACTIVO_FIJO){
      $resultado = $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVentaPorActivoFijo($data);
    }
    else {
      $resultado = $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVentaOtraVenta($data);
    }

    $ParametroVentaStockNegativo = $this->sConstanteSistema->ObtenerParametroVentaStockNegativo();
    
    foreach ($resultado as $key => $value) {
      $filtro["IdAsignacionSede"] = $data["IdAsignacionSede"];
      $filtro["IdProducto"] = $value["IdProducto"];
      $resultado[$key]["IdDetalleReferencia"] =$value["IdDetalleComprobanteVenta"];
      $StockProducto = $this->sAlmacenMercaderia->ObtenerAlmacenMercaderiaPorProductoAlmacen($filtro);

      $resultado[$key]["StockProducto"]= (count($StockProducto)>0) ? $StockProducto[0]['StockMercaderia'] : "";

      $resultado[$key]["ParametroVentaStockNegativo"] = $ParametroVentaStockNegativo;
      
      $resultado[$key]["Producto"] = $this->sProducto->Producto;
      $resultado[$key]["PorcentajeIGV"] = PORCENTAJE_IGV;
      $resultado[$key]["SumaTributo"] = $value["ISCItem"] + $value["IGVItem"]+$value["IGVReferencialItem"];
      $resultado[$key]["PrecioUnitarioSoles"] = "";
      $resultado[$key]["CantidadPrevia"] = $value["Cantidad"];
      $resultado[$key]["NuevoDetalleComprobanteVenta"] = $this->DetalleComprobanteVenta;
      $resultado[$key]["ListaPrecios"] = array();
      $resultado[$key]["ListaLotes"] = array();
      $resultado[$key]["ListaZofra"] = array();
      $resultado[$key]["ListaDua"] = array();
      $resultado[$key]["ListaBonificaciones"] = array();

      if($data["IdTipoVenta"] == TIPO_VENTA_MERCADERIA) {
      $resultado[$key]["NombreMarca"] = $value["NombreMarca"] == "NO ESPECIFICADO" ? "-" : $value["NombreMarca"];
      }
      $resultado[$key]["ProductoBonificado"] = $resultado[$key]["IndicadorOperacionGratuita"] == 1 ? true : false;
      
      if(array_key_exists("IdMotivoNotaCredito",$data)) {
        $resultado[$key]["ValorUnitarioNC"] = $data["IdMotivoNotaCredito"] == 5 ? $resultado[$key]["ValorUnitarioDescuentoUnitario"] : $resultado[$key]["ValorUnitario"];
        $resultado[$key]["IGVItemNC"] =  $data["IdMotivoNotaCredito"] == 5 ? $resultado[$key]["IGVItemDescuentoUnitario"] : $resultado[$key]["IGVItem"];
        $resultado[$key]["ValorVentaItemNC"] =  $data["IdMotivoNotaCredito"] == 5 ? $resultado[$key]["ValorVentaItemDescuentoUnitario"] : $resultado[$key]["ValorVentaItem"];
        $resultado[$key]["PrecioUnitarioNC"] =  $data["IdMotivoNotaCredito"] == 5 ? $resultado[$key]["DescuentoUnitario"] : $resultado[$key]["PrecioUnitario"];        
        $resultado[$key]["SumaTributo"] = $value["ISCItem"] + $resultado[$key]["IGVItemNC"] +$value["IGVReferencialItem"];
        $resultado[$key]["ValorReferencial"] =$value["ValorReferencial"];
      }
      else {
        $resultado[$key]["SumaTributo"] = $value["ISCItem"] + $resultado[$key]["IGVItem"] +$value["IGVReferencialItem"];        
      }
      
      
      
    }

    return $resultado;
  }

  function ValidarDetalleComprobanteVenta($data, $sede, $i = 0)
  {
    $resultado="";

    if(strlen($data["IdProducto"]) == 0)
    {
      $resultado = $resultado."En el ".($i)."° item del comprobante de venta, no se han encontrado resultados para tu búsqueda de cliente."."\n";
    }

    $cantidad = str_replace(',', '', $data["Cantidad"]);
    $preciounitario = str_replace(',', '', $data["PrecioUnitario"]);
    $descuentoitem = str_replace(',', '', $data["DescuentoUnitario"]);

    $parametroRestriccionCantidad = $this->sConstanteSistema->ObtenerParametroRestriccionCantidad();
    if($parametroRestriccionCantidad == 1)
    {
      $data["IdAsignacionSede"] = $sede;
      $almacenmercaderia = $this->sAlmacenMercaderia->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);
      if(count($almacenmercaderia) > 0)
      {
        if($data["CantidadPrevia"] == 0)
        {
          if($data["Cantidad"] > $almacenmercaderia[0]["StockMercaderia"])
          {
            $resultado =$resultado."En el ".($i)."° item del comprobante de venta la cantidad supera al stock de almacen."."\n";
          }
        }
        else {         
            $cantidadreal = $almacenmercaderia[0]["StockMercaderia"] + ($data["CantidadPrevia"] - $data["Cantidad"]);
            
            if($cantidadreal < 0) {
              $resultado =$resultado."En el ".($i)."° item del comprobante de venta la cantidad supera al stock de almacen."."\n";
            }
        }
      }
      else {
        $resultado =$resultado."En el ".($i)."° item del comprobante de venta la cantidad supera al stock de almacen."."\n";
      }
    }

    if($cantidad <= 0 || !is_numeric($cantidad) )
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de venta la cantidad debe ser mayor que cero y numérico."."\n";
    }

    if($preciounitario < 0 || !is_numeric($preciounitario) )
    {
      $resultado =$resultado ."En el ".($i)."° item del comprobante de venta el precio debe ser mayor que o igual que cero y numérico."."\n";
    }

    if($descuentoitem < 0 || !is_numeric($descuentoitem) )
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de venta el descuento debe ser mayor que o igual que cero y numérico."."\n";
    }

    if($data["SubTotal"] < 0)
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de venta el descuento no debe ser mayor al importe."."\n";
    }

    $dataUsuario["AliasUsuarioVenta"] = $this->sesionusuario->obtener_alias_usuario();
    $resultadoUsuario = $this->sUsuario->ObtenerUsuarioPorAliasUsuarioVenta($dataUsuario);
    if (count($resultadoUsuario) > 0 ) {
      if ($resultadoUsuario[0]["IndicadorExoneradoPrecioMinimo"] == 0) {
        $resultadoPrecioMinimo = $this->ValidarSiPrecioUnitarioEsMenorAlPrecioMinimo($data);
        $resultado = $resultado.$resultadoPrecioMinimo;
      }
    }    

    return $resultado;
  }

  function ValidarDetallesComprobanteVenta($data, $sede = null)
  {
    $resultado="";
    $total = count($data);

    if($total == 0)
      $resultado =$resultado."Ingresar por lo menos un item al comprobante."."\n";

    foreach ($data as $key => $value)
    {

      if($key < ($total - 1))//recorre hasta la penultima
      {
        $resultado = $resultado.$this->ValidarDetalleComprobanteVenta($value,$sede,$key+1);
      }

    }
    return $resultado;
  }

  function ConsultarSaldosPorDetallesPrecuenta($data)
  {
    $resultado = $this->mDetalleComprobanteVenta->ConsultarSaldosPorDetallesPrecuenta($data);
    return $resultado;
  }

  function ValidarSiPrecioUnitarioEsMenorAlPrecioMinimo($data) {
    $data["IdSede"]=$this->ComprobanteVenta["IdSede"];
    $resultado = $this->sListaPrecioMercaderia->ObtenerPrecioMinimoPorIdProducto($data);
    if(count($resultado) > 0) {
      $PrecioMinimo=$resultado[0]["Precio"];
      $PrecioUnitario=$data["PrecioUnitario"];
      
      if($PrecioMinimo > $PrecioUnitario) {      
        return "El producto ".$data["CodigoMercaderia"]." - ".$data["NombreProducto"]." debe tener un precio minimo de S/. ".$PrecioMinimo."\n";
      }
      else {
        return "";
      }
    } 
    else{
      return "";
    }
  }
  
  function ConsultarDetallesComprobanteVentaPorIdComprobanteVenta($data) {
      $resultado = $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($data);
      return $resultado;
  }

}

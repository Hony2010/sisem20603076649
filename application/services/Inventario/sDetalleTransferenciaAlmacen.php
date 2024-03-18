<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleTransferenciaAlmacen extends MY_Service {

  public $DetalleTransferenciaAlmacen = array();

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->model('Inventario/mDetalleTransferenciaAlmacen');
    $this->load->service('Catalogo/sProducto');
    $this->load->service('Catalogo/sMercaderia');          
    $this->load->service("Inventario/sMovimientoAlmacen");
    $this->load->service("Inventario/sAlmacenMercaderia");
    $this->DetalleTransferenciaAlmacen = $this->mDetalleTransferenciaAlmacen->DetalleTransferenciaAlmacen;
  }

  public function Cargar() {     
    $this->DetalleTransferenciaAlmacen["CodigoMercaderia"] = "";
    $this->DetalleTransferenciaAlmacen["NombreProducto"] = "";
    $this->DetalleTransferenciaAlmacen["NombreUnidadMedida"] = "";
    $this->DetalleTransferenciaAlmacen["AbreviaturaUnidadMedida"] = "";
    $this->DetalleTransferenciaAlmacen["NumeroItem"] = "0";
    $this->DetalleTransferenciaAlmacen["Cantidad"] = "";
    $this->DetalleTransferenciaAlmacen["ValorUnitario"] = "";
    $this->DetalleTransferenciaAlmacen["IdMoneda"] = ID_MONEDA_SOLES;
    $this->DetalleTransferenciaAlmacen["NumeroLote"] = "";
    $this->DetalleTransferenciaAlmacen["FechaVencimientoLote"] = "";
    $this->DetalleTransferenciaAlmacen["NumeroDocumentoSalidaZofra"] = "";   
    $this->DetalleTransferenciaAlmacen["FechaEmisionDocumentoSalidaZofra"] = "";   
    $this->DetalleTransferenciaAlmacen["NumeroDua"] = "";   
    $this->DetalleTransferenciaAlmacen["FechaEmisionDua"] = "";   
    $this->DetalleTransferenciaAlmacen["NumeroItemDua"] = "";   
    $this->DetalleTransferenciaAlmacen["FechaHoy"] = $this->Base->ObtenerFechaServidor("d/m/Y");
    
    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleTransferenciaAlmacen" =>$this->DetalleTransferenciaAlmacen
    );

    $resultado = array_merge($this->DetalleTransferenciaAlmacen,$data);
    return $resultado;
  }
 
  function InsertarDetallesTransferenciaAlmacen($data) {
    $dataTransferenciaAlmacen = $data;
    $detalle = $data["DetallesTransferenciaAlmacen"];
    $productos = [];
    
    for($i=0; $i < count($detalle) ; $i++) {
          
      if ($detalle[$i]["IdProducto"] != null) {
        array_push($productos, $detalle[$i]["IdProducto"]);
        $detalle[$i]["IdTransferenciaAlmacen"] = $data["IdTransferenciaAlmacen"];
        $resultado = $this->InsertarDetalleTransferenciaAlmacen($detalle[$i],$i + 1);
        
        if(is_array($resultado)) {
          $detalle[$i] = $resultado;              
          $dataTransferenciaAlmacen["DetallesTransferenciaAlmacen"][0] = $resultado;
          $resultadoMovimientosAlmacen = $this->sMovimientoAlmacen->InsertarMovimientosAlmacenPorDetalleTransferenciaAlmacen($dataTransferenciaAlmacen);
        }
        else {
          return $resultado;
        }
      }
    }
    
    $data["DetallesTransferenciaAlmacen"] = $detalle;
    $data["IdProductos"]=$productos;
    
    return $data;
  }
  
  function ConsultarDetallesTransferenciaAlmacen($data) {
    
    $resultado = $this->mDetalleTransferenciaAlmacen->ConsultarDetallesTransferenciaAlmacen($data);    
    foreach ($resultado as $key => $value) {
      $resultado[$key]["NuevoDetalleTransferenciaAlmacen"] = $this->DetalleTransferenciaAlmacen;
    }
    return $resultado;
  }

  function ActualizarDetallesTransferenciaAlmacen($data) {

    $resultado = $this->BorrarDetallesTransferenciaAlmacen($data);
    $resultado = $this->InsertarDetallesTransferenciaAlmacen($data);

    return $resultado;
  }  

  function AnularDetallesTransferenciaAlmacen($data) { 
    //consultar y recorrer los items antes del cambio
    $resultadoDetalles = $this->ConsultarDetallesTransferenciaAlmacen($data);
    $productos = [];
    if (count($resultadoDetalles) > 0) {
      foreach($resultadoDetalles as $key => $item) {
        if ($item["IdProducto"] != null) {
          array_push($productos, $item["IdProducto"]);
          $dataTransferenciaAlmacen=$data;
          $dataTransferenciaAlmacen["DetallesTransferenciaAlmacen"][0] = $item;
          $resultadoMovimientosAlmacen = $this->sMovimientoAlmacen->BorrarMovimientosAlmacenPorDetalleTransferenciaAlmacen($dataTransferenciaAlmacen);
          
          if (!is_array($resultadoMovimientosAlmacen)) {
            return $resultadoMovimientosAlmacen;
          }
        }
      }
    }

    $data["DetallesTransferenciaAlmacen"] = $resultadoDetalles;
    $data["IdProductos"]=$productos;

    return $data;
  }

  function BorrarDetallesTransferenciaAlmacen($data) { 
    //consultar y recorrer los items antes del cambio
    $resultadoDetalles = $this->ConsultarDetallesTransferenciaAlmacen($data);
    $productos = [];
    
    if (count($resultadoDetalles) > 0) {      
      foreach($resultadoDetalles as $key => $item) {
        //borra cada item
        $resultadoDetalle = $this->BorrarDetalleTransferenciaAlmacen($item);
        //borra el movimientos del detalle correspondiente
        $dataTransferenciaAlmacen=$data;
        $dataTransferenciaAlmacen["DetallesTransferenciaAlmacen"][0] = $item;
        $resultadoMovimientosAlmacen = $this->sMovimientoAlmacen->BorrarMovimientosAlmacenPorDetalleTransferenciaAlmacen($dataTransferenciaAlmacen);
        
        if (!is_array($resultadoMovimientosAlmacen)) {
          return $resultadoMovimientosAlmacen;
        } 
      }
    }

    $data["DetallesTransferenciaAlmacen"] = $resultadoDetalles;
    $data["IdProductos"]=$productos;

    return $data;
  }
  
  function InsertarDetalleTransferenciaAlmacen($data,$indice = null) {
    $resultado = $this->ValidarDetalleTransferenciaAlmacen($data);

    if($resultado == "") {
      $data["IdDetalleTransferenciaAlmacen"]="";
      $data["Cantidad"] = is_string($data["Cantidad"]) ? str_replace(',',"",$data["Cantidad"]) : $data["Cantidad"];
      $data["ValorUnitario"] = is_string($data["ValorUnitario"]) ? str_replace(',',"",$data["ValorUnitario"]) : $data["ValorUnitario"];
      $data["NumeroItem"] = $indice == null ? 1 : $indice;      
      $data["IndicadorEstado"]=ESTADO_ACTIVO;
      $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
      $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
      $resultado = $this->mDetalleTransferenciaAlmacen->InsertarDetalleTransferenciaAlmacen($data);   

      return $resultado;
    }
    else {
      return $resultado;
    }

  }

  function ValidarDetalleTransferenciaAlmacen($data) {    
    return "";
  }

  function ActualizarDetalleTransferenciaAlmacen($data) {
    $resultado = $this->mDetalleTransferenciaAlmacen->ActualizarDetalleTransferenciaAlmacen($data);   
    return $resultado;
  }

  function BorrarDetalleTransferenciaAlmacen($data) {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();          
    $resultado=$this->ActualizarDetalleTransferenciaAlmacen($data);
    return $resultado;
  }

}
<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleNotaSalida extends MY_Service {

  public $DetalleNotaSalida = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->model('Inventario/mDetalleNotaSalida');
    $this->load->model('Venta/mDetalleComprobanteVenta');
    $this->load->model('Compra/mDetalleComprobanteCompra');
    $this->load->service('Catalogo/sProducto');
    
    //DATA INICIAL PARA DETALLE NOTA SALIDA
    $this->DetalleNotaSalida = $this->mDetalleNotaSalida->DetalleNotaSalida;

    $this->DetalleNotaSalida["IdDetalleNotaSalida"] = "0";
    $this->DetalleNotaSalida["CodigoMercaderia"] = "";
    $this->DetalleNotaSalida["AbreviaturaUnidadMedida"] = "";
    $this->DetalleNotaSalida["NombreProducto"] = "";
    $this->DetalleNotaSalida["IdTipoAfectacionIGV"] = "1";
    $this->DetalleNotaSalida["IdTipoSistemaISC"] = "1";
    $this->DetalleNotaSalida["ISCItem"] = 0.00;
    $this->DetalleNotaSalida["IGVItem"] = 0.00;
    $this->DetalleNotaSalida["DescuentoItem"] = "";
    $this->DetalleNotaSalida["ValorUnitario"] = "";
    $this->DetalleNotaSalida["Cantidad"] = "";
    $this->DetalleNotaSalida["IdTipoPrecio"] = 1;
    $this->DetalleNotaSalida["SaldoPendienteSalida"] = "";
    $this->DetalleNotaSalida["PrecioUnitario"] = "";

    $this->DetalleNotaSalida["IdDetalleComprobanteVenta"] = "0";
    $this->DetalleNotaSalida["IdDetalleComprobanteCompra"] = "0";
  }

  function Cargar()
  {
    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleNotaSalida" =>$this->DetalleNotaSalida
    );

    $resultado = array_merge($this->DetalleNotaSalida,$data);

    return $resultado;
  }

  function ActualizarDetalleNotaSalida($data)
  {
    $resultado = $this->mDetalleNotaSalida->ActualizarDetalleNotaSalida($data);
    return $resultado;
  }

  function ActualizarDetallesNotaSalida($IdNotaSalida,$data)
  {
    //borrar todos los elementos
    $this->mDetalleNotaSalida->BorrarDetalleNotaSalidaPorIdNotaSalida($IdNotaSalida);

    //insertar todos los elementos
    $resultado=$this->InsertarDetallesNotaSalida($IdNotaSalida,$data);
    return $resultado;
  }

  function BorrarDetalleNotaSalida($data)
  {
    $IdDetalleNotaSalida=$data["IdNotaSalida"];
    $this->mDetalleNotaSalida->BorrarDetalleNotaSalidaPorIdNotaSalidaEstado($IdDetalleNotaSalida);
    return $data;
  }

  // function BorrarDetallesNotaSalida($data)
  // {
  //   foreach($data as $key => $value) {
  //     $IdDetalleNotaSalida=$value["IdDetalleNotaSalida"];
  //     $this->mDetalleNotaSalida->BorrarDetalleNotaSalida($IdDetalleNotaSalida);
  //   }
  // }

  function InsertarDetallesNotaSalida($IdNotaSalida,$data)
  {
    for($i=0; $i < count($data) ; $i++)
    {
      if ($data[$i]["IdProducto"] != null)
      {
        $data[$i]["IdNotaSalida"] = $IdNotaSalida;
        $data[$i]["IdDetalleNotaSalida"]="";
        $data[$i]["NumeroItem"] = $i+1;
        if(is_string($data[$i]["Cantidad"])){$data[$i]["Cantidad"] = str_replace(',',"",$data[$i]["Cantidad"]);}
        $data[$i]["SaldoPendienteComprobante"] = $data[$i]["Cantidad"];
        if(array_key_exists('ValorUnitario', $data[$i])){
          if(is_string($data[$i]["ValorUnitario"])){$data[$i]["ValorUnitario"] = str_replace(',',"",$data[$i]["ValorUnitario"]);}
        }
        $resultado = $this->mDetalleNotaSalida->InsertarDetalleNotaSalida($data[$i]);
        $data[$i]["IdDetalleNotaSalida"] = $resultado;
      }
    }

    return $data;
  }

  function ConsultarDetallesComprobanteVenta($data){
    $resultado = $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVentaPorNotaSalida($data);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Modulo"] = ID_MODULO_VENTA;
      $resultado[$key]["IdDetalleComprobante"] = $value["IdDetalleComprobanteVenta"];
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleNotaSalida"]=$this->DetalleNotaSalida;
    }

    return $resultado;
  }

  function ConsultarDetallesComprobanteCompra($data){
    $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesComprobanteCompraPorNotaSalida($data);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["ValorUnitario"] = 0.00;
      $resultado[$key]["Modulo"] = ID_MODULO_COMPRA;
      $resultado[$key]["IdMotivoNotaCredito"] = $data["IdMotivoNotaCredito"];
      $resultado[$key]["IdDetalleComprobante"] = $value["IdDetalleComprobanteCompra"];
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleNotaSalida"]=$this->DetalleNotaSalida;
    }

    return $resultado;
  }

  function ConsultarDetallesNotaSalida($data){
    $resultado = $this->mDetalleNotaSalida->ConsultarDetallesNotaSalida($data);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["SaldoPendienteSalida"]=$value["Cantidad"];
      $resultado[$key]["NuevoDetalleNotaSalida"]=$this->DetalleNotaSalida;
    }

    return $resultado;
  }

  // function ValidarDetalleNotaSalida($data, $i = 0)
  // {
  //   $resultado="";

  //   if(strlen($data["IdProducto"]) == 0)
  //   {
  //     $resultado = $resultado."En el ".($i)."° item del comprobante de venta, no se han encontrado resultados para tu búsqueda de cliente."."\n";
  //   }

  //   if($data["Cantidad"] <= 0 || !is_numeric($data["Cantidad"]) )
  //   {
  //     $resultado =$resultado."En el ".($i)."° item del comprobante de venta la cantidad debe ser mayor que cero y numérico."."\n";
  //   }

  //   if($data["PrecioUnitario"] < 0 || !is_numeric($data["PrecioUnitario"]) )
  //   {
  //     $resultado =$resultado ."En el ".($i)."° item del comprobante de venta el precio debe ser mayor que o igual que cero y numérico."."\n";
  //   }

  //   if($data["DescuentoItem"] < 0 || !is_numeric($data["DescuentoItem"]) )
  //   {
  //     $resultado =$resultado."En el ".($i)."° item del comprobante de venta el descuento debe ser mayor que o igual que cero y numérico."."\n";
  //   }

  //   if($data["SubTotal"] < 0)
  //   {
  //     $resultado =$resultado."En el ".($i)."° item del comprobante de venta el descuento no debe ser mayor al importe."."\n";
  //   }

  //   return $resultado;
  // }

  // function ValidarDetallesNotaSalida($data)
  // {
  //   $resultado="";
  //   $total =count($data);

  //   if($total == 0)
  //     $resultado =$resultado."Ingresar por lo menos un item al comprobante."."\n";

  //   foreach ($data as $key => $value)
  //   {

  //     if($key < ($total - 1))//recorre hasta la penultima
  //     {
  //       $resultado = $resultado.$this->ValidarDetalleNotaSalida($value,$key+1);
  //     }

  //   }

  //   return $resultado;
  // }

  function ActualizarSaldoPendienteDetalleComprobante($data)
  {
    //borrar todos los elementos
    foreach ($data as $key => $value) {
      if($value["Modulo"] == ID_MODULO_VENTA)
      {
        $nueva_data["SaldoPendienteSalida"] = $value["SaldoPendienteSalida"] - $value["Cantidad"];
        $nueva_data["IdDetalleComprobanteVenta"] = $value["IdDetalleComprobanteVenta"];
        $this->mDetalleComprobanteVenta->ActualizarDetalleComprobanteVenta($nueva_data);
      }
      else {
        $nueva_data["SaldoPendienteSalida"] = $value["SaldoPendienteSalida"] - $value["Cantidad"];
        $nueva_data["IdDetalleComprobanteCompra"] = $value["IdDetalleComprobanteCompra"];
        $this->mDetalleComprobanteCompra->ActualizarDetalleComprobanteCompra($nueva_data);
      }
    }
    return "";
  }

  function TotalSaldoPendienteSalidaVenta($data)
  {
    //borrar todos los elementos
    $resultado = $this->mDetalleComprobanteVenta->TotalSaldoPendienteSalida($data);
    return $resultado;
  }

  function TotalSaldoPendienteSalidaCompra($data)
  {
    //borrar todos los elementos
    $resultado = $this->mDetalleComprobanteCompra->TotalSaldoPendienteSalida($data);
    return $resultado;
  }

  //[PARA CAMBIOS DE VINCULACION Y REVERSION DE SALDOS]

}

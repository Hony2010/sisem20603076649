<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleNotaEntrada extends MY_Service {

  public $DetalleNotaEntrada = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->model('Inventario/mDetalleNotaEntrada');
    $this->load->model('Venta/mDetalleComprobanteVenta');
    $this->load->model('Compra/mDetalleComprobanteCompra');
    $this->load->service('Catalogo/sProducto');

    //DATA INICIAL PARA DETALLE NOTA ENTRADA
    $this->DetalleNotaEntrada = $this->mDetalleNotaEntrada->DetalleNotaEntrada;

    $this->DetalleNotaEntrada["IdDetalleNotaEntrada"] = "0";
    $this->DetalleNotaEntrada["CodigoMercaderia"] = "";
    $this->DetalleNotaEntrada["AbreviaturaUnidadMedida"] = "";
    $this->DetalleNotaEntrada["NombreProducto"] = "";
    $this->DetalleNotaEntrada["IdTipoAfectacionIGV"] = "1";
    $this->DetalleNotaEntrada["IdTipoSistemaISC"] = "1";
    $this->DetalleNotaEntrada["ISCItem"] = 0.00;
    $this->DetalleNotaEntrada["IGVItem"] = 0.00;
    $this->DetalleNotaEntrada["DescuentoItem"] = "";
    $this->DetalleNotaEntrada["ValorUnitario"] = "";
    $this->DetalleNotaEntrada["Cantidad"] = "";
    $this->DetalleNotaEntrada["IdTipoPrecio"] = 1;
    $this->DetalleNotaEntrada["SaldoPendienteEntrada"] = "";
    $this->DetalleNotaEntrada["PrecioUnitario"] = "";

    $this->DetalleNotaEntrada["IdDetalleComprobanteVenta"] = "0";
    $this->DetalleNotaEntrada["IdDetalleComprobanteCompra"] = "0";
  }

  function Cargar()
  { 
    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleNotaEntrada" =>$this->DetalleNotaEntrada
    );

    $resultado = array_merge($this->DetalleNotaEntrada,$data);

    return $resultado;
  }

  function ActualizarDetallesNotaEntrada($IdNotaEntrada,$data)
  {
    //borrar todos los elementos
    $this->mDetalleNotaEntrada->BorrarDetalleNotaEntradaPorIdNotaEntrada($IdNotaEntrada);

    //insertar todos los elementos
    $resultado=$this->InsertarDetallesNotaEntrada($IdNotaEntrada,$data);
    return $resultado;
  }

  function BorrarDetalleNotaEntrada($data)
  {
    $IdDetalleNotaEntrada=$data["IdNotaEntrada"];
    $this->mDetalleNotaEntrada->BorrarDetalleNotaEntradaPorIdNotaEntradaEstado($IdDetalleNotaEntrada);
    return $data;
  }

  // function BorrarDetallesNotaEntrada($data)
  // {
  //   foreach($data as $key => $value) {
  //     $IdDetalleNotaEntrada=$value["IdDetalleNotaEntrada"];
  //     $this->mDetalleNotaEntrada->BorrarDetalleNotaEntrada($IdDetalleNotaEntrada);
  //   }
  // }

  function InsertarDetallesNotaEntrada($IdNotaEntrada,$data)
  {
    for($i=0; $i < count($data) ; $i++)
    {
      if ($data[$i]["IdProducto"] != null)
      {
        $data[$i]["IdNotaEntrada"] = $IdNotaEntrada;
        $data[$i]["IdDetalleNotaEntrada"]="";
        $data[$i]["NumeroItem"] = $i+1;
        if(is_string($data[$i]["Cantidad"])){$data[$i]["Cantidad"] = str_replace(',',"",$data[$i]["Cantidad"]);}
        $data[$i]["SaldoPendienteComprobante"] = $data[$i]["Cantidad"];
        if(array_key_exists('ValorUnitario', $data[$i])){
          if(is_string($data[$i]["ValorUnitario"])){$data[$i]["ValorUnitario"] = str_replace(',',"",$data[$i]["ValorUnitario"]);}
        }
        $resultado = $this->mDetalleNotaEntrada->InsertarDetalleNotaEntrada($data[$i]);
        $data[$i]["IdDetalleNotaEntrada"] = $resultado;
      }
    }

    return $data;
  }

  function ConsultarDetallesComprobanteVenta($data, $buscador = true){
    if($buscador)
    {
      $resultado = $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVentaPorNotaEntrada($data);
    }
    else {
      $resultado = $this->mDetalleComprobanteVenta->ConsultarDetallesComprobanteVenta($data);
    }

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Modulo"] = ID_MODULO_VENTA;
      $resultado[$key]["IdDetalleComprobante"] = $value["IdDetalleComprobanteVenta"];
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleNotaEntrada"]=$this->DetalleNotaEntrada;
    }

    return $resultado;
  }

  function ConsultarDetallesComprobanteCompra($data, $buscador = true){
    if($buscador)
    {
      $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesComprobanteCompraPorNotaEntrada($data);
    }
    else {
      $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($data);
    }

    foreach ($resultado as $key => $value) {
      $resultado[$key]["ValorUnitario"] = 0.00;
      $resultado[$key]["Modulo"] = ID_MODULO_COMPRA;
      $resultado[$key]["IdDetalleComprobante"] = $value["IdDetalleComprobanteCompra"];
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleNotaEntrada"]=$this->DetalleNotaEntrada;
    }

    return $resultado;
  }

  function ConsultarDetallesNotaEntrada($data){
    $resultado = $this->mDetalleNotaEntrada->ConsultarDetallesNotaEntrada($data);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["SaldoPendienteEntrada"]=$value["Cantidad"];
      $resultado[$key]["NuevoDetalleNotaEntrada"]=$this->DetalleNotaEntrada;
    }

    return $resultado;
  }

  // function ValidarDetalleNotaEntrada($data, $i = 0)
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

  // function ValidarDetallesNotaEntrada($data)
  // {
  //   $resultado="";
  //   $total =count($data);

  //   if($total == 0)
  //     $resultado =$resultado."Ingresar por lo menos un item al comprobante."."\n";

  //   foreach ($data as $key => $value)
  //   {

  //     if($key < ($total - 1))//recorre hasta la penultima
  //     {
  //       $resultado = $resultado.$this->ValidarDetalleNotaEntrada($value,$key+1);
  //     }

  //   }

  //   return $resultado;
  // }

  function ActualizarSaldoPendienteDetalleComprobanteVenta($data)
  {
    //borrar todos los elementos
    foreach ($data as $key => $value) {
      if($value["Modulo"] == ID_MODULO_VENTA)
      {
        $nueva_data["SaldoPendienteEntrada"] = $value["SaldoPendienteEntrada"] - $value["Cantidad"];
        $nueva_data["IdDetalleComprobanteVenta"] = $value["IdDetalleComprobanteVenta"];
        $this->mDetalleComprobanteVenta->ActualizarDetalleComprobanteVenta($nueva_data);
      }
      else {
        $nueva_data["SaldoPendienteEntrada"] = $value["SaldoPendienteEntrada"] - $value["Cantidad"];
        $nueva_data["IdDetalleComprobanteCompra"] = $value["IdDetalleComprobanteCompra"];
        $this->mDetalleComprobanteCompra->ActualizarDetalleComprobanteCompra($nueva_data);
      }
    }
    return "";
  }

  function TotalSaldoPendienteEntradaVenta($data)
  {
    //borrar todos los elementos
    $resultado = $this->mDetalleComprobanteVenta->TotalSaldoPendienteEntrada($data);
    return $resultado;
  }

  function TotalSaldoPendienteEntradaCompra($data)
  {
    //borrar todos los elementos
    $resultado = $this->mDetalleComprobanteCompra->TotalSaldoPendienteEntrada($data);
    return $resultado;
  }

}

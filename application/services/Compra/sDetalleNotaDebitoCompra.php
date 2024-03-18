<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleNotaDebitoCompra extends MY_Service {

  public $DetalleNotaDebitoCompra = array();

  public function __construct()
  {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('herencia');
        $this->load->model('Compra/mDetalleComprobanteCompra');
        $this->load->service('Catalogo/sProducto');
        $this->DetalleNotaDebitoCompra = $this->mDetalleComprobanteCompra->DetalleComprobanteCompra;
  }

  function Cargar()
  {
    $this->DetalleNotaDebitoCompra["IdDetalleNotaDebitoCompra"] = "0";
    $this->DetalleNotaDebitoCompra["CodigoMercaderia"] = "";
    $this->DetalleNotaDebitoCompra["AbreviaturaUnidadMedida"] = "";
    $this->DetalleNotaDebitoCompra["NombreProducto"] = "";
    $this->DetalleNotaDebitoCompra["AfectoIGV"] = "1";
    $this->DetalleNotaDebitoCompra["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleNotaDebitoCompra["ISCItem"] = 0.00;
    $this->DetalleNotaDebitoCompra["IGVItem"] = 0.00;
    $this->DetalleNotaDebitoCompra["DescuentoItem"] = "";
    $this->DetalleNotaDebitoCompra["PrecioUnitario"] = "";
    $this->DetalleNotaDebitoCompra["Cantidad"] = "";
    $this->DetalleNotaDebitoCompra["IdReferenciaDCV"] = "";

    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleNotaDebitoCompra" =>$this->DetalleNotaDebitoCompra
    );

    $resultado = array_merge($this->DetalleNotaDebitoCompra,$data);

    return $resultado;
  }

  function ActualizarDetallesComprobanteCompra($IdComprobanteCompra,$data)
  {
      //borrar todos los elementos
      $this->mDetalleComprobanteCompra->BorrarDetalleNotaDebitoCompraPorIdComprobanteCompra($IdComprobanteCompra);

      //insertar todos los elementos
      $resultado=$this->InsertarDetallesComprobanteCompra($IdComprobanteCompra,$data);
      return $resultado;
  }

  function ActualizarDetalleNotaDebitoCompra($data)
  {
    $resultado = $this->mDetalleComprobanteCompra->ActualizarDetalleNotaDebitoCompra($data);

    return $resultado;
  }


  function BorrarDetallesNotaDebitoCompra($data)
  {
    $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($data);

    if(count($resultado) > 0)
    {
      foreach($resultado as $key => $value) {
        // $IdDetalleNotaDebitoCompra=$value["IdDetalleNotaDebitoCompra"];
        $IdDetalleNotaDebitoCompra=$value["IdDetalleComprobanteCompra"];
        $this->mDetalleComprobanteCompra->BorrarDetalleComprobanteCompra($IdDetalleNotaDebitoCompra);
      }
    }
  }

  function InsertarDetallesComprobanteCompra($IdComprobanteCompra,$data)
  {
     for($i=0; $i < count($data) ; $i++) {
       if ($data[$i]["IdProducto"] != null) {
         $data[$i]["IdComprobanteCompra"] = $IdComprobanteCompra;
         $data[$i]["IdDetalleNotaDebitoCompra"]="";
         $data[$i]["NumeroItem"] = $i+1;
         $resultado = $this->mDetalleComprobanteCompra->InsertarDetalleNotaDebitoCompra($data[$i]);
         $data[$i]["IdDetalleNotaDebitoCompra"] = $resultado;
       }
     }

     return $data;
  }

  function ConsultarDetallesComprobanteCompra($data){
    $resultado = $this->mDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($data);

    $this->DetalleNotaDebitoCompra["IdDetalleNotaDebitoCompra"] = "0";
    $this->DetalleNotaDebitoCompra["CodigoMercaderia"] = "";
    $this->DetalleNotaDebitoCompra["AbreviaturaUnidadMedida"] = "";
    $this->DetalleNotaDebitoCompra["NombreProducto"] = "";
    $this->DetalleNotaDebitoCompra["CodigoTipoAfectacionIGV"] = "";
    $this->DetalleNotaDebitoCompra["ISCItem"] = 0.00;
    $this->DetalleNotaDebitoCompra["IGVItem"] = 0.00;
    $this->DetalleNotaDebitoCompra["DescuentoItem"] = "";
    $this->DetalleNotaDebitoCompra["PrecioUnitario"] = "";
    $this->DetalleNotaDebitoCompra["Cantidad"] = "";

    foreach ($resultado as $key => $value) {
      $resultado[$key]["Producto"]=$this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleNotaDebitoCompra"]=$this->DetalleNotaDebitoCompra;
    }

    return $resultado;
  }

  function ValidarDetalleNotaDebitoCompra($data, $i = 0)
  {
    $resultado="";

    if(strlen($data["IdProducto"]) == 0)
    {
      $resultado = $resultado."En el ".($i)."° item del comprobante de venta, no se han encontrado resultados para tu búsqueda de cliente."."\n";
    }

    if($data["Cantidad"] <= 0 || !is_numeric($data["Cantidad"]) )
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de venta la cantidad debe ser mayor que cero y numérico."."\n";
    }

    if($data["PrecioUnitario"] < 0 || !is_numeric($data["PrecioUnitario"]) )
    {
      $resultado =$resultado ."En el ".($i)."° item del comprobante de venta el precio debe ser mayor que o igual que cero y numérico."."\n";
    }

    if($data["DescuentoItem"] < 0 || !is_numeric($data["DescuentoItem"]) )
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de venta el descuento debe ser mayor que o igual que cero y numérico."."\n";
    }

    if($data["SubTotal"] < 0)
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de venta el descuento no debe ser mayor al importe."."\n";
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
        $resultado = $resultado.$this->ValidarDetalleNotaDebitoCompra($value,$key+1);
      }

    }

    return $resultado;
  }


}

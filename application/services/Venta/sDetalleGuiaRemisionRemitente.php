<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sDetalleGuiaRemisionRemitente extends MY_Service {

  public $DetalleGuiaRemisionRemitente = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->model('Venta/mDetalleGuiaRemisionRemitente');
    $this->load->service('Catalogo/sProducto');
    $this->load->service('Catalogo/sMercaderia');
    $this->load->service('Inventario/sAlmacenMercaderia');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->DetalleGuiaRemisionRemitente = $this->mDetalleGuiaRemisionRemitente->DetalleGuiaRemisionRemitente;

    //DETALLE PARSEADA
    $this->DetalleGuiaRemisionRemitente["IdDetalleGuiaRemisionRemitente"] = "0";
    $this->DetalleGuiaRemisionRemitente["CodigoMercaderia"] = "";
    $this->DetalleGuiaRemisionRemitente["AbreviaturaUnidadMedida"] = "";
    $this->DetalleGuiaRemisionRemitente["NombreProducto"] = "";
    $this->DetalleGuiaRemisionRemitente["NombreMarca"] = "";
    $this->DetalleGuiaRemisionRemitente["CantidadPrevia"] = "";
    $this->DetalleGuiaRemisionRemitente["Cantidad"] = "";
    $this->DetalleGuiaRemisionRemitente["SaldoPendienteGuiaRemision"] = "";
    
    $this->DetalleGuiaRemisionRemitente["IdLoteProducto"] = null;
    $this->DetalleGuiaRemisionRemitente["NumeroLote"] = "";
    $this->DetalleGuiaRemisionRemitente["ListaLotes"] = array();

  }

  function Cargar()
  {
    $data = array(
      "Producto" => $this->sProducto->Producto,
      "NuevoDetalleGuiaRemisionRemitente" =>$this->DetalleGuiaRemisionRemitente
    );
    $resultado = array_merge($this->DetalleGuiaRemisionRemitente, $data);
    return $resultado;
  }

  function ActualizarDetallesGuiaRemisionRemitente($IdGuiaRemisionRemitente,$data)
  {
    //borrar todos los elementos
    $this->mDetalleGuiaRemisionRemitente->BorrarDetalleGuiaRemisionRemitentePorIdGuiaRemisionRemitente($IdGuiaRemisionRemitente);

    //insertar todos los elementos
    $resultado=$this->InsertarDetallesGuiaRemisionRemitente($IdGuiaRemisionRemitente,$data);
    return $resultado;
  }

  function ActualizarDetalleGuiaRemisionRemitente($data)
  {
    $resultado = $this->mDetalleGuiaRemisionRemitente->ActualizarDetalleGuiaRemisionRemitente($data);
    return $resultado;
  }

  function ActualizarDetalleGuiaRemisionRemitentePorIdGuiaRemisionRemitente($data)
  {
    $resultado = $this->mDetalleGuiaRemisionRemitente->ActualizarDetalleGuiaRemisionRemitentePorIdGuiaRemisionRemitente($data);
    return $resultado;
  }

  function ConsultarDetalleGuiaRemisionRemitentePorId($data)
  {
      $resultado = $this->mDetalleGuiaRemisionRemitente->ConsultarDetalleGuiaRemisionRemitentePorId($data);
      return $resultado;
  }

  function BorrarDetallesGuiaRemisionRemitente($data)
  {
    foreach($data as $key => $value) {
      $IdDetalleGuiaRemisionRemitente = $value["IdDetalleGuiaRemisionRemitente"];
      $this->mDetalleGuiaRemisionRemitente->BorrarDetalleGuiaRemisionRemitente($IdDetalleGuiaRemisionRemitente);
    }
  }

  function EliminarDetallesPorIdGuiaRemisionRemitente($data) // cambiar a estado E
  {
    $id['IdGuiaRemisionRemitente'] = $data['IdGuiaRemisionRemitente'];
    $resultado = $this->mDetalleGuiaRemisionRemitente->EliminarDetallesPorIdGuiaRemisionRemitente($id);
    return $resultado;
  }

  function AnularDetallesPorIdGuiaRemisionRemitente($data) // cambiar a estado E
  {
    $id['IdGuiaRemisionRemitente'] = $data['IdGuiaRemisionRemitente'];
    $resultado = $this->mDetalleGuiaRemisionRemitente->AnularDetallesPorIdGuiaRemisionRemitente($id);
    return $resultado;
  }

  function InsertarDetallesGuiaRemisionRemitente($IdGuiaRemisionRemitente, $data)
  {  
    for($i=0; $i < count($data) ; $i++) {
      if ($data[$i]["IdProducto"] != null) {
        $data[$i]["IdGuiaRemisionRemitente"] = $IdGuiaRemisionRemitente;
        $data[$i]["IdDetalleGuiaRemisionRemitente"]="";
        $data[$i]["NumeroItem"] = $i+1;

        $data[$i]["Cantidad"] =  (is_string($data[$i]["Cantidad"])) ? str_replace(',',"",$data[$i]["Cantidad"]) : $data[$i]["Cantidad"]; 
        $data[$i]["Peso"] =  (is_string($data[$i]["Peso"])) ? str_replace(',',"",$data[$i]["Peso"]) : $data[$i]["Peso"]; 

        $resultado = $this->mDetalleGuiaRemisionRemitente->InsertarDetalleGuiaRemisionRemitente($data[$i]);
        $data[$i]["IdDetalleGuiaRemisionRemitente"] = $resultado;
        $data[$i]["IndicadorEstado"] = ESTADO_ACTIVO;
      }
    }
    // print_r($data);exit;
    return $data;
  }

  function ConsultarDetallesGuiaRemisionRemitente($data, $estadoSecundarioActivo = false){
    $resultado = $this->mDetalleGuiaRemisionRemitente->ConsultarDetallesGuiaRemisionRemitente($data, $estadoSecundarioActivo);
    foreach ($resultado as $key => $value) {
      $resultado[$key]["CantidadPrevia"] =$resultado[$key]["Cantidad"];
      $resultado[$key]["Producto"] = $this->sProducto->Producto;
      $resultado[$key]["NuevoDetalleGuiaRemisionRemitente"] = $this->DetalleGuiaRemisionRemitente;
      $resultado[$key]["ListaLotes"] = array();
    }
    return $resultado;
  }

  function ValidarDetalleGuiaRemisionRemitente($data, $i = 0)
  {
    $resultado="";
    if(strlen($data["IdProducto"]) == 0)
    {
      $resultado = $resultado."En el ".($i)."° item del comprobante de venta, no se han encontrado resultados para tu búsqueda de cliente."."\n";
    }

    $cantidad = str_replace(',', '', $data["Cantidad"]);
    if($cantidad <= 0 || !is_numeric($cantidad) )
    {
      $resultado =$resultado."En el ".($i)."° item del comprobante de venta la cantidad debe ser mayor que cero y numérico."."\n";
    }

    return $resultado;
  }

  function ValidarDetallesGuiaRemisionRemitente($data)
  {
    $resultado = "";
    $total = count($data);

    if($total == 0)
      $resultado = $resultado."Ingresar por lo menos un item al comprobante."."\n";

    foreach ($data as $key => $value)
    {
      if($key < ($total - 1))//recorre hasta la penultima
      {
        $resultado = $resultado.$this->ValidarDetalleGuiaRemisionRemitente($value, $key + 1);
      }
    }
    return $resultado;
  }

}

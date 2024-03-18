<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mCuotaPagoClienteComprobanteVenta extends CI_Model {

  public $CuotaPagoClienteComprobanteVenta = array();

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->CuotaPagoClienteComprobanteVenta = $this->Base->Construir("CuotaPagoClienteComprobanteVenta");
  }

  // function ListarCuotaPagoClienteComprobanteVentas() {
  //   $query = $this->db->query("Select *
  //                             FROM CuotaPagoClienteComprobanteVenta
  //                             WHERE IndicadorEstado = 'A'
  //                             ORDER BY NumeroOrdenDireccion ASC");
  //   $resultado = $query->result();
  //   return $resultado;
  // }

  // function InsertarCuotaPagoClienteComprobanteVenta($data)
  // {
  //   $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
  //   $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
  //   $data["IndicadorEstado"]=ESTADO_ACTIVO;
  //   $resultado = $this->mapper->map($data,$this->CuotaPagoClienteComprobanteVenta);
  //   $this->db->insert('CuotaPagoClienteComprobanteVenta', $resultado);
  //   $resultado["IdCuotaPagoClienteComprobanteVenta"] = $this->db->insert_id();
  //   return $resultado;
  // }

  // function ActualizarCuotaPagoClienteComprobanteVenta($data)
  // {
  //   $id=$data["IdCuotaPagoClienteComprobanteVenta"];
  //   $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
  //   $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
  //   $resultado = $this->mapper->map($data,$this->CuotaPagoClienteComprobanteVenta);
  //   $this->db->where('IdCuotaPagoClienteComprobanteVenta', $id);
  //   $this->db->update('CuotaPagoClienteComprobanteVenta', $resultado);
  // }

  // function BorrarCuotaPagoClienteComprobanteVenta($data)
  // {
  //   $data["IndicadorEstado"]=ESTADO_ELIMINADO;
  //   $this->ActualizarCuotaPagoClienteComprobanteVenta($data);
  //   return $data;
  // }

  // function BorrarDireccionesPorIdCliente($data)
  // {
  //   $id=$data["IdCliente"];
  //   $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
  //   $resultado = $this->mapper->map($data,$this->CuotaPagoClienteComprobanteVenta);
  //   $this->db->where('IdCliente', $id);
  //   $this->db->update('CuotaPagoClienteComprobanteVenta', $resultado);
  // }

  // function ConsultarDireccionesCliente($data)
  // {
  //   $id=$data["IdPersona"];
  //   $this->db->select("DC.*,0 AS EstadoDireccion") //0: Sin Cambios,1:Insertado,2:Actualizado, 3 : Borrado
  //   ->from('CuotaPagoClienteComprobanteVenta As DC')
  //   ->where("DC.IdCliente = '$id' AND DC.IndicadorEstado = 'A'")
  //   ->order_by('NumeroOrdenDireccion', 'ASC');
  //   $query = $this->db->get();
  //   $resultado = $query->result();
  //   return $resultado;
  // }

  
  // function ObtenerCuotaPagoClienteComprobanteVentaPorIdClienteYDireccion($data) {
  //   $direccion=$data["Direccion"];
  //   $id=$data["IdCliente"];         
  //   $sql = "select 
  //   DC.*
  //   from CuotaPagoClienteComprobanteVenta As DC
  //   where DC.IdCliente = '$id' AND DC.Direccion = '$direccion' AND DC.IndicadorEstado = 'A'";
  //   $query=$this->db->query($sql);

  //   $resultado = $query->result_array();

  //   return $resultado;
  // }

  // function ObtenerNumeroOrdenMaximoIdCliente($data) {  
  //   $id=$data["IdCliente"];     
  //   $query=$this->db->query("select 
  //     Max(DC.NumeroOrdenDireccion) as NumeroOrdenDireccionMaximo
  //     from CuotaPagoClienteComprobanteVenta As DC
  //     where DC.IdCliente = '$id' AND DC.IndicadorEstado = 'A'");
  //   $resultado = $query->result_array();    
  //   return $resultado;
  // }

  function InsertarCuotaPagoClienteComprobanteVenta($data) {
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    
    $resultado = $this->mapper->map_real($data,$this->CuotaPagoClienteComprobanteVenta);
    $this->db->insert('CuotaPagoClienteComprobanteVenta', $resultado);
    
    $resultado["IdCuotaPagoClienteComprobanteVenta"] = $this->db->insert_id();
    return $resultado;
  }

  function EliminarCuotasPagoClienteComprobanteVentaPorIdComprobanteVenta($data) {
    $IdComprobanteVenta=$data["IdComprobanteVenta"];
    $this->db->where("IdComprobanteVenta", $IdComprobanteVenta);
    $this->db->delete("CuotaPagoClienteComprobanteVenta");
    return true;
  }

  function ConsultarCuotasPagoClienteComprobanteVentaPorIdComprobanteVenta($data) {
    $IdComprobanteVenta=$data["IdComprobanteVenta"];
    $query=$this->db->query("select 
    IdCuotaPagoClienteComprobanteVenta,
    IdComprobanteVenta,
    NumeroCuota,
    IdentificadorCuota,
    MontoCuota,
    IdMoneda,
    DATE_FORMAT(FechaPagoCuota,'%d/%m/%Y') as FechaPagoCuota,
    IndicadorEstado,
    UsuarioRegistro,
    UsuarioModificacion,
    FechaRegistro,
    FechaModificacion,
    FechaPagoCuota as FechaPagoCuotaSUNAT
    from CuotaPagoClienteComprobanteVenta
    where IdComprobanteVenta = '$IdComprobanteVenta' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();        
    return $resultado;
  }

}

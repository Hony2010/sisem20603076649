<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDireccionCliente extends CI_Model {

  public $DireccionCliente = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->DireccionCliente = $this->Base->Construir("DireccionCliente");
  }

  function ListarDireccionClientes()
  {
    $query = $this->db->query("Select *
                              FROM DireccionCliente
                              WHERE IndicadorEstado = 'A'
                              ORDER BY NumeroOrdenDireccion ASC");
    $resultado = $query->result();
    return $resultado;
  }

  function InsertarDireccionCliente($data)
  {
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $resultado = $this->mapper->map($data,$this->DireccionCliente);
    $this->db->insert('DireccionCliente', $resultado);
    $resultado["IdDireccionCliente"] = $this->db->insert_id();
    return $resultado;
  }

  function ActualizarDireccionCliente($data)
  {
    $id=$data["IdDireccionCliente"];
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $resultado = $this->mapper->map($data,$this->DireccionCliente);
    $this->db->where('IdDireccionCliente', $id);
    $this->db->update('DireccionCliente', $resultado);
  }

  function BorrarDireccionCliente($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $this->ActualizarDireccionCliente($data);
    return $data;
  }

  function BorrarDireccionesPorIdCliente($data)
  {
    $id=$data["IdCliente"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $resultado = $this->mapper->map($data,$this->DireccionCliente);
    $this->db->where('IdCliente', $id);
    $this->db->update('DireccionCliente', $resultado);
  }

  function ConsultarDireccionesCliente($data)
  {
    $id=$data["IdPersona"];
    $this->db->select("DC.*,0 AS EstadoDireccion") //0: Sin Cambios,1:Insertado,2:Actualizado, 3 : Borrado
    ->from('DireccionCliente As DC')
    ->where("DC.IdCliente = '$id' AND DC.IndicadorEstado = 'A'")
    ->order_by('NumeroOrdenDireccion', 'ASC');
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ConsultarDireccionesClienteParaJSON($data)
  {
    $id=$data["IdPersona"];
    $this->db->select("DC.IdDireccionCliente, DC.Direccion, DC.NumeroOrdenDireccion")
    ->from('DireccionCliente As DC')
    ->where("DC.IdCliente = '$id' AND DC.IndicadorEstado = 'A'")
    ->order_by('NumeroOrdenDireccion', 'ASC');
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ObtenerDireccionClientePorIdClienteYDireccion($data) {
    $direccion=$data["Direccion"];
    $id=$data["IdCliente"];         
    $sql = "select 
    DC.*
    from DireccionCliente As DC
    where DC.IdCliente = '$id' AND DC.Direccion = '$direccion' AND DC.IndicadorEstado = 'A'";
    $query=$this->db->query($sql);

    $resultado = $query->result_array();

    return $resultado;
  }

  function ObtenerNumeroOrdenMaximoIdCliente($data) {  
    $id=$data["IdCliente"];     
    $query=$this->db->query("select 
      Max(DC.NumeroOrdenDireccion) as NumeroOrdenDireccionMaximo
      from DireccionCliente As DC
      where DC.IdCliente = '$id' AND DC.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    
    return $resultado;
  }

  function ConsultarDireccionesClientePorIdCliente($data) {
    $id=$data["IdCliente"];
    $query=$this->db->query("select DC.*,0 AS EstadoDireccion
    from DireccionCliente As DC
    where(DC.IdCliente = '$id' AND DC.IndicadorEstado = 'A')
    order by NumeroOrdenDireccion ASC");

    $resultado = $query->result_array();    
    return $resultado;
  }
}

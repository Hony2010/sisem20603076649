<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mProducto extends CI_Model {

        public $Producto = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->Producto = $this->Base->Construir("Producto");
        }

        function InsertarProducto($data) {
          $data["UsuarioRegistro"]= $this->sesionusuario->obtener_sesion_nombre_usuario();
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->Producto);
          $this->db->insert('Producto', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarProducto($data)
        {
          $id=$data["IdProducto"];
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->Producto);
          $this->db->where('IdProducto', $id);
          $this->db->update('Producto', $resultado);
          return $resultado;
        }

        function BorrarProducto($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $resultado = $this->ActualizarProducto($data);
          return $resultado;
        }

        function ConsultarProductoEnDetalleComprobanteVenta($data)
        {
          $id=$data["IdProducto"];
          $this->db->select("*")
          ->from('DetalleComprobanteVenta')
          ->where("IndicadorEstado='A' AND IdProducto = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarProductoEnDetalleComprobanteCompra($data)
        {
          $id=$data["IdProducto"];
          $this->db->select("*")
          ->from('DetalleComprobanteCompra')
          ->where("IndicadorEstado='A' AND IdProducto = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarProductoEnInventarioInicial($data)
        {
          $id=$data["IdProducto"];
          $this->db->select("*")
          ->from('InventarioInicial')
          ->where("IndicadorEstado='A' AND IdProducto = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerProductoPorNombreONombreLargo($data) {
          $NombreProducto=$data["NombreProducto"];          
          $sql="SELECT * FROM Producto
          WHERE NombreProducto='$NombreProducto' OR NombreLargoProducto='$NombreProducto' And IndicadorEstado = 'A'";
          $query = $this->db->query($sql);
          $resultado = $query->result_array();
          return $resultado;          
        }

}

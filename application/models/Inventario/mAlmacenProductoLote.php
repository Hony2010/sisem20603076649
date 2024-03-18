<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAlmacenProductoLote extends CI_Model {

        public $AlmacenProductoLote = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->AlmacenProductoLote = $this->Base->Construir("AlmacenProductoLote");
        }


        function InsertarAlmacenProductoLote($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->AlmacenProductoLote);
          $this->db->insert('AlmacenProductoLote', $resultado);
          $resultado["IdAlmacenProductoLote"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarAlmacenProductoLote($data)
        {
          $id=$data["IdAlmacenProductoLote"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->AlmacenProductoLote);
          $this->db->where('IdAlmacenProductoLote', $id);
          $this->db->update('AlmacenProductoLote', $resultado);

          return $resultado;
        }

        function BorrarAlmacenProductoLote($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarAlmacenProductoLote($data);
        }

        function ObtenerAlmacenProductoLotePorProductoAlmacen($data)
        {
          $id=$data["IdLoteProducto"];
          $almacen=$data["IdAsignacionSede"];
          $query = $this->db->query("Select * from AlmacenProductoLote
                                     WHERE IdLoteProducto = '$id' AND IdAsignacionSede = '$almacen'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarListasLoteProductoPorIdProducto($data)
        {
          $id = $data["IdProducto"];
          $query = $this->db->query("select APL.IdAlmacenProductoLote, APL.IdAsignacionSede,
                      APL.IdLoteProducto, APL.StockProductoLote, LP.IdLoteProducto, LP.IdProducto,
                      LP.NumeroLote, LP.FechaVencimiento
                      from almacenproductolote APL
                      inner join loteproducto LP on LP.IdLoteProducto = APL.IdLoteProducto
                      where LP.IdProducto = '$id' and APL.StockProductoLote > 0 and APL.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ProductosEnAlmacenProductoLote()
        {
          $query = $this->db->query("Select distinct IdProducto
              from AlmacenProductoLote
              where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function SedesPorProductoEnAlmacenProductoLote($data)
        {
          $id = $data["IdProducto"];
          $query = $this->db->query("Select distinct IdAsignacionSede
                from AlmacenProductoLote
                where IdProducto = '$id' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAlmacenMercaderia extends CI_Model {

        public $AlmacenMercaderia = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->AlmacenMercaderia = $this->Base->Construir("AlmacenMercaderia");
        }


        function InsertarAlmacenMercaderia($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->AlmacenMercaderia);
          $this->db->insert('AlmacenMercaderia', $resultado);
          $resultado["IdAlmacenMercaderia"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarAlmacenMercaderia($data)
        {
          $id=$data["IdAlmacenMercaderia"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->AlmacenMercaderia);
          $this->db->where('IdAlmacenMercaderia', $id);
          $this->db->update('AlmacenMercaderia', $resultado);

          return $resultado;
        }

        function BorrarAlmacenMercaderia($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarAlmacenMercaderia($data);
        }

        function BorrarAlmacenMercaderiaPorIdProducto($data)
        {
          $id=$data["IdProducto"];
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->AlmacenMercaderia);
          $this->db->where('IdProducto', $id);
          $this->db->update('AlmacenMercaderia', $resultado);

          return $resultado;
        }

        function ObtenerAlmacenMercaderiaPorProductoAlmacen($data)
        {
          $id = $data["IdProducto"];
          $idasignacionsede = $data["IdAsignacionSede"];
          $query = $this->db->query("Select * from AlmacenMercaderia
                                     WHERE IdProducto = '$id' AND IdAsignacionSede = '$idasignacionsede'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ProductosEnAlmacenMercaderia()
        {
          $query = $this->db->query("Select distinct IdProducto
              from AlmacenMercaderia
              where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function SedesPorProductoEnAlmacenMercaderia($data)
        {
          $id = $data["IdProducto"];
          $query = $this->db->query("Select distinct IdAsignacionSede
                from AlmacenMercaderia
                where IdProducto = '$id' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarListasStockPorIdProducto($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("select am.IdAlmacenMercaderia, am.IdAsignacionSede, am.IdProducto, am.StockMercaderia
                                    from almacenmercaderia as am
                                    where am.IdProducto = '$id' and am.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoListaPrecio extends CI_Model {

        public $TipoListaPrecio = array();

        public function __construct() {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoListaPrecio = $this->Base->Construir("TipoListaPrecio");
        }

        function ListarTiposListaPrecio() {
          $query = $this->db->query("select * from tipolistaprecio as TLP
                                    where TLP.IndicadorEstado = 'A'
                                    order by ordenlistaprecio");
          $resultado = $query->result_array();
          return $resultado;
        }
        
        function InsertarTipoListaPrecio($data) {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoListaPrecio);
          $this->db->insert('TipoListaPrecio', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoListaPrecio($data) {
          $id=$data["IdTipoListaPrecio"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoListaPrecio);
          $this->db->where('IdTipoListaPrecio', $id);
          $this->db->update('TipoListaPrecio', $resultado);
        }

        function BorrarTipoListaPrecio($data) {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoListaPrecio($data);
        }

        function ObtenerTipoListaPrecioMinimo() {
          $query = $this->db->query("select * from tipolistaprecio as TLP
          where TLP.IndicadorEstado = 'A' and TLP.IndicadorPrecioMinimo='1'");
          $resultado = $query->result_array();
          return $resultado;          
        }

        function ListarTiposListaPrecioSinTipoPrecioMinimo() {
          $query = $this->db->query("select * from tipolistaprecio as TLP
                                    where TLP.IndicadorEstado = 'A' and TLP.IndicadorPrecioMinimo='0'
                                    order by ordenlistaprecio");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerTipoListaPrecioEspecialCliente() {
          $query = $this->db->query("select * from tipolistaprecio
          where IndicadorEstado = 'A' and IndicadorPrecioEspecialCliente='1'");
          $resultado = $query->result_array();
          return $resultado;          
        }

 }

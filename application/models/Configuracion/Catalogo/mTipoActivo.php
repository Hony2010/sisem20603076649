<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoActivo extends CI_Model {

        public $TipoActivo = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoActivo = $this->Base->Construir("TipoActivo");
        }

        function ListarTiposActivo()
        {
          $this->db->select("TipoActivo.*")
          ->from('TipoActivo')
          ->where("IndicadorEstado = 'A'")
          ->order_by('NombreTipoActivo');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarTipoActivo($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoActivo);
          $this->db->insert('TipoActivo', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoActivo($data)
        {
          $id=$data["IdTipoActivo"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoActivo);
          $this->db->where('IdTipoActivo', $id);
          $this->db->update('TipoActivo', $resultado);
        }

        function BorrarTipoActivo($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoActivo($data);
        }

        function ConsultarTipoActivoEnActivoFijo($data)
        {
          $id=$data["IdTipoActivo"];
          $this->db->select("AF.*")
          ->from('ActivoFijo as AF')
          ->join('Producto as P','AF.IdProducto = P.IdProducto')
          ->where("AF.IdTipoActivo = '$id' AND P.IndicadorEstado = 'A'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }

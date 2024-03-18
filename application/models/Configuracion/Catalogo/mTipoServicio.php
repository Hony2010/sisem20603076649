<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoServicio extends CI_Model {

        public $TipoServicio = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoServicio = $this->Base->Construir("TipoServicio");
        }

        function ListarTiposServicio()
        {
          $this->db->select("TipoServicio.*")
          ->from('TipoServicio')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdTipoServicio');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarTipoServicio($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoServicio);
          $this->db->insert('TipoServicio', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoServicio($data)
        {
          $id=$data["IdTipoServicio"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoServicio);
          $this->db->where('IdTipoServicio', $id);
          $this->db->update('TipoServicio', $resultado);
        }

        function BorrarTipoServicio($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoServicio($data);
        }

        function ConsultarTipoServicioEnServicio($data)
        {
          $id=$data["IdTipoServicio"];
          $this->db->select("S.*")
          ->from('Servicio as S')
          ->join('Producto as P','S.IdProducto = P.IdProducto')
          ->where("S.IdTipoServicio = '$id' AND P.IndicadorEstado = 'A'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }

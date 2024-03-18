<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoSede extends CI_Model {

        public $TipoSede = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoSede = $this->Base->Construir("TipoSede");
        }

        function ListarTiposSede()
        {
          $this->db->select("*")
          ->from('TipoSede')
          ->where("IndicadorEstado = 'A' or IndicadorEstado = 'T'")
          ->order_by('IdTipoSede');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarTipoSede($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoSede);
          $this->db->insert('TipoSede', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoSede($data)
        {
          $id=$data["IdTipoSede"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoSede);
          $this->db->where('IdTipoSede', $id);
          $this->db->update('TipoSede', $resultado);
        }

        function BorrarTipoSede($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoSede($data);
        }

        function ConsultarTipoSedeEnSede($data)
        {
          $id=$data["IdTipoSede"];
          $this->db->select("Sede.*")
          ->from('Sede')
          ->where("IndicadorEstado='A' AND IdTipoSede = '$id'");
          $query = $this->db->get();
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarTipoSedeParaSede()
        {
          $this->db->select("*")
          ->from('TipoSede')
          ->where('IndicadorEstado="T"')
          ->order_by('IdTipoSede');
          $query = $this->db->get();
          $resultado = $query->result_array();
          return $resultado;
        }

 }

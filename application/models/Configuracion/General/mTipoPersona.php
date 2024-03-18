<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoPersona extends CI_Model {

        public $TipoPersona = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoPersona = $this->Base->Construir("TipoPersona");
        }

        function ListarTiposPersona()
        {
          $this->db->select("*")
          ->from('TipoPersona')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdTipoPersona');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarTipoPersona($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoPersona);
          $this->db->insert('TipoPersona', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoPersona($data)
        {
          $id=$data["IdTipoPersona"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoPersona);
          $this->db->where('IdTipoPersona', $id);
          $this->db->update('TipoPersona', $resultado);
        }

        function BorrarTipoPersona($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoPersona($data);
        }

        function ConsultarTipoPersonaEnPersona($data)
        {
          $id=$data["IdTipoPersona"];
          $this->db->select("Persona.*")
          ->from('Persona')
          ->where("IndicadorEstado='A' AND IdTipoPersona = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }

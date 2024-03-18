<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoListaRaleo extends CI_Model {

        public $TipoListaRaleo = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoListaRaleo = $this->Base->Construir("TipoListaRaleo");
        }

        function ListarTiposListaRaleo()
        {
          $this->db->select("TipoListaRaleo.*")
          ->from('TipoListaRaleo')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdTipoListaRaleo');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarTipoListaRaleo($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoListaRaleo);
          $this->db->insert('TipoListaRaleo', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoListaRaleo($data)
        {
          $id=$data["IdTipoListaRaleo"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoListaRaleo);
          $this->db->where('IdTipoListaRaleo', $id);
          $this->db->update('TipoListaRaleo', $resultado);
        }

        function BorrarTipoListaRaleo($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoListaRaleo($data);
        }
 }

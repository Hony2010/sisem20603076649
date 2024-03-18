<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mRegimenTributario extends CI_Model {

        public $RegimenTributario = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->RegimenTributario = $this->Base->Construir("RegimenTributario");
        }

        function ListarRegimenesTributario()
        {
          $this->db->select("*")
          ->from('RegimenTributario')
          ->where("IndicadorEstado = 'A' or IndicadorEstado = 'T'")
          ->order_by('IdRegimenTributario');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarRegimenTributario($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->RegimenTributario);
          $this->db->insert('RegimenTributario', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarRegimenTributario($data)
        {
          $id=$data["IdRegimenTributario"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->RegimenTributario);
          $this->db->where('IdRegimenTributario', $id);
          $this->db->update('RegimenTributario', $resultado);
        }

        function BorrarRegimenTributario($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarRegimenTributario($data);
        }

        function ConsultarRegimenTributarioEnEmpresa($data)
        {
          $id=$data["IdRegimenTributario"];
          $this->db->select("Empresa.*")
          ->from('Empresa')
          ->where("IndicadorEstado='A' AND IdRegimenTributario = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }

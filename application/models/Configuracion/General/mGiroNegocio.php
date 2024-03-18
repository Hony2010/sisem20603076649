<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mGiroNegocio extends CI_Model {

        public $GiroNegocio = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->GiroNegocio = $this->Base->Construir("GiroNegocio");
        }

        function ListarGirosNegocio()
        {
          $this->db->select("*")
          ->from('GiroNegocio')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdGiroNegocio');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarGiroNegocio($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->GiroNegocio);
          $this->db->insert('GiroNegocio', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarGiroNegocio($data)
        {
          $id=$data["IdGiroNegocio"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->GiroNegocio);
          $this->db->where('IdGiroNegocio', $id);
          $this->db->update('GiroNegocio', $resultado);
        }

        function BorrarGiroNegocio($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarGiroNegocio($data);
        }

        function ConsultarGiroNegocioEnEmpresa($data)
        {
          $id=$data["IdGiroNegocio"];
          $this->db->select("Empresa.*")
          ->from('Empresa')
          ->where("IndicadorEstado='A' AND IdGiroNegocio = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }

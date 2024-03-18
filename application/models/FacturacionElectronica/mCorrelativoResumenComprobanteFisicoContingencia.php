<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mCorrelativoResumenComprobanteFisicoContingencia extends CI_Model {

        public $CorrelativoResumenComprobanteFisicoContingencia = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->CorrelativoResumenComprobanteFisicoContingencia = $this->Base->Construir("CorrelativoResumenComprobanteFisicoContingencia");
        }


        function ObtenerCorrelativoResumenComprobanteFisicoContingencia($data) {

          $fechacomunicacionbaja=$data["FechaComprobanteFisicoContingencia"];

          $query = $this->db->query("
          select
            ccb.*
          from correlativoresumencomprobantefisicocontingencia ccb
          where ccb.FechaComprobanteFisicoContingencia = '$fechacomunicacionbaja'");

          $resultado = $query->row();
          return $resultado;

        }

        function InsertarCorrelativoResumenComprobanteFisicoContingencia($data)
        {
          $resultado = $this->mapper->map($data,$this->CorrelativoResumenComprobanteFisicoContingencia);
          $this->db->insert('CorrelativoResumenComprobanteFisicoContingencia', $resultado);
          $data["IdCorrelativoResumenComprobanteFisicoContingencia"] = $this->db->insert_id();

          return($data);
        }


        function ActualizarCorrelativoResumenComprobanteFisicoContingencia($data)
        {
          $fecha = $data["FechaComprobanteFisicoContingencia"];
          $resultado = $this->mapper->map($data,$this->CorrelativoResumenComprobanteFisicoContingencia);
          $this->db->where('FechaComprobanteFisicoContingencia', $fecha);
          $this->db->update('CorrelativoResumenComprobanteFisicoContingencia', $resultado);
        }

}

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mCorrelativoResumenDiario extends CI_Model {

        public $CorrelativoResumenDiario = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->CorrelativoResumenDiario = $this->Base->Construir("CorrelativoResumenDiario");
        }


        function ObtenerCorrelativoResumenDiario($data) {

          $fechaResumenDiario=$data["FechaResumenDiario"];

          $query = $this->db->query("
          select
            crd.*
          from correlativoresumendiario crd
          where crd.FechaResumenDiario = '$fechaResumenDiario'");

          $resultado = $query->row();
          return $resultado;

        }

        function InsertarCorrelativoResumenDiario($data)
        {
          $resultado = $this->mapper->map($data,$this->CorrelativoResumenDiario);
          $this->db->insert('CorrelativoResumenDiario', $resultado);
          $data["IdCorrelativoResumenDiario"] = $this->db->insert_id();

          return($data);
        }


        function ActualizarCorrelativoResumenDiario($data)
        {
          $fecha = $data["FechaResumenDiario"];
          $resultado = $this->mapper->map($data,$this->CorrelativoResumenDiario);
          $this->db->where('FechaResumenDiario', $fecha);
          $this->db->update('CorrelativoResumenDiario', $resultado);
        }

}

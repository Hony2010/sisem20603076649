<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mCorrelativoComunicacionBaja extends CI_Model {

        public $CorrelativoComunicacionBaja = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->CorrelativoComunicacionBaja = $this->Base->Construir("CorrelativoComunicacionBaja");
        }


        function ObtenerCorrelativoComunicacionBaja($data) {

          $fechacomunicacionbaja=$data["FechaComunicacionBaja"];

          $query = $this->db->query("
          select
            ccb.*
          from correlativocomunicacionbaja ccb
          where ccb.FechaComunicacionBaja = '$fechacomunicacionbaja'");

          $resultado = $query->row();
          return $resultado;

        }

        function InsertarCorrelativoComunicacionBaja($data)
        {
          $resultado = $this->mapper->map($data,$this->CorrelativoComunicacionBaja);
          $this->db->insert('CorrelativoComunicacionBaja', $resultado);
          $data["IdCorrelativoComunicacionBaja"] = $this->db->insert_id();

          return($data);
        }


        function ActualizarCorrelativoComunicacionBaja($data)
        {
          $fecha = $data["FechaComunicacionBaja"];
          $resultado = $this->mapper->map($data,$this->CorrelativoComunicacionBaja);
          $this->db->where('FechaComunicacionBaja', $fecha);
          $this->db->update('CorrelativoComunicacionBaja', $resultado);
        }

}

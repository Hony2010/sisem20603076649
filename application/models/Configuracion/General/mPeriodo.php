<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mPeriodo extends CI_Model {

        public $Periodo = array();

        public function __construct() {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Periodo = $this->Base->Construir("Periodo");
        }

        function ListarPeriodos() {
          $query = $this->db->query("select
              p.*
              from periodo as p
              where p.IndicadorEstado='A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarPeriodoPorId($data) {
          $id = $data["IdPeriodo"];
          $query = $this->db->query("select p.*
                                    from periodo as p
                                    where p.IndicadorEstado='A' and IdPeriodo = '$id'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarPeriodoAños() {
          $query = $this->db->query("select DISTINCT(Año)
                                    from periodo as p
                                    where p.IndicadorEstado='A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarPeriodoPorAño($data) {
          $año = $data["Año"];
          $query = $this->db->query("select p.*
                                    from periodo as p
                                    where p.IndicadorEstado='A' and año = '$año'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerPeriodoPorFecha($data) {
          $año = $data["FechaEmision"];
          $query = $this->db->query("select *
                  from periodo
                  where NumeroMes = MONTH('$año') and
                  Año = YEAR('$año') and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }

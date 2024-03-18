<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMigracionInventario extends CI_Model {

        public $MigracionInventario = array();

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->Genero = $this->Base->Construir("MigracionInventario");
        }

        function ListarMigracionInventario() {
          $query = $this->db->query("SELECT * FROM  MigracionInventario LIMIT 1500,500");//500/1000/1500/
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarMigracionMarcasModelosInventario() {
            $query = $this->db->query("SELECT DISTINCT NombreMarca,NombreModelo FROM  MigracionInventario");
            $resultado = $query->result_array();
            return $resultado;
        }

        function ListarMigracionUnidadesMedidaInventario() {
            $query = $this->db->query("SELECT DISTINCT NombreUnidadMedida FROM  MigracionInventario");
            $resultado = $query->result_array();
            return $resultado;
        }

        function ListarMigracionLineasProductoInventario() {
            $query = $this->db->query("SELECT DISTINCT NombreLineaProducto FROM  MigracionInventario");
            $resultado = $query->result_array();
            return $resultado;
        }

        function ListarMigracionFamiliasSubFamiliasProductoInventario() {
            $query = $this->db->query("SELECT DISTINCT NombreFamiliaProducto,NombreSubFamiliaProducto FROM  MigracionInventario");
            $resultado = $query->result_array();
            return $resultado;
        }

        function ListarMigracionSedesProductoInventario() {
            $query = $this->db->query("SELECT DISTINCT NombreAlmacen FROM  MigracionInventario");
            $resultado = $query->result_array();
            return $resultado;
        }
}

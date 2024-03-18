<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mCostoAgregado extends CI_Model {

        public $CostoAgregado = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->CostoAgregado = $this->Base->Construir("CostoAgregado");
        }

        function ListarCostosAgregado()
        {
          $query = $this->db->query("Select CA.*, P.*
                                     From CostoAgregado As CA
                                     Inner Join Producto As P On CA.IdProducto = P.IdProducto
                                     Where P.IndicadorEstado='A'
                                     ORDER  BY(P.NombreProducto) ASC");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerCostoAgregadoPorIdProducto($data)
        {
          $criterio=$data["IdProducto"];
          $query = $this->db->query("Select CA.*, P.*
              From CostoAgregado As CA
              Inner Join Producto as P on CA.IdProducto = P.IdProducto
              Where P.IndicadorEstado= 'A' AND CA.IdProducto = '$criterio'
              ORDER BY(CA.IdProducto)");
          $resultado = $query->row();
          return $resultado;
        }

        function InsertarCostoAgregado($data)
        {
          $resultado = $this->mapper->map($data,$this->CostoAgregado);
          $this->db->insert('CostoAgregado', $resultado);
          // $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarCostoAgregado($data)
       	{
       		$id=$data["IdProducto"];
       		$resultado = $this->mapper->map($data,$this->CostoAgregado);
       		$this->db->where('IdProducto', $id);
       		$this->db->update('CostoAgregado', $resultado);
       	}

        function ConsultarCostosAgregado($data)
        {
          $criterio=$data["textofiltro"];
          $this->db->select("CA.*, P.*")
          ->from('CostoAgregado As CA')
          ->Join('Producto As P','CA.IdProducto = P.IdProducto')
          ->where('P.NombreProducto like "%'.$criterio.'%" or CA.IdProducto like "%'.$criterio.'%" AND P.IndicadorEstado="A"' );
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarCostoAgregadoParaJSON()
        {
          $query = $this->db->query("Select CA.*, P.*
              From CostoAgregado As CA
              Join Producto As P on CA.IdProducto = P.IdProducto
              where P.IndicadorEstado='A'");
          $resultado = $query->result_array();
          return $resultado;
        }
}

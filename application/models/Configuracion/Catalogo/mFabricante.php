<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mFabricante extends CI_Model {

        public $Fabricante = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Fabricante = $this->Base->Construir("Fabricante");
        }

        function ListarFabricantes()
        {
          $query = $this->db->query("Select *
                                    From Fabricante
                                    Where IndicadorEstado = 'A'
                                    order by NoEspecificado desc,NombreFabricante asc");
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarFabricante($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->Fabricante);
          $this->db->insert('Fabricante', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarFabricante($data)
        {
          $id=$data["IdFabricante"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->Fabricante);
          $this->db->where('IdFabricante', $id);
          $this->db->update('Fabricante', $resultado);
        }

        function BorrarFabricante($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarFabricante($data);
        }

        function ConsultarFabricanteEnMercaderia($data)
        {
          $id=$data["IdFabricante"];
          $this->db->select("M.*")
          ->from('Mercaderia As M')
          ->join('Producto as P','M.IdProducto = P.IdProducto')
          ->where("M.IdFabricante = '$id' AND P.IndicadorEstado = 'A'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarFabricantesJSON($data)
        {
          $query = $this->db->query("Select F.*
                                    From Fabricante As F
                                    WHERE F.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }

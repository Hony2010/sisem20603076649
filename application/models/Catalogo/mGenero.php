<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mGenero extends CI_Model {

        public $Genero = array();

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->Genero = $this->Base->Construir("Genero");
        }

        function ListarGeneros() {          
            $sql="select * from Genero where IndicadorEstado = 'A'";
            $query = $this->db->query($sql);
            $resultado = $query->result();
            return $resultado;
        }          

        function ObtenerGenero($data) {        
            $nombregenero=$data["NombreGenero"];  
            $sql="select * from Genero where IndicadorEstado = 'A' and NombreGenero='".$nombregenero."'";
            $query = $this->db->query($sql);
            $resultado = $query->result();
            return $resultado;
        }       

        function InsertarGenero($data) {
            $resultado = $this->mapper->map($data,$this->Genero);
            $this->db->insert('Genero', $resultado);
            $resultado = $this->db->insert_id();
            return $resultado;
        }

        function ActualizarGenero($data) {       		
       		$resultado = $this->mapper->map($data,$this->Genero);
       		$this->db->where('IdGenero', $data["IdGenero"]);//$id=$data["IdCasillero"];            
            $this->db->update('Genero', $resultado);
            return $data;
       	}

}

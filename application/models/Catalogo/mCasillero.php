<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mCasillero extends CI_Model {

        public $Casillero = array();

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->Casillero = $this->Base->Construir("Casillero");
        }

        function ListarCasilleros() {          
            $sql="select * from Casillero where IndicadorEstado = 'A'";
            $query = $this->db->query($sql);
            $resultado = $query->result();
            return $resultado;
        }          

        function ObtenerCasillero($data) {        
            $nombrecasillero=$data["NombreCasillero"];  
            $sql="select * from Casillero where IndicadorEstado = 'A' and NombreCasillero='".$nombrecasillero."'";
            $query = $this->db->query($sql);
            $resultado = $query->result();
            return $resultado;
        }       

        function InsertarCasillero($data) {
            $resultado = $this->mapper->map($data,$this->Casillero);
            $this->db->insert('Casillero', $resultado);
            $resultado = $this->db->insert_id();
            return $resultado;
        }

        function ActualizarCasillero($data) {       		
       		$resultado = $this->mapper->map($data,$this->Casillero);
       		$this->db->where('IdCasillero', $data["IdCasillero"]);//$id=$data["IdCasillero"];            
            $this->db->update('Casillero', $resultado);
            return $data;
       	}

}

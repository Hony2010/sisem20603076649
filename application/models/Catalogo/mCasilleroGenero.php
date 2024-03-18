<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mCasilleroGenero extends CI_Model {

        public $CasilleroGenero = array();

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->CasilleroGenero = $this->Base->Construir("CasilleroGenero");
        }

        function ListarCasillerosGenero($data) {          
            $sql="select c.NombreCasillero,g.NombreGenero,cg.* 
            from CasilleroGenero cg
            left join Casillero c 
            on c.IdCasillero = cg.IdCasillero
            left join Genero g
            on g.IdGenero=cg.IdGenero
            where cg.IndicadorEstado = 'A' and c.IndicadorEstado='A' And g.IndicadorEstado='A' And cg.IdGenero like '".$data["IdGenero"]."' ";
            $query = $this->db->query($sql);
            $resultado = $query->result_array();
            return $resultado;
        }          

        function ObtenerCasilleroGenero($data) {        
            $IdGenero=$data["IdGenero"];  
            $IdCasillero=$data["IdCasillero"];  
            $sql="select * from CasilleroGenero where IndicadorEstado = 'A' and IdGenero='".$IdGenero."' and IdCasillero='".$IdCasillero."'";
            $query = $this->db->query($sql);
            $resultado = $query->result_array();
            return $resultado;
        }       

        function InsertarCasilleroGenero($data) {
            $resultado = $this->mapper->map($data,$this->CasilleroGenero);
            $this->db->insert('CasilleroGenero', $resultado);
            $resultado = $this->db->insert_id();
            return $resultado;
        }

        function ActualizarCasilleroGenero($data) {       		
       		$resultado = $this->mapper->map($data,$this->CasilleroGenero);
       		$this->db->where('IdCasilleroGenero', $data["IdCasilleroGenero"]);//$id=$data["IdCasilleroGenero"];            
            $this->db->update('CasilleroGenero', $resultado);
            return $data;
       	}

}

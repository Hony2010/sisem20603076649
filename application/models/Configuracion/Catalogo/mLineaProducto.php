<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mLineaProducto extends CI_Model {

        public $LineaProducto = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->LineaProducto = $this->Base->Construir("LineaProducto");
        }

        function ListarLineasProducto()
        {
          $query = $this->db->query("Select *
                                    From LineaProducto
                                    Where IndicadorEstado = 'A'
                                    order by NoEspecificado desc, NombreLineaProducto asc");
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarLineaProducto($data) {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->LineaProducto);
          $this->db->insert('LineaProducto', $resultado);
          $resultado["IdLineaProducto"] = $this->db->insert_id();
          return $resultado;
        }

        function ActualizarLineaProducto($data)
       	{
       	  $id=$data["IdLineaProducto"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
       		$resultado = $this->mapper->map($data,$this->LineaProducto);
       		$this->db->where('IdLineaProducto', $id);
          $this->db->update('LineaProducto', $resultado);
       	}

        function BorrarLineaProducto($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        	$this->ActualizarLineaProducto($data);
        }

        function ConsultarLineasProductoEnMercaderia($data)
        {
          $id=$data["IdLineaProducto"];
          $this->db->select("M.*")
          ->from('Mercaderia As M')
          ->join('Producto as P','M.IdProducto = P.IdProducto')
          ->where("M.IdLineaProducto = '$id' AND P.IndicadorEstado = 'A'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarLineasJSON($data)
        {
          $query = $this->db->query("Select LP.*
                                    From LineaProducto As LP
                                    WHERE LP.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerLineaProductoPorNombreLineaProducto($data) {
          $NombreaLineaProducto=$data["NombreLineaProducto"];
          $sql= "Select * from LineaProducto Where NombreLineaProducto='$NombreaLineaProducto' And IndicadorEstado='A'";
          $query = $this->db->query($sql);
          $resultado = $query->result_array();
          return $resultado;
        }
}

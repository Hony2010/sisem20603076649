<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMarca extends CI_Model {

        public $Marca = array();
        public $Modelo = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Marca = $this->Base->Construir("Marca");
               $this->Modelo = $this->Base->Construir("Modelo");
        }

        function ListarMarcas()
        {
        $query = $this->db->query("Select *
                                  From Marca
                                  Where IndicadorEstado = 'A'
                                  order by NoEspecificado desc, NombreMarca asc");
        $resultado = $query->result();
        return $resultado;
        }

        function InsertarMarca($data) {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->Marca);
          $this->db->insert('Marca', $resultado);
          $resultado["IdMarca"] = $this->db->insert_id();
          return $resultado;
        }

        function ActualizarMarca($data)
       	{
       	  $id=$data["IdMarca"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
       		$resultado = $this->mapper->map($data,$this->Marca);
       		$this->db->where('IdMarca', $id);
       		$this->db->update('Marca', $resultado);
       	}

        function BorrarMarca($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        	$this->ActualizarMarca($data);
        }

        function ConsultarMarcaEnModelo($data)
        {
          $id=$data["IdMarca"];
          $this->db->select('*')
          ->from('Modelo')
          ->where("IndicadorEstado='A' AND IdMarca = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarMarcaEnMercaderia($data)
        {
          $id=$data["IdMarca"];
          $this->db->select("P.IdProducto, P.NombreProducto, P.IndicadorEstado, MDL.IdModelo, MDL.NombreModelo, MDL.IndicadorEstado, MRC.IdMarca, MRC.NombreMarca, MRC.IndicadorEstado")
          ->from('Mercaderia As M')
          ->join('Producto As P', 'M.IdProducto = P.IdProducto')
          ->join('Modelo As MDL', 'M.IdModelo = MDL.IdModelo')
          ->join('Marca AS MRC', 'MRC.IdMarca = MDL.IdMarca')
          ->where("MRC.IdMarca = '$id' AND P.IndicadorEstado = 'A'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarMarcaEnActivoFijo($data)
        {
          $id=$data["IdMarca"];
          $this->db->select("P.IdProducto, P.NombreProducto, P.IndicadorEstado, MDL.IdModelo, MDL.NombreModelo, MDL.IndicadorEstado, MRC.IdMarca, MRC.NombreMarca, MRC.IndicadorEstado")
          ->from('ActivoFijo As AF')
          ->join('Producto As P', 'AF.IdProducto = P.IdProducto')
          ->join('Modelo As MDL', 'AF.IdModelo = MDL.IdModelo')
          ->join('Marca AS MRC', 'MRC.IdMarca = MDL.IdMarca')
          ->where("MRC.IdMarca = '$id' AND P.IndicadorEstado = 'A'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerMarcaYNoEspecificadoEnModelo($data)
        {
          $id=$data["IdFamiliaProducto"];
          $this->db->select('*')
          ->from('Modelo')
          ->where("NoEspecificado ='S' AND IdMarca ='$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarNoEspecificadoEnModelo($data) {
          $data["NombreModelo"] = "NO ESPECIFICADO";
          $data["NoEspecificado"] = "S";
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->Modelo);
          $this->db->insert('Modelo', $resultado);
          $resultado["IdModelo"] = $this->db->insert_id();
          return $resultado;
        }

        function ConsultarMarca($data)
        {
          $criterio=$data["textofiltro"];
          $this->db->select("Marca.*")
          ->from('Marca')
          ->where('NombreMarca like "%'.$criterio.'%" or IdMarca like "%'.$criterio.'%"' );
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarMarcasJSON($data)
        {
          $query = $this->db->query("Select M.*
                                    From Marca As M
                                    WHERE M.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMarcaPorNombreMarca($data) {
          $NombreMarca=$data["NombreMarca"];
          $sql = "Select M.* From Marca As M WHERE M.NombreMarca='$NombreMarca' And M.IndicadorEstado = 'A'";
          $query = $this->db->query($sql);
          $resultado = $query->result_array();
          return $resultado;
        }
}

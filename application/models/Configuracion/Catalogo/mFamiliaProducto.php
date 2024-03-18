<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mFamiliaProducto extends CI_Model {

        public $FamiliaProducto = array();
        public $SubFamiliaProducto = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->FamiliaProducto = $this->Base->Construir("FamiliaProducto");
               $this->SubFamiliaProducto = $this->Base->Construir("SubFamiliaProducto");
        }

        function ListarFamiliasProducto()
        {
          $query = $this->db->query("Select *
                                    From Familiaproducto
                                    Where IndicadorEstado = 'A'
                                    order by NoEspecificado desc, NombreFamiliaProducto asc");
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarFamiliaProducto($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->FamiliaProducto);
          $this->db->insert('FamiliaProducto', $resultado);
          $resultado["IdFamiliaProducto"] = $this->db->insert_id();
          return $resultado;
        }

        function ActualizarFamiliaProducto($data)
       	{
       	  $id=$data["IdFamiliaProducto"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
       		$resultado = $this->mapper->map($data,$this->FamiliaProducto);
       		$this->db->where('IdFamiliaProducto', $id);
       		$this->db->update('FamiliaProducto', $resultado);
       	}

        function BorrarFamiliaProducto($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        	$this->ActualizarFamiliaProducto($data);
        }

        function ConsultarFamiliasProductoEnSubFamiliaProducto($data)
        {
          $id=$data["IdFamiliaProducto"];
          $this->db->select('*')
          ->from('SubFamiliaProducto')
          ->where("IndicadorEstado = 'A' AND IdFamiliaProducto = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarFamiliasProductoEnMercaderia($data)
        {
          $id=$data["IdFamiliaProducto"];
          $this->db->select("M.IdProducto, P.NombreProducto, P.IndicadorEstado, SFP.IdSubFamiliaProducto, SFP.NombreSubFamiliaProducto, SFP.IndicadorEstado, FP.IdFamiliaProducto, FP.NombreFamiliaProducto, FP.IndicadorEstado")
          ->from('Mercaderia As M')
          ->join('Producto As P', 'M.IdProducto = P.IdProducto')
          ->join('SubFamiliaProducto As SFP', 'M.IdSubFamiliaProducto = SFP.IdSubFamiliaProducto')
          ->join('FamiliaProducto AS FP', 'FP.IdFamiliaProducto = SFP.IdFamiliaProducto')
          ->where("FP.IdFamiliaProducto = '$id' AND P.IndicadorEstado = 'A' AND SFP.IndicadorEstado = 'A' AND FP.IndicadorEstado='A'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerFamiliaProductoYNoEspecificadoEnSubFamiliaProducto($data)
        {
          $id=$data["IdFamiliaProducto"];
          $this->db->select("*")
          ->from('SubFamiliaProducto')
          ->where("NoEspecificado ='S' AND IdFamiliaProducto ='$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarNoEspecificadoEnSubFamiliaProducto($data) {
          $data["NombreSubFamiliaProducto"] = "NO ESPECIFICADO";
          $data["NoEspecificado"] = "S";
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->SubFamiliaProducto);
          $this->db->insert('SubFamiliaProducto', $resultado);
          $resultado["IdSubFamiliaProducto"] = $this->db->insert_id();
          return $resultado;
        }
        
        function ConsultarFamiliasProducto($data)
        {
          $criterio=$data["textofiltro"];
          $this->db->select("FamiliaProducto.*")
          ->from('FamiliaProducto')
          ->where('NombreFamiliaProducto like "%'.$criterio.'%" or IdFamiliaProducto like "%'.$criterio.'%" AND IndicadorEstado="A"' );
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarFamiliasJSON($data)
        {
          $query = $this->db->query("Select FP.*
                                    From FamiliaProducto As FP
                                    WHERE FP.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerFamiliaProductoPorNombreFamilia($data) {
          $NombreFamilia=$data["NombreFamiliaProducto"];
          $sql="Select FP.*
          From FamiliaProducto As FP
          WHERE FP.NombreFamiliaProducto='$NombreFamilia' And FP.IndicadorEstado = 'A'";
          $query = $this->db->query($sql);
          $resultado = $query->result_array();

          return $resultado;
        }
}

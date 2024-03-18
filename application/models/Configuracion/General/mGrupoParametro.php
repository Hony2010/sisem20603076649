<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mGrupoParametro extends CI_Model {

        public $GrupoParametro = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->GrupoParametro = $this->Base->Construir("GrupoParametro");
        }

        function ListarGruposParametro()
        {
          $this->db->select("*")
          ->from('GrupoParametro')
          ->where('IndicadorEstado="A"')
          ->order_by('IdGrupoParametro');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarGrupoParametro($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->GrupoParametro);
          $this->db->insert('GrupoParametro', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarGrupoParametro($data)
       	{
       		$id=$data["IdGrupoParametro"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
       		$resultado = $this->mapper->map($data,$this->GrupoParametro);
       		$this->db->where('IdGrupoParametro', $id);
       		$this->db->update('GrupoParametro', $resultado);
       	}

        function BorrarGrupoParametro($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        	$this->ActualizarGrupoParametro($data);
        }

        function ConsultarGrupoParametroEnParametroSistema($data)
        {
          $id=$data["IdGrupoParametro"];
          $this->db->select('*')
          ->from('ParametroSistema')
          ->where("IndicadorEstado = 'A' AND IdGrupoParametro = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarGrupoParametro($data)
        {
          $criterio=$data["textofiltro"];
          $this->db->select("*")
          ->from('GrupoParametro')
          ->where('NombreGrupoParametro like "%'.$criterio.'%"  AND IndicadorEstado="A" or IdGrupoParametro like "%'.$criterio.'%"' );
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
}

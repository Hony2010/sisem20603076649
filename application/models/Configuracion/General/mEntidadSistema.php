<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mEntidadSistema extends CI_Model {

        public $EntidadSistema = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->EntidadSistema = $this->Base->Construir("EntidadSistema");
        }

        function ListarEntidadesSistema($data)
        {
          $id=$data["IdEntidadSistema"];
          $this->db->select("*")
          ->from('EntidadSistema')
          ->where("IdEntidadSistema = '$id' AND IndicadorEstado = 'A'")
          ->order_by('IdEntidadSistema');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarEntidadSistema($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->EntidadSistema);
          $this->db->insert('EntidadSistema', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarEntidadSistema($data)
       	{
       		$id=$data["IdEntidadSistema"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
       		$resultado = $this->mapper->map($data,$this->EntidadSistema);
       		$this->db->where('IdEntidadSistema', $id);
       		$this->db->update('EntidadSistema', $resultado);
       	}

        function BorrarEntidadSistema($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        	$this->ActualizarEntidadSistema($data);
        }

        function ConsultarEntidadSistemaEnParametroSistema($data)
        {
          $id=$data["IdEntidadSistema"];
          $this->db->select("*")
          ->from('ParametroSistema')
          ->where("IndicadorEstado='A' AND IdEntidadSistema = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarEntidadSistema($data)
        {
          $criterio=$data["textofiltro"];
          $this->db->select("*")
          ->from('EntidadSistema')
          ->where('IndicadorEstado="A" AND NombreEntidadSistema like "%'.$criterio.'%" or IdEntidadSistema like "%'.$criterio.'%"' );
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
}

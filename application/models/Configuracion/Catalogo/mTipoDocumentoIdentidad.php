<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoDocumentoIdentidad extends CI_Model {

        public $TipoDocumentoIdentidad = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoDocumentoIdentidad = $this->Base->Construir("TipoDocumentoIdentidad");
        }

        function ListarTiposDocumentoIdentidad()
        {
          $this->db->select("*")
          ->from('TipoDocumentoIdentidad')
          ->where("IndicadorEstado = 'A' OR IndicadorEstado = 'T'")
          ->order_by('IdTipoDocumentoIdentidad');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarTipoDocumentoIdentidad($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoDocumentoIdentidad);
          $this->db->insert('TipoDocumentoIdentidad', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoDocumentoIdentidad($data)
        {
          $id=$data["IdTipoDocumentoIdentidad"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoDocumentoIdentidad);
          $this->db->where('IdTipoDocumentoIdentidad', $id);
          $this->db->update('TipoDocumentoIdentidad', $resultado);
        }

        function BorrarTipoDocumentoIdentidad($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoDocumentoIdentidad($data);
        }

        function ConsultarTipoDocumentoIdentidadEnPersona($data)
        {
          $id=$data["IdTipoDocumentoIdentidad"];
          $this->db->select("Persona.*")
          ->from('Persona')
          ->where("IndicadorEstado='A' AND IdTipoDocumentoIdentidad = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerCodigoTipoDocumentoIdentidadParaInsertar($data)
        {
          $codigo=$data["CodigoDocumentoIdentidad"];
          $query = $this->db->query("Select *
                                     From TipoDocumentoIdentidad
                                     Where CodigoDocumentoIdentidad = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerCodigoTipoDocumentoIdentidadParaActualizar($data)
        {
          $id=$data["IdTipoDocumentoIdentidad"];
          $codigo=$data["CodigoDocumentoIdentidad"];
          $query = $this->db->query("Select *
                                     From TipoDocumentoIdentidad
                                     Where (IdTipoDocumentoIdentidad > '$id' Or IdTipoDocumentoIdentidad < '$id' ) and CodigoDocumentoIdentidad = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }

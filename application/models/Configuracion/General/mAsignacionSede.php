<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAsignacionSede extends CI_Model {

        public $TipoDocumento = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->AsignacionSede = $this->Base->Construir("AsignacionSede");
        }

        function ListarAsignacionesSede()
        {
          $query = $this->db->query("Select ASS.IdAsignacionSede, S.NombreSede, TS.NombreTipoSede
                                    From asignacionsede As ASS
                                    Inner Join sede As S on ASS.IdSede = S.IdSede
                                    Inner Join tiposede As TS on ASS.IdTipoSede = TS.IdTipoSede
                                    Where ASS.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarTipoSede($data)
        {
          $id = $data;
          $query = $this->db->query("Select ASS.*, TS.NombreTipoSede
                                    From asignacionsede As ASS
                                    Inner Join sede As S on ASS.IdSede = S.IdSede
                                    Inner Join tiposede As TS on ASS.IdTipoSede = TS.IdTipoSede
                                    where ASS.IdSede = '$id' and ASS.IndicadorEstado ='A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarAsignacionSede($data)
        {
          $id = $data;
          $query = $this->db->query("Select ASS.*, TS.NombreTipoSede, S.CodigoSede, S.NombreSede
                From asignacionsede As ASS
                Inner Join sede As S on ASS.IdSede = S.IdSede
                Inner Join tiposede As TS on ASS.IdTipoSede = TS.IdTipoSede
                where ASS.IdAsignacionSede = '$id' and ASS.IndicadorEstado ='A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarAsignacionSede($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->AsignacionSede);
          $this->db->insert('AsignacionSede', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarAsignacionSede($data)
        {
          $id=$data["IdAsignacionSede"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->AsignacionSede);
          $this->db->where('IdAsignacionSede', $id);
          $this->db->update('AsignacionSede', $resultado);
        }

        function BorrarAsignacionSede($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarAsignacionSede($data);
        }


        function ObtenerAsignacionSedePorIdSedeYIdTipoSede($data) {
          $IdSede = $data["IdSede"];
          $IdTipoSede = $data["IdTipoSede"];

          $query = $this->db->query("Select ASS.*, TS.NombreTipoSede, S.CodigoSede, S.NombreSede
                From asignacionsede As ASS
                Inner Join sede As S on ASS.IdSede = S.IdSede
                Inner Join tiposede As TS on ASS.IdTipoSede = TS.IdTipoSede
                where ASS.IdSede = '$IdSede' and ASS.IdTipoSede = '$IdTipoSede' and ASS.IndicadorEstado ='A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarAsignacionesSedesPorIdTipoSede($data) {
          $IdTipoSede = $data["IdTipoSede"];

          $query = $this->db->query("Select ASS.*, TS.NombreTipoSede, S.CodigoSede, S.NombreSede
                From asignacionsede As ASS
                Inner Join sede As S on ASS.IdSede = S.IdSede
                Inner Join tiposede As TS on ASS.IdTipoSede = TS.IdTipoSede
                Where ASS.IdTipoSede = '$IdTipoSede' and ASS.IndicadorEstado ='A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerAsignacionSedeTipoAlmacenPorNombreSede($data) {
          $IdTipoSede = $data["IdTipoSede"];
          $NombreSede = $data["NombreSede"];

          $query = $this->db->query("Select ASS.*, TS.NombreTipoSede, S.CodigoSede, S.NombreSede
                From asignacionsede As ASS
                Inner Join sede As S on ASS.IdSede = S.IdSede
                Inner Join tiposede As TS on ASS.IdTipoSede = TS.IdTipoSede
                Where ASS.IdTipoSede = '$IdTipoSede' And S.NombreSede='$NombreSede' and ASS.IndicadorEstado ='A'");
          
                $resultado = $query->result_array();
          return $resultado;
        }
 }

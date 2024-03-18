<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAccesoUsuarioAlmacen extends CI_Model {

        public $AccesoUsuarioAlmacen = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->AccesoUsuarioAlmacen = $this->Base->Construir("AccesoUsuarioAlmacen");
        }

        function InsertarAccesoUsuarioAlmacen($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          // $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->AccesoUsuarioAlmacen);
          $this->db->insert('AccesoUsuarioAlmacen', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarAccesoUsuarioAlmacen($data)
        {
          $id=$data["IdAccesoUsuarioAlmacen"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->AccesoUsuarioAlmacen);
          $this->db->where('IdAccesoUsuarioAlmacen', $id);
          $this->db->update('AccesoUsuarioAlmacen', $resultado);
          return $data;
        }

        function BorrarAccesoUsuarioAlmacen($data)
        {
          $id=$data["IdAccesoUsuarioAlmacen"];
          $this->db->where("IdAccesoUsuarioAlmacen",$id);
          $this->db->delete("AccesoUsuarioAlmacen");
        }

        function BorrarAccesoUsuarioAlmacenPorUsuario($data)
        {
          $id=$data["IdUsuario"];
          $this->db->where("IdUsuario",$id);
          $this->db->delete("AccesoUsuarioAlmacen");
        }

        function ConsultarAlmacenesUsuario($data)
        {
          $id = $data;
          $query = $this->db->query("select AUA.*
                              from accesousuarioalmacen as AUA
                              where AUA.IdUsuario='$id'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarSedesTipoAlmacenPorUsuario($data)
        {
          $IdTipoSede = ID_TIPO_SEDE_ALMACEN;
          $IdUsuario = $data["IdUsuario"];
          $IndicadorAlmacenZofra = '%';
          $query = $this->db->query("Select ASS.IdAsignacionSede, S.*, TS.NombreTipoSede, TS.IdTipoSede, ASS.IndicadorAlmacenZofra
                    From AccesoUsuarioAlmacen AUA
                    inner Join asignacionsede As ASS on AUA.IdSede = ASS.IdSede
                    Inner Join sede As S on ASS.IdSede = S.IdSede
                    Inner Join tiposede As TS on ASS.IdTipoSede = TS.IdTipoSede
                    Where (AUA.IdUsuario = '$IdUsuario' and TS.IdTipoSede = '$IdTipoSede' and ASS.IndicadorAlmacenZofra like '$IndicadorAlmacenZofra') and  AUA.IndicadorEstado = 'A'
                    and S.IndicadorEstado = 'A' AND ASS.IndicadorEstado='A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ValidarAccesoUsuarioAlmacen($data)
        {
          $usuario = $data["IdUsuario"];
          $sede = $data["IdSede"];
          $query = $this->db->query("SELECT AUA.*
              FROM accesousuarioalmacen as AUA
              WHERE AUA.IdUsuario='$usuario' AND AUA.IdSede = '$sede'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mSede extends CI_Model {

        public $Sede = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Sede = $this->Base->Construir("Sede");
        }

        function ListarSedes()
        {
          $this->db->select("S.*")
          ->from('Sede As S')
          ->where("S.IndicadorEstado = 'A'")
          ->order_by('IdSede');
          $query = $this->db->get();
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarSedeTiposAgencia()
        {
          $IdTipoSede = ID_TIPO_SEDE_AGENCIA;
          $query = $this->db->query("select ASE.IdAsignacionSede, ASE.IdSede, S.NombreSede, ASE.IdTipoSede, TS.NombreTipoSede
                                    from  asignacionsede as ASE
                                    inner join sede as S on ASE.IdSede = S.IdSede
                                    inner join tiposede as TS on ASE.IdTipoSede = TS.IdTipoSede
                                    where ASE.IdTipoSede = '$IdTipoSede' and ASE.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarSede($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->Sede);
          $this->db->insert('Sede', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarSede($data)
        {
          $id=$data["IdSede"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->Sede);
          $this->db->where('IdSede', $id);
          $this->db->update('Sede', $resultado);
        }

        function BorrarSede($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarSede($data);
        }

        function ConsultarExistenciaSedeEnEmpleado($data)
        {
          $id=$data["IdSede"];
          $this->db->select("Empleado.*")
          ->from('Empleado')
          ->where("IndicadorEstado='A' AND IdSede = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerCodigoSedeParaInsertar($data)
        {
          $codigo=$data["CodigoSede"];
          $query = $this->db->query("Select *
                                     From Sede
                                     Where CodigoSede = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerCodigoSedeParaActualizar($data)
        {
          $id=$data["IdSede"];
          $codigo=$data["CodigoSede"];
          $query = $this->db->query("Select *
                                     From Sede
                                     Where (IdSede > '$id' Or IdSede < '$id' ) and CodigoSede = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarSedesTipoAlmacen()
        {
          $IdTipoSede = ID_TIPO_SEDE_ALMACEN;
          $query = $this->db->query("Select ASS.IdAsignacionSede, S.*, TS.NombreTipoSede, TS.IdTipoSede, ASS.IndicadorAlmacenZofra
                                     From asignacionsede As ASS
                                     Inner Join sede As S on ASS.IdSede = S.IdSede
                                     Inner Join tiposede As TS on ASS.IdTipoSede = TS.IdTipoSede
                                     Where  TS.IdTipoSede = '$IdTipoSede' and S.IndicadorEstado = 'A' AND ASS.indicadorestado='A'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }

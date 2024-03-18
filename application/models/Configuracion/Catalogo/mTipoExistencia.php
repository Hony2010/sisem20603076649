<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoExistencia extends CI_Model {

        public $TipoExistencia = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoExistencia = $this->Base->Construir("TipoExistencia");
        }

        function ListarTiposExistencia()
        {
          $this->db->select("TipoExistencia.*")
          ->from('TipoExistencia')
          ->where("IndicadorEstado = 'A' or IndicadorEstado = 'T'")
          ->order_by('IdTipoExistencia');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarTipoExistencia($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoExistencia);
          $this->db->insert('TipoExistencia', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoExistencia($data)
        {
          $id=$data["IdTipoExistencia"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoExistencia);
          $this->db->where('IdTipoExistencia', $id);
          $this->db->update('TipoExistencia', $resultado);
        }

        function BorrarTipoExistencia($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoExistencia($data);
        }

        function ConsultarTipoExistenciaEnMercaderia($data)
        {
          $id=$data["IdTipoExistencia"];
          $this->db->select("M.*")
          ->from('Mercaderia As M')
          ->join('Producto as P','M.IdProducto = P.IdProducto')
          ->where("M.IdTipoExistencia = '$id' AND (P.IndicadorEstado = 'A' or IndicadorEstado = 'T')");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerCodigoTipoExistenciaParaInsertar($data)
        {
          $codigo=$data["CodigoTipoExistencia"];
          $query = $this->db->query("Select *
                                     From TipoExistencia
                                     Where CodigoTipoExistencia = '$codigo' and (IndicadorEstado = 'A' or IndicadorEstado = 'T')");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerCodigoTipoExistenciaParaActualizar($data)
        {
          $id=$data["IdTipoExistencia"];
          $codigo=$data["CodigoTipoExistencia"];
          $query = $this->db->query("Select *
                                     From TipoExistencia
                                     Where (IdTipoExistencia > '$id' Or IdTipoExistencia < '$id' ) and CodigoTipoExistencia = '$codigo' and (IndicadorEstado = 'A' or IndicadorEstado = 'T')");
          $resultado = $query->result_array();
          return $resultado;
        }
 }

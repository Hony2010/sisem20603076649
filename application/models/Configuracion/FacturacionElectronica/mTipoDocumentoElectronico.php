<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoDocumentoElectronico extends CI_Model {

        public $TipoDocumentoElectronico = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoDocumentoElectronico = $this->Base->Construir("TipoDocumentoElectronico");
        }

        function ListarTiposDocumentoElectronico()
        {
          $this->db->select("*")
          ->from('TipoDocumentoElectronico')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdTipoDocumentoElectronico');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarTipoDocumentoElectronico($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoDocumentoElectronico);
          $this->db->insert('TipoDocumentoElectronico', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoDocumentoElectronico($data)
        {
          $id=$data["IdTipoDocumentoElectronico"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoDocumentoElectronico);
          $this->db->where('IdTipoDocumentoElectronico', $id);
          $this->db->update('TipoDocumentoElectronico', $resultado);
        }

        function BorrarTipoDocumentoElectronico($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoDocumentoElectronico($data);
        }
        function ConsultarTipoDocumentoElectronicoEnComprobanteVenta($data)
        {
          $id=$data["IdTipoDocumentoElectronico"];
          $this->db->select("*")
          ->from('ComprobanteVenta')
          ->where("IdTipoDocumentoElectronico = '$id' AND IndicadorEstado = 'A' ");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerCodigoTipoDocumentoElectronicoParaInsertar($data)
        {
          $codigo=$data["CodigoTipoDocumentoElectronico"];
          $query = $this->db->query("Select *
                                     From TipoDocumentoElectronico
                                     Where CodigoTipoDocumentoElectronico = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerCodigoTipoDocumentoElectronicoParaActualizar($data)
        {
          $id=$data["IdTipoDocumentoElectronico"];
          $codigo=$data["CodigoTipoDocumentoElectronico"];
          $query = $this->db->query("Select *
                                     From TipoDocumentoElectronico
                                     Where (IdTipoDocumentoElectronico > '$id' Or IdTipoDocumentoElectronico < '$id' ) and CodigoTipoDocumentoElectronico = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerTipoDocumentoElectronico($data)
        {
          $codigo=$data["CodigoTipoDocumentoElectronico"];
          $query = $this->db->query("Select *
                                     From TipoDocumentoElectronico
                                     Where CodigoTipoDocumentoElectronico = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->row();
          return $resultado;
        }

 }

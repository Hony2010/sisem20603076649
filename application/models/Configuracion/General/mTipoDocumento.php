<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoDocumento extends CI_Model {

        public $TipoDocumento = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoDocumento = $this->Base->Construir("TipoDocumento");
        }

        function ListarTiposDocumento()
        {
          $this->db->select("*")
          ->from('TipoDocumento')
          ->where("(IndicadorEstado = 'A' OR IndicadorEstado = 'T')")
          ->order_by('IdTipoDocumento');
          $query = $this->db->get();
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarTipoDocumento($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoDocumento);
          $this->db->insert('TipoDocumento', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoDocumento($data)
        {
          $id=$data["IdTipoDocumento"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoDocumento);
          $this->db->where('IdTipoDocumento', $id);
          $this->db->update('TipoDocumento', $resultado);
        }

        function BorrarTipoDocumento($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoDocumento($data);
        }
        function ConsultarTipoDocumentoEnComprobanteVenta($data)
        {
          $id=$data["IdTipoDocumento"];
          $this->db->select("*")
          ->from('ComprobanteVenta')
          ->where("IdTipoDocumento = '$id' AND (IndicadorEstado = 'A' OR IndicadorEstado = 'T') ");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerCodigoTipoDocumentoParaInsertar($data)
        {
          $codigo=$data["CodigoTipoDocumento"];
          $query = $this->db->query("Select *
                                     From TipoDocumento
                                     Where CodigoTipoDocumento = '$codigo' and (IndicadorEstado = 'A' OR IndicadorEstado = 'T')");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerCodigoTipoDocumentoParaActualizar($data)
        {
          $id=$data["IdTipoDocumento"];
          $codigo=$data["CodigoTipoDocumento"];
          $query = $this->db->query("Select *
                                     From TipoDocumento
                                     Where (IdTipoDocumento > '$id' Or IdTipoDocumento < '$id' ) and CodigoTipoDocumento = '$codigo' and (IndicadorEstado = 'A' OR IndicadorEstado = 'T')");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNombreAbreviadoTipoDocumentoParaInsertar($data)
        {
          $codigo=$data["NombreAbreviado"];
          $query = $this->db->query("Select *
                                     From TipoDocumento
                                     Where NombreAbreviado = '$codigo' and (IndicadorEstado = 'A' OR IndicadorEstado = 'T')");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNombreAbreviadoTipoDocumentoParaActualizar($data)
        {
          $id=$data["IdTipoDocumento"];
          $codigo=$data["NombreAbreviado"];
          $query = $this->db->query("Select *
                                     From TipoDocumento
                                     Where (IdTipoDocumento > '$id' Or IdTipoDocumento < '$id' ) and NombreAbreviado = '$codigo' and (IndicadorEstado = 'A' OR IndicadorEstado = 'T')");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerTipoDocumento($data)
        {
          $codigo=$data["CodigoTipoDocumento"];
          $query = $this->db->query("Select *
                                     From TipoDocumento
                                     Where CodigoTipoDocumento = '$codigo' and (IndicadorEstado = 'A' OR IndicadorEstado = 'T')");
          $resultado = $query->row();
          return $resultado;
        }

        function ObtenerTipoDocumentoPorId($data)
        {
          $id=$data["IdTipoDocumento"];
          $query = $this->db->query("Select *
                                     From TipoDocumento
                                     Where IdTipoDocumento = '$id' and (IndicadorEstado = 'A' OR IndicadorEstado = 'T')");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarTiposDocumentoPorIndicadorDocumentoReporteCompra()
        {
          $query = $this->db->query("Select *
                                     From TipoDocumento
                                     Where IndicadorDocumentoReporteCompra = '1' and (IndicadorEstado = 'A' OR IndicadorEstado = 'T')");
          $resultado = $query->result_array();
          return $resultado;
        }
 }

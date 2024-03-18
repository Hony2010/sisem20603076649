<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoDocumentoModuloSistema extends CI_Model {

        public $TipoDocumento = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoDocumentoModuloSistema = $this->Base->Construir("TipoDocumentoModuloSistema");
        }

        function ListarTiposDocumentoModuloSistema()
        {
          $query = $this->db->query("Select TDMS.IdTipoDocumentoModuloSistema, TD.NombreTipoDocumento, MS.NombreModuloSistema
                                    From tipodocumentomodulosistema As TDMS
                                    Inner Join tipodocumento As TD on TDMS.IdTipoDocumento = TD.IdTipoDocumento
                                    Inner Join modulosistema As MS on TDMS.IdModuloSistema = MS.IdModuloSistema
                                    Where TDMS.IndicadorEstado = 'A' AND MS.IndicadorEstado = 'A' AND (TD.IndicadorEstado = 'A' OR TD.IndicadorEstado = 'T')");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarTiposDocumentoModuloSistemaPorIdModulo($data, $excluir = 0)
        {
          $id = $data["IdModuloSistema"];
          $exluir = $excluir;
          $sql="Select TDMS.IdTipoDocumentoModuloSistema, TD.IdTipoDocumento, TD.NombreTipoDocumento, MS.NombreModuloSistema,TD.NombreAbreviado,TD.CodigoTipoDocumento
          From tipodocumentomodulosistema As TDMS
          Inner Join tipodocumento As TD on TDMS.IdTipoDocumento = TD.IdTipoDocumento
          Inner Join modulosistema As MS on TDMS.IdModuloSistema = MS.IdModuloSistema
          Where MS.IndicadorEstado = 'A' AND (TD.IndicadorEstado = 'A' OR TD.IndicadorEstado = 'T')
          AND TDMS.IndicadorEstado = 'A' AND TDMS.IdModuloSistema like'$id' AND TD.IdTipoDocumento NOT IN($exluir)";          
          
          $query = $this->db->query($sql);
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarModuloSistema($data)
        {
          $id = $data;
          $query = $this->db->query("Select TDMS.*, MS.NombreModuloSistema
                                    From tipodocumentomodulosistema As TDMS
                                    Inner Join tipodocumento As TD on TDMS.IdTipoDocumento = TD.IdTipoDocumento
                                    Inner Join modulosistema As MS on TDMS.IdModuloSistema = MS.IdModuloSistema
                                    where TDMS.IdTipoDocumento = '$id' and TDMS.IndicadorEstado ='A' ");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarTipoDocumentoModuloSistema($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoDocumentoModuloSistema);
          $this->db->insert('TipoDocumentoModuloSistema', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoDocumentoModuloSistema($data)
        {
          $id=$data["IdTipoDocumentoModuloSistema"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoDocumentoModuloSistema);
          $this->db->where('IdTipoDocumentoModuloSistema', $id);
          $this->db->update('TipoDocumentoModuloSistema', $resultado);
        }

        function BorrarTipoDocumentoModuloSistema($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoDocumentoModuloSistema($data);
        }
 }

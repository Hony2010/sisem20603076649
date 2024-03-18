<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mProveedor extends CI_Model {

        public $Proveedor = array();
        public $Persona = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Proveedor = $this->Base->Construir("Proveedor");
               $this->Persona = $this->Base->Construir("Persona");
        }

        function ObtenerNumeroFila()
        {
          $query = $this->db->query("Select Count(IdPersona) As NumeroFila From Proveedor");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarProveedores($inicio, $ValorParametroSistema)
        {
          $query = $this->db->query("Select Pro.*, P.*, P.ApellidoCompleto, TDI.NombreAbreviado, TDI.CodigoDocumentoIdentidad, TP.NombreTipoPersona
                                     From Proveedor As Pro
                                     Inner Join Persona As P On Pro.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                     Where P.IndicadorEstado = 'A'
                                     ORDER  BY (P.IdPersona) ASC
                                     LIMIT $inicio,$ValorParametroSistema");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarProveedor($data)
        {
          $resultado = $this->mapper->map($data,$this->Proveedor);
          $this->db->insert('Proveedor', $resultado);
          $resultado["IdPersona"] = $this->db->insert_id();
          return($resultado);
        }

        function ObtenerNumeroTotalProveedores($data)
        {
          $criterio=$data["textofiltro"];
          $query = $this->db->query("Select Pro.IdPersona, P.*, P.ApellidoCompleto, TDI.NombreAbreviado,  TDI.CodigoDocumentoIdentidad, TP.NombreTipoPersona
                                     From Proveedor As Pro
                                     Inner Join Persona As P On Pro.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                     Where P.IndicadorEstado= 'A' AND (P.RazonSocial like '%$criterio%' or P.NumeroDocumentoIdentidad like '%$criterio%')
                                     ORDER BY (P.IdPersona)");
          $resultado = $query->num_rows();
          return $resultado;
        }

        function ConsultarProveedores($inicio,$ValorParametroSistema,$data)
        {
          $criterio=$data["textofiltro"];
          $query = $this->db->query("Select Pro.*, P.*, P.ApellidoCompleto, TDI.NombreAbreviado,  TDI.CodigoDocumentoIdentidad, TP.NombreTipoPersona
                                     From Proveedor As Pro
                                     Inner Join Persona As P On Pro.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                     Where P.IndicadorEstado= 'A' AND (P.RazonSocial like '%$criterio%' or P.NumeroDocumentoIdentidad like '%$criterio%')
                                     ORDER  BY (P.IdPersona) ASC
                                     LIMIT $inicio,$ValorParametroSistema");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerProveedorPorIdPersona($data)
        {
          $criterio=$data["IdPersona"];
          $query = $this->db->query("Select PR.IdPersona AS IdProveedor, P.*, TDI.CodigoDocumentoIdentidad
                                     From Proveedor As PR
                                     Inner Join Persona As P On PR.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Where P.IndicadorEstado='A' AND PR.IdPersona = '$criterio'");
          $resultado = $query->row();
          return $resultado;
        }

        function ObtenerNumeroFilaPorConsultaProveedor($data)
        {
          $criterio=$data["textofiltro"];
          $query = $this->db->query("Select Count(P.IdPersona) As NumeroFila
                                     From Proveedor As Pro
                                     Inner Join Persona As P On Pro.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                     Where P.IndicadorEstado= 'A' AND (P.RazonSocial like '%$criterio%' or P.NumeroDocumento like '%$criterio%')
                                     ORDER  BY (P.IdPersona) ASC");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNumeroDocumentoIdentidadParaInsertar($data)
        {
          $numero=$data["NumeroDocumentoIdentidad"];
          $query = $this->db->query("Select Pro.*
                                    From Proveedor As Pro
                                    Inner Join Persona As P On Pro.IdPersona = P.IdPersona
                                    Where P.NumeroDocumentoIdentidad ='$numero' and P.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNumeroDocumentoIdentidadParaActualizar($data)
        {
          $id=$data["IdPersona"];
          $numero=$data["NumeroDocumentoIdentidad"];
          $query = $this->db->query("Select Pro.*
                                    From Proveedor As Pro
                                    Inner Join Persona As P On Pro.IdPersona = P.IdPersona
                                    Where (P.IdPersona > '$id' Or P.IdPersona < '$id' ) and P.NumeroDocumentoIdentidad = '$numero' and P.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarProveedorParaJSON()
        {
          $query = $this->db->query("Select PR.IdPersona AS IdProveedor,PR.EstadoProveedor, P.*, TDI.CodigoDocumentoIdentidad
                                     From Proveedor As PR
                                     Inner Join Persona As P On PR.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Where P.IndicadorEstado='A'");
          $resultado = $query->result_array();
          return $resultado;
        }
}

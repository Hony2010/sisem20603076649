<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mVerificacionCorrelatividad extends CI_Model {

        public $VerificacionCorrelatividad = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->VerificacionCorrelatividad = $this->Base->Construir("CorrelativoDisponible");
        }

        function ObtenerCorrelatividadComprobante($data, $filtro)
        {
          $tipo=$data["IdTipoDocumento"];
          $serie=$data["SerieDocumento"];
          $fechainicio=$filtro["FechaInicio"];
          $fechafin=$filtro["FechaFin"];
            $query = $this->db->query("select comprobanteventa.NumeroDocumento from comprobanteventa
                                       inner join tipodocumento on tipodocumento.IdTipoDocumento = comprobanteventa.IdTipoDocumento
                                       where tipodocumento.IdTipoDocumento = '$tipo' and comprobanteventa.SerieDocumento = '$serie' and comprobanteventa.IndicadorEstado != 'E'
                                       and comprobanteventa.FechaEmision between '$fechainicio' and '$fechafin'
                                       order by comprobanteventa.NumeroDocumento asc");
            $resultado = $query->result_array();
            return $resultado;
        }
        function BorrarCorrelatividadComprobanteVenta()
        {
          $this->db->where("IdCorrelativoDisponible != ","");
          $this->db->delete("CorrelativoDisponible");
        }

        function InsertarCorrelatividadComprobanteVenta($data)
        {
           $resultado = $this->mapper->map($data,$this->VerificacionCorrelatividad);
           $this->db->insert('CorrelativoDisponible', $resultado);
           $resultado = $this->db->insert_id();
           return($resultado);
        }

        function ListarCorrelativosDocumento()
        {
          $factura=ID_TIPO_DOCUMENTO_FACTURA;
          $boleta=ID_TIPO_DOCUMENTO_BOLETA;
          $notacredito=ID_TIPODOCUMENTO_NOTACREDITO;
          $notadebito=ID_TIPODOCUMENTO_NOTADEBITO;
          $ordenpedido=ID_TIPO_DOCUMENTO_ORDEN_PEDIDO;

          $query = $this->db->query("Select CD.*, TD.NombreTipoDocumento, TD.NombreAbreviado, S.NombreSede
                                    From CorrelativoDocumento AS CD
                                    Inner Join TipoDocumento As TD on CD.IdTipoDocumento = TD.IdTipoDocumento
                                    Inner Join Sede As S on CD.IdSede = S.IdSede
                                    Where CD.IndicadorEstado = 'A' and TD.IdTipoDocumento = '$factura' or TD.IdTipoDocumento = '$boleta' or TD.IdTipoDocumento = '$notacredito' or TD.IdTipoDocumento = '$notadebito' or TD.IdTipoDocumento = '$ordenpedido'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }

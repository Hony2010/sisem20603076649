<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mResumenDiario extends CI_Model {

        public $ResumenDiario = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->ResumenDiario = $this->Base->Construir("ResumenDiario");
        }

        function ConsultarComprobantesVenta($data)
        {
          $NumeroDocumento=$data["NumeroDocumento"];
          $RazonSocial=$data["RazonSocial"];
          $codigo_serie=CODIGO_SERIE_BOLETA;
          $codigo_tipo_documento_serie=CODIGO_TIPO_DOCUMENTO_BOLETA;
          $codigo_serie_factura = CODIGO_SERIE_FACTURA;
          $EstadoActivo =  ESTADO_DOCUMENTO_ACTIVO;
          $EstadoAnulado =  ESTADO_DOCUMENTO_ANULADO;
          $IndicadorEstadoCPE = ESTADO_CPE_GENERADO;
          $CodigoEstadoAnulado = CODIGO_ESTADO_ANULADO;
          $excluye = "'".ESTADO_CPE_ACEPTADO."', '".ESTADO_CPE_EN_PROCESO."', '".ESTADO_CPE_RECHAZADO."'";
          $FechaEmision=$data["FechaEmision"];

          $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
          $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
          $extensionConsulta = "";
          if($vistaventa == 0)
          {
            $extensionConsulta = " And u.IdUsuario = '$idusuario' ";
          }

          $consulta = "select cv.IdComprobanteVenta, cv.FechaEmision, cv.CodigoEstado, cv.IndicadorEstado, cv.IndicadorEstadoCPE,
          cv.IndicadorEstadoResumenDiario, td.CodigoTipoDocumento, cv.SerieDocumento, cv.NumeroDocumento,
          CONCAT(td.NombreAbreviado,' ',cv.SerieDocumento,'-',cv.NumeroDocumento) as Numero,
          p.RazonSocial as RazonSocialCliente, m.CodigoMoneda, cv.Total, cv.IdCliente, p.NumeroDocumentoIdentidad,
          p.NumeroDocumentoIdentidad as NumeroDocumentoCliente, tdi.CodigoDocumentoIdentidad, dr.SerieDocumentoReferencia,
          dr.NumeroDocumentoReferencia, CONCAT(dr.SerieDocumentoReferencia,'-',dr.NumeroDocumentoReferencia) as SerieNumeroDocumentoReferencia,
          dr.CodigoTipoDocumentoReferencia, cv.ValorVentaGravado, cv.ValorVentaNoGravado, cv.ValorVentaInafecto,
          cv.IGV, cv.CodigoEstado, cv.ISC, cv.ICBPER, cv.OtroCargo, cv.OtroTributo,
          CONCAT(cv.SerieDocumento,'-',cv.NumeroDocumento) as SerieNumeroDocumento,
          CONCAT(m.SimboloMoneda,' ',CAST(cv.Total as char(10))) as TotalComprobante,
          cv.IndicadorEstadoComunicacionBaja, cv.SituacionCPE
          from comprobanteventa as cv
          inner join moneda m on m.IdMoneda = cv.IdMoneda
          inner join tipodocumento td on td.IdTipoDocumento=cv.IdTipoDocumento
          inner join cliente c on c.IdPersona = cv.IdCliente
          inner join usuario u on u.IdUsuario = cv.IdUsuario
          inner join Persona p on p.IdPersona = c.IdPersona
          inner join tipodocumentoidentidad tdi on tdi.IdTipoDocumentoIdentidad =p.IdTipoDocumentoIdentidad
          left join documentoreferencia dr on dr.IdComprobanteNota = cv.IdComprobanteVenta
          where
          td.CodigoTipoDocumento = '$codigo_tipo_documento_serie' AND
          (cv.SerieDocumento like '%$NumeroDocumento%' or
          cv.NumeroDocumento like '%$NumeroDocumento%')  and
          (p.RazonSocial like '%$RazonSocial%' or
          p.NumeroDocumentoIdentidad like '%$RazonSocial%') and
          cv.FechaEmision = '$FechaEmision' and
          (cv.IndicadorEstado ='$EstadoActivo' or (cv.IndicadorEstado='$EstadoAnulado' and cv.CodigoEstado = '$CodigoEstadoAnulado')) and
          (cv.IndicadorEstadoCPE = '$IndicadorEstadoCPE') and
          cv.IndicadorEstadoResumenDiario not in ($excluye)
          ".$extensionConsulta."
          ORDER BY cv.FechaEmision DESC, td.NombreTipoDocumento, cv.SerieDocumento, cv.NumeroDocumento";
          $query = $this->db->query($consulta);

          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarResumenesDiario($data) {
          $NumeroDocumento=$data["NumeroDocumento"];
          $FechaInicio=$data["FechaInicio"];
          $FechaFin=$data["FechaFin"];
          $codigo = $data["CodigoEstado"];
          $query = $this->db->query("select rd.*
                      from resumendiario rd
                      where rd.IndicadorEstado = 'A' AND (rd.NombreResumenDiario like '%$NumeroDocumento%')  and
                      (rd.FechaEmisionDocumento BETWEEN '$FechaInicio' AND '$FechaFin') and rd.IndicadorEstadoResumenDiario like '$codigo'
                      ORDER BY rd.FechaResumenDiario DESC, rd.NombreResumenDiario");

          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarResumenDiario($data)
        {
          $data["FechaGeneracionResumenDiario"]=$this->Base->ObtenerFechaServidor();
          //$data["FechaComunicacionBaja"]=$data["FechaGeneracionBaja"];
          $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_DOCUMENTO_ACTIVO;
          // $data["IndicadorEstadoResumenDiario"]=ESTADO_CPE_GENERADO;

          $resultado = $this->mapper->map($data,$this->ResumenDiario);
          $this->db->insert('ResumenDiario', $resultado);
          $data["IdResumenDiario"] = $this->db->insert_id();

          return($data);
        }

        function BorrarResumenDiario($data)
        {
          $data["IndicadorEstado"]=ESTADO_DOCUMENTO_ELIMINADO;
          $this->ActualizarResumenDiario($data);
        }

        function ActualizarResumenDiario($data)
        {
          $id = $data["IdResumenDiario"];
          $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->ResumenDiario);
          $this->db->where('IdResumenDiario', $id);
          $this->db->update('ResumenDiario', $resultado);
          return $resultado;
        }

        function ConsultarComprobantesVentaPorResumen($data)
        {
          $Resumen=$data["IdResumenDiario"];

          $consulta = "SELECT
                  CV.IdComprobanteVenta, CV.IndicadorEstadoResumenDiario, CV.IndicadorEstado, CV.IndicadorEstadoComunicacionBaja, 
                  CV.IndicadorEstadoCPE, CV.CodigoEstado, CV.SerieDocumento
                  FROM detalleresumendiario DRD
                  INNER JOIN resumendiario RD ON RD.IdResumenDiario = DRD.IdResumenDiario
                  INNER JOIN comprobanteventa CV ON CV.IdComprobanteVenta = DRD.IdComprobanteVenta
                  WHERE RD.IdResumenDiario = '$Resumen' AND RD.IndicadorEstado = 'A'
          ";
          $query = $this->db->query($consulta);

          $resultado = $query->result_array();
          return $resultado;
        }
}

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mComunicacionBaja extends CI_Model {

        public $ComunicacionBaja = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->ComunicacionBaja = $this->Base->Construir("ComunicacionBaja");
        }

        function ConsultarFacturasElectronicasConComunicacionBaja($data)
        {
          $RazonSocial=$data["RazonSocial"];
          $fecha = $this->Base->ObtenerFechaServidor("Y-m-d");
          $fechacomunicacionbaja=$this->Base->ObtenerFechaServidor("Ymd");
          $NumeroDocumento=$data["NumeroDocumento"];
          $EstadoAnulado =  ESTADO_DOCUMENTO_ANULADO;//$data["EstadoCPE"];
          $IndicadorEstadoCPE = ESTADO_CPE_ACEPTADO;
          $excluye = "'".ESTADO_CPE_ACEPTADO."', '".ESTADO_CPE_EN_PROCESO."', '".ESTADO_CPE_RECHAZADO."'";
          $FechaEmision=$data["FechaEmision"];

          $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
          $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
          $extensionConsulta = "";
          if($vistaventa == 0)
          {
            $extensionConsulta = " And u.IdUsuario = '$idusuario' ";
          }

          $consulta = "select cv.IdComprobanteVenta, CONCAT(td.NombreAbreviado,' ',cv.SerieDocumento,'-',cv.NumeroDocumento) as Numero,
          cv.FechaEmision, cv.IndicadorEstado, cv.IndicadorEstadoCPE, cv.IndicadorEstadoComunicacionBaja,
          cv.IndicadorEstadoResumenDiario, cv.SerieDocumento, cv.NumeroDocumento, td.CodigoTipoDocumento,
          '' as MotivoBaja, p.RazonSocial as RazonSocialCliente, CONCAT(m.SimboloMoneda,' ',CAST(cv.Total as char(10))) as TotalComprobante,
          p.NumeroDocumentoIdentidad, cv.SituacionCPE
          from comprobanteventa as cv
          inner join tipodocumento td on td.IdTipoDocumento=cv.IdTipoDocumento
          inner join cliente c on c.IdPersona = cv.IdCliente
          inner join usuario u on u.IdUsuario = cv.IdUsuario
          inner join Persona p on p.IdPersona = c.IdPersona
          inner join moneda m on m.IdMoneda = cv.IdMoneda
          where (cv.SerieDocumento like '%$NumeroDocumento%' or
          cv.NumeroDocumento like '%$NumeroDocumento%')  and
          (p.RazonSocial like '%$RazonSocial%' or
          p.NumeroDocumentoIdentidad like '%$RazonSocial%') and
          cv.FechaEmision = '$FechaEmision' and
          cv.IndicadorEstado ='$EstadoAnulado' and
          cv.IndicadorEstadoCPE = '$IndicadorEstadoCPE' and
          cv.IndicadorEstadoComunicacionBaja not in ($excluye)
          ".$extensionConsulta."
          ORDER BY cv.FechaEmision DESC, td.NombreTipoDocumento, cv.SerieDocumento, cv.NumeroDocumento";
          $query = $this->db->query($consulta);

          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarComunicacionesBaja($data) {
          $NumeroDocumento=$data["NumeroDocumento"];
          //$EstadoCPE =  $data["EstadoCPE"];
          $FechaInicio=$data["FechaInicio"];
          $FechaFin=$data["FechaFin"];
          $codigo = $data["CodigoEstado"];
          $query = $this->db->query("
          select cb.*
          from comunicacionbaja cb
          where cb.IndicadorEstado = 'A' AND (cb.NombreComunicacionBaja like '%$NumeroDocumento%')  and
          (cb.FechaEmisionDocumento BETWEEN '$FechaInicio' AND '$FechaFin') and
          cb.IndicadorEstadoComunicacionBaja like '$codigo'
          ORDER BY cb.FechaComunicacionBaja DESC, cb.NombreComunicacionBaja");

          $resultado = $query->result_array();
          return $resultado;

        }

        function InsertarComunicacionBaja($data)
        {
          $data["FechaGeneracionBaja"]=$this->Base->ObtenerFechaServidor();
          //$data["FechaComunicacionBaja"]=$data["FechaGeneracionBaja"];
          $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_DOCUMENTO_ACTIVO;
          //$data["IndicadorEstadoComunicacionBaja"]=ESTADO_CPE_GENERADO;

          $resultado = $this->mapper->map($data,$this->ComunicacionBaja);
          $this->db->insert('ComunicacionBaja', $resultado);
          $data["IdComunicacionBaja"] = $this->db->insert_id();

          return($data);
        }

        function BorrarComunicacionBaja($data)
        {
          $data["IndicadorEstado"]=ESTADO_DOCUMENTO_ELIMINADO;
          $this->ActualizarComunicacionBaja($data);
        }

        function ActualizarComunicacionBaja($data)
        {
          $id = $data["IdComunicacionBaja"];
          $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->ComunicacionBaja);
          $this->db->where('IdComunicacionBaja', $id);
          $this->db->update('ComunicacionBaja', $resultado);
          return $resultado;
        }

        function ConsultarComprobantesVentaPorBaja($data)
        {
          $Baja =$data["IdComunicacionBaja"];

          $consulta = "SELECT
                  CV.IdComprobanteVenta, CV.IndicadorEstadoResumenDiario, 
                  CV.IndicadorEstado, CV.IndicadorEstadoComunicacionBaja, 
                  CV.IndicadorEstadoCPE, CV.CodigoEstado, CV.SerieDocumento
                  FROM detallecomunicacionbaja DCB
                  INNER JOIN comunicacionbaja CB ON CB.IdComunicacionBaja = DCB.IdComunicacionBaja
                  INNER JOIN comprobanteventa CV ON CV.IdComprobanteVenta = DCB.IdComprobanteVenta
                  WHERE CB.IdComunicacionBaja = '$Baja' AND CB.IndicadorEstado = 'A'          
          ";
          $query = $this->db->query($consulta);

          $resultado = $query->result_array();
          return $resultado;
        }
}

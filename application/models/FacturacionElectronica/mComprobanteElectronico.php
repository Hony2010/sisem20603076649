<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mComprobanteElectronico extends CI_Model {

        public $ComprobanteElectronico = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('sesionusuario');
               $this->load->library('mapper');
               $this->ComprobanteElectronico = $this->Base->Construir("ComprobanteElectronico");
        }

        function InsertarComprobanteElectronico($data)
        {
          // $data["CodigoHash"] = "";
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $ignore = array();//array("CodigoHash"=>"CodigoHash");
          $data["IndicadorEstadoPublicacionWeb"] = ESTADO_PW_PENDIENTE;
          $resultado = $this->mapper->map($data,$this->ComprobanteElectronico,$ignore);
          if(array_key_exists("CodigoHash", $data))
          {
            $resultado["CodigoHash"] = $data["CodigoHash"];
          }
          $this->db->insert('ComprobanteElectronico', $resultado);
          $resultado["IdComprobanteElectronico"] = $this->db->insert_id();

          return($resultado);
        }

        function ActualizarComprobanteElectronico($data)
        {
          $id = $data["IdComprobanteElectronico"];

          $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $ignore = null;
          // if(array_key_exists("CodigoHash", $data))
          // {
          //   $ignore = array("CodigoHash"=>"CodigoHash");
          // }
          $resultado = $this->mapper->map($data,$this->ComprobanteElectronico,$ignore);
          if(array_key_exists("CodigoHash", $data))
          {
            $resultado["CodigoHash"] = $data["CodigoHash"];
          }
          $this->db->where('IdComprobanteElectronico', $id);
          $this->db->update('ComprobanteElectronico', $resultado);
          return $resultado;
        }

        function ObtenerComprobanteElectronico($data)
        {
          $id=$data["IdComprobanteVenta"];
          $query = $this->db->query("Select *
                                     From ComprobanteElectronico
                                     Where IdComprobanteVenta = '$id'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerComprobanteElectronicoVigentePorIdComprobanteVenta($data) {
          $id=$data["IdComprobanteVenta"];
          $query = $this->db->query("Select *
                                     From ComprobanteElectronico
                                     Where IdComprobanteVenta = '$id' And IndicadorEstado='A'");
          $resultado = $query->row();
          return $resultado;
        }

        function BorrarComprobanteElectronico($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarComprobanteElectronico($data);
          return $data;
        }

        function ActivarComprobanteElectronico($data)
        {
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $this->ActualizarComprobanteElectronico($data);
        }

        function ConsultarComprobantesVentaElectronico($data) {
          $codigo_serie = !array_key_exists("CodigoSerie",$data)? "" : $data["CodigoSerie"];
          $estado = ESTADO_ACTIVO;
          $NumeroDocumento=$data["NumeroDocumento"];
          $RazonSocial=!array_key_exists("RazonSocial",$data)? "%" : $data["RazonSocial"];
          $FechaInicio=$data["FechaInicio"];
          $FechaFin=$data["FechaFin"];
          $IdTipoDocumento= !array_key_exists("IdTipoDocumento",$data)? "%" : $data["IdTipoDocumento"];
          $estadocpe = $data["EstadoCPE"];
          $EstadoCPE_1=$estadocpe == "'%'" ? "'%'" : "''";
          $EstadoCPE_2=$estadocpe == "%" ? "'%'" : $data["EstadoCPE"];

          $excluye = "'".ESTADO_CPE_ACEPTADO."'".", '".ESTADO_CPE_RECHAZADO."'";
          $estado_aceptado = ESTADO_CPE_ACEPTADO;
          $estadoAnulado = ESTADO_DOCUMENTO_ANULADO;

          $estadoComunicacionBaja ="";

          $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
          $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
          $extensionConsulta = "";
          if($vistaventa == 0)
          {
            $extensionConsulta = " And u.IdUsuario = '$idusuario' ";
          }

          $consulta = "select CONCAT(td.NombreAbreviado,' ',cv.SerieDocumento,'-',cv.NumeroDocumento) as Numero,
              CONCAT(m.SimboloMoneda,' ',CAST(cv.Total as char(10))) as TotalComprobante,
              pe.NumeroDocumentoIdentidad, td.NombreAbreviado, td.NombreTipoDocumento,
              pe.RazonSocial as RazonSocialCliente, pe.Direccion,
              cpe.CodigoError, efe.NombreErrorFacturacionElectronica AS DescripcionError, cv.*,
              m.CodigoMoneda, tdi.CodigoDocumentoIdentidad, td.CodigoTipoDocumento, cpe.NombreArchivoComprobante, sce.*,
              cpe.IndicadorEstadoPublicacionWeb,cpe.IdComprobanteElectronico
              from comprobanteelectronico as cpe
              inner join comprobanteventa as cv on cpe.IdComprobanteVenta=cv.IdComprobanteVenta
              inner join tipodocumento td on td.IdTipoDocumento=cv.IdTipoDocumento
              inner join cliente cli on cli.IdPersona = cv.IdCliente
              inner join usuario u on u.IdUsuario = cv.IdUsuario
              inner join Persona pe on pe.IdPersona = cli.IdPersona
              inner join tipodocumentoidentidad tdi on tdi.IdTipoDocumentoIdentidad=pe.IdTipoDocumentoIdentidad
              inner join moneda m on m.IdMoneda=cv.IdMoneda
              left join errorfacturacionelectronica efe on cpe.CodigoError = efe.CodigoErrorFacturacionElectronica
              LEFT JOIN situacioncomprobanteelectronico sce ON sce.CodigoSituacionComprobanteElectronico = cv.SituacionCPE
              where cv.SerieDocumento like '$codigo_serie%' and
              (cv.SerieDocumento like '%$NumeroDocumento%' or
              cv.NumeroDocumento like '%$NumeroDocumento%')  and
              (pe.RazonSocial like '%$RazonSocial%' or pe.NumeroDocumentoIdentidad like '%$RazonSocial%')  and
              (cv.IndicadorEstado = '$estado' or cv.IndicadorEstado = '$estadoAnulado') and cpe.IndicadorEstado='$estado' and
              (cv.FechaEmision between '$FechaInicio' AND '$FechaFin') And
              ( ($EstadoCPE_1= '%') OR  (cpe.IndicadorEstadoCPE in ($EstadoCPE_2) ) ) And
              (cv.IdTipoDocumento like '%$IdTipoDocumento%')
              ".$extensionConsulta."
              ORDER BY td.NombreAbreviado ASC, cv.FechaEmision DESC, cv.SerieDocumento DESC,cv.NumeroDocumento DESC";

          $query = $this->db->query($consulta);
          $resultado = $query->result_array();

          return $resultado;
        }

        function ConsultarComprobantesVentaEnvio($data)
        {
          $codigo_serie = !array_key_exists("CodigoSerie",$data)? "" : $data["CodigoSerie"];
          $estado =ESTADO_ACTIVO;
          $NumeroDocumento=$data["NumeroDocumento"];
          $RazonSocial=$data["RazonSocial"];
          $FechaInicio=$data["FechaInicio"];
          $FechaFin=$data["FechaFin"];
          $IdTipoDocumento= !array_key_exists("IdTipoDocumento",$data)? "%" : $data["IdTipoDocumento"];
          $EstadoCPE=$data["EstadoCPE"];
          // $excluye = "'".ESTADO_CPE_ACEPTADO."'".", '".ESTADO_CPE_RECHAZADO."'";
          $excluye = "'".ESTADO_CPE_ACEPTADO."'".", '".ESTADO_CPE_EN_PROCESO."'";
          // $excluye = "'".ESTADO_CPE_ACEPTADO."'";
          $estadoComunicacionBaja ="";

          $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
          $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
          $extensionConsulta = "";
          if($vistaventa == 0)
          {
            $extensionConsulta = " And u.IdUsuario = '$idusuario' ";
          }

          $consulta = "select
                        CONCAT(td.NombreAbreviado,' ',cv.SerieDocumento,'-',cv.NumeroDocumento) as Numero,
                        CONCAT(m.SimboloMoneda,' ',CAST(cv.Total as char(10))) as TotalComprobante,
                        pe.NumeroDocumentoIdentidad,
                        pe.RazonSocial as RazonSocialCliente,
                        pe.Direccion,
                        cpe.IdComprobanteElectronico,
                        cv.*,
                        m.CodigoMoneda,
                        tdi.CodigoDocumentoIdentidad,
                        td.CodigoTipoDocumento,
                        cpe.NombreArchivoComprobante
                        from comprobanteelectronico as cpe
                        inner join comprobanteventa as cv on cpe.IdComprobanteVenta=cv.IdComprobanteVenta
                        inner join tipodocumento td on td.IdTipoDocumento=cv.IdTipoDocumento
                        inner join usuario u on u.IdUsuario = cv.IdUsuario
                        inner join cliente cli on cli.IdPersona = cv.IdCliente
                        inner join Persona pe on pe.IdPersona = cli.IdPersona
                        inner join tipodocumentoidentidad tdi on tdi.IdTipoDocumentoIdentidad=pe.IdTipoDocumentoIdentidad
                        inner join moneda m on m.IdMoneda=cv.IdMoneda
                        where cv.SerieDocumento like '$codigo_serie%' and
                        (cv.SerieDocumento like '%$NumeroDocumento%' or
                        cv.NumeroDocumento like '%$NumeroDocumento%')  and
                        (pe.RazonSocial like '%$RazonSocial%' or
                        pe.NumeroDocumentoIdentidad like '%$RazonSocial%')  and
                        (cv.IndicadorEstado = '$estado') and cpe.IndicadorEstado='$estado' and
                        (cv.FechaEmision between '$FechaInicio' AND '$FechaFin') and
                        (cpe.IndicadorEstadoCPE like '%$EstadoCPE%') And
                        (cv.IdTipoDocumento like '%$IdTipoDocumento%') And
                        (cpe.IndicadorEstadoCPE not in($excluye))
                        ".$extensionConsulta."
                        ORDER BY cv.FechaEmision DESC, td.NombreTipoDocumento, cv.SerieDocumento, cv.NumeroDocumento";

          $query = $this->db->query($consulta);
              //like '%$EstadoCPE%'and
              //like '%$EstadoCPE%'
          $resultado = $query->result_array();
          //(cpe.IndicadorEstadoCPE not in($excluye)) And
          return $resultado;
        }

        function ConsultarComprobantesVentaEnvioPendiente($data)
        {
          $codigo_serie = !array_key_exists("CodigoSerie",$data)? "" : $data["CodigoSerie"];
          $estado =ESTADO_ACTIVO;
          $NumeroDocumento=$data["NumeroDocumento"];
          $RazonSocial=$data["RazonSocial"];
          $FechaInicio=$data["FechaInicio"];
          $FechaFin=$data["FechaFin"];
          $IdTipoDocumento= !array_key_exists("IdTipoDocumento",$data)? "%" : $data["IdTipoDocumento"];
          $EstadoCPE=ESTADO_CPE_EN_PROCESO;//$data["EstadoCPE"];
          // $excluye = "'".ESTADO_CPE_ACEPTADO."'".", '".ESTADO_CPE_RECHAZADO."'";
          // $excluye = "'".ESTADO_CPE_ACEPTADO."'".", '".ESTADO_CPE_EN_PROCESO."'";
          // $excluye = "'".ESTADO_CPE_ACEPTADO."'";
          $estadoComunicacionBaja ="";

          $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
          $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
          $extensionConsulta = "";
          if($vistaventa == 0)
          {
            $extensionConsulta = " And u.IdUsuario = '$idusuario' ";
          }

          $consulta = "select CONCAT(td.NombreAbreviado,' ',cv.SerieDocumento,'-',cv.NumeroDocumento) as Numero,
                        CONCAT(m.SimboloMoneda,' ',CAST(cv.Total as char(10))) as TotalComprobante,
                        pe.NumeroDocumentoIdentidad, pe.RazonSocial as RazonSocialCliente, pe.Direccion,
                        cpe.IdComprobanteElectronico, cv.*, cpe.IndicadorEstadoCPE, m.CodigoMoneda,
                        tdi.CodigoDocumentoIdentidad, td.CodigoTipoDocumento, cpe.NombreArchivoComprobante
                        from comprobanteelectronico as cpe
                        inner join comprobanteventa as cv on cpe.IdComprobanteVenta=cv.IdComprobanteVenta
                        inner join tipodocumento td on td.IdTipoDocumento=cv.IdTipoDocumento
                        inner join usuario u on u.IdUsuario = cv.IdUsuario
                        inner join cliente cli on cli.IdPersona = cv.IdCliente
                        inner join Persona pe on pe.IdPersona = cli.IdPersona
                        inner join tipodocumentoidentidad tdi on tdi.IdTipoDocumentoIdentidad=pe.IdTipoDocumentoIdentidad
                        inner join moneda m on m.IdMoneda=cv.IdMoneda
                        where
                        cv.SerieDocumento like '$codigo_serie%' and
                        (cv.SerieDocumento like '%$NumeroDocumento%' or
                        cv.NumeroDocumento like '%$NumeroDocumento%')  and
                        (pe.RazonSocial like '%$RazonSocial%' or
                        pe.NumeroDocumentoIdentidad like '%$RazonSocial%')  and
                        (cv.IndicadorEstado = '$estado') and cpe.IndicadorEstado='$estado' and
                        (cv.FechaEmision between '$FechaInicio' AND '$FechaFin') and
                        (cpe.IndicadorEstadoCPE like '%$EstadoCPE%') And
                        (cv.IdTipoDocumento like '%$IdTipoDocumento%') And
                        (cpe.IndicadorEstadoCPE in('".ESTADO_CPE_EN_PROCESO."'))
                        ".$extensionConsulta."
                        ORDER BY cv.FechaEmision DESC, td.NombreTipoDocumento, cv.SerieDocumento, cv.NumeroDocumento";

          $query = $this->db->query($consulta);
              //like '%$EstadoCPE%'and
              //like '%$EstadoCPE%'
          $resultado = $query->result_array();
          //(cpe.IndicadorEstadoCPE not in($excluye)) And
          return $resultado;
        }

        function ConsultarComprobantesElectronicoPublicacionWeb($data)
        {

          $estado =ESTADO_ACTIVO;
          $FechaInicio=$data["FechaInicio"];
          $FechaFin=$data["FechaFin"];
          $IdTipoDocumento= !array_key_exists("IdTipoDocumento",$data)? "%" : $data["IdTipoDocumento"];
          $EstadoPW=$data["IndicadorEstadoPW"];
          // $excluye = "'".ESTADO_CPE_ACEPTADO."'".", '".ESTADO_CPE_RECHAZADO."'";
          // $estadoComunicacionBaja ="";
          $limit = !array_key_exists("FilasPorPagina",$data)? "" : "LIMIT 1,".$data["FilasPorPagina"];

          $query = $this->db->query("select CONCAT(td.NombreAbreviado,' ',cv.SerieDocumento,'-',cv.NumeroDocumento) as Numero,
                      CONCAT(m.SimboloMoneda,' ',CAST(cv.Total as char(10))) as TotalComprobante, pe.NumeroDocumentoIdentidad,
                      pe.RazonSocial as RazonSocialCliente, pe.Direccion, cv.*,
                      cpe.IdComprobanteElectronico, cpe.IndicadorEstadoPublicacionWeb, m.CodigoMoneda,
                      tdi.CodigoDocumentoIdentidad, td.CodigoTipoDocumento, cpe.NombreArchivoComprobante
                      from comprobanteelectronico as cpe
                      inner join comprobanteventa as cv on cpe.IdComprobanteVenta=cv.IdComprobanteVenta
                      inner join tipodocumento td on td.IdTipoDocumento=cv.IdTipoDocumento
                      inner join cliente cli on cli.IdPersona = cv.IdCliente
                      inner join Persona pe on pe.IdPersona = cli.IdPersona
                      inner join tipodocumentoidentidad tdi on tdi.IdTipoDocumentoIdentidad=pe.IdTipoDocumentoIdentidad
                      inner join moneda m on m.IdMoneda=cv.IdMoneda
                      where
                      (cv.IndicadorEstado = '$estado') and cpe.IndicadorEstado='$estado' and
                      (cv.FechaEmision between '$FechaInicio' AND '$FechaFin') and
                      (cpe.IndicadorEstadoPublicacionWeb like '%$EstadoPW%') And
                      (cv.IdTipoDocumento like '$IdTipoDocumento')
                      ORDER BY cv.FechaEmision DESC, td.NombreTipoDocumento, cv.SerieDocumento, cv.NumeroDocumento ".
                      $limit);
              //like '%$EstadoCPE%'and
              //like '%$EstadoCPE%'
          $resultado = $query->result_array();
          //(cpe.IndicadorEstadoCPE not in($excluye)) And
          return $resultado;
        }

        function ConsultarComprobantesElectronico($data)
        {
          $NumeroDocumento=$data["NumeroDocumento"];
          $FechaInicio=$data["FechaInicio"];
          $FechaFin=$data["FechaFin"];
          $EstadoCPE=$data["EstadoCPE"];

          $query = $this->db->query("select cpe.IdComprobanteElectronico, CONCAT(td.NombreAbreviado,'-',cv.SerieDocumento,'-',cv.NumeroDocumento) as Numero,
            	cv.FechaEmision, pe.RazonSocial as NombreCliente, pe.NumeroDocumentoIdentidad,
            	CONCAT(m.SimboloMoneda,' ',CAST(cv.Total as char(10))) as TotalComprobante,
            	cpe.IndicadorEstado, sce.NombreSituacionComprobanteElectronico as EstadoComprobanteElectronico ,
              cv.Total, cv.IdComprobanteVenta
              from comprobanteelectronico as cpe
              inner join comprobanteventa as cv on cpe.IdComprobanteVenta = cv.IdComprobanteVenta
              inner join tipodocumento td on td.IdTipoDocumento=cv.IdTipoDocumento
              inner join cliente cli on cli.IdPersona = cv.IdCliente
              inner join Persona pe on pe.IdPersona = cli.IdPersona
              inner join moneda m on m.IdMoneda=cv.IdMoneda
              left join situacioncomprobanteelectronico sce on sce.CodigoSituacionComprobanteElectronico = cpe.IndicadorEstado
              where (cv.SerieDocumento like '$NumeroDocumento' or
              cv.NumeroDocumento like '$NumeroDocumento')  and
              (cv.FechaEmision between '$FechaInicio' AND '$FechaFin') and
              cpe.IndicadorEstado like '$EstadoCPE'");

          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarFacturasNoEnviadasVencidasSunat() {
          $sql = "SELECT cv.* FROM comprobanteventa cv
                    INNER JOIN comprobanteelectronico ce ON ce.IdComprobanteVenta = cv.IdComprobanteVenta
                    WHERE cv.FechaEmision < CAST(DATE_SUB(NOW(), INTERVAL 7 DAY) AS DATE)
                   AND cv.IndicadorEstado = 'A'
                   AND ce.IndicadorEstado = 'A'
                   AND cv.SerieDocumento LIKE 'F%'
                   AND cv.IndicadorEstadoCPE = 'G'";
          
          $query = $this->db->query($sql);

          $resultado = $query->result_array();

          return $resultado;
        }


        function ConsultarFacturasNoEnviadasSunat($dias) {
          $plazoDias = PLAZO_DE_DIAS_SUNAT;
          $sql = "SELECT cv.* FROM comprobanteventa cv
          INNER JOIN comprobanteelectronico ce ON ce.IdComprobanteVenta = cv.IdComprobanteVenta
          WHERE (DATE_SUB(DATE_ADD(cv.FechaEmision, INTERVAL $plazoDias DAY), INTERVAL ('$dias' - 1) DAY) BETWEEN DATE_SUB(CURDATE(), INTERVAL ('$dias' - 1) DAY) AND CURDATE())
          AND cv.IndicadorEstado = 'A'
          AND ce.IndicadorEstado = 'A'
          AND cv.SerieDocumento LIKE 'F%'
          AND cv.IndicadorEstadoCPE = 'G'";
          
          $query = $this->db->query($sql);

          $resultado = $query->result_array();

          return $resultado;
        }

        function ConsultarCantidadFacturasNoEnviadasSunat($dias) {
          $plazoDias = PLAZO_DE_DIAS_SUNAT;
          $sql ="SELECT count(*) 'Total' FROM comprobanteventa cv
          INNER JOIN comprobanteelectronico ce ON ce.IdComprobanteVenta = cv.IdComprobanteVenta
          WHERE (DATE_SUB(DATE_ADD(cv.FechaEmision, INTERVAL $plazoDias DAY), INTERVAL ('$dias' - 1) DAY) BETWEEN DATE_SUB(CURDATE(), INTERVAL ('$dias' - 1) DAY) AND CURDATE())
          AND cv.IndicadorEstado = 'A'
          AND ce.IndicadorEstado = 'A'
          AND cv.SerieDocumento LIKE 'F%'
          AND cv.IndicadorEstadoCPE = 'G'";
          
          $query = $this->db->query($sql);

          $resultado = $query->row();
          return $resultado;
        }

        function ConsultarComprobanteElectronicosParaValidacion($data)
        {
          $FechaInicio=$data["FechaInicio"];
          $FechaFin=$data["FechaFin"];
          $TipoDocumento=$data["TipoDocumento"];
          $TextoFiltro=$data["TextoFiltro"];

          $query = $this->db->query("SELECT CV.*, TV.NombreTipoVenta,
                        TD.NombreAbreviado, TD.CodigoTipoDocumento, TDI.CodigoDocumentoIdentidad,
                        Per.IdPersona, CONCAT(Per.NumeroDocumentoIdentidad,'  -  ',Per.RazonSocial)  AS RazonSocialCiente,
                        Per.RazonSocial, Per.NumeroDocumentoIdentidad
                        FROM ComprobanteVenta AS CV
                        INNER JOIN comprobanteelectronico AS CE ON CE.IdComprobanteVenta = CV.IdComprobanteVenta
                        LEFT JOIN TipoVenta AS TV ON TV.IdTipoVenta = CV.IdTipoVenta
                        LEFT JOIN TipoDocumento AS TD ON TD.IdTipoDocumento = CV.IdTipoDocumento
                        LEFT JOIN Cliente AS C ON C.IdPersona = CV.IdCliente
                        LEFT JOIN Persona AS Per ON Per.IdPersona = C.IdPersona
                        LEFT JOIN Usuario AS U ON U.IdUsuario = CV.IdUsuario
                        LEFT JOIN TipoDocumentoIdentidad AS TDI ON TDI.IdTipoDocumentoIdentidad = Per.IdTipoDocumentoIdentidad
                        WHERE (CV.IndicadorEstado = 'A' OR CV.IndicadorEstado = 'N' ) AND
                        (CV.SerieDocumento LIKE '$TextoFiltro' OR
                        CV.NumeroDocumento LIKE '%$TextoFiltro%' OR
                        Per.RazonSocial LIKE '%$TextoFiltro%' OR
                        Per.NumeroDocumentoIdentidad LIKE '%$TextoFiltro%') AND
                        CV.IdTipoVenta LIKE '%' AND
                        CV.IdTipoDocumento LIKE '$TipoDocumento' AND
                        CV.FechaEmision BETWEEN '$FechaInicio' AND '$FechaFin'
                        AND ((CV.IndicadorEstadoCPE = 'C' OR CV.IndicadorEstadoCPE = 'R') OR
                        (CV.IndicadorEstadoResumenDiario = 'C' OR CV.IndicadorEstadoResumenDiario = 'R')) AND CE.IndicadorEstado = 'A'
                        ORDER BY TD.NombreTipoDocumento, CV.FechaEmision DESC, TD.NombreAbreviado ASC, CV.SerieDocumento DESC,CV.NumeroDocumento DESC");

          $resultado = $query->result_array();
          return $resultado;
        }
}

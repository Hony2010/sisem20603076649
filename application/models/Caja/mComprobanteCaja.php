<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mComprobanteCaja extends CI_Model {

    public $ComprobanteCaja = array();

    public function __construct()
    {
            parent::__construct();
            $this->load->database();
            $this->load->model("Base");
            $this->load->model("Configuracion/General/mSituacionComprobanteElectronico");
            $this->load->library('shared');
            $this->load->library('mapper');
            $this->load->library('sesionusuario');
            $this->ComprobanteCaja = $this->Base->Construir("ComprobanteCaja");
    }

    function ObtenerComprobanteCajaPorSerieDocumento($data)
    {
        $tipo=$data["IdTipoDocumento"];
        $serie=$data["SerieDocumento"];
        $numero=$data["NumeroDocumento"];
        $query = $this->db->query("Select CC.*
            from ComprobanteCaja As CC
            where CC.IndicadorEstado = 'A' and CC.SerieDocumento = '$serie' and CC.NumeroDocumento='$numero' and CC.IdTipoDocumento = '$tipo'");
        $resultado = $query->row();
        return $resultado;
    }

    // function ObtenerDuplicadoDeComprobanteCaja($data)
    // {
    //     $numero=$data["NumeroDocumento"];
    //     $query = $this->db->query("Select *
    //                                 From ComprobanteCaja
    //                                 Where IndicadorEstado = 'A' and NumeroDocumento = '$numero'");
    //     $resultado = $query->result_array();
    //     return $resultado;
    // }

    function ListarComprobantesCaja($data)
    {
        $id=$data["IdComprobanteCaja"];
        $query = $this->db->query("Select * from DetalleComprobanteCaja
                                    where IdComprobanteCaja = '$id'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function InsertarComprobanteCaja($data)
    {
        $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
        $data["IndicadorEstado"]=ESTADO_ACTIVO;
        $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
        $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
        
        if (array_key_exists("IdTurno", $data)){
            $data["IdTurno"] = ($data["IdTurno"] == '') ? null : $data["IdTurno"];
        }
        if (array_key_exists("IdComprobanteCompra", $data)){
            $data["IdComprobanteCompra"] = ($data["IdComprobanteCompra"] == '') ? null : $data["IdComprobanteCompra"];
        }
        if (array_key_exists("IdComprobanteVenta", $data)){
            $data["IdComprobanteVenta"] = ($data["IdComprobanteVenta"] == '') ? null : $data["IdComprobanteVenta"];
        }
        if (array_key_exists("IdPersona", $data)){
            $data["IdPersona"] = ($data["IdPersona"] == '') ? null : $data["IdPersona"];
        }
        
        $resultado = $this->mapper->map($data,$this->ComprobanteCaja);

        $this->db->insert('ComprobanteCaja', $resultado);
        $resultado["IdComprobanteCaja"] = $this->db->insert_id();
        return($resultado);
    }

    function ActualizarComprobanteCaja($data)
    {
        $id=$data["IdComprobanteCaja"];
        $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
        $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
        if (array_key_exists("IdTurno", $data)){
            $data["IdTurno"] = ($data["IdTurno"] == '') ? null : $data["IdTurno"];
        }
        if (array_key_exists("IdComprobanteCompra", $data)){
            $data["IdComprobanteCompra"] = ($data["IdComprobanteCompra"] == '') ? null : $data["IdComprobanteCompra"];
        }
        if (array_key_exists("IdComprobanteVenta", $data)){
            $data["IdComprobanteVenta"] = ($data["IdComprobanteVenta"] == '') ? null : $data["IdComprobanteVenta"];
        }
        if (array_key_exists("IdPersona", $data)){
            $data["IdPersona"] = ($data["IdPersona"] == '') ? null : $data["IdPersona"];
        }
         
        $resultado = $this->mapper->map($data,$this->ComprobanteCaja);

        $this->db->where('IdComprobanteCaja', $id);
        $this->db->update('ComprobanteCaja', $resultado);
        return $resultado;
    }

    function BorrarComprobanteCaja($data) {
        $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        $resultado = $this->ActualizarComprobanteCaja($data);
        return $resultado;
    }

    function ObtenerComprobanteCaja($data)
    {
        $IdComprobanteCaja=$data["IdComprobanteCaja"];

        $query = $this->db->query("select
                                    CONCAT(TD.NombreAbreviado, '-', CC.SerieDocumento,'-',CC.NumeroDocumento) AS Numero,
                                    CONCAT(M.SimboloMoneda,' ',CAST(CC.MontoComprobante AS char(10))) AS TotalComprobante,
                                    P.NumeroDocumentoIdentidad, TD.NombreAbreviado, TD.NombreTipoDocumento,
                                    P.RazonSocial AS RazonSocialCliente, P.Direccion,
                                    CC.*, M.CodigoMoneda, TDI.CodigoDocumentoIdentidad,
                                    TD.CodigoTipoDocumento FROM ComprobanteCaja CC
                                    INNER JOIN TipoDocumento TD ON TD.IdTipoDocumento = CC.IdTipoDocumento
                                    INNER JOIN Persona P On P.IdPersona = CC.IdPersona
                                    INNER JOIN TipoDocumentoIdentidad TDI ON TDI.IdTipoDocumentoIdentidad = P.IdTipoDocumentoIdentidad
                                    INNER JOIN Moneda M ON M.IdMoneda = CC.IdMoneda
                                    LEFT JOIN TipoOperacionCaja CCA ON CCA.IdTipoOperacionCaja = CC.IdTipoOperacionCaja
                                    WHERE CC.IdComprobanteCaja = '$IdComprobanteCaja'");

        $resultado = $query->result_array();
        return $resultado;
    }

    function ConsultarComprobanteCajaPorId($data)
    {
        $id=$data["IdComprobanteCaja"];

        $query = $this->db->query("Select CC.*, C.NombreCaja, CCA.NombreTipoOperacionCaja,
                                    TD.NombreAbreviado, P.IdPersona, M.NombreMoneda, U.AliasUsuarioVenta,
                                    P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                                    M.SimboloMoneda From ComprobanteCaja AS CC
                                    INNER JOIN Caja C ON C.IdCaja = CC.IdCaja
                                    INNER JOIN TipoOperacionCaja CCA ON CCA.IdTipoOperacionCaja = CC.IdTipoOperacionCaja
                                    INNER JOIN Moneda M ON M.IdMoneda = CC.IdMoneda
                                    INNER JOIN Persona P ON P.IdPersona = CC.IdPersona
                                    INNER JOIN TipoDocumento TD ON TD.IdTipoDocumento = CC.IdTipoDocumento
                                    INNER JOIN Usuario U ON U.IdUsuario = CC.IdUsuario
                                    Where CC.IndicadorEstado = 'A' AND CC.IdComprobanteCaja like '$id'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ConsultarComprobantesCaja($data,$numerofilainicio,$numerorfilasporpagina)
    {
        $criterio=$data["TextoFiltro"];
        $fechainicio =$data["FechaInicio"];
        $fechafin =$data["FechaFin"];
        $tipodocumento=$data["IdTipoDocumento"];
        $moneda=$data["IdMoneda"];
        $idcaja = $data["IdCaja"];
        $idusuario = $data["IdUsuario"];
        //$idmediopago=$data["IdMedioPago"];
        $idtipooperacioncaja = $data["IdTipoOperacionCaja"];
        $idtipooperacioncajacobranzacliente = ID_TIPO_OPERACION_CAJA_COBRANZA_CLIENTE;
        $idtipooperacioncajapagoproveedor = ID_TIPO_OPERACION_CAJA_PAGO_PROVEEDOR;
        $consulta = "Select CC.*, C.NombreCaja,
                    (SELECT GROUP_CONCAT(CONCAT(TDI.NombreAbreviado, ' ', CV.SerieDocumento, '-', CV.NumeroDocumento) SEPARATOR ', ') FROM movimientocaja MC
                    INNER JOIN comprobanteventa CV ON CV.IdComprobanteVenta = MC.IdComprobanteVenta
                    INNER JOIN tipodocumento TDI ON TDI.IdTipoDocumento = CV.IdTipoDocumento
                    WHERE MC.IdComprobanteCaja = CC.IdComprobanteCaja AND (MC.IndicadorEstado = 'A' OR MC.IndicadorEstado = 'N')
                    AND MC.IdComprobanteVenta IS NOT NULL AND CV.IndicadorEstado = 'A') AS 'DocumentoReferencia',
                    TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                    P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                    M.SimboloMoneda, TOC.NombreConceptoCaja, TOC.IndicadorTipoComprobante,
                    U.NombreUsuario,MP.NombreAbreviado as NombreAbreviadoMedioPago 
                    From ComprobanteCaja CC
                    left join Caja C ON C.IdCaja = CC.IdCaja
                    left join TipoOperacionCaja TOC ON TOC.IdTipoOperacionCaja = CC.IdTipoOperacionCaja
                    left join TipoDocumento TD ON TD.IdTipoDocumento = CC.IdTipoDocumento
                    left join Persona P ON P.IdPersona = CC.IdPersona
                    left join Moneda M ON M.IdMoneda = CC.IdMoneda
                    left join Usuario U ON U.IdUsuario = CC.IdUsuario
                    left join MedioPago MP ON MP.IdMedioPago = CC.IdMedioPago
                    WHERE (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado = 'N' )
                    AND (CC.SerieDocumento like '%$criterio%'
                    OR CC.NumeroDocumento like '%$criterio%')
                    AND CC.IdTipoDocumento like '$tipodocumento'
                    AND CC.IdMoneda like '$moneda'
                    AND CC.FechaComprobante BETWEEN '$fechainicio' AND '$fechafin'
                    AND CC.IdCaja like '$idcaja'
                    AND CC.IdUsuario like '$idusuario'                    
                    AND (CC.IdTipoOperacionCaja like '$idtipooperacioncaja' AND CC.IdTipoOperacionCaja != '$idtipooperacioncajacobranzacliente' AND CC.IdTipoOperacionCaja != '$idtipooperacioncajapagoproveedor')
                    ORDER BY TD.NombreTipoDocumento, CC.FechaComprobante DESC, TD.NombreAbreviado ASC, CC.SerieDocumento DESC, CC.NumeroDocumento DESC
                    LIMIT $numerofilainicio, $numerorfilasporpagina";//OR P.RazonSocial like '%$criterio%' OR P.NumeroDocumentoIdentidad like '%$criterio%'
        $query = $this->db->query($consulta);
        $resultado = $query->result_array();
        return $resultado;
    }

    function ObtenerNumeroTotalComprobantesCaja($data)
    {
        $criterio=$data["TextoFiltro"];
        $fechainicio =$data["FechaInicio"];
        $fechafin =$data["FechaFin"];
        $idcaja =$data["IdCaja"];
        $idtipooperacioncaja =$data["IdTipoOperacionCaja"];
        $tipodocumento=$data["IdTipoDocumento"];
        $moneda=$data["IdMoneda"];
        $idusuario = $data["IdUsuario"];
        //$idmediopago=$data["IdMedioPago"];
        $idtipooperacioncajacobranzacliente = ID_TIPO_OPERACION_CAJA_COBRANZA_CLIENTE;
        $idtipooperacioncajapagoproveedor = ID_TIPO_OPERACION_CAJA_PAGO_PROVEEDOR;
        
        $consulta = "Select CC.*, C.NombreCaja,
                    TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                    P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                    M.SimboloMoneda, CCA.NombreConceptoCaja From ComprobanteCaja CC
                    left join Caja C ON C.IdCaja = CC.IdCaja
                    left join TipoOperacionCaja CCA ON CCA.IdTipoOperacionCaja = CC.IdTipoOperacionCaja
                    left join TipoDocumento TD ON TD.IdTipoDocumento = CC.IdTipoDocumento
                    left join Persona P ON P.IdPersona = CC.IdPersona
                    left join Moneda M ON M.IdMoneda = CC.IdMoneda
                    left join Usuario U ON U.IdUsuario = CC.IdUsuario
                    Where (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado='N' ) and
                    (CC.SerieDocumento like '%$criterio%' or
                    CC.NumeroDocumento like '%$criterio%') And
                    (CC.IdTipoOperacionCaja like '$idtipooperacioncaja' AND CC.IdTipoOperacionCaja != '$idtipooperacioncajacobranzacliente' AND CC.IdTipoOperacionCaja != '$idtipooperacioncajapagoproveedor') And
                    CC.IdCaja like '$idcaja' And
                    CC.IdTipoDocumento like '$tipodocumento' And
                    CC.IdMoneda like '$moneda' And
                    CC.IdUsuario like '$idusuario' And                    
                    CC.FechaComprobante BETWEEN '$fechainicio' And '$fechafin'
                    ORDER BY CC.SerieDocumento,CC.NumeroDocumento";//or P.RazonSocial like '%$criterio%' or P.NumeroDocumentoIdentidad like '%$criterio%'

        $query = $this->db->query($consulta);
        $resultado = $query->num_rows();
        return $resultado;
    }

    function ObtenerFechaMenor($data)
    {
        $tipodocumento=$data["IdTipoDocumento"];
        $numero=$data["NumeroDocumento"];
        $serie=$data["SerieDocumento"];
        $query = $this->db->query("Select max(FechaComprobante) AS FechaComprobanteMenor  FROM ComprobanteCaja
        WHERE (IndicadorEstado='A' or IndicadorEstado='N') AND NumeroDocumento<'$numero' AND
        SerieDocumento='$serie' AND IdTipoDocumento='$tipodocumento'");
        $resultado = $query->row();
        return $resultado;
    }

    function ObtenerFechaMayor($data)
    {
        $tipodocumento=$data["IdTipoDocumento"];
        $numero=$data["NumeroDocumento"];
        $serie=$data["SerieDocumento"];
        $query = $this->db->query("Select min(FechaComprobante) AS FechaComprobanteMayor FROM comprobanteCaja
        WHERE (IndicadorEstado='A' or IndicadorEstado='N') AND NumeroDocumento>'$numero' AND
        SerieDocumento='$serie' AND IdTipoDocumento='$tipodocumento'");
        $resultado = $query->row();
        return $resultado;
    }

    function ObtenerMinimoMaximoFechaEmisionComprobanteCaja() {
        $query = $this->db->query("Select min(fechaemision) as minimofechaemision,max(fechaemision) as maximofechaemision from ComprobanteCaja");
        $resultado = $query->row();
        return $resultado;
    }

    // function ObtenerDocumentosPorIdComprobanteVenta($data)
    // {
    //     $comprobante = $data["IdComprobanteVenta"];

    //     $query = $this->db->query("Select * FROM ComprobanteCaja CC
    //                                 WHERE IdComprobanteVenta = '$comprobante'
    //                                 AND IndicadorEstado = 'A'");
    //     $resultado = $query->result_array();
    //     return $resultado;
    // }    
    
    // function ObtenerDocumentosPorIdComprobanteCompra($data)
    // {
    //     $comprobante = $data["IdComprobanteCompra"];

    //     $query = $this->db->query("Select * FROM ComprobanteCaja CC
    //                                 WHERE IdComprobanteCompra = '$comprobante'
    //                                 AND IndicadorEstado = 'A'");
    //     $resultado = $query->result_array();
    //     return $resultado;
    // }

    function ObtenerDocumentosPorIdComprobanteVentaReferencia($data)
    {
        $comprobante = $data["IdComprobanteVenta"];
        $query = $this->db->query("Select * FROM ComprobanteCaja CC
                                    WHERE IdComprobanteVentaReferencia = '$comprobante'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ObtenerDocumentosPorIdComprobanteCompraReferencia($data)
    {
        $comprobante = $data["IdComprobanteCompra"];
        $query = $this->db->query("Select * FROM ComprobanteCaja CC
                                    WHERE IdComprobanteCompraReferencia = '$comprobante'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

    //Para Apertura Caja
    function ObtenerComprobanteCajaApertura($data)
    {
        $caja = $data["IdCaja"];
        $idmotivo = ID_TIPO_OPERACION_CAJA_SALDO_INICIAL;
        $turno = $data["IdTurno"];
        $fecha = $data["FechaComprobante"];

        $query = $this->db->query("SELECT CC.* FROM ComprobanteCaja CC
                                    WHERE IdCaja = '$caja' AND IdTurno = '$turno'
                                    AND IdTipoOperacionCaja = '$idmotivo' AND DATE(FechaComprobante) = '$fecha'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ConsultarCobranzasCliente($data,$numerofilainicio,$numerorfilasporpagina)
    {
        $criterio=$data["TextoFiltro"];
        $fechainicio =$data["FechaInicio"];
        $fechafin =$data["FechaFin"];
        $tipodocumento=$data["IdTipoDocumento"];
        $idcaja = $data["IdCaja"];
        $idusuario = $data["IdUsuario"];
        $idtipooperacioncaja = ID_TIPO_OPERACION_CAJA_COBRANZA_CLIENTE; //$data["IdTipoOperacionCaja"];
        $idtipodocumento=$data["IdTipoDocumento"];
        $idmediopago=$data["IdMedioPago"];

        $consulta = "Select CC.*, C.NombreCaja,
                    (SELECT GROUP_CONCAT(CONCAT(TDI.NombreAbreviado, ' ', CV.SerieDocumento, '-', CV.NumeroDocumento) SEPARATOR ', ') FROM movimientocaja MC
                    INNER JOIN comprobanteventa CV ON CV.IdComprobanteVenta = MC.IdComprobanteVenta
                    INNER JOIN tipodocumento TDI ON TDI.IdTipoDocumento = CV.IdTipoDocumento
                    WHERE MC.IdComprobanteCaja = CC.IdComprobanteCaja AND (MC.IndicadorEstado = 'A' OR MC.IndicadorEstado = 'N')
                    AND MC.IdComprobanteVenta IS NOT NULL AND CV.IndicadorEstado = 'A') AS 'DocumentoReferencia',
                    TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                    P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                    M.SimboloMoneda, TOC.NombreConceptoCaja, TOC.IndicadorTipoComprobante,
                    MP.NombreMedioPago,U.NombreUsuario,
                    date_format(CC.FechaRegistro, '%r') as HoraEmision
                    From ComprobanteCaja CC
                    left join Caja C ON C.IdCaja = CC.IdCaja
                    left join TipoOperacionCaja TOC ON TOC.IdTipoOperacionCaja = CC.IdTipoOperacionCaja
                    left join TipoDocumento TD ON TD.IdTipoDocumento = CC.IdTipoDocumento
                    left join Persona P ON P.IdPersona = CC.IdPersona
                    left join Moneda M ON M.IdMoneda = CC.IdMoneda
                    left join Usuario U ON U.IdUsuario = CC.IdUsuario
                    left join MedioPago MP on MP.IdMedioPago = CC.IdMedioPago
                    WHERE (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado = 'N' )
                    AND (CC.SerieDocumento like '%$criterio%'
                    OR CC.NumeroDocumento like '%$criterio%' OR p.RazonSocial like '%$criterio%')
                    AND CC.IdTipoDocumento like '$tipodocumento'
                    AND CC.FechaComprobante BETWEEN '$fechainicio' AND '$fechafin'
                    AND CC.IdCaja like '$idcaja'
                    AND CC.IdUsuario like '$idusuario'
                    AND CC.IdMedioPago like '$idmediopago'
                    AND CC.IdTipoOperacionCaja like '$idtipooperacioncaja'
                    ORDER BY TD.NombreTipoDocumento, CC.FechaComprobante DESC, TD.NombreAbreviado ASC, CC.SerieDocumento DESC, CC.NumeroDocumento DESC
                    LIMIT $numerofilainicio, $numerorfilasporpagina"; // OR P.RazonSocial like '%$criterio%' OR P.NumeroDocumentoIdentidad like '%$criterio%'
        $query = $this->db->query($consulta);
        $resultado = $query->result_array();
        return $resultado;
    }

    function ObtenerNumeroTotalCobranzasCliente($data)
    {
        $criterio=$data["TextoFiltro"];
        $fechainicio =$data["FechaInicio"];
        $fechafin =$data["FechaFin"];
        $idcaja =$data["IdCaja"];
        $idusuario = $data["IdUsuario"];
        $idtipooperacioncaja = ID_TIPO_OPERACION_CAJA_COBRANZA_CLIENTE; //$data["IdTipoOperacionCaja"];
        $idtipodocumento=$data["IdTipoDocumento"];
        $idmediopago=$data["IdMedioPago"];

        $consulta = "Select CC.*, C.NombreCaja,
                    TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                    P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                    M.SimboloMoneda, CCA.NombreConceptoCaja ,MP.NombreMedioPago,U.NombreUsuario
                    From ComprobanteCaja CC
                    left join Caja C ON C.IdCaja = CC.IdCaja
                    left join TipoOperacionCaja CCA ON CCA.IdTipoOperacionCaja = CC.IdTipoOperacionCaja
                    left join TipoDocumento TD ON TD.IdTipoDocumento = CC.IdTipoDocumento
                    left join Persona P ON P.IdPersona = CC.IdPersona
                    left join Moneda M ON M.IdMoneda = CC.IdMoneda
                    left join Usuario U ON U.IdUsuario = CC.IdUsuario
                    left join MedioPago MP on MP.IdMedioPago = CC.IdMedioPago
                    Where (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado='N' ) and
                    (CC.SerieDocumento like '%$criterio%'
                    OR CC.NumeroDocumento like '%$criterio%' OR p.RazonSocial like '%$criterio%') And
                    CC.IdTipoOperacionCaja like '$idtipooperacioncaja' And
                    CC.IdCaja like '$idcaja' And
                    CC.IdUsuario like '$idusuario' And
                    CC.IdMedioPago like '$idmediopago' And
                    CC.IdTipoDocumento like '$idtipodocumento' And
                    CC.FechaComprobante BETWEEN '$fechainicio' And '$fechafin'
                    ORDER BY CC.SerieDocumento,CC.NumeroDocumento"; //P.RazonSocial like '%$criterio%' or P.NumeroDocumentoIdentidad like '%$criterio%'

        $query = $this->db->query($consulta);
        $resultado = $query->num_rows();
        return $resultado;
    }

    //PARA LOS PAGOS
    function ConsultarPagosProveedor($data,$numerofilainicio,$numerorfilasporpagina)
    {
        $criterio=$data["TextoFiltro"];
        $fechainicio =$data["FechaInicio"];
        $fechafin =$data["FechaFin"];
        $idtipodocumento=$data["IdTipoDocumento"];
        $idcaja = $data["IdCaja"];
        $idtipooperacioncaja = ID_TIPO_OPERACION_CAJA_PAGO_PROVEEDOR; //$data["IdTipoOperacionCaja"];
        $idmediopago=$data["IdMedioPago"];
        $idusuario = $data["IdUsuario"];

        $consulta = "Select CC.*, C.NombreCaja,
                    (SELECT GROUP_CONCAT(CONCAT(TDI.NombreAbreviado, ' ', CCC.SerieDocumento, '-', CCC.NumeroDocumento) SEPARATOR ', ') FROM movimientocaja MC
                    INNER JOIN comprobantecompra CCC ON CCC.IdComprobanteCompra = MC.IdComprobanteCompra
                    INNER JOIN tipodocumento TDI ON TDI.IdTipoDocumento = CCC.IdTipoDocumento
                    WHERE MC.IdComprobanteCaja = CC.IdComprobanteCaja AND (MC.IndicadorEstado = 'A' OR MC.IndicadorEstado = 'N')
                    AND MC.IdComprobanteCompra IS NOT NULL AND CCC.IndicadorEstado = 'A') AS 'DocumentoReferencia',
                    TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                    P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                    M.SimboloMoneda, TOC.NombreConceptoCaja, TOC.IndicadorTipoComprobante,
                    MP.NombreMedioPago,U.NombreUsuario
                    From ComprobanteCaja CC
                    left join Caja C ON C.IdCaja = CC.IdCaja
                    left join TipoOperacionCaja TOC ON TOC.IdTipoOperacionCaja = CC.IdTipoOperacionCaja
                    left join TipoDocumento TD ON TD.IdTipoDocumento = CC.IdTipoDocumento
                    left join Persona P ON P.IdPersona = CC.IdPersona
                    left join Moneda M ON M.IdMoneda = CC.IdMoneda
                    left join Usuario U ON U.IdUsuario = CC.IdUsuario
                    left join MedioPago MP on MP.IdMedioPago = CC.IdMedioPago
                    WHERE (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado = 'N' )
                    AND (CC.SerieDocumento like '%$criterio%'
                    OR CC.NumeroDocumento like '%$criterio%')
                    AND CC.IdTipoDocumento like '$idtipodocumento'
                    AND CC.FechaComprobante BETWEEN '$fechainicio' AND '$fechafin'
                    AND CC.IdCaja like '$idcaja'
                    AND CC.IdUsuario like '$idusuario'
                    AND CC.IdMedioPago like '$idmediopago'
                    AND CC.IdTipoOperacionCaja like '$idtipooperacioncaja'
                    ORDER BY TD.NombreTipoDocumento, CC.FechaComprobante DESC, TD.NombreAbreviado ASC, CC.SerieDocumento DESC, CC.NumeroDocumento DESC
                    LIMIT $numerofilainicio, $numerorfilasporpagina";//            OR P.RazonSocial like '%$criterio%' OR P.NumeroDocumentoIdentidad like '%$criterio%'

        $query = $this->db->query($consulta);
        $resultado = $query->result_array();
        return $resultado;
    }

    function ObtenerNumeroTotalPagosProveedor($data)
    {
        $criterio=$data["TextoFiltro"];
        $fechainicio =$data["FechaInicio"];
        $fechafin =$data["FechaFin"];
        $idcaja =$data["IdCaja"];
        $idtipooperacioncaja = ID_TIPO_OPERACION_CAJA_PAGO_PROVEEDOR; //$data["IdTipoOperacionCaja"];
        $idtipodocumento=$data["IdTipoDocumento"];
        $idmediopago=$data["IdMedioPago"];
        $idusuario = $data["IdUsuario"];

        $consulta = "Select CC.*, C.NombreCaja,
                    TD.NombreAbreviado, P.IdPersona, M.NombreMoneda,
                    P.RazonSocial, P.NumeroDocumentoIdentidad, P.Direccion,
                    M.SimboloMoneda, CCA.NombreConceptoCaja,
                    MP.NombreMedioPago,U.NombreUsuario
                    From ComprobanteCaja CC
                    left join Caja C ON C.IdCaja = CC.IdCaja
                    left join TipoOperacionCaja CCA ON CCA.IdTipoOperacionCaja = CC.IdTipoOperacionCaja
                    left join TipoDocumento TD ON TD.IdTipoDocumento = CC.IdTipoDocumento
                    left join Persona P ON P.IdPersona = CC.IdPersona
                    left join Moneda M ON M.IdMoneda = CC.IdMoneda
                    left join Usuario U ON U.IdUsuario = CC.IdUsuario
                    left join MedioPago MP on MP.IdMedioPago = CC.IdMedioPago
                    Where (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado='N' ) and
                    (CC.SerieDocumento like '%$criterio%' or
                    CC.NumeroDocumento like '%$criterio%') And
                    CC.IdTipoOperacionCaja like '$idtipooperacioncaja' And
                    CC.IdCaja like '$idcaja' And
                    CC.IdTipoDocumento like '$idtipodocumento' And
                    CC.IdUsuario like '$idusuario' And
                    CC.IdMedioPago like '$idmediopago' And
                    CC.FechaComprobante BETWEEN '$fechainicio' And '$fechafin'
                    ORDER BY CC.SerieDocumento,CC.NumeroDocumento"; //or P.RazonSocial like '%$criterio%' or P.NumeroDocumentoIdentidad like '%$criterio%'

        $query = $this->db->query($consulta);
        $resultado = $query->num_rows();
        return $resultado;
    }
}

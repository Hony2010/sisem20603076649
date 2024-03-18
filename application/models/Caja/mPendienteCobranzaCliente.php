<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mPendienteCobranzaCliente extends CI_Model {

  public $PendienteCobranzaCliente = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->PendienteCobranzaCliente = $this->Base->Construir("PendienteCobranzaCliente");
  }

  function InsertarPendienteCobranzaCliente($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
  
    $resultado = $this->mapper->map($data,$this->PendienteCobranzaCliente);
    $this->db->insert('PendienteCobranzaCliente', $resultado);
    $resultado["IdPendienteCobranzaCliente"] = $this->db->insert_id();
    return($resultado);
  }

  function ActualizarPendienteCobranzaCliente($data)
  {
    $id=$data["IdPendienteCobranzaCliente"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    
    $resultado = $this->mapper->map($data,$this->PendienteCobranzaCliente);
    $this->db->where('IdPendienteCobranzaCliente', $id);
    $this->db->update('PendienteCobranzaCliente', $resultado);

    return $resultado;
  }

  function BorrarPendienteCobranzaCliente($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->ActualizarPendienteCobranzaCliente($data);
    return $resultado;
  }

  function ObtenerPendienteCobranzaClientePorIdComprobanteVenta($data)
  {
    $comprobante=$data["IdComprobanteVenta"];
    $query = $this->db->query("Select pcc.*, m.*, p.RazonSocial,cv.AliasUsuarioVenta as AliasUsuarioVenta
    FROM PendienteCobranzaCliente pcc
                                LEFT JOIN comprobanteventa cv ON cv.IdComprobanteVenta = pcc.IdComprobanteVenta
                                LEFT JOIN moneda m ON m.Idmoneda = cv.IdMoneda
                                LEFT JOIN persona p ON p.IdPersona = pcc.IdCliente
                                WHERE pcc.IdComprobanteVenta = '$comprobante'
                                AND  pcc.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  // function ConsultarPendientesCobranzaClientePorIdCliente($data)
  // {
  //   $cliente=$data["IdCliente"];

  //   $query = $this->db->query("Select pcc.*, m.* FROM PendienteCobranzaCliente pcc
  //                               LEFT JOIN moneda m ON m.Idmoneda = pcc.IdMoneda
  //                               WHERE pcc.IdCliente = '$cliente'
  //                               AND pcc.SaldoPendiente > 0
  //                               AND  pcc.IndicadorEstado = 'A'
  //                               ORDER BY pcc.FechaEmision ASC");
  //   $resultado = $query->result_array();
  //   return $resultado;
  // }

  function ConsultarPendientesCobranzaClientePorIdClienteYFiltro($data)
  {
    $cliente=$data["IdCliente"];
    $criterio=$data["TextoFiltro"];
    $fechainicio=$data["FechaInicio"];
    $fechafin=$data["FechaFin"];
    $query = $this->db->query("Select pcc.*, m.* FROM PendienteCobranzaCliente pcc
                                LEFT JOIN moneda m ON m.Idmoneda = pcc.IdMoneda
                                WHERE pcc.IdCliente = '$cliente'
                                AND (pcc.SerieDocumento like '%$criterio%' or pcc.NumeroDocumento like '%$criterio%')
                                AND (pcc.FechaEmision BETWEEN '$fechainicio' AND '$fechafin')
                                AND pcc.SaldoPendiente > 0
                                AND pcc.IndicadorEstado = 'A'
                                ORDER BY pcc.FechaEmision ASC");
    $resultado = $query->result_array();
    return $resultado;
  }

  //CONSULTAS PARA SALDO INICIAL CUENTA COBRANZA - ALTERNATIVOS
  function ObtenerPendienteCobranzaClientePorIdSaldoInicialCuentaCobranza($data)
  {
    $comprobante=$data["IdSaldoInicialCuentaCobranza"];
    $query = $this->db->query("Select pcc.*, m.*, p.RazonSocial FROM PendienteCobranzaCliente pcc
                                LEFT JOIN saldoinicialcuentacobranza sicc ON sicc.IdSaldoInicialCuentaCobranza = pcc.IdSaldoInicialCuentaCobranza
                                LEFT JOIN moneda m ON m.Idmoneda = sicc.IdMoneda
                                LEFT JOIN persona p ON p.IdPersona = pcc.IdCliente
                                WHERE pcc.IdSaldoInicialCuentaCobranza = '$comprobante'
                                AND  pcc.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarPendientesCobranzaClienteInicialPorIdCliente($data)
  {
    $cliente=$data["IdCliente"];

    $query = $this->db->query("Select pcc.*, m.* FROM PendienteCobranzaCliente pcc
                                LEFT JOIN saldoinicialcuentacobranza sicc ON sicc.IdSaldoInicialCuentaCobranza = pcc.IdSaldoInicialCuentaCobranza
                                LEFT JOIN moneda m ON m.Idmoneda = sicc.IdMoneda
                                WHERE pcc.IdCliente = '$cliente'
                                AND pcc.SaldoPendiente > 0
                                AND  pcc.IndicadorEstado = 'A'
                                ORDER BY pcc.FechaEmision ASC");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarPendientesCobranzaClienteInicialPorIdClienteYFiltro($data)
  {
    $cliente=$data["IdCliente"];
    $criterio=$data["TextoFiltro"];
    $fechainicio=$data["FechaInicio"];
    $fechafin=$data["FechaFin"];
    $query = $this->db->query("Select pcc.*, m.* FROM PendienteCobranzaCliente pcc
                                LEFT JOIN saldoinicialcuentacobranza sicc ON sicc.IdSaldoInicialCuentaCobranza = pcc.IdSaldoInicialCuentaCobranza
                                LEFT JOIN moneda m ON m.Idmoneda = sicc.IdMoneda
                                WHERE pcc.IdCliente = '$cliente'
                                AND (sicc.SerieDocumento like '%$criterio%' or sicc.NumeroDocumento like '%$criterio%')
                                AND (CV.FechaEmision BETWEEN '$fechainicio' AND '$fechafin')
                                AND pcc.SaldoPendiente > 0
                                AND pcc.IndicadorEstado = 'A'
                                ORDER BY pcc.FechaEmision ASC");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarPendientesCobranzaClienteParaJSON()
  {
    $query = $this->db->query("Select pcc.* FROM PendienteCobranzaCliente pcc
    left join documentoreferencia dr
    ON dr.IdComprobanteVenta=pcc.IdComprobanteVenta
    WHERE pcc.IdComprobanteVenta IS NOT NULL
    AND (pcc.SaldoPendiente > 0 AND NOT pcc.CodigoTipoDocumento in ('NV','NC'))
    AND (pcc.IndicadorEstado = 'A') and dr.IdComprobanteNota is null
    ORDER BY pcc.FechaEmision DESC, pcc.CodigoTipoDocumento, pcc.SerieDocumento, pcc.NumeroDocumento");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarPendientesCobranzaClienteVentaParaLetra($data)
  {
    $fechainicio = $data["FechaInicio"];
    $fechafin = $data["FechaFin"];
    $cliente = $data["IdCliente"];
    $moneda = $data["IdMoneda"];

    $query = $this->db->query("Select * FROM PendienteCobranzaCliente 
                                WHERE IdComprobanteVenta IS NOT NULL
                                AND FechaEmision BETWEEN '$fechainicio' And '$fechafin'
                                AND IdCliente = '$cliente' 
                                AND IdMoneda = '$moneda' 
                                AND SaldoPendiente > 0
                                AND IndicadorEstado = 'A'
                                ORDER BY FechaEmision DESC, CodigoTipoDocumento, SerieDocumento, NumeroDocumento");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComprobantesVentaPendientesCobranzaClientePorVendedor($data) {
    $fechainicio = $data["FechaInicio"];
    $fechafin = $data["FechaFin"];
    $IdVendedor = $data["IdPersona"];    
    $IdCliente =$data["IdPersona"]; 
    $IdRol = $data["IdRol"];
    if ($IdRol == 1) {
      $condicionPersona = "AND U.IdPersona like '$IdVendedor' AND U.EstadoUsuario='1'";
    }
    else {
      $condicionPersona = "AND P.IdPersona like '$IdCliente' ";      
    }
    $query = $this->db->query(" select PCC.*,
                                CONCAT(PCC.CodigoTipoDocumento,' - ',PCC.SerieDocumento,' - ',PCC.NumeroDocumento) AS DocumentoReferencia,
                                CONCAT(PCC.CodigoTipoDocumento,' - ',PCC.SerieDocumento,' - ',PCC.NumeroDocumento) AS Documento,
                                P.RazonSocial,PCC.SaldoPendiente as MontoACobrar,P.IdPersona, CV.IdFormaPago,8 as IdMedioPago,
                                PCC.SaldoPendiente as Importe,
                                CV.AliasUsuarioVenta as AliasUsuarioVenta
                                FROM PendienteCobranzaCliente PCC
                                LEFT JOIN ComprobanteVenta CV
                                ON PCC.IdComprobanteVenta=CV.IdComprobanteVenta
                                LEFT JOIN Usuario U
                                ON U.AliasUsuarioVenta = CV.AliasUsuarioVenta
                                LEFT JOIN Persona P
                                ON P.IdPersona=CV.IdCliente
                                WHERE PCC.IdComprobanteVenta IS NOT NULL
                                AND (PCC.FechaEmision BETWEEN '$fechainicio' And '$fechafin')                              
                                AND PCC.SaldoPendiente > 0
                                AND PCC.IndicadorEstado = 'A'
                                AND CV.IndicadorEstado = 'A'
                                $condicionPersona
                                ORDER BY PCC.FechaEmision DESC, PCC.CodigoTipoDocumento, PCC.SerieDocumento, PCC.NumeroDocumento");
    $resultado = $query->result_array();

    return $resultado;
  }

}

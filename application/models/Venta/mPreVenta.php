<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mPreVenta extends CI_Model {

  public $ComprobanteVenta = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->model("Configuracion/General/mSituacionComprobanteElectronico");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->ComprobanteVenta = $this->Base->Construir("ComprobanteVenta");
  }

  function ConsultarUltimaComandaPorNumeroMesa($data)
  {
    $mesa=$data["IdMesa"];
    $estado=ESTADO_PRE_CUENTA_PENDIENTE;
    $tipo=ID_TIPO_DOCUMENTO_COMANDA;
    $consulta = "Select CV.*, TV.NombreTipoVenta,
                TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                FP.NombreFormaPago,
                Per.RazonSocial, Per.NumeroDocumentoIdentidad,
                MND.SimboloMoneda, M.*
                From ComprobanteVenta As CV
                left Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                left Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                left Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                left Join Cliente As C on C.IdPersona = CV.IdCliente
                left Join Persona As Per on Per.IdPersona = C.IdPersona
                left Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                left join Mesa M ON M.IdMesa = CV.IdMesa
                Where CV.IdMesa='$mesa' and CV.IndicadorEstado = 'A'
                and CV.IndicadorPreCuenta='$estado'
                and CV.IdTipoDocumento = '$tipo'";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComandaPorIdComprobanteVenta($data)
  {
    $venta=$data["IdComprobanteVenta"];

    $consulta = "Select CV.*, TV.NombreTipoVenta,
                TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                FP.NombreFormaPago,
                Per.RazonSocial, Per.NumeroDocumentoIdentidad, 
                MND.SimboloMoneda, M.*
                From ComprobanteVenta As CV
                left Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                left Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                left Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                left Join Cliente As C on C.IdPersona = CV.IdCliente
                left Join Persona As Per on Per.IdPersona = C.IdPersona
                left Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                left join Mesa M ON M.IdMesa = CV.IdMesa
                Where CV.IdComprobanteVenta='$venta' and CV.IndicadorEstado = 'A'";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComandasPorMesa($data)
  {
    $mesa=$data["IdMesa"];
    $tipo=ID_TIPODOCUMENTO_COMANDA;
    $fechainicio=$data["FechaInicio"];
    $fechafin=$data["FechaFin"];
    $consulta = "Select CV.*, TV.NombreTipoVenta,
                TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                FP.NombreFormaPago,
                Per.RazonSocial, Per.NumeroDocumentoIdentidad, 
                MND.SimboloMoneda, M.*
                From ComprobanteVenta As CV
                left Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                left Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                left Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                left Join Cliente As C on C.IdPersona = CV.IdCliente
                left Join Persona As Per on Per.IdPersona = C.IdPersona
                left Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                left join Mesa M ON M.IdMesa = CV.IdMesa
                Where CV.IdMesa='$mesa' and CV.IndicadorEstado = 'A'
                and ( CV.FechaEmision BETWEEN '$fechainicio' and '$fechafin')
                and CV.IdTipoDocumento = '$tipo'
                order by CV.NumeroDocumento desc";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarPreVentasPorMesa($data)
  {
    $mesa=$data["IdMesa"];
    $tipo=ID_TIPO_DOCUMENTO_ORDEN_PEDIDO;
    $fechainicio=$data["FechaInicio"];
    $fechafin=$data["FechaFin"];
    $consulta = "Select CV.*, TV.NombreTipoVenta,
                TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                FP.NombreFormaPago,
                Per.RazonSocial, Per.NumeroDocumentoIdentidad, 
                MND.SimboloMoneda, M.*
                From ComprobanteVenta As CV
                left Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                left Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                left Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                left Join Cliente As C on C.IdPersona = CV.IdCliente
                left Join Persona As Per on Per.IdPersona = C.IdPersona
                left Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                left join Mesa M ON M.IdMesa = CV.IdMesa
                Where CV.IdMesa='$mesa' and (CV.IndicadorEstado = 'A' or CV.IndicadorEstado = 'N')
                and ( CV.FechaEmision BETWEEN '$fechainicio' And '$fechafin')
                and CV.IdTipoDocumento = '$tipo'
                order by CV.NumeroDocumento desc";
    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }
}

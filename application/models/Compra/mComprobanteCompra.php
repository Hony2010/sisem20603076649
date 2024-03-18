<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mComprobanteCompra extends CI_Model {

  public $ComprobanteCompra = array();

  public function __construct()
  {
          parent::__construct();
          $this->load->database();
          $this->load->model("Base");
          $this->load->library('shared');
          $this->load->library('mapper');
          $this->load->library('sesionusuario');
          $this->ComprobanteCompra = $this->Base->Construir("ComprobanteCompra");
  }


  function ObtenerComprobanteCompraPorSerieDocumento($data)
  {
    $tipo=$data["IdTipoDocumento"];
    $serie=$data["SerieDocumento"];
    $numero=$data["NumeroDocumento"];
    $query = $this->db->query("Select CC.*
        from ComprobanteCompra As CC
        where CC.IndicadorEstado = 'A' and CC.SerieDocumento = '$serie' and CC.NumeroDocumento='$numero' and CC.IdTipoDocumento = '$tipo'");
    $resultado = $query->row();
    return $resultado;
  }


  function ObtenerDuplicadoDeComprobanteCompra($data)
  {
    $numero=$data["NumeroDocumento"];
    $query = $this->db->query("Select *
                                From ComprobanteCompra
                                Where IndicadorEstado = 'A' and NumeroDocumento = '$numero'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarComprobantesCompra($data)
  {
    $id=$data["IdComprobanteCompra"];
    $query = $this->db->query("Select * from DetalleComprobanteCompra
                                where IdComprobanteCompra = '$id'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarCompras()
  {
    $query = $this->db->query("Select CC.*, TV.NombreTipoCompra,
                                TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                                FP.NombreFormaPago, U.NombreUsuario,
                                Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion
                                From ComprobanteCompra As CC
                                Inner Join TipoCompra As TV on TV.IdTipoCompra = CC.IdTipoCompra
                                Inner Join FormaPago As FP on FP.IdFormaPago = CC.IdFormaPago
                                Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CC.IdTipoDocumento
                                Inner Join Proveedor As P on P.IdPersona = CC.IdProveedor
                                Inner Join Persona As Per on Per.IdPersona = P.IdPersona
                                Inner Join Moneda As MND on MND.IdMoneda = CC.IdMoneda
                                Inner Join Usuario As U on U.IdUsuario = CC.IdUsuario
                                Where CC.IndicadorEstado = 'A'
                                ORDER BY (CC.IdComprobanteCompra)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarComprobanteCompra($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;

    if(array_key_exists('IdDocumentoSalidaZofra', $data))
    {
      $data["IdDocumentoSalidaZofra"] = $data["IdDocumentoSalidaZofra"] == "" ? null : $data["IdDocumentoSalidaZofra"];
    }

    if(array_key_exists('IdDua', $data))
    {
      $data["IdDua"] = $data["IdDua"] == "" ? null : $data["IdDua"];
    }

    if (array_key_exists("IdCaja", $data)){
      $data["IdCaja"] = ($data["IdCaja"] == '') ? null : $data["IdCaja"];
    }

    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->ComprobanteCompra);
    $this->db->insert('ComprobanteCompra', $resultado);
    $resultado["IdComprobanteCompra"] = $this->db->insert_id();
    return($resultado);
  }


  function ActualizarComprobanteCompra($data)
  {
    $id=$data["IdComprobanteCompra"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    if(array_key_exists('IdDocumentoSalidaZofra', $data))
    {
      $data["IdDocumentoSalidaZofra"] = $data["IdDocumentoSalidaZofra"] == "" ? null : $data["IdDocumentoSalidaZofra"];
    }

    if(array_key_exists('IdDua', $data))
    {
      $data["IdDua"] = $data["IdDua"] == "" ? null : $data["IdDua"];
    }
    if (array_key_exists("IdCaja", $data)){
      $data["IdCaja"] = ($data["IdCaja"] == '') ? null : $data["IdCaja"];
    }
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->ComprobanteCompra);
    $this->db->where('IdComprobanteCompra', $id);
    $this->db->update('ComprobanteCompra', $resultado);

    return $resultado;
  }

  function BorrarComprobanteCompra($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->ActualizarComprobanteCompra($data);
    return $resultado;
  }

  function ObtenerComprobanteCompra($data)
  {
    $IdComprobanteCompra=$data["IdComprobanteCompra"];

    $query = $this->db->query("select
                              CONCAT(td.NombreAbreviado,'-',CC.SerieDocumento,'-',CC.NumeroDocumento) as Numero,
                              CONCAT(m.SimboloMoneda,' ',CAST(CC.Total as char(10))) as TotalComprobante,
                              pe.NumeroDocumentoIdentidad, pe.RazonSocial as RazonSocialProveedor,
                              pe.Direccion, CC.*, m.CodigoMoneda, tdi.CodigoDocumentoIdentidad
                              from ComprobanteCompra as CC
                              inner join tipodocumento td on td.IdTipoDocumento=CC.IdTipoDocumento
                              inner join proveedor pro on pro.IdPersona = CC.IdProveedor
                              inner join Persona pe on pe.IdPersona = pro.IdPersona
                              inner join tipodocumentoidentidad tdi on tdi.IdTipoDocumentoIdentidad=pe.IdTipoDocumentoIdentidad
                              inner join moneda m on m.IdMoneda=CC.IdMoneda
                              where CC.IdComprobanteCompra='$IdComprobanteCompra'");

    $resultado = $query->result_array();
    return $resultado;
  }


  function ConsultarComprobantesCompra($data,$numerofilainicio,$numerorfilasporpagina)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $tipodocumento =$data["TipoDocumento"];
    $tipocompra =$data["TipoCompra"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    if($vistaventa == 0)
    {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }

    $consulta = "Select
                  CC.*, TV.NombreTipoCompra,
                  TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                  FP.NombreFormaPago, U.NombreUsuario,
                  Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
                  MND.SimboloMoneda, DSZ.NumeroDocumentoSalidaZofra,sd.IdSede
                  From ComprobanteCompra As CC
                  Inner Join TipoCompra As TV on TV.IdTipoCompra = CC.IdTipoCompra
                  Inner Join FormaPago As FP on FP.IdFormaPago = CC.IdFormaPago
                  Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CC.IdTipoDocumento
                  Inner Join Proveedor As P on P.IdPersona = CC.IdProveedor
                  Inner Join Persona As Per on Per.IdPersona = P.IdPersona
                  Inner Join Moneda As MND on MND.IdMoneda = CC.IdMoneda
                  Inner Join Usuario As U on U.IdUsuario = CC.IdUsuario
                  inner join asignacionsede as sd on sd.IdAsignacionSede = cc.IdAsignacionSede
                  left Join DocumentoSalidaZofra As DSZ on DSZ.IdDocumentoSalidaZofra = CC.IdDocumentoSalidaZofra
                  Where (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado = 'N' ) and
                  (CC.SerieDocumento like '%$criterio%' or
                  CC.NumeroDocumento like '%$criterio%' or
                  Per.RazonSocial like '%$criterio%' or
                  Per.NumeroDocumentoIdentidad like '%$criterio%') And
                  CC.FechaEmision BETWEEN '$fechainicio' And '$fechafin' And
                  CC.IdTipoCompra like '$tipocompra' And
                  CC.IdTipoDocumento like '$tipodocumento'
                  ".$extensionConsulta."
                  ORDER BY CC.FechaEmision DESC, TD.NombreAbreviado ASC, CC.SerieDocumento DESC,CC.NumeroDocumento DESC
                  LIMIT $numerofilainicio,$numerorfilasporpagina";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalComprobantesCompra($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $tipodocumento =$data["TipoDocumento"];
    $tipocompra =$data["TipoCompra"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    if($vistaventa == 0)
    {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }
    $consulta = "Select COUNT(CC.IdComprobanteCompra) as total
                  From ComprobanteCompra As CC
                  Inner Join TipoCompra As TV on TV.IdTipoCompra = CC.IdTipoCompra
                  Inner Join FormaPago As FP on FP.IdFormaPago = CC.IdFormaPago
                  Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CC.IdTipoDocumento
                  Inner Join Proveedor As P on P.IdPersona = CC.IdProveedor
                  Inner Join Persona As Per on Per.IdPersona = P.IdPersona
                  Inner Join Moneda As MND on MND.IdMoneda = CC.IdMoneda
                  Inner Join Usuario As U on U.IdUsuario = CC.IdUsuario
                  Where (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado='N' ) and
                  (CC.SerieDocumento like '%$criterio%' or
                  CC.NumeroDocumento like '%$criterio%' or
                  Per.RazonSocial like '%$criterio%' or
                  Per.NumeroDocumentoIdentidad like '%$criterio%') And
                  CC.FechaEmision BETWEEN '$fechainicio' And '$fechafin' AND
                  CC.IdTipoCompra like '$tipocompra' And
                  CC.IdTipoDocumento like '$tipodocumento'
                  ".$extensionConsulta."
                  ORDER BY CC.IdComprobanteCompra DESC, CC.SerieDocumento,CC.NumeroDocumento";
    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado[0]['total'];
  }

  function ConsultarComprobantesCompraPorProveedor($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $proveedor =$data["IdPersona"];
    $documento = $data["IdTipoDocumento"];
    $tipocompra = $data["IdTipoCompra"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    if($vistaventa == 0)
    {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }

    $consulta = "Select
                  CC.*, TV.NombreTipoCompra,
                  TD.NombreAbreviado, TD.CodigoTipoDocumento, Per.IdPersona, MND.NombreMoneda,
                  FP.NombreFormaPago, U.NombreUsuario,
                  Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
                  MND.SimboloMoneda,
                  ss.IdSede
                  From ComprobanteCompra As CC
                  Inner Join TipoCompra As TV on TV.IdTipoCompra = CC.IdTipoCompra
                  Inner Join FormaPago As FP on FP.IdFormaPago = CC.IdFormaPago
                  Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CC.IdTipoDocumento
                  Inner Join Proveedor As P on P.IdPersona = CC.IdProveedor
                  Inner Join Persona As Per on Per.IdPersona = P.IdPersona
                  Inner Join Moneda As MND on MND.IdMoneda = CC.IdMoneda
                  Inner Join Usuario As U on U.IdUsuario = CC.IdUsuario
                  Inner Join Moneda As M on M.IdMoneda = CC.IdMoneda
                  Inner join AsignacionSede As ss on ss.IdAsignacionSede = CC.IdAsignacionSede
                  Where CC.IdProveedor = '$proveedor' AND CC.IdTipoDocumento = '$documento'  AND (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado = 'N' ) and
                  (CC.SerieDocumento like '%$criterio%' or
                  CC.NumeroDocumento like '%$criterio%') And
                  CC.FechaEmision BETWEEN '$fechainicio' And '$fechafin' AND CC.IdTipoCompra NOT IN('$tipocompra')
                  ".$extensionConsulta."
                  ORDER BY CC.IdComprobanteCompra DESC, CC.SerieDocumento,CC.NumeroDocumento";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComprobantesCompraPorProveedorParaCostoAgregado($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $proveedor =$data["IdPersona"];
    $documento = $data["IdTipoDocumento"];
    $tipocompra = ID_TIPOCOMPRA_MERCADERIA;

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    if($vistaventa == 0)
    {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }

    $consulta = "Select
                  CC.*, TV.NombreTipoCompra,
                  TD.NombreAbreviado, TD.CodigoTipoDocumento, Per.IdPersona, MND.NombreMoneda,
                  FP.NombreFormaPago, U.NombreUsuario,
                  Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
                  MND.SimboloMoneda,ss.IdSede
                  From ComprobanteCompra As CC
                  Inner Join TipoCompra As TV on TV.IdTipoCompra = CC.IdTipoCompra
                  Inner Join FormaPago As FP on FP.IdFormaPago = CC.IdFormaPago
                  Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CC.IdTipoDocumento
                  Inner Join Proveedor As P on P.IdPersona = CC.IdProveedor
                  Inner Join Persona As Per on Per.IdPersona = P.IdPersona
                  Inner Join Moneda As MND on MND.IdMoneda = CC.IdMoneda
                  Inner Join Usuario As U on U.IdUsuario = CC.IdUsuario
                  Inner Join Moneda As M on M.IdMoneda = CC.IdMoneda
                  Inner Join AsignacionSede As ss on ss.IdAsignacionSede = CC.IdAsignacionSede
                  Where CC.IdProveedor = '$proveedor' AND CC.IdTipoDocumento = '$documento'  AND (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado = 'N' ) and
                  (CC.SerieDocumento like '%$criterio%' or
                  CC.NumeroDocumento like '%$criterio%') And
                  CC.FechaEmision BETWEEN '$fechainicio' And '$fechafin' AND CC.IdTipoCompra = '$tipocompra'
                  ".$extensionConsulta."
                  ORDER BY CC.IdComprobanteCompra DESC, CC.SerieDocumento,CC.NumeroDocumento";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComprobantesCompraPorProveedorParaNotaCredito($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $proveedor =$data["IdPersona"];
    $documento = $data["IdTipoDocumento"];
    // $tipocompra = $data["IdTipoCompra"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    if($vistaventa == 0)
    {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }

    $consulta = "Select
                  CC.*, TV.NombreTipoCompra,
                  TD.NombreAbreviado, TD.CodigoTipoDocumento, Per.IdPersona, MND.NombreMoneda,
                  FP.NombreFormaPago, U.NombreUsuario,
                  Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
                  MND.SimboloMoneda,
                  ss.IdSede
                  From ComprobanteCompra As CC
                  Inner Join TipoCompra As TV on TV.IdTipoCompra = CC.IdTipoCompra
                  Inner Join FormaPago As FP on FP.IdFormaPago = CC.IdFormaPago
                  Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CC.IdTipoDocumento
                  Inner Join Proveedor As P on P.IdPersona = CC.IdProveedor
                  Inner Join Persona As Per on Per.IdPersona = P.IdPersona
                  Inner Join Moneda As MND on MND.IdMoneda = CC.IdMoneda
                  Inner Join Usuario As U on U.IdUsuario = CC.IdUsuario
                  Inner Join Moneda As M on M.IdMoneda = CC.IdMoneda
                  Inner join AsignacionSede As ss on ss.IdAsignacionSede = CC.IdAsignacionSede
                  Where CC.IdProveedor = '$proveedor' AND CC.IdTipoDocumento = '$documento'  AND (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado = 'N' ) and
                  (CC.SerieDocumento like '%$criterio%' or
                  CC.NumeroDocumento like '%$criterio%') And
                  (CC.FechaEmision BETWEEN '$fechainicio' And '$fechafin') AND CC.SaldoNotaCredito > 0
                  ".$extensionConsulta."
                  ORDER BY CC.IdComprobanteCompra DESC, CC.SerieDocumento,CC.NumeroDocumento";
    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComprobantesCompraPendienteNotaPorCliente($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $proveedor =$data["IdPersona"];
    $documento = $data["IdTipoDocumento"];
    $moneda = $data["IdMoneda"];
    $nota = $data["TipoNota"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    if($vistaventa == 0)
    {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }

    $consulta = "Select
                  CC.*, TV.NombreTipoCompra,
                  TD.NombreAbreviado, TD.CodigoTipoDocumento, Per.IdPersona, MND.NombreMoneda,
                  FP.NombreFormaPago, U.NombreUsuario,
                  Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
                  MND.SimboloMoneda, MND.CodigoMoneda
                  From ComprobanteCompra As CC
                  Inner Join TipoCompra As TV on TV.IdTipoCompra = CC.IdTipoCompra
                  Inner Join FormaPago As FP on FP.IdFormaPago = CC.IdFormaPago
                  Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CC.IdTipoDocumento
                  Inner Join Proveedor As P on P.IdPersona = CC.IdProveedor
                  Inner Join Persona As Per on Per.IdPersona = P.IdPersona
                  Inner Join Moneda As MND on MND.IdMoneda = CC.IdMoneda
                  Inner Join Usuario As U on U.IdUsuario = CC.IdUsuario
                  Inner Join Moneda As M on M.IdMoneda = CC.IdMoneda
                  Where CC.IdProveedor = '$proveedor' AND CC.IdTipoDocumento = '$documento' AND CC.IdMoneda = '$moneda' AND (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado = 'N' ) and
                  (CC.SerieDocumento like '%$criterio%' or
                  CC.NumeroDocumento like '%$criterio%' or
                  Per.RazonSocial like '%$criterio%' or
                  Per.NumeroDocumentoIdentidad like '%$criterio%') AND
                  CC.FechaEmision BETWEEN '$fechainicio' And '$fechafin' AND CC.EstadoPendienteNota = '$nota'
                  ".$extensionConsulta."
                  ORDER BY CC.IdComprobanteCompra DESC, CC.SerieDocumento,CC.NumeroDocumento";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalComprobantesCompraPorCliente($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $cliente =$data["IdPersona"];
    $query = $this->db->query("Select
                                CC.*, TV.NombreTipoCompra,
                                TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                                FP.NombreFormaPago, U.NombreUsuario,
                                Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
                                MND.SimboloMoneda
                                From ComprobanteCompra As CC
                                Inner Join TipoCompra As TV on TV.IdTipoCompra = CC.IdTipoCompra
                                Inner Join FormaPago As FP on FP.IdFormaPago = CC.IdFormaPago
                                Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CC.IdTipoDocumento
                                Inner Join Proveedor As P on P.IdPersona = CC.IdProveedor
                                Inner Join Persona As Per on Per.IdPersona = P.IdPersona
                                Inner Join Moneda As MND on MND.IdMoneda = CC.IdMoneda
                                Inner Join Usuario As U on U.IdUsuario = CC.IdUsuario
                                Where C.IdPersona = '$cliente' AND (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado='N' ) and
                                (CC.SerieDocumento like '%$criterio%' or
                                CC.NumeroDocumento like '%$criterio%' or
                                Per.RazonSocial like '%$criterio%' or
                                Per.NumeroDocumentoIdentidad like '%$criterio%') And
                                CC.FechaEmision BETWEEN '$fechainicio' And '$fechafin'
                                ORDER BY CC.IdComprobanteCompra DESC, CC.SerieDocumento,CC.NumeroDocumento");
    $resultado = $query->num_rows();
    return $resultado;
  }

  function BuscarDocumentosIngreso($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $documento = ID_TIPODOCUMENTO_DOCUMENTOINGRESO.", ".ID_TIPODOCUMENTO_DOCUMENTOCONTROL;//$data["IdTipoDocumento"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    if($vistaventa == 0)
    {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }

    $consulta = "Select
                  CC.*, TV.NombreTipoCompra,
                  TD.NombreAbreviado, TD.CodigoTipoDocumento, Per.IdPersona, MND.NombreMoneda,
                  FP.NombreFormaPago, U.NombreUsuario,
                  Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
                  MND.SimboloMoneda
                  From ComprobanteCompra As CC
                  Inner Join TipoCompra As TV on TV.IdTipoCompra = CC.IdTipoCompra
                  Inner Join FormaPago As FP on FP.IdFormaPago = CC.IdFormaPago
                  Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CC.IdTipoDocumento
                  Inner Join Proveedor As P on P.IdPersona = CC.IdProveedor
                  Inner Join Persona As Per on Per.IdPersona = P.IdPersona
                  Inner Join Moneda As MND on MND.IdMoneda = CC.IdMoneda
                  Inner Join Usuario As U on U.IdUsuario = CC.IdUsuario
                  Inner Join Moneda As M on M.IdMoneda = CC.IdMoneda
                  Where CC.IdTipoDocumento IN($documento)  AND (CC.IndicadorEstado = 'A' OR CC.IndicadorEstado = 'N' ) and
                  (CC.SerieDocumento like '%$criterio%' or
                  CC.NumeroDocumento like '%$criterio%') And
                  CC.FechaEmision BETWEEN '$fechainicio' And '$fechafin'
                  ".$extensionConsulta."
                  ORDER BY CC.IdComprobanteCompra DESC, CC.SerieDocumento,CC.NumeroDocumento";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDuplicadoParaInsertar($data)
  {
    $serie=$data["SerieDocumento"];
    $proveedor=$data["IdProveedor"];
    $numero=$data["NumeroDocumento"];
    $tipodocumento=$data["IdTipoDocumento"];
    $query = $this->db->query("Select cc.*
                              from comprobantecompra as cc
                              where (cc.NumeroDocumento = '$numero' and cc.SerieDocumento = '$serie') and cc.IdProveedor = '$proveedor' and cc.IdTipoDocumento = '$tipodocumento' and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDuplicadoParaActualizar($data)
  {
    $serie=$data["SerieDocumento"];
    $proveedor=$data["IdProveedor"];
    $numero=$data["NumeroDocumento"];
    $tipodocumento=$data["IdTipoDocumento"];
    $id=$data["IdComprobanteCompra"];
    $query = $this->db->query("Select cc.*
                              from comprobantecompra as cc
                              Where cc.IdComprobanteCompra != '$id' and (cc.NumeroDocumento = '$numero' and cc.SerieDocumento = '$serie') and cc.IdProveedor = '$proveedor' and cc.IdTipoDocumento = '$tipodocumento' and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerReferenciasParaDocumentoIngreso($data)
  {
    $id=$data["IdComprobanteCompra"];
    $query = $this->db->query("Select cc.*, TD.NombreAbreviado
        from comprobantecompra as cc
        inner join tipodocumento as TD on TD.IdTipoDocumento = CC.IdTipoDocumento
        Where cc.IdDocumentoIngresoZofra = '$id' and CC.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDocumentosReferenciaCompraPorComprobante($data)
  {
    $id=$data["IdComprobanteCompra"];
    $query = $this->db->query("Select CC.*, TD.NombreAbreviado
      from documentoreferenciacompra as DRC
      inner join comprobantecompra as CC on DRC.IdComprobanteNota = CC.IdComprobanteCompra
      inner join tipodocumento as TD on TD.IdTipoDocumento = CC.IdTipoDocumento
      Where DRC.IdComprobanteCompra = '$id' and (CC.IndicadorEstado = 'A' and DRC.IndicadorEstado = 'A')");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerCompraMercaderiaEnDocumentoReferencia($data)
  {
    $id=$data["IdComprobanteCompra"];
    $query = $this->db->query("select CC.SerieDocumento, CC.NumeroDocumento,TD.NombreAbreviado, CC.FechaEmision, DRCA.*
                              from documentoreferenciacostoagregado as DRCA
                              inner join comprobantecompra as CC on DRCA.IdComprobanteCostoAgregado = CC.IdComprobanteCompra
                              inner join tipodocumento as TD on TD.IdTipoDocumento = CC.IdTipoDocumento
                              where DRCA.IdComprobanteCompra = '$id' and DRCA.IndicadorEstado = 'A'
                              group by CC.NumeroDocumento");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarListarDocumentoReferenciaParaJSON($data)
  {
    $FechaInicio = $data['FechaInicio'];
    $FechaFinal = $data['FechaFinal'];

    $query = $this->db->query("select CC.IdComprobanteCompra, CC.IdDocumentoIngresoZofra, CONCAT(CC.DocumentoIngreso,'  ',DATE_FORMAT(CC.FechaEmision,'%d/%m/%Y'))as DocumentoIngreso,
                              if(CC.DocumentoIngreso ='',if(CC.IdTipoDocumento='79',concat('DOC. INGRESO ',CC.SerieDocumento,' - ',CC.NumeroDocumento),concat('DOC. CONTROL ',CC.SerieDocumento,' - ',CC.NumeroDocumento) ),
                                (select If(CCOM.IdTipoDocumento='79',concat('DOC. INGRESO ',CC.DocumentoIngreso), concat('DOC. CONTROL ',CC.DocumentoIngreso))
                                from ComprobanteCompra as CCOM
                                where CCOM.IdComprobanteCompra=CC.IdDocumentoIngresoZofra)) as CodigoTipoDocumento
                              from comprobantecompra CC
                              where  CC.IndicadorEstado='A' and (CC.IdTipoDocumento = '79' or CC.IdTipoDocumento = '80' or CC.IdDocumentoIngresoZofra != '0')
                              and ((CC.FechaEmisionDocumentoIngreso between '$FechaInicio' and '$FechaFinal') or (CC.FechaEmision between '$FechaInicio' and '$FechaFinal'))
                              and CC.IdDocumentoIngresoZofra like '%'");
            $resultado = $query->result_array();
    return $resultado;
  }

 }

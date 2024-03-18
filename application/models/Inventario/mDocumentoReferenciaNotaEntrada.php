<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDocumentoReferenciaNotaEntrada extends CI_Model {

  public $DocumentoReferenciaNotaEntrada = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->DocumentoReferenciaNotaEntrada = $this->Base->Construir("DocumentoReferenciaNotaEntrada");
  }

  function ListarDocumentosReferencia($data)
  {
    $id=$data["IdDocumentoReferenciaNotaEntrada"];
    $query = $this->db->query("Select * from DocumentoReferenciaNotaEntrada
                                where IdDocumentoReferenciaNotaEntrada = '$id'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarDocumentosReferenciaCompra($data)
  {
    $id=$data["IdNotaEntrada"];
    $query = $this->db->query("Select
      CC.*, TC.NombreTipoCompra,
      TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda, TD.CodigoTipoDocumento,
      FP.NombreFormaPago, U.AliasUsuarioVenta,
      Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
      MND.SimboloMoneda
      from documentoreferencianotaentrada DRNE
      inner join comprobantecompra CC on CC.IdComprobanteCompra = DRNE.IdComprobanteCompra
      Inner Join TipoCompra As TC on TC.IdTipoCompra = CC.IdTipoCompra
      Inner Join FormaPago As FP on FP.IdFormaPago = CC.IdFormaPago
      Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CC.IdTipoDocumento
      Inner Join Proveedor As C on C.IdPersona = CC.IdProveedor
      Inner Join Persona As Per on Per.IdPersona = C.IdPersona
      Inner Join Moneda As MND on MND.IdMoneda = CC.IdMoneda
      Inner Join Usuario As U on U.IdUsuario = CC.IdUsuario
      where DRNE.idnotaentrada= '$id' AND DRNE.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarDocumentosReferenciaVenta($data)
  {
    $id=$data["IdNotaEntrada"];
    $query = $this->db->query("Select
      CV.*, TV.NombreTipoVenta,
      TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda, TD.CodigoTipoDocumento,
      FP.NombreFormaPago, U.AliasUsuarioVenta,
      Per.RazonSocial,Per.NumeroDocumentoIdentidad,Per.Direccion,
      MND.SimboloMoneda
      from documentoreferencianotaentrada DRNE
      inner join comprobanteventa CV on CV.IdComprobanteVenta = DRNE.IdComprobanteVenta
      Inner Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
      Inner Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
      Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
      Inner Join Cliente As C on C.IdPersona = CV.IdCliente
      Inner Join Persona As Per on Per.IdPersona = C.IdPersona
      Inner Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
      Inner Join Usuario As U on U.IdUsuario = CV.IdUsuario
      where DRNE.idnotaentrada= '$id' AND DRNE.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarDocumentosReferenciaPorNotaEntrada($data)
  {
    $id=$data["IdNotaEntrada"];
    $query = $this->db->query("Select
      DRNE.*
      from documentoreferencianotaentrada DRNE
      inner join notaentrada NE on NE.IdNotaEntrada = DRNE.IdNotaEntrada
      where DRNE.idnotaentrada= '$id' AND DRNE.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarDocumentoReferenciaNotaEntrada($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->DocumentoReferenciaNotaEntrada);
    $this->db->insert('DocumentoReferenciaNotaEntrada', $resultado);
    $resultado["IdDocumentoReferenciaNotaEntrada"] = $this->db->insert_id();
    return($resultado);
  }


  function ActualizarDocumentoReferenciaNotaEntrada($data)
  {
    $id=$data["IdDocumentoReferenciaNotaEntrada"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->DocumentoReferenciaNotaEntrada);
    $this->db->where('IdDocumentoReferenciaNotaEntrada', $id);
    $this->db->update('DocumentoReferenciaNotaEntrada', $resultado);

    return $resultado;
  }

  function BorrarDocumentoReferenciaNotaEntrada($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $this->ActualizarDocumentoReferenciaNotaEntrada($data);
  }

  function ObtenerDocumentosReferenciaByComprobanteVenta($data)
  {
    $id=$data["IdComprobanteVenta"];
    $query = $this->db->query("Select DRNE.IdDocumentoReferenciaNotaEntrada, NE.* from DocumentoReferenciaNotaEntrada DRNE
            inner join NotaEntrada NE on NE.IdNotaEntrada = DRNE.IdNotaEntrada
            where DRNE.IdComprobanteVenta= '$id' AND DRNE.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDocumentosReferenciaByComprobanteCompra($data)
  {
    $id=$data["IdComprobanteCompra"];
    $query = $this->db->query("Select DRNE.IdDocumentoReferenciaNotaEntrada, NE.* from DocumentoReferenciaNotaEntrada DRNE
            inner join NotaEntrada NE on NE.IdNotaEntrada = DRNE.IdNotaEntrada
            where DRNE.IdComprobanteCompra= '$id' AND DRNE.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }
}

<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mDocumentoReferenciaCostoAgregado extends CI_Model
{

  public $DocumentoReferenciaCostoAgregado = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->DocumentoReferenciaCostoAgregado = $this->Base->Construir("DocumentoReferenciaCostoAgregado");
  }

  function ListarDocumentosReferenciaCostoAgregado($data)
  {
    $id = $data["IdDocumentoReferenciaCostoAgregado"];
    $query = $this->db->query("Select * from DocumentoReferenciaCostoAgregado
                                     where IdDocumentoReferenciaCostoAgregado = '$id' AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarDocumentosReferenciaCostoAgregado($data)
  {
    $id = $data["IdComprobanteCostoAgregado"];
    $query = $this->db->query("select CC.IdTipoDocumento, DCC.*, DRCA.*,td.CodigoTipoDocumento, td.NombreAbreviado,
                                    s.IdSede
                                    FROM documentoreferenciacostoagregado as DRCA
                                    Inner Join detallecomprobantecompra as DCC on DCC.IdDetalleComprobanteCompra = DRCA.IdDetalleComprobanteCompra
                                    Inner Join comprobantecompra as CC on CC.IdComprobanteCompra = DRCA.IdComprobanteCostoAgregado
                                    Inner Join proveedor as PRO on CC.IdProveedor = PRO.IdPersona
                                    Inner Join persona as P on P.IdPersona  = PRO.IdPersona
                                    inner join tipodocumento as td on  td.IdTipoDocumento = CC.IdTipoDocumento
                                    inner join asignacionsede as s on s.IdAsignacionSede = CC.IdAsignacionSede
                                    WHERE (DRCA.IndicadorEstado = 'A' OR DRCA.IndicadorEstado = 'N') and DRCA.IdComprobanteCostoAgregado = '$id'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerMovimientoAlmacenPorCostoAgregadoComprobanteCompra($data)
  {
    $id = $data["IdProducto"];
    $sede = $data["IdAsignacionSede"];
    $comprobante = $data["IdComprobanteCompra"];
    $query = $this->db->query("select DCC.IdProducto, DRCA.MontoProrrateadoPorUnidad, MA.*
                                    from documentoreferenciacostoagregado as DRCA
                                    inner join detallecomprobantecompra as DCC on DRCA.IdDetalleComprobanteCompra = DCC.IdDetalleComprobanteCompra
                                    inner join documentoreferencianotaentrada as DRNE on DRCA.IdComprobanteCompra = DRNE.IdComprobanteCompra
                                    inner join movimientoalmacen as MA on DRNE.IdNotaEntrada = MA.IdNotaEntrada
                                    where DCC.IdProducto = '$id' and DRCA.IdComprobanteCompra = '$comprobante' and MA.IdAsignacionSede = '$sede' and MA.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarDocumentoReferenciaCostoAgregado($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data, $this->DocumentoReferenciaCostoAgregado);
    
    $this->db->insert('DocumentoReferenciaCostoAgregado', $resultado);
    $resultado["IdDocumentoReferenciaCostoAgregado"] = $this->db->insert_id();
    return ($resultado);
  }


  function ActualizarDocumentoReferenciaCostoAgregado($data)
  {
    $id = $data["IdDocumentoReferenciaCostoAgregado"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data, $this->DocumentoReferenciaCostoAgregado);
    $this->db->where('IdDocumentoReferenciaCostoAgregado', $id);
    $this->db->update('DocumentoReferenciaCostoAgregado', $resultado);

    return $resultado;
  }

  function BorrarDocumentoReferenciaCostoAgregado($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $this->ActualizarDocumentoReferenciaCostoAgregado($data);
  }

  function BorrarDocumentoReferenciaPorIdComprobanteCostoAgregado($IdComprobanteCostoAgregado)
  {
    $this->db->where("IdComprobanteCostoAgregado", $IdComprobanteCostoAgregado);
    $this->db->delete("DocumentoReferenciaCostoAgregado");
  }

  function ActualizarDocumentosReferenciaPorIdComprobanteCostoAgregado($data)
  {
    $idComprobanteCostoAgregado = $data["IdComprobanteCostoAgregado"];
    $estado = $data["IndicadorEstado"];
    $this->db->set('IndicadorEstado', $estado);
    $this->db->where("IdComprobanteCostoAgregado", $idComprobanteCostoAgregado);
    $this->db->update("DocumentoReferenciaCostoAgregado"); 
    return $data;
  }
}

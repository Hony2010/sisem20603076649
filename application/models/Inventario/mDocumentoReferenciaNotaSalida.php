<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDocumentoReferenciaNotaSalida extends CI_Model {

  public $DocumentoReferenciaNotaSalida = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->DocumentoReferenciaNotaSalida = $this->Base->Construir("DocumentoReferenciaNotaSalida");
  }

  function ListarDocumentosReferencia($data)
  {
    $id=$data["IdDocumentoReferenciaNotaSalida"];
    $query = $this->db->query("Select * from DocumentoReferenciaNotaSalida
                                where IdDocumentoReferenciaNotaSalida = '$id'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarDocumentoReferenciaNotaSalida($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->DocumentoReferenciaNotaSalida);
    $this->db->insert('DocumentoReferenciaNotaSalida', $resultado);
    $resultado["IdDocumentoReferenciaNotaSalida"] = $this->db->insert_id();
    return($resultado);
  }


  function ActualizarDocumentoReferenciaNotaSalida($data)
  {
    $id=$data["IdDocumentoReferenciaNotaSalida"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $resultado = $this->mapper->map($data,$this->DocumentoReferenciaNotaSalida);
    $this->db->where('IdDocumentoReferenciaNotaSalida', $id);
    $this->db->update('DocumentoReferenciaNotaSalida', $resultado);

    return $resultado;
  }

  function BorrarDocumentoReferenciaNotaSalida($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $this->ActualizarDocumentoReferenciaNotaSalida($data);
  }

  function ObtenerDocumentosReferenciaByComprobanteVenta($data)
  {
    $id=$data["IdComprobanteVenta"];
    $query = $this->db->query("Select DRNS.IdDocumentoReferenciaNotaSalida, NS.* from DocumentoReferenciaNotaSalida DRNS
            inner join NotaSalida NS on NS.IdNotaSalida = DRNS.IdNotaSalida
            where DRNS.IdComprobanteVenta= '$id' AND DRNS.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDocumentosReferenciaByComprobanteCompra($data)
  {
    $id=$data["IdComprobanteCompra"];
    $query = $this->db->query("Select DRNS.IdDocumentoReferenciaNotaSalida, NS.* from DocumentoReferenciaNotaSalida DRNS
            inner join NotaSalida NS on NS.IdNotaSalida = DRNS.IdNotaSalida
            where DRNS.IdComprobanteCompra= '$id' AND DRNS.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDocumentosReferenciaByComprobanteVentaYNotaSalida($data)
  {
    $id=$data["IdComprobanteVenta"];
    $idnota=$data["IdNotaSalida"];
    $query = $this->db->query("Select DRNS.IdDocumentoReferenciaNotaSalida, NS.* from DocumentoReferenciaNotaSalida DRNS
            inner join NotaSalida NS on NS.IdNotaSalida = DRNS.IdNotaSalida
            where DRNS.IdComprobanteVenta= '$id' AND DRNS.IdNotaSalida= '$idnota' AND DRNS.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

}

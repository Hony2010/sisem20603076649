<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDetalleComunicacionBaja extends CI_Model {

  public $mDetalleComunicacionBaja = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->DetalleComunicacionBaja = $this->Base->Construir("DetalleComunicacionBaja");
  }

  function ConsultarDetallesComunicacionBaja($data) {
    $id=$data["IdComunicacionBaja"];

    $query = $this->db->query("select dcb.*, cv.SerieDocumento, cv.NumeroDocumento,
              CONCAT(td.NombreAbreviado,'-',cv.SerieDocumento,'-',cv.NumeroDocumento) as Numero, p.RazonSocial
              from detallecomunicacionbaja dcb 
              inner join comprobanteventa cv on cv.IdComprobanteVenta = dcb.IdComprobanteVenta
              inner join tipodocumento td on td.IdTipoDocumento=cv.IdTipoDocumento
              inner join cliente c on c.IdPersona = cv.IdCliente
              inner join Persona p on p.IdPersona = c.IdPersona
              where (dcb.IdComunicacionBaja = '$id')");

    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarDetalleComunicacionBaja($data)
  {
    $resultado = $this->mapper->map($data,$this->DetalleComunicacionBaja);
    $this->db->insert('DetalleComunicacionBaja', $resultado);
    $data["IdDetalleComunicacionBaja"] = $this->db->insert_id();
    return($data);
  }

  function BorrarDetalleComunicacionBajaPorIdComunicacionBaja($IdComunicacionBaja)
  {
    $this->db->where("IdComunicacionBaja",$IdComunicacionBaja);
    $this->db->delete("DetalleComunicacionBaja");
    return $this->db->affected_rows();
  }


}

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mProformaComprobanteVenta extends CI_Model {

  public $ProformaComprobanteVenta = array();

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->ProformaComprobanteVenta = $this->Base->Construir("ProformaComprobanteVenta");
  }

  function ListarProformasComprobanteVenta($data) {

    $idcomprobanteventa=$data["IdComprobanteVenta"];

    $sql = "Select                               
                CONCAT(TD.NombreAbreviado, ' ', CV.SerieDocumento, '-', CV.NumeroDocumento) AS Documento,
                PF.*
                From proformacomprobanteventa as PF
                left Join comprobanteventa as CV on CV.IdComprobanteVenta = PF.IdProforma
                Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                Where PF.IdComprobanteVenta='$idcomprobanteventa' and PF.IndicadorEstado = 'A'";

    $query = $this->db->query($sql);
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarProformaComprobanteVenta($data) {
    $resultado = $this->mapper->map($data,$this->ProformaComprobanteVenta);
    $this->db->insert('ProformaComprobanteVenta', $resultado);
    $resultado["IdProformaComprobanteVenta"] = $this->db->insert_id();
    return $resultado;
  }


  function ActualizarProformaComprobanteVenta($data) {
    $id=$data["IdProformaComprobanteVenta"];    
    $resultado = $this->mapper->map($data,$this->ProformaComprobanteVenta);
    $this->db->where('IdProformaComprobanteVenta', $id);
    $this->db->update('ProformaComprobanteVenta', $resultado);

    return $resultado;
  }

  function ObtenerProformaComprobanteVenta($data) {
    $idcomprobanteventa=$data["IdComprobanteVenta"];
    $idproforma=$data["IdProforma"];

    $sql = "Select *
            From proformacomprobanteventa as PF                
            Where PF.IdComprobanteVenta=$idcomprobanteventa and PF.IdProforma=$idproforma";

    $query = $this->db->query($sql);
    $resultado = $query->result_array();
    return $resultado;    
  }

}

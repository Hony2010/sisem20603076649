<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDetalleResumenDiario extends CI_Model {

  public $mDetalleResumenDiario = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->DetalleResumenDiario = $this->Base->Construir("DetalleResumenDiario");
  }

  function ConsultarDetallesResumenDiario($data) {
    $id=$data["IdResumenDiario"];

    $query = $this->db->query("select drd.*, cv.SerieDocumento, cv.NumeroDocumento, 
                CONCAT(td.NombreAbreviado,' ',cv.SerieDocumento,'-',cv.NumeroDocumento) as Numero,
                p.RazonSocial, m.CodigoMoneda, cv.Total, cv.IdCliente, p.NumeroDocumentoIdentidad as NumeroDocumentoCliente,
                tdi.CodigoDocumentoIdentidad, td.CodigoTipoDocumento, cv.ValorVentaGravado, cv.ValorVentaNoGravado,
                cv.ValorVentaInafecto, cv.IGV, cv.ISC, cv.OtroCargo, cv.OtroTributo,
                CONCAT(cv.SerieDocumento,'-',cv.NumeroDocumento) as SerieNumeroDocumento, erd.NombreEstadoResumenDiario as NombreEstado
                from detalleresumendiario drd
                inner join comprobanteventa cv on cv.IdComprobanteVenta = drd.IdComprobanteVenta
                inner join moneda m on m.IdMoneda = cv.IdMoneda
                inner join tipodocumento td on td.IdTipoDocumento=cv.IdTipoDocumento
                inner join cliente c on c.IdPersona = cv.IdCliente
                inner join Persona p on p.IdPersona = c.IdPersona
                inner join tipodocumentoidentidad tdi on tdi.IdTipoDocumentoIdentidad =p.IdTipoDocumentoIdentidad
                left join estadoresumendiario erd on erd.CodigoEstadoResumenDiario = drd.CodigoEstado
                where (drd.IdResumenDiario = '$id')
                ORDER BY td.NombreTipoDocumento, cv.SerieDocumento, cv.NumeroDocumento");

    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarDetalleResumenDiario($data)
  {
    $resultado = $this->mapper->map($data,$this->DetalleResumenDiario);
    $this->db->insert('DetalleResumenDiario', $resultado);
    $data["IdDetalleResumenDiario"] = $this->db->insert_id();
    return($data);
  }

  function BorrarDetalleResumenDiarioPorIdResumenDiario($IdResumenDiario)
  {
    $this->db->where("IdResumenDiario",$IdResumenDiario);
    $this->db->delete("DetalleResumenDiario");
    return $this->db->affected_rows();
  }

}

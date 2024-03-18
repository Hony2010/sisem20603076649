<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTransportista extends CI_Model {

  public $Transportista = array();

  public function __construct()
  {
          parent::__construct();
          $this->load->database();
          $this->load->model("Base");
          $this->load->library('shared');
          $this->load->library('mapper');
          $this->Transportista = $this->Base->Construir("Transportista");
        //$this->Persona = $this->Base->Construir("Persona");
  }

  function ObtenerNumeroFila()
  {
    $query = $this->db->query("Select count(IdTransportista) As NumeroFila From Transportista");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarTransportistas($inicio, $ValorParametroSistema)
  {
    $query = $this->db->query("Select T.*, TDI.NombreAbreviado, TDI.CodigoDocumentoIdentidad, TP.NombreTipoPersona, P.* 
                                From Transportista As T
                                left Join Persona As P On T.IdTransportista = P.IdPersona
                                left Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                left Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                Where P.IndicadorEstado='A'
                                ORDER  BY (P.IdPersona) ASC
                                LIMIT $inicio,$ValorParametroSistema");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarTransportista($data)
  {
    $resultado = $this->mapper->map($data,$this->Transportista);
  
    $this->db->insert('Transportista', $resultado);
    $resultado["IdTransportista"] = $this->db->insert_id();
    return($resultado);
  }

  function ActualizarTransportista($data)
  {
    $id=$data["IdTransportista"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $resultado = $this->mapper->map($data,$this->Transportista);
    $this->db->where('IdTransportista', $id);
    $this->db->update('Transportista', $resultado);
  }
  
  function BorrarTransportista($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $this->ActualizarTransportista($data);
  }

  function ObtenerNumeroTotalTransportistas($data)
  {
    $criterio=$data["textofiltro"];
    $query = $this->db->query("Select count(T.IdTransportista) as cantidad
                                From Transportista As T
                                Inner Join Persona As P On T.IdTransportista = P.IdPersona
                                Where P.IndicadorEstado='A' AND (P.RazonSocial like '%$criterio%'  or P.NumeroDocumentoIdentidad like '%$criterio%')
                                ORDER  BY (P.IdPersona)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarTransportistas($inicio,$ValorParametroSistema,$data)
  {
    $criterio=$data["textofiltro"];
    $query = $this->db->query("Select T.*, P.IdPersona, P.NombreCompleto, P.ApellidoCompleto, TDI.NombreAbreviado,  TDI.CodigoDocumentoIdentidad,
                                TP.NombreTipoPersona, P.RazonSocial, P.*
                                From Transportista As T
                                Inner Join Persona As P On T.IdTransportista = P.IdPersona
                                Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                Where P.IndicadorEstado='A' AND (P.RazonSocial like '%$criterio%'  or P.NumeroDocumentoIdentidad like '%$criterio%')
                                ORDER  BY (P.IdPersona)
                                LIMIT $inicio,$ValorParametroSistema");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarSugerenciaTransportistasPorRuc($data)
  {
    $criterio=$data["textofiltro"];
    $query = $this->db->query("Select P.NumeroDocumentoIdentidad
                                From Transportista As T
                                Inner Join Persona As P On T.IdTransportista = P.IdPersona
                                Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                Where P.IndicadorEstado='A' AND P.NumeroDocumentoIdentidad like '%$criterio%'
                                ORDER  BY (P.NumeroDocumentoIdentidad) ASC");
    $resultado = $query->result_array();
    return $resultado;
  }

  // function ConsultarTransportistasPorIdPersona($data)
  // {
  //   $criterio=$data["IdPersona"];
  //   $query = $this->db->query("Select T.IdTransportista AS IdTransportista,P.IdPersona,  TDI.CodigoDocumentoIdentidad, P.NombreCompleto, P.ApellidoCompleto, TP.NombreTipoPersona, P.RazonSocial, P.NombreComercial,
  //                               P.RepresentanteLegal, P.NumeroDocumentoIdentidad, P.Direccion, P.Email, P.Celular, P.TelefonoFijo, P.CondicionContribuyente, P.EstadoContribuyente
  //                               From Transportista As T
  //                               Inner Join Persona As P On T.IdTransportista = P.IdPersona
  //                               Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
  //                               Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
  //                               Where P.IndicadorEstado='A' AND T.IdTransportista = '$criterio'
  //                               ORDER  BY (P.NumeroDocumentoIdentidad) ASC");
  //   $resultado = $query->row();
  //   return $resultado;
  // }

  function ObtenerNumeroFilaPorConsultaTransportista($data)
  {
    $criterio=$data["textofiltro"];
    $query = $this->db->query("Select count(P.IdTransportista) As NumeroFila
                                From Transportista As T
                                Inner Join Persona As P On T.IdTransportista = P.IdPersona
                                Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                Where P.IndicadorEstado= 'A' and (P.RazonSocial like '%$criterio%' or P.NumeroDocumento like '%$criterio%')
                                ORDER  BY (RazonSocial) ASC");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarPersonaEnVenta($data)
  {
    $estadoactivo=ESTADO_ACTIVO;
    $id=$data["IdPersona"];
    $query = $this->db->query("Select CV.*
                                From ComprobanteVenta CV
                                Where CV.IdTransportista = '$id'
                                AND CV.IndicadorEstado='$estadoactivo'");
    $resultado = $query->result_array();
    return $resultado;
  }

  // function ObtenerTransportistaPorIdPersona($data)
  // {
  //   $criterio=$data["IdPersona"];
  //   $query = $this->db->query("Select T.IdTransportista AS IdTransportista, P.*, TDI.CodigoDocumentoIdentidad, T.NombreZona
  //                               From Transportista As T
  //                               Inner Join Persona As P On T.IdTransportista = P.IdPersona
  //                               Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
  //                               Where P.IndicadorEstado='A' AND T.IdTransportista = '$criterio'");
  //   $resultado = $query->row();
  //   return $resultado;
  // }

  function ObtenerNumeroDocumentoIdentidadParaInsertar($data)
  {
    $numero=$data["NumeroDocumentoIdentidad"];
    $query = $this->db->query("Select T.*
                              From Transportista As T
                              Inner Join Persona As P On T.IdTransportista = P.IdPersona
                              Where P.NumeroDocumentoIdentidad ='$numero' and P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroDocumentoIdentidadParaActualizar($data)
  {
    $id=$data["IdPersona"];
    $numero=$data["NumeroDocumentoIdentidad"];
    $query = $this->db->query("Select T.*
                              From Transportista As T
                              Inner Join Persona As P On T.IdTransportista = P.IdPersona
                              Where (P.IdPersona > '$id' Or P.IdPersona < '$id' ) and P.NumeroDocumentoIdentidad = '$numero' and P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarTransportistaParaJSON()
  {
    $query = $this->db->query("Select P.*, TDI.CodigoDocumentoIdentidad, T.*
                                From Transportista As T
                                Inner Join Persona As P On T.IdTransportista = P.IdPersona
                                Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                Where P.IndicadorEstado='A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarRucEnVentasJSON($data)
  {
    $ruc = $data['NumeroDocumentoIdentidad'];
    $query = $this->db->query("Select * FROM cliente c
                                INNER JOIN persona p ON p.IdPersona = c.IdPersona
                                WHERE p.NumeroDocumentoIdentidad = '$ruc'
                                AND p.IndicadorEstado = 'A'");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarRazonSocialEnVentasJSON($data)
  {
    $razonsocial = $data['RazonSocial'];
    $query = $this->db->query("Select * FROM cliente c
                                INNER JOIN persona p ON p.IdPersona = c.IdPersona
                                WHERE p.RazonSocial = '$razonsocial'
                                AND p.IndicadorEstado = 'A'");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarTransportistaEnVentasJSON($data)
  {
    $ruc = $data['NumeroDocumentoIdentidad'];
    
    $query = $this->db->query("Select * FROM cliente c
                                INNER JOIN persona p ON p.IdPersona = c.IdPersona
                                WHERE p.NumeroDocumentoIdentidad = '$ruc'
                                AND p.IndicadorEstado = 'A'");
    $resultado = $query->row();
    return $resultado;
  }

}

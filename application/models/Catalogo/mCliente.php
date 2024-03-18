<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mCliente extends CI_Model
{

  public $Cliente = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->Cliente = $this->Base->Construir("Cliente");
    //$this->Persona = $this->Base->Construir("Persona");
  }

  function ObtenerNumeroFila()
  {
    $query = $this->db->query("Select Count(IdPersona) As NumeroFila From Cliente");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarClientes($inicio, $ValorParametroSistema)
  {
    $query = $this->db->query("Select C.*, TDI.NombreAbreviado, TDI.CodigoDocumentoIdentidad, TP.NombreTipoPersona, P.* , GA.NombreGradoAlumno
                                     From Cliente As C
                                     left Join Persona As P On C.IdPersona = P.IdPersona
                                     left Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     left Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                     left Join GradoAlumno as GA on GA.IdGradoAlumno = P.IdGradoAlumno
                                     Where P.IndicadorEstado='A'
                                     ORDER  BY (P.IdPersona) ASC
                                     LIMIT $inicio,$ValorParametroSistema");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarCliente($data)
  {
    $resultado = $this->mapper->map($data, $this->Cliente);
    $this->db->insert('Cliente', $resultado);
    $resultado["IdPersona"] = $this->db->insert_id();
    return ($resultado);
  }

  function ActualizarCliente($data)
  {
    $id = $data["IdPersona"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $resultado = $this->mapper->map($data, $this->Cliente);
    $this->db->where('IdPersona', $id);
    $this->db->update('Cliente', $resultado);
  }

  function ObtenerNumeroTotalClientes($data)
  {
    $criterio = $data["textofiltro"];
    $query = $this->db->query("Select count(C.IdPersona) as cantidad
                                     From Cliente As C
                                     Inner Join Persona As P On C.IdPersona = P.IdPersona
                                     Where P.IndicadorEstado='A' AND (P.RazonSocial like '%$criterio%'  or P.NumeroDocumentoIdentidad like '%$criterio%')
                                     ORDER  BY (P.IdPersona)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarClientes($inicio, $ValorParametroSistema, $data)
  {
    $criterio = $data["textofiltro"];
    $query = $this->db->query("Select C.*, P.IdPersona, P.NombreCompleto, P.ApellidoCompleto, TDI.NombreAbreviado,  TDI.CodigoDocumentoIdentidad,
                                    TP.NombreTipoPersona, P.RazonSocial, P.*, GA.NombreGradoAlumno
                                     From Cliente As C
                                     Inner Join Persona As P On C.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                     left Join GradoAlumno as GA on GA.IdGradoAlumno = P.IdGradoAlumno
                                     Where P.IndicadorEstado='A' AND (P.RazonSocial like '%$criterio%'  or P.NumeroDocumentoIdentidad like '%$criterio%')
                                     ORDER  BY (P.IdPersona)
                                     LIMIT $inicio,$ValorParametroSistema");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarSugerenciaClientesPorRuc($data)
  {
    $criterio = $data["textofiltro"];
    $query = $this->db->query("Select P.NumeroDocumentoIdentidad
                                     From Cliente As C
                                     Inner Join Persona As P On C.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                     Where P.IndicadorEstado='A' AND P.NumeroDocumentoIdentidad like '%$criterio%'
                                     ORDER  BY (P.NumeroDocumentoIdentidad) ASC");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarClientesPorIdPersona($data)
  {
    $criterio = $data["IdPersona"];
    $query = $this->db->query("Select C.IdPersona AS IdCliente,P.IdPersona,  TDI.CodigoDocumentoIdentidad, P.NombreCompleto, P.ApellidoCompleto, TP.NombreTipoPersona, P.RazonSocial, P.NombreComercial,
                                     P.RepresentanteLegal, P.NumeroDocumentoIdentidad, P.Direccion, P.Email, P.Celular, P.TelefonoFijo, P.CondicionContribuyente, P.EstadoContribuyente
                                     From Cliente As C
                                     Inner Join Persona As P On C.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                     Where P.IndicadorEstado='A' AND C.IdPersona = '$criterio'
                                     ORDER  BY (P.NumeroDocumentoIdentidad) ASC");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerNumeroFilaPorConsultaCliente($data)
  {
    $criterio = $data["textofiltro"];
    $query = $this->db->query("Select Count(P.IdPersona) As NumeroFila
                                     From Cliente As C
                                     Inner Join Persona As P On C.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                     Where P.IndicadorEstado= 'A' and (P.RazonSocial like '%$criterio%' or P.NumeroDocumento like '%$criterio%')
                                     ORDER  BY (RazonSocial) ASC");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarPersonaEnVenta($data)
  {
    $estadoactivo = ESTADO_ACTIVO;
    $id = $data["IdPersona"];
    $query = $this->db->query("Select CV.*
                                     From ComprobanteVenta CV
                                     Where CV.IdCliente = '$id'
                                     AND CV.IndicadorEstado='$estadoactivo'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerClientePorIdPersona($data)
  {
    $criterio = $data["IdPersona"];
    $query = $this->db->query("Select C.IdPersona AS IdCliente, P.*, TDI.CodigoDocumentoIdentidad, C.NombreZona
                                     From Cliente As C
                                     Inner Join Persona As P On C.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Where P.IndicadorEstado='A' AND C.IdPersona = '$criterio'");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerNumeroDocumentoIdentidadParaInsertar($data)
  {
    $numero = $data["NumeroDocumentoIdentidad"];
    $query = $this->db->query("Select C.*
                                    From Cliente As C
                                    Inner Join Persona As P On C.IdPersona = P.IdPersona
                                    Where P.NumeroDocumentoIdentidad ='$numero' and P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroDocumentoIdentidadParaActualizar($data)
  {
    $id = $data["IdPersona"];
    $numero = $data["NumeroDocumentoIdentidad"];
    $query = $this->db->query("Select C.*
                                    From Cliente As C
                                    Inner Join Persona As P On C.IdPersona = P.IdPersona
                                    Where (P.IdPersona > '$id' Or P.IdPersona < '$id' ) and P.NumeroDocumentoIdentidad = '$numero' and P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarClienteParaJSON()
  {
    $query = $this->db->query("Select C.IdPersona AS IdCliente, P.*, TDI.CodigoDocumentoIdentidad, GA.NombreGradoAlumno,
                                      C.IndicadorAfiliacionTarjeta, C.FechaInicioAfiliacionTarjeta, C.FechaFinAfiliacionTarjeta,C.EstadoCliente
                                     From Cliente As C
                                     Inner Join Persona As P On C.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     left Join GradoAlumno as GA on GA.IdGradoAlumno = P.IdGradoAlumno
                                     Where P.IndicadorEstado='A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarClienteParaJSONPorIdCliente($data)
  {
    $id = $data["IdPersona"];
    $query = $this->db->query("Select C.IdPersona AS IdCliente, P.*, TDI.CodigoDocumentoIdentidad, GA.NombreGradoAlumno,
                                      C.IndicadorAfiliacionTarjeta, C.FechaInicioAfiliacionTarjeta, C.FechaFinAfiliacionTarjeta,
                                      C.EstadoCliente
                                     From Cliente As C
                                     Inner Join Persona As P On C.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     left Join GradoAlumno as GA on GA.IdGradoAlumno = P.IdGradoAlumno
                                     Where C.IdPersona = '$id' AND P.IndicadorEstado='A'");
    $resultado = $query->row_array();
    return $resultado;
  }

  function ConsultarRucEnVentasJSON($data)
  {
    $ruc = $data['NumeroDocumentoIdentidad'];
    // $nombreproducto = $data['NombreProducto'];
    $query = $this->db->query("Select * FROM cliente c
                                      INNER JOIN persona p ON p.IdPersona = c.IdPersona
                                      WHERE p.NumeroDocumentoIdentidad = '$ruc'
                                      AND p.IndicadorEstado = 'A'");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarRazonSocialEnVentasJSON($data)
  {
    // $codigo = $data['CodigoMercaderia'];
    $razonsocial = $data['RazonSocial'];
    $query = $this->db->query("Select * FROM cliente c
                                      INNER JOIN persona p ON p.IdPersona = c.IdPersona
                                      WHERE p.RazonSocial = '$razonsocial'
                                      AND p.IndicadorEstado = 'A'");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarClienteEnVentasJSON($data)
  {
    $ruc = $data['NumeroDocumentoIdentidad'];

    $query = $this->db->query("Select * FROM cliente c
                                      INNER JOIN persona p ON p.IdPersona = c.IdPersona
                                      WHERE p.NumeroDocumentoIdentidad = '$ruc'
                                      AND p.IndicadorEstado = 'A'");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarClienteParaJSONExportacion($data)
  {
    $id = $data["IdPersona"];
    $query = $this->db->query("Select C.*, P.IdPersona, P.NombreCompleto, P.ApellidoCompleto, TDI.NombreAbreviado,  TDI.CodigoDocumentoIdentidad,
                                    TP.NombreTipoPersona, P.RazonSocial, P.*, GA.NombreGradoAlumno
                                    From Cliente As C
                                    Inner Join Persona As P On C.IdPersona = P.IdPersona
                                    Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                    Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                    left Join GradoAlumno as GA on GA.IdGradoAlumno = P.IdGradoAlumno
                                    WHERE P.IdPersona = '$id' AND P.IndicadorEstado = 'A'");
    $resultado = $query->row();
    return $resultado;
  }

  function ConsultarClientesEnVentasJSON($data)
  {
    $query = $this->db->query("Select C.*, P.IdPersona, P.NombreCompleto, P.ApellidoCompleto, TDI.NombreAbreviado,  TDI.CodigoDocumentoIdentidad,
                                    TP.NombreTipoPersona, P.RazonSocial, P.*, GA.NombreGradoAlumno
                                    From Cliente As C
                                    Inner Join Persona As P On C.IdPersona = P.IdPersona
                                    Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                    Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                    left Join GradoAlumno as GA on GA.IdGradoAlumno = P.IdGradoAlumno
                                    WHERE P.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  
  function ObtenerClientePorIdCliente($data)
  {
    $IdCliente = $data["IdCliente"];
    $query = $this->db->query("Select C.*, P.IdPersona, 
    TDI.NombreAbreviado,  TDI.CodigoDocumentoIdentidad,
    TP.NombreTipoPersona, P.RazonSocial, P.*, GA.NombreGradoAlumno,
    CASE WHEN P.NombreCompleto IS NULL THEN '' ELSE P.NombreCompleto END AS NombreCompleto,
    CASE WHEN P.ApellidoCompleto IS NULL THEN '' ELSE P.ApellidoCompleto END AS ApellidoCompleto
                                     From Cliente As C
                                     Inner Join Persona As P On C.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Inner Join TipoPersona As TP on P.IdTipoPersona = TP.IdTipoPersona
                                     left Join GradoAlumno as GA on GA.IdGradoAlumno = P.IdGradoAlumno
                                     Where P.IndicadorEstado='A' AND (P.IdPersona='$IdCliente')");
    $resultado = $query->result_array();
    return $resultado;
  }
}

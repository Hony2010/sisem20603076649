<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mUsuario extends CI_Model
{

  public $Usuario = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->Usuario = $this->Base->Construir("Usuario");
  }

  function ListarUsuarios($data = null) {
    
    if ($data == null) {      
      $data["EstadoUsuario"]="1";//solo habilitados
    }
    $filtroEstadoUsuario = $data["EstadoUsuario"];
    
    $query = $this->db->query("Select P.*, U.*, CE.IdRol, CE.NombreRol, U.IdPersona AS AnteriorIdPersona
                                    From Usuario As U
                                    Inner Join Persona As P on P.IdPersona = U.IdPersona
                                    Inner Join Empleado As E on E.IdPersona = P.IdPersona
                                    Inner Join Rol CE on CE.IdRol = P.IdRol
                                    Where (U.IndicadorEstado = 'A' or U.IndicadorEstado = 'B') 
                                    And U.EstadoUsuario like '$filtroEstadoUsuario'
                                    ORDER BY (U.NombreUsuario)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarUsuariosPorSede($data)
  {
    $sede = $data["IdSede"];
    $query = $this->db->query("Select U.*, P.*, CE.IdRol, CE.NombreRol, U.IdPersona AS AnteriorIdPersona,'true' AS EstadoSeleccion
                  From Usuario As U
                  Inner Join Persona As P on P.IdPersona = U.IdPersona
                  Inner Join Empleado As E on E.IdPersona = P.IdPersona
                  Inner Join Rol CE on CE.IdRol = P.IdRol
                  Where E.IdSede = '$sede' AND (U.IndicadorEstado = 'A' or U.IndicadorEstado = 'B') AND U.EstadoUsuario='1'
                  ORDER BY (U.IdUsuario)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarUsuario($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $resultado = $this->mapper->map($data, $this->Usuario);
    $this->db->insert('Usuario', $resultado);
    $resultado = $this->db->insert_id();
    return ($resultado);
  }

  function ActualizarUsuario($data)
  {
    $id = $data["IdUsuario"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $resultado = $this->mapper->map_real($data, $this->Usuario);
    $this->db->where('IdUsuario', $id);
    $this->db->update('Usuario', $resultado);
    return $data;
  }

  function BorrarUsuario($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $this->ActualizarUsuario($data);
  }

  function BloquearUsuario($data)
  {
    $data["IndicadorEstado"] = ESTADO_BLOQUEADO;
    $this->ActualizarUsuario($data);
  }

  function ActivarUsuario($data)
  {
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $this->ActualizarUsuario($data);
  }

  function MarcarSesionActivo($data)
  {
    $data["IndicadorSesionActivo"] = 'S';
    $this->ActualizarUsuario($data);
  }

  function DesmarcarSesionActivo($data)
  {
    $data["IndicadorSesionActivo"] = 'N';
    $this->ActualizarUsuario($data);
  }

  function ObtenerDuplicadoDeNombreUsuarioParaInsertar($data)
  {
    $nombre = $data["NombreUsuario"];
    $query = $this->db->query("Select *
                                     From Usuario
                                     Where NombreUsuario = '$nombre'  and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }


  function ObtenerDuplicadoDeNombreUsuarioParaActualizar($data)
  {
    $id = $data["IdUsuario"];
    $nombre = $data["NombreUsuario"];
    $query = $this->db->query("Select *
                                     From Usuario
                                     Where ((IdUsuario > '$id' Or IdUsuario < '$id' ) and NombreUsuario = '$nombre')  and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDuplicadoDeAliasUsuarioVentaParaInsertar($data)
  {
    $nombre = $data["AliasUsuarioVenta"];
    $query = $this->db->query("Select *
                                     From Usuario
                                     Where AliasUsuarioVenta = '$nombre' and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }


  function ObtenerDuplicadoDeAliasUsuarioVentaParaActualizar($data)
  {
    $id = $data["IdUsuario"];
    $nombre = $data["AliasUsuarioVenta"];
    $query = $this->db->query("Select *
                                     From Usuario
                                     Where ((IdUsuario > '$id' Or IdUsuario < '$id' ) and AliasUsuarioVenta = '$nombre') and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerCuenta($data)
  {
    $nombre = $data["NombreUsuario"];
    $clave = $data["ClaveUsuario"];

    $query = $this->db->query("Select U.IdUsuario, U.NombreUsuario, U.AliasUsuarioVenta, U.IdPersona, U.TemaSistema, U.IndicadorEstado, U.IndicadorVistaPreviaImpresion,U.IndicadorVistaPrecioMinimo ,
                            		    U.NombreZona, P.ApellidoCompleto, P.NombreCompleto, P.RazonSocial, P.NumeroDocumentoIdentidad, P.IdRol, P.Foto,
                          		      E.IdEmpleado, E.IdSede, S.CodigoSede, S.NombreSede,
                            		    R.IndicadorVerTodasVentas, R.IndicadorVerComboVentas, R.NombreRol
                                    From usuario As U
                                    Inner Join persona As P On P.IdPersona = U.IdPersona
                                    Inner Join rol as R On P.IdRol = R.IdRol
                                    Inner Join empleado As E On E.IdPersona = P.IdPersona
                                    Inner Join sede As S On S.IdSede = E.IdSede
                                    where (U.NombreUsuario = '$nombre' and U.ClaveUsuario = '$clave') And U.IndicadorEstado = 'A'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ObtenerCuentaPorNombreUsuario($data)
  {
    $nombre = $data["NombreUsuario"];

    $query = $this->db->query("Select U.IdUsuario, U.NombreUsuario, U.AliasUsuarioVenta, U.IdPersona, U.TemaSistema, U.IndicadorEstado,
                            		    U.NombreZona, P.ApellidoCompleto, P.NombreCompleto, P.RazonSocial, P.NumeroDocumentoIdentidad, P.IdRol, P.Foto,
                          		      E.IdEmpleado, E.IdSede, S.CodigoSede, S.NombreSede,
                            		    R.IndicadorVerTodasVentas, R.IndicadorVerComboVentas, R.NombreRol
                                    From usuario As U
                                    Inner Join persona As P On P.IdPersona = U.IdPersona
                                    Inner Join rol as R On P.IdRol = R.IdRol
                                    Inner Join empleado As E On E.IdPersona = P.IdPersona
                                    Inner Join sede As S On S.IdSede = E.IdSede
                                    where U.NombreUsuario = '$nombre' And U.IndicadorEstado = 'A'");
    $resultado = $query->result_array();

    return $resultado;
  }

  function ObtenerTotalUsuariosActivos()
  {
    $query = $this->db->query("Select count(IdUsuario) as Total from Usuario Where IndicadorEstado = 'A' ");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerDuplicadoDeNumeroSerieParaInsertar($data)
  {
    $numero = $data["NumeroSerie"];
    $query = $this->db->query("Select *
                                     From Usuario
                                     Where (NumeroSerie = '$numero' and NumeroSerie != 0) and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDuplicadoDeNumeroSerieParaActualizar($data)
  {
    $id = $data["IdUsuario"];
    $numero = $data["NumeroSerie"];
    $query = $this->db->query("Select *
                                     From Usuario
                                     Where IdUsuario != '$id' and  NumeroSerie != 0 and NumeroSerie = '$numero' and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerUsuarioPorAliasUsuarioVenta($data)
  {
    $alias = $data["AliasUsuarioVenta"];
    $query = $this->db->query("Select *
                                     From Usuario
                                     Where AliasUsuarioVenta = '$alias' and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerUsuario($data) {
   $IdUsuario = $data["IdUsuario"];
   $Sql = "Select * From Usuario Where IdUsuario=$IdUsuario";
   $query = $this->db->query($Sql);
   $resultado = $query->result_array();
   return $resultado;
  }


  

}

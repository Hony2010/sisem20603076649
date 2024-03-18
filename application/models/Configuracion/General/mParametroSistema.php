<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class mParametroSistema extends CI_Model
{

  public $ParametroSistema = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->ParametroSistema = $this->Base->Construir("ParametroSistema");
  }

  function ObtenerNumeroFila()
  {
    $query = $this->db->query("Select Count(IdParametroSistema) As NumeroFila From ParametroSistema  Where IndicadorEstado = 'A' ");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerParametroSistemaPorId($data)
  {
    $id = $data["IdParametroSistema"];
    $this->db->select("*")
      ->from('ParametroSistema')
      ->where("IdParametroSistema = '$id'");
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ObtenerParametroSistemaPorIdGrupo($data)
  {
    $id = $data["IdGrupoParametro"];
    $this->db->select("*")
      ->from('ParametroSistema')
      ->where("IdGrupoParametro = '$id'");
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ListarParametrosSistema($inicio)
  {
    $numfilas = NUM_FILAS_POR_PAGINA;
    $query = $this->db->query("Select PS.IdParametroSistema,  PS.NombreParametroSistema, PS.ValorParametroSistema, GP.NombreGrupoParametro, PS.IndicadorEstado
                                     From ParametroSistema As PS
                                     Inner Join EntidadSistema As ES on PS.IdEntidadSistema = ES.IdEntidadSistema
                                     Inner Join ModuloSistema As MS on ES.IdModuloSistema = MS.IdModuloSistema
                                     Inner Join GrupoParametro As GP on PS.IdGrupoParametro = GP.IdGrupoParametro
                                     Where PS	.IndicadorEstado = 'A'
                                     ORDER BY(PS.IdParametroSistema)
                                     LIMIT $inicio,$numfilas");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarParametroSistema($data)
  {
    $data["FechaRegistro"] = $this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $resultado = $this->mapper->map($data, $this->ParametroSistema);
    $this->db->insert('ParametroSistema', $resultado);
    $resultado = $this->db->insert_id();
    return ($resultado);
  }

  function ActualizarParametroSistema($data)
  {
    $id = $data["IdParametroSistema"];
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();
    $resultado = $this->mapper->map($data, $this->ParametroSistema);
    $this->db->where('IdParametroSistema', $id);
    $this->db->update('ParametroSistema ', $resultado);
    return $resultado;
  }

  function BorrarParametroSistema($data)
  {
    $data["IndicadorEstado"] = ESTADO_ELIMINADO;
    $this->ActualizarParametroSistema($data);
  }

  function ConsultarParametroSistema($data)
  {
    $criterio = $data["textofiltro"];
    $this->db->select("PS.IdParametroSistema,  PS.NombreParametroSistema, PS.ValorParametroSistema, GP.NombreGrupoParametro, PS.IndicadorEstado")
      ->from('ParametroSistema As PS')
      ->join('EntidadSistema As ES', 'PS.IdEntidadSistema = ES.IdEntidadSistema')
      ->join('ModuloSistema As MS', 'ES.IdModuloSistema = MS.IdModuloSistema')
      ->join('GrupoParametro As GP', 'PS.IdGrupoParametro = GP.IdGrupoParametro')
      ->where('PS.IdParametroSistema like "%' . $criterio . '%" or PS.NombreParametroSistema like "%' . $criterio . '%" or GP.NombreGrupoParametro like "%' . $criterio . '%" and PS.IndicadorEstado="A"');
    $query = $this->db->get();
    $resultado = $query->result();
    return $resultado;
  }

  function ObtenerParametroSistemaPorIdParametroSistema($data)
  {
    $id = $data["IdParametroSistema"];
    $this->db->select("*")
      ->from('ParametroSistema')
      ->where("IdParametroSistema = '$id'");
    $query = $this->db->get();
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerParametroSistemaPorNombreParametroSistema($data) {
    $NombreParametroSistema= $data["NombreParametroSistema"];
    $query = $this->db->query("select * from parametrosistema as ps 
    where ps.IndicadorEstado = 'A' and ps.NombreParametroSistema='$NombreParametroSistema'");
    $resultado = $query->result();
    return $resultado;
  }
}

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mCorrelativoDocumento extends CI_Model {

  public $CorrelativoDocumento = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->CorrelativoDocumento = $this->Base->Construir("CorrelativoDocumento");
  }

  function ListarCorrelativosDocumento()
  {
    $query = $this->db->query("Select CD.*, TD.NombreTipoDocumento, TD.NombreAbreviado, S.NombreSede
                              From CorrelativoDocumento AS CD
                              Inner Join TipoDocumento As TD on CD.IdTipoDocumento = TD.IdTipoDocumento
                              Inner Join Sede As S on CD.IdSede = S.IdSede
                              Where CD.IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarCorrelativoDocumento($data)
  {
    $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;

    if (array_key_exists("IdSubTipoDocumento",$data)){
      $data["IdSubTipoDocumento"] = $data["IdSubTipoDocumento"] == "" ? null : $data["IdSubTipoDocumento"];
    }
    else {
      $data["IdSubTipoDocumento"] = null;
    }

    $resultado = $this->mapper->map($data,$this->CorrelativoDocumento);
    $this->db->insert('CorrelativoDocumento', $resultado);
    $resultado = $this->db->insert_id();
    return($resultado);
  }

  function ActualizarCorrelativoDocumento($data)
  {
    $id=$data["IdCorrelativoDocumento"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
    if (array_key_exists("IdSubTipoDocumento",$data)){
      $data["IdSubTipoDocumento"] = $data["IdSubTipoDocumento"] == "" ? null : $data["IdSubTipoDocumento"];
    }
    else {
      $data["IdSubTipoDocumento"] = null;
    }
    $resultado = $this->mapper->map($data,$this->CorrelativoDocumento);
    $resultado["UltimoDocumento"] = $resultado["UltimoDocumento"] == '00000000' ? null : $resultado["UltimoDocumento"];
    $this->db->where('IdCorrelativoDocumento', $id);
    $this->db->update('CorrelativoDocumento', $resultado);
  }

  function BorrarCorrelativoDocumento($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $this->ActualizarCorrelativoDocumento($data);
  }


  function ListarSeriesDocumento($data)
  {

    $IdTipoDocumento=$data["IdTipoDocumento"];
    $IdSedeAgencia=$data["IdSedeAgencia"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    if($vistaventa == 0)
    {
      $extensionConsulta = " and S.IdSede='$IdSedeAgencia' ";
    }

    if(array_key_exists("IdSubTipoDocumento",$data)) {
      if($data["IdSubTipoDocumento"]!==null && $data["IdSubTipoDocumento"]!=="") {
        $IdSubTipoDocumento=$data["IdSubTipoDocumento"];
        if ($data["IdSubTipoDocumento"]!=="%")
          $sql_filtro_subtipodocumento = "and CD.IdSubTipoDocumento like '$IdSubTipoDocumento'";
        else 
          $sql_filtro_subtipodocumento = "";
      }
      else {
        $sql_filtro_subtipodocumento = "and (LENGTH(CD.IdSubTipoDocumento) = 0 || CD.IdSubTipoDocumento IS NULL) ";
      }
    }
    else {
      $sql_filtro_subtipodocumento = "and (LENGTH(CD.IdSubTipoDocumento) = 0 || CD.IdSubTipoDocumento IS NULL) ";
    }

    //$CondicionTipoDocumento = $IdTipoDocumento == "%" ? " Where (TD.IdTipoDocumento LIKE '%' " : " Where (TD.IdTipoDocumento IN ('$IdTipoDocumento') ";
    /*
    $consulta = "Select CD.*,S.NombreSede
                  From CorrelativoDocumento As CD
                  Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CD.IdTipoDocumento
                  Inner Join Sede As S on S.IdSede = CD.IdSede"
                  .$CondicionTipoDocumento.$extensionConsulta.") and
                  CD.IndicadorEstado='A' and $sql_filtro_subtipodocumento
                  ORDER BY CD.Orden ASC";
    */
    $consulta = "Select CD.*,S.NombreSede
    From CorrelativoDocumento As CD
    Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CD.IdTipoDocumento
    Inner Join Sede As S on S.IdSede = CD.IdSede
    Where (TD.IdTipoDocumento like '$IdTipoDocumento'
    ".$extensionConsulta.") and
    CD.IndicadorEstado='A' $sql_filtro_subtipodocumento
    ORDER BY CD.Orden ASC";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerCorrelativoDocumento($data) {
    $IdCorrelativoDocumento=$data["IdCorrelativoDocumento"];
    $query = $this->db->query("Select CD.*
                                From CorrelativoDocumento as CD
                                Where CD.IdCorrelativoDocumento = '$IdCorrelativoDocumento'");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerCorrelativoDocumentoPorTipoDocumentoYSede($data) {
    $SerieDocumento=$data["SerieDocumento"];
    $IdTipoDocumento=$data["IdTipoDocumento"];
    $IdSede=$data["IdSedeAgencia"];

    $query = $this->db->query("Select CD.*
                                From CorrelativoDocumento as CD
                                Where CD.SerieDocumento = '$SerieDocumento' And CD.IdTipoDocumento='$IdTipoDocumento' And CD.IdSede='$IdSede'");
    $resultado = $query->row();
    return $resultado;
  }

  function ValidarSerieYTipoDocumentoEnCorrelativoDocumento($data) {
    $SerieDocumento=$data["SerieDocumento"];
    $IdTipoDocumento=$data["IdTipoDocumento"];
    // $IdSede=$data["IdSedeAgencia"];

    $query = $this->db->query("select *
                  from CorrelativoDocumento
                  where IdTipoDocumento = '$IdTipoDocumento' and SerieDocumento = '$SerieDocumento' and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ValidarActualizarSerieYTipoDocumentoEnCorrelativoDocumento($data) {
    $SerieDocumento=$data["SerieDocumento"];
    $IdTipoDocumento=$data["IdTipoDocumento"];
    $IdCorrelativoDocumento=$data["IdCorrelativoDocumento"];

    $query = $this->db->query("select *
                    from CorrelativoDocumento
                    where IdCorrelativoDocumento != '$IdCorrelativoDocumento'
                    and IdTipoDocumento = '$IdTipoDocumento'
                    and SerieDocumento = '$SerieDocumento' and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ValidarCorrelativoDocumentoJSONPorSerieYTipo($data) {
    $serie=$data["SerieDocumento"];
    $idtipo=$data["IdTipoDocumento"];

    $query = $this->db->query("Select * FROM correlativodocumento c
                                WHERE c.SerieDocumento = '$serie'
                                AND c.IdTipoDocumento = '$idtipo' AND c.IndicadorEstado = 'A'");
    $resultado = $query->row();
    return $resultado;
  }

  function ValidarSerieEnCorrelativoDocumento($data) {
    $SerieDocumento=$data["SerieDocumento"];

    $query = $this->db->query("select *
                  from CorrelativoDocumento
                  where SerieDocumento = '$SerieDocumento' and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ValidarActualizarSerieEnCorrelativoDocumento($data) {
    $SerieDocumento=$data["SerieDocumento"];
    $IdCorrelativoDocumento=$data["IdCorrelativoDocumento"];

    $query = $this->db->query("select *
                    from CorrelativoDocumento
                    where IdCorrelativoDocumento != '$IdCorrelativoDocumento'
                    and SerieDocumento = '$SerieDocumento' and IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerCorrelativoDocumentoParaJSON($data) {
    $SerieDocumento=$data["SerieDocumento"];
    $TipoDocumento=$data["IdTipoDocumento"];
    // $IdCorrelativoDocumento=$data["IdCorrelativoDocumento"];

    $query = $this->db->query("select *
                    from CorrelativoDocumento
                    where SerieDocumento = '$SerieDocumento' 
                    AND IdTipoDocumento = '$TipoDocumento' 
                    and IndicadorEstado = 'A'");
    $resultado = $query->row_array();
    return $resultado;
  }

  function ListarCorrelativosDocumentoPorNumeroSerie($data) {
    $NumeroSerie=$data["NumeroSerie"] == null ? 0 : $data["NumeroSerie"];
    $sql = "select *
    from CorrelativoDocumento
    where SerieDocumento LIKE '%$NumeroSerie' and IndicadorEstado = 'A'";

    $query = $this->db->query($sql);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarCorrelativosDocumentoDespuesUltimaFechaSincronizacion($data) {
    if(array_key_exists("UltimaFechaSincronizacionCorrelativoDocumento",$data)) {
      $fecha=$data["UltimaFechaSincronizacionCorrelativoDocumento"];
      $filtro=" WHERE C.FechaRegistro >= '".$fecha."' or C.FechaModificacion >='".$fecha."'";
    }
    else{
      $filtro="";
    }
    
    $query = $this->db->query("Select *       
      From CorrelativoDocumento As C
       $filtro");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerSeriesDocumentoPorIdUsuarioYIdTipoDocumentoYIdSubTipoDocumento($data) {
    $IdSubTipoDocumento = array_key_exists("IdSubTipoDocumento",$data) ? $data["IdSubTipoDocumento"] : null;
    $IdTipoDocumento = $data["IdTipoDocumento"];
    $IdUsuario = $data["IdUsuario"];
    $condicion0 = $IdTipoDocumento =="%" ? " And 1 " : " And cd.IdTipoDocumento = $IdTipoDocumento ";
    $condicion = $IdSubTipoDocumento == null ? " And cd.IdSubTipoDocumento is null " : ($IdSubTipoDocumento =="%" ? "" :  " And cd.IdSubTipoDocumento=$IdSubTipoDocumento ");

    $sql = "SELECT CD.* , S.NombreSede
    FROM correlativodocumento cd
    INNER JOIN accesousuarioalmacen aua
    ON aua.IdSede = cd.IdSede
    INNER JOIN sede s
    ON s.IdSede = cd.IdSede    
    WHERE aua.IdUsuario = $IdUsuario 
    $condicion0
    $condicion
    and cd.IndicadorEstado = 'A' 
    and s.IndicadorEstado = 'A' and aua.IndicadorEstado = 'A' ";
        
    $query = $this->db->query($sql);
    $resultado = $query->result_array();
    
  
    return $resultado;
    
  }
  

}

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mUnidadMedida extends CI_Model {

        public $UnidadMedida = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->UnidadMedida = $this->Base->Construir("UnidadMedida");
        }

        function ListarUnidadesMedida()
        {
          $query = $this->db->query("Select UM.*
                                    from UnidadMedida as UM
                                    Where UM.IndicadorEstado = 'A'
                                    order by NombreUnidadMedida");
          $resultado = $query->result();
          return $resultado;
        }

        function ListarOtraUnidadesMedida($inicio, $ValorParametroSistema)
        {
          $query = $this->db->query("Select UM.*
                                    from UnidadMedida as UM
                                    Where UM.IndicadorEstado = 'E'
                                    ORDER BY(UM.NombreUnidadMedida)
                                    LIMIT $inicio,$ValorParametroSistema");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarOtraUnidadesMedida($data, $numerofilainicio,$numerorfilasporpagina)
        {
          $criterio=$data["textofiltro"];
          $query = $this->db->query("Select UM.*
                                    from UnidadMedida as UM
                                    Where UM.IndicadorEstado = 'E' AND (UM.NombreUnidadMedida like '%$criterio%')
                                     ORDER BY(UM.NombreUnidadMedida)
                                     LIMIT $numerofilainicio,$numerorfilasporpagina");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarUnidadMedida($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->UnidadMedida);
          $this->db->insert('UnidadMedida', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarUnidadMedida($data)
        {
          $id=$data["IdUnidadMedida"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->UnidadMedida);
          $this->db->where('IdUnidadMedida', $id);
          $this->db->update('UnidadMedida', $resultado);
        }

        function ActualizarOtraUnidadMedida($data)
        {
          $id=$data["IdUnidadMedida"];
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->UnidadMedida);
          $this->db->where('IdUnidadMedida', $id);
          $this->db->update('UnidadMedida', $resultado);
        }

        function BorrarUnidadMedida($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarUnidadMedida($data);
        }

        function ConsultarUnidadMedidaEnMercaderia($data)
        {
          $id=$data["IdUnidadMedida"];
          $this->db->select("M.*")
          ->from('Mercaderia As M')
          ->join('Producto as P','M.IdProducto = P.IdProducto')
          ->where("M.IdUnidadMedida = '$id' AND P.IndicadorEstado = 'A'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ConsultarUnidadMedidaEnCostoServicio($data)
        {
          $id=$data["IdUnidadMedida"];
          $this->db->select("CS.*")
          ->from('CostoServicio As CS')
          ->join('Producto as P','CS.IdProducto = P.IdProducto')
          ->where("CS.IdUnidadMedida = '$id' AND P.IndicadorEstado = 'A'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerDuplicadoDeCodigoUnidadMedidaParaInsertar($data)
        {
          $codigo=$data["CodigoUnidadMedidaSunat"];
          $query = $this->db->query("Select UM.*
                                     From UnidadMedida UM
                                     Where UM.CodigoUnidadMedidaSunat = '$codigo' and UM.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDuplicadoDeAbreviaturaUnidadMedidaParaInsertar($data)
        {
          $abreviatura=$data["AbreviaturaUnidadMedida"];
          $query = $this->db->query("Select *
                                     From UnidadMedida
                                     Where AbreviaturaUnidadMedida = '$abreviatura' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDuplicadoDeCodigoUnidadMedidaParaActualizar($data)
        {
          $id=$data["IdUnidadMedida"];
          $codigo=$data["CodigoUnidadMedidaSunat"];
          $query = $this->db->query("Select UM.*
                                     From UnidadMedida UM
                                     Where (UM.IdUnidadMedida > '$id' Or UM.IdUnidadMedida < '$id' ) and UM.CodigoUnidadMedidaSunat = '$codigo' and UM.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDuplicadoDeAbreviaturaUnidadMedidaParaActualizar($data)
        {
          $id=$data["IdUnidadMedida"];
          $abreviatura=$data["AbreviaturaUnidadMedida"];
          $query = $this->db->query("Select *
                                     From UnidadMedida
                                     Where (IdUnidadMedida > '$id' Or IdUnidadMedida < '$id' ) and AbreviaturaUnidadMedida = '$abreviatura' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNumeroTotalOtraUnidadesMedida($data)
        {
          $criterio=$data["textofiltro"];

          $query = $this->db->query("Select UM.*
                                    from UnidadMedida as UM
                                    Where UM.IndicadorEstado = 'E' AND (UM.NombreUnidadMedida like '%$criterio%')
                                     ORDER BY(UM.NombreUnidadMedida)");
          $resultado = $query->num_rows();
          return $resultado;
        }

        function ObtenerUnidadMedidaPorNombreOAbreviatura($data) {
          $criterio=$data["NombreUnidadMedida"];
          $IndicadorEstado=$data["IndicadorEstado"];

          $query = $this->db->query("Select UM.*
                                    from UnidadMedida as UM
                                    Where UM.IndicadorEstado like '$IndicadorEstado' AND
                                    (UM.NombreUnidadMedida = '$criterio' or UM.AbreviaturaUnidadMedida = '$criterio')");
          $resultado = $query->result_array();
          return $resultado;
        }


 }

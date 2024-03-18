<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mLoteProducto extends CI_Model {

        public $LoteProducto = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->LoteProducto = $this->Base->Construir("LoteProducto");
        }


        function InsertarLoteProducto($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          
          $resultado = $this->mapper->map($data,$this->LoteProducto);
          if( array_key_exists("FechaVencimiento",$data )){
            $resultado["FechaVencimiento"] = $data["FechaVencimiento"] == "" ? null : $data["FechaVencimiento"];
          }

          $this->db->insert('LoteProducto', $resultado);
          $resultado["IdLoteProducto"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarLoteProducto($data)
        {
          $id=$data["IdLoteProducto"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
                              
          $resultado = $this->mapper->map($data,$this->LoteProducto);          
          if( array_key_exists("FechaVencimiento",$data )){
            $resultado["FechaVencimiento"] = $data["FechaVencimiento"] == "" ? null : $data["FechaVencimiento"];
          }
          
          $this->db->where('IdLoteProducto', $id);
          $this->db->update('LoteProducto', $resultado);

          return $resultado;
        }

        function BorrarLoteProducto($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarLoteProducto($data);
        }

        function ObtenerLoteProductoPorProductoLote($data)
        {
          $id=$data["IdProducto"];
          $lote=$data["NumeroLote"];
          $query = $this->db->query("Select * from LoteProducto
                                     WHERE IdProducto = '$id' AND NumeroLote = '$lote'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerLoteProductoPorIdLoteProductoAndIdProducto($data)
        {
          $id=$data["IdProducto"];
          $idlote=$data["IdLoteProducto"];
          $query = $this->db->query("Select * from LoteProducto
                                     WHERE IdProducto = '$id' AND IdLoteProducto = '$idlote'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerLotesProductoPorProductoLote()
        {
          $query = $this->db->query("Select * from LoteProducto
                                     WHERE IdProducto = '$id'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }

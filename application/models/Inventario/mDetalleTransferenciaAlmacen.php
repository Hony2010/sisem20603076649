<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDetalleTransferenciaAlmacen extends CI_Model {

        public $DetalleTransferenciaAlmacen = array();

        public function __construct() {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->DetalleTransferenciaAlmacen = $this->Base->Construir("DetalleTransferenciaAlmacen");
        }

        function ConsultarDetallesTransferenciaAlmacen($data) {
          
          $id = $data["IdTransferenciaAlmacen"];

          $query = $this->db->query("Select DTA.*,UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,
                                    M.CodigoMercaderia, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, P.NombreProducto, 
                                    TAI.CodigoTipoAfectacionIGV, TSI.CodigoTipoSistemaISC, TP.CodigoTipoPrecio
                                    From DetalleTransferenciaAlmacen As DTA
                                    Inner Join Mercaderia As M on M.IdProducto=DTA.IdProducto
                                    Inner Join Producto As P on P.IdProducto=M.IdProducto
                                    Inner Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
                                    left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                    left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                    left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                    Where DTA.IndicadorEstado = 'A' and DTA.IdTransferenciaAlmacen = '$id'");
          $resultado = $query->result_array();

          return $resultado;
        }       

        function InsertarDetalleTransferenciaAlmacen($data) {
          //  $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          //  $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          //  $data["IndicadorEstado"]=ESTADO_ACTIVO;
           $resultado = $this->mapper->map($data,$this->DetalleTransferenciaAlmacen);
           $this->db->insert('DetalleTransferenciaAlmacen', $resultado);
           $data["IdDetalleTransferenciaAlmacen"] = $this->db->insert_id();
           return $data;
        }
                
        function ActualizarDetalleTransferenciaAlmacen($data) {          
          $resultado = $this->mapper->map($data,$this->DetalleTransferenciaAlmacen);
          $this->db->where('IdDetalleTransferenciaAlmacen', $data["IdDetalleTransferenciaAlmacen"]);          
          $this->db->update('DetalleTransferenciaAlmacen', $resultado);
          
          return $resultado;
        }
 }

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDetalleNotaEntrada extends CI_Model {

        public $DetalleNotaEntrada = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->DetalleNotaEntrada = $this->Base->Construir("DetalleNotaEntrada");
        }

        function ConsultarDetallesNotaEntrada($data)
        {
          $id = $data["IdNotaEntrada"];

          $query = $this->db->query("Select DNE.*,UM.AbreviaturaUnidadMedida,UM.CodigoUnidadMedidaSunat as CodigoUnidadMedida,
                                    M.CodigoMercaderia, M.IdTipoAfectacionIGV, M.IdTipoSistemaISC, P.NombreProducto, 
                                    TAI.CodigoTipoAfectacionIGV, TSI.CodigoTipoSistemaISC, TP.CodigoTipoPrecio
                                    From DetalleNotaEntrada As DNE
                                    Inner Join Mercaderia As M on M.IdProducto=DNE.IdProducto
                                    Inner Join Producto As P on P.IdProducto=M.IdProducto
                                    Inner Join UnidadMedida As UM on M.IdUnidadMedida=UM.IdUnidadMedida
                                    left Join TipoSistemaISC As TSI on TSI.IdTipoSistemaISC = M.IdTipoSistemaISC
                                    left Join TipoAfectacionIGV as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
                                    left Join TipoPrecio as TP on TP.IdTipoPrecio = M.IdTipoPrecio
                                    Where DNE.IndicadorEstado = 'A' and DNE.IdNotaEntrada = '$id'");
          $resultado = $query->result_array();

          return $resultado;
        }

        function TotalProductosVendido()
        {
          $query = $this->db->query("Select count(DISTINCT IdProducto) as total
                                    From detallecomprobanteventa
                                    Where IndicadorEstado = 'A' or IndicadorEstado = 'N'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarDetalleNotaEntrada($data)
        {
           $data["UsuarioRegistro"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
           $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
           $data["IndicadorEstado"]=ESTADO_ACTIVO;
           $resultado = $this->mapper->map($data,$this->DetalleNotaEntrada);
           $this->db->insert('DetalleNotaEntrada', $resultado);
           $resultado = $this->db->insert_id();
           return($resultado);
        }

        function BorrarDetalleNotaEntrada($IdDetalleNotaEntrada)
        {
          $this->db->where("IdDetalleNotaEntrada",$IdDetalleNotaEntrada);
          $this->db->delete("DetalleNotaEntrada");
        }

        function BorrarDetalleNotaEntradaPorIdNotaEntrada($IdNotaEntrada)
        {
          $this->db->where("IdNotaEntrada",$IdNotaEntrada);
          $this->db->delete("DetalleNotaEntrada");
        }

        function BorrarDetalleNotaEntradaPorIdNotaEntradaEstado($IdNotaEntrada)
        {
          $query = $this->db->query("UPDATE DetalleNotaEntrada SET IndicadorEstado = 'E'
                                    Where IdNotaEntrada = '$IdNotaEntrada'");
          return "";
        }

 }

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mProductoProveedor extends CI_Model {

        public $ProductoProveedor = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->ProductoProveedor = $this->Base->Construir("ProductoProveedor");
        }

        function ConsultarProductoProveedorPorIdProducto($data) {

          $IdProducto = $data["IdProducto"];
          $query = $this->db->query("Select *
                                    from ProductoProveedor as CPP
                                    where CPP.IndicadorEstado = 'A' and CPP.IdProducto = '$IdProducto'
                                    ORDER BY(CPP.IdProducto)");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarProductoProveedorPorIdProductoAndIdProveedor($data) {

          $IdProducto = $data["IdProducto"];
          $IdProveedor = $data["IdProveedor"];
          $query = $this->db->query("Select *
                                    from ProductoProveedor as CPP
                                    where CPP.IndicadorEstado = 'A' and (CPP.IdProveedor = '$IdProveedor' and CPP.IdProducto = '$IdProducto')
                                    ORDER BY(CPP.IdProducto)");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarCodigoProductoProveedorParaInsertar($data)
        {
          $CodigoProductoProveedor = $data["CodigoProductoProveedor"];
          $IdProveedor = $data["IdProveedor"];
          $query = $this->db->query("Select *
                                    from productoproveedor as pp
                                    where pp.CodigoProductoProveedor = '$CodigoProductoProveedor' and pp.IdProveedor = '$IdProveedor' and pp.IndicadorEstado =  'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarCodigoProductoProveedorParaActualizar($data)
        {
          $CodigoProductoProveedor = $data["CodigoProductoProveedor"];
          $IdProveedor = $data["IdProveedor"];
          $IdProductoProveedor = $data["IdProductoProveedor"];
          $query = $this->db->query("Select *
                                    from productoproveedor as pp
                                    where (pp.IdProductoProveedor <> '$IdProductoProveedor' ) and (pp.CodigoProductoProveedor = '$CodigoProductoProveedor' and pp.IdProveedor = '$IdProveedor' and pp.IndicadorEstado =  'A')");
          $resultado = $query->result_array();
          return $resultado;
        }


        function InsertarProductoProveedor($data)
        {
          $data["IndicadorEstado"] = ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->ProductoProveedor);
          $this->db->insert('ProductoProveedor', $resultado);
          $resultado["IdProductoProveedor"] = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarProductoProveedor($data)
        {
          $id=$data["IdProductoProveedor"];
          $data["IndicadorEstado"] = ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->ProductoProveedor);
          $this->db->where('IdProductoProveedor', $id);
          $this->db->update('ProductoProveedor', $resultado);
        }


}

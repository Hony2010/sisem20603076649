<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sProductoProveedor extends MY_Service {

      public $ProductoProveedor = array();

      public function __construct()
      {
            parent::__construct();
            $this->load->database();
            $this->load->model("Base");
            $this->load->library('shared');
            $this->load->library('mapper');
            $this->load->library('json');
            $this->load->model('Catalogo/mProductoProveedor');
            $this->load->service("Configuracion/Catalogo/sTipoListaPrecio");
            $this->load->service("Configuracion/Catalogo/sFamiliaProducto");
            $this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
            $this->load->service("Configuracion/Catalogo/sMarca");
            $this->load->service("Configuracion/Catalogo/sModelo");
            $this->load->service("Configuracion/Catalogo/sLineaProducto");
            $this->load->service("Catalogo/sMercaderia");

            $this->ProductoProveedor = $this->mProductoProveedor->ProductoProveedor;
      }

      function Inicializar()
      {

      }

      function ConsultarCodigoProductoProveedorParaInsertar($data)
      {
        $resultado = $this->mProductoProveedor->ConsultarCodigoProductoProveedorParaInsertar($data);
        return $resultado;
      }

      function ConsultarCodigoProductoProveedorParaActualizar($data)
      {
        $resultado = $this->mProductoProveedor->ConsultarCodigoProductoProveedorParaActualizar($data);
        return $resultado;
      }


      function ConsultarProductoProveedorPorIdProductoAndIdProveedor($data)
      {
        $resultado = $this->mProductoProveedor->ConsultarProductoProveedorPorIdProductoAndIdProveedor($data);
        return $resultado;
      }

      function ConsultarProductoProveedorPorIdProducto($data)
      {
        $resultado = $this->mProductoProveedor->ConsultarProductoProveedorPorIdProducto($data);
        $response = array();
        if(count($resultado) > 0)
        {
          foreach ($resultado as $key => $value) {
            $lista["IdProveedor"] = $value["IdProveedor"];
            $lista["IdProductoProveedor"] = $value["IdProductoProveedor"];
            $lista["CodigoProductoProveedor"] = $value["CodigoProductoProveedor"];
            array_push($response, $lista);
          }

        }
        return $response;
      }

      function ValidarProductoProveedor($data)
      {
          $resultadof = [];
          $resultado = "";
          $item = 0;
          foreach ($data["DetallesComprobanteCompra"] as $key => $value) {
            if ($value["CodigoProductoProveedorSave"] == 1) {
              $value["IdProveedor"] = $data["IdProveedor"];
              if ($value["IdProductoProveedor"] == "") {
                $validarInsertar = $this->ConsultarCodigoProductoProveedorParaInsertar($value);
                if (count($validarInsertar) > 0) {
                  $item++;
                  $resultado.= '';//'El código "'.$value['CodigoProductoProveedor'].'" del Item '.$item.'° ya fue registrado con el mismo proveedor </br>';
                }
              }
              else {
                $validarActualizar = $this->ConsultarCodigoProductoProveedorParaActualizar($value);
                if (count($validarActualizar) > 0) {
                  $item++;
                  $resultado.= '';//'El código "'.$value['CodigoProductoProveedor'].'" del Item '.$item.'° ya fue registrado con el mismo proveedor </br>';
                }
              }
            }
          }
          if (is_string($resultado)) {
            return $resultado;
          }
          else {
            return "";
          }
      }

      function InsertarProductoProveedor($data) {
        foreach ($data["DetallesComprobanteCompra"] as $key => $value) {
          $resultado = "";
          if ($value["CodigoProductoProveedorSave"] == 1) {
            $value["IdProveedor"] = $data["IdProveedor"];
            if ($value["IdProductoProveedor"] == "") {
              $resultado = $this->mProductoProveedor->InsertarProductoProveedor($value);
            }
            else {
              $resultado = $this->mProductoProveedor->ActualizarProductoProveedor($value);
            }
          }
        }
        
        return $resultado;
      }

      function ActualizarProductoProveedor($data) {
        $resultado = $this->mProductoProveedor->ActualizarProductoProveedor($data);
        return $resultado;
      }
}

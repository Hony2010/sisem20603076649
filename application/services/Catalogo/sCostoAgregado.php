<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCostoAgregado extends MY_Service {

        public $CostoAgregado = array();
        public $producto = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->library('herencia');
              $this->load->library('archivo');
              $this->load->library('jsonconverter');
              $this->load->model('Catalogo/mCostoAgregado');
              $this->load->service('Catalogo/sProducto');
              $this->CostoAgregado = $this->mCostoAgregado->CostoAgregado;
              $this->Producto = $this->sProducto->Producto;
              $this->CostoAgregado = $this->herencia->Heredar($this->Producto,$this->CostoAgregado);
        }

        function ListarCostosAgregado()
        {
          $resultado = $this->mCostoAgregado->ListarCostosAgregado();
          return $resultado;
        }

        function ValidarCostoAgregado($data)
        {
          $resultado= $this->sProducto->ValidarNombreProducto($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            return "";
          }
        }

        function InsertarCostoAgregado($data)
        {
          //$data["NombreProducto"] = trim($data["NombreProducto"]);
          $resultado = $this -> ValidarCostoAgregado($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $producto = $this->sProducto->InsertarProducto($data);

            if(is_string($producto) && $producto != "")
            {
              return $producto;
            }
            else
            {
              $data["IdProducto"] = $producto["IdProducto"];
              $resultado = $this->mCostoAgregado->InsertarCostoAgregado($data);

              return $producto;
            }
          }
        }

        function ActualizarCostoAgregado($data)
        {
          $data["NombreProducto"] = trim($data["NombreProducto"]);
          $resultado = $this -> ValidarCostoAgregado($data);

          if ($resultado!="")
          {
            return $resultado;
          }
          else
          {
            $data['IndicadorEstado'] = ESTADO_ACTIVO;
            $producto = $this->sProducto->ActualizarProducto($data);
            if(is_string($producto) && $producto != "")
            {
              return $producto;
            }
            else
            {
              $this->mCostoAgregado->ActualizarCostoAgregado($data);
              return "";
            }
          }
        }

        function ObtenerCostoAgregadoPorIdProducto($data)
        {
              $resultado=$this->mCostoAgregado->ObtenerCostoAgregadoPorIdProducto($data);
              return $resultado;
        }

        function BorrarCostoAgregado($data)
        {
          $resultadocompra= $this->sProducto->ValidarExistenciaProductoEnDetalleComprobanteCompra($data);
          if ($resultadocompra != "") {
            return $resultadocompra ;
          }
          else
          {
            $resultado= $this->sProducto->BorrarProducto($data);
            return "";
          }
        }

        function ConsultarCostosAgregado($data)
        {
          $resultado=$this->mCostoAgregado->ConsultarCostosAgregado($data);
          return $resultado;
        }

        function PrepararDataJSONCostoAgregado()
        {
          $response = array();
          $costosagregado = $this->mCostoAgregado->ConsultarCostoAgregadoParaJSON();
          foreach ($costosagregado as $key => $value) {
            $nueva_fila = Array (
    					"IdProducto" => $value["IdProducto"],
    					"NombreProducto" => $value["NombreProducto"]
    				);

            array_push($response, $nueva_fila);
          }

          return $response;
        }

        function CrearJSONCostoAgregadoTodos()
        {
          //PARA CREAR EL JSON Gasto
          $url = DIR_ROOT_ASSETS.'/data/costoagregado/costosagregados.json';
          $data_json = $this->PrepararDataJSONCostoAgregado();
          $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);

          return $resultado;
        }


        //PARA EL TRADADO DEL JSONH
        function PreparaDataFilaCostoAgregado($data)
        {
          $nueva_fila = Array (
            "IdProducto" => $data["IdProducto"],
            "NombreProducto" => $data["NombreProducto"]
          );

          return $nueva_fila;
        }

        function InsertarJSONDesdeCostoAgregado($data)
        {
          $url = DIR_ROOT_ASSETS.'/data/costoagregado/costosagregados.json';
          $nueva_fila = $this->PreparaDataFilaCostoAgregado($data);
          $resultado2 = $this->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $nueva_fila);
          return $resultado2;
        }

        function ActualizarJSONDesdeCostoAgregado($data)
        {
          $url = DIR_ROOT_ASSETS.'/data/costoagregado/costosagregados.json';
          $nueva_fila = $this->PreparaDataFilaCostoAgregado($data);
          $resultado2 = $this->jsonconverter->ActualizarFilaEnArchivoJSON($url, $nueva_fila, "IdProducto");

          return $resultado2;
        }

        function BorrarJSONDesdeCostoAgregado($data)
        {
          $url = DIR_ROOT_ASSETS.'/data/costoagregado/costosagregados.json';
          $resultado = $this->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdProducto");

          return $resultado;
        }


}

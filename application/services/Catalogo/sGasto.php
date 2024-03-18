<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sGasto extends MY_Service {

        public $Gasto = array();
        public $Producto = array();

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
              $this->load->model('Catalogo/mGasto');
              $this->load->service('Catalogo/sProducto');
              //$this->load->model('Catalogo/mProducto');
              $this->load->service('Configuracion/General/sParametroSistema');
              $this->Gasto = $this->mGasto->Gasto;
              $this->Producto = $this->sProducto->Producto;
              $this->Gasto = $this->herencia->Heredar($this->Producto,$this->Gasto);
        }

        function ObtenerNumeroFila()
        {
          $resultado=$this->mGasto->ObtenerNumeroFila();
          $total=$resultado[0]['NumeroFila'];
          return $total;;
        }

        function ObtenerNumeroFilaPorConsultaGasto($data)
        {
          $resultado=$this->mGasto->ObtenerNumeroFilaPorConsultaGasto($data);
          $total=$resultado[0]['NumeroFila'];
          return $total;
        }

        function ObtenerGastoPorIdProducto($data)
        {
              $resultado=$this->mGasto->ObtenerGastoPorIdProducto($data);
              return $resultado;
        }

        function ObtenerNumeroPagina()
        {
          $data['IdParametroSistema']= ID_NUM_POR_PAGINA_GASTO;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
            $total = $this->ObtenerNumeroFila();
            $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
            if (($total%$ValorParametroSistema)>0)
            {
              $numeropagina = ($total/$ValorParametroSistema)+1;
              return intval($numeropagina);
            }
            else
            {
              $numeropagina = ($total/$ValorParametroSistema);
              return intval($numeropagina);
            }
          }
        }

        function ObtenerNumeroPaginaPorConsultaGasto($data)
        {
          $data['IdParametroSistema']= ID_NUM_POR_PAGINA_GASTO;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
            $total = $this->ObtenerNumeroFilaPorConsultaGasto($data);
            $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
            if (($total%$ValorParametroSistema)>0)
            {
              $numeropagina = ($total/$ValorParametroSistema)+1;
              return intval($numeropagina);
            }
            else
            {
              $numeropagina = ($total/$ValorParametroSistema);
              return intval($numeropagina);
            }
          }
        }


        function ListarGastos($pagina)
        {
          $data['IdParametroSistema']= ID_NUM_POR_PAGINA_GASTO;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
              $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
              $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;
              $resultado = $this->mGasto->ListarGastos($inicio,$ValorParametroSistema);
              return $resultado ;
          }
        }

        function ValidarGasto($data)
        {
            return "";
        }

        function InsertarGasto($data)
        {
          $data['NombreProducto'] = trim($data['NombreProducto']);
          $resultado = $this->ValidarGasto($data);

          if ($resultado != "")
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
              $resultado = $this->mGasto->InsertarGasto($data);

              return $producto;
            }
          }
        }

        function ActualizarGasto($data)
        {
          $resultado = $this->ValidarGasto($data);
          if ($resultado != "")
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
              $this->mGasto->ActualizarGasto($data);
              return "";
            }
          }
        }

        function BorrarGasto($data)
        {
          $resultadocompra= $this->sProducto->ValidarExistenciaProductoEnDetalleComprobanteCompra($data);
          if ($resultadocompra != "") {
            return $resultadocompra ;
          }
          else
          {
            $resultado= $this->sProducto->BorrarProducto($data);
            return $resultado;
          }
        }

        function ConsultarGastos($data,$pagina)
        {
          $data['IdParametroSistema']= ID_NUM_POR_PAGINA_GASTO;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
              $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
              $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;
              $resultado=$this->mGasto->ConsultarGastos($inicio,$ValorParametroSistema,$data);
              return $resultado;
          }
        }

        function PrepararDataJSONGasto()
        {
          $response = array();
          $gastos = $this->mGasto->ConsultarGastoParaJSON();
          foreach ($gastos as $key => $value) {
            $nueva_fila = Array (
    					"IdProducto" => $value["IdProducto"],
    					"NombreProducto" => $value["NombreProducto"]
    				);

            array_push($response, $nueva_fila);
          }

          return $response;
        }

        function CrearJSONGastoTodos()
        {
          //PARA CREAR EL JSON Gasto
          $url = DIR_ROOT_ASSETS.'/data/gasto/gastos.json';
          $data_json = $this->PrepararDataJSONGasto();
          $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);

          return $resultado;
        }

        //PARA EL TRADADO DEL JSONH
        function PreparaDataFilaGasto($data)
        {
          $nueva_fila = Array (
            "IdProducto" => $data["IdProducto"],
            "NombreProducto" => $data["NombreProducto"]
          );

          return $nueva_fila;
        }

        function InsertarJSONDesdeGasto($data)
        {
          $url = DIR_ROOT_ASSETS.'/data/gasto/gastos.json';
          $nueva_fila = $this->PreparaDataFilaGasto($data);
          $resultado2 = $this->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $nueva_fila);
          return $resultado2;
        }

        function ActualizarJSONDesdeGasto($data)
        {
          $url = DIR_ROOT_ASSETS.'/data/gasto/gastos.json';
          $nueva_fila = $this->PreparaDataFilaGasto($data);
          // print_r($nueva_fila);exit;
          $resultado2 = $this->jsonconverter->ActualizarFilaEnArchivoJSON($url, $nueva_fila, "IdProducto");

          return $resultado2;
        }

        function BorrarJSONDesdeGasto($data)
        {
          $url = DIR_ROOT_ASSETS.'/data/gasto/gastos.json';
          $resultado = $this->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdProducto");

          return $resultado;
        }

}

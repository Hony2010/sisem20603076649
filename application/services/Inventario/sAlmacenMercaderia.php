<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAlmacenMercaderia extends MY_Service {

        public $AlmacenMercaderia = array();

        public function __construct()
        {
          parent::__construct();
          $this->load->database();
          $this->load->library('shared');
          $this->load->library('sesionusuario');
          $this->load->library('mapper');
          $this->load->library('herencia');
          $this->load->library('reporter');
          $this->load->library('imprimir');
          $this->load->helper("date");
          $this->load->model("Base");
          $this->load->model('Inventario/mAlmacenMercaderia');
          $this->load->model('Catalogo/mMercaderia');

          $this->AlmacenMercaderia = $this->mAlmacenMercaderia->AlmacenMercaderia;
        }

        function Cargar() {

        }

        function ObtenerAlmacenMercaderiaPorProductoAlmacen($data)
        {
          $productoalmacen = $this->mAlmacenMercaderia->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);
          return $productoalmacen;
        }

        function AgregarAlmacenMercaderiaNotaSalida($data)
        {
          $productoalmacen = $this->mAlmacenMercaderia->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);

          if(count($productoalmacen)>0)
          {
            $nuevacantidad = (str_replace(',',"",$productoalmacen[0]["StockMercaderia"])) - (str_replace(',',"",$data["CantidadSalida"]));
            $nueva_data["IdAlmacenMercaderia"] = $productoalmacen[0]["IdAlmacenMercaderia"];
            $nueva_data["StockMercaderia"] = $nuevacantidad;
            $actualizacion = $this->ActualizarAlmacenMercaderia($nueva_data);
            return $actualizacion;
          }
          else {
            $nuevacantidad = 0 - $data["CantidadSalida"];
            $data["StockMercaderia"] = $nuevacantidad;

            $insercion = $this->InsertarAlmacenMercaderia($data);
            return $insercion;
          }

        }

        function ActualizarAlmacenMercaderiaInventarioInicial($data)
        {
          $productoalmacen = $this->mAlmacenMercaderia->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);
          if(count($productoalmacen)>0)
          {
            $nuevacantidad = $productoalmacen[0]["StockMercaderia"] - $data["CantidadEntrada"];
            $nueva_data["IdAlmacenMercaderia"] = $productoalmacen[0]["IdAlmacenMercaderia"];
            $nueva_data["StockMercaderia"] = $nuevacantidad;
            $actualizacion = $this->ActualizarAlmacenMercaderia($nueva_data);
          }
        }

        function ActualizarAlmacenMercaderiaNotaEntrada($data)
        {
          $productoalmacen = $this->mAlmacenMercaderia->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);
          if(count($productoalmacen)>0)
          {
            $nuevacantidad = $productoalmacen[0]["StockMercaderia"] - $data["CantidadEntrada"];
            $nueva_data["IdAlmacenMercaderia"] = $productoalmacen[0]["IdAlmacenMercaderia"];
            $nueva_data["StockMercaderia"] = $nuevacantidad;
            $actualizacion = $this->ActualizarAlmacenMercaderia($nueva_data);
          }
        }

        function ActualizarAlmacenMercaderiaNotaSalida($data)
        {
          $productoalmacen = $this->mAlmacenMercaderia->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);
          if(count($productoalmacen)>0)
          {
            $nuevacantidad = $productoalmacen[0]["StockMercaderia"] + $data["CantidadSalida"];
            $nueva_data["IdAlmacenMercaderia"] = $productoalmacen[0]["IdAlmacenMercaderia"];
            $nueva_data["StockMercaderia"] = $nuevacantidad;
            $actualizacion = $this->ActualizarAlmacenMercaderia($nueva_data);
          }
        }

        function AgregarAlmacenMercaderiaNotaEntrada($data)
        {
          $productoalmacen = $this->mAlmacenMercaderia->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);

          if(count($productoalmacen)>0)
          {
            $nuevacantidad = $productoalmacen[0]["StockMercaderia"] + $data["CantidadEntrada"];
            $nueva_data["IdAlmacenMercaderia"] = $productoalmacen[0]["IdAlmacenMercaderia"];
            $nueva_data["StockMercaderia"] = $nuevacantidad;
            $actualizacion = $this->ActualizarAlmacenMercaderia($nueva_data);
            return $actualizacion;
          }
          else {
            $nuevacantidad = 0 + $data["CantidadEntrada"];
            $data["StockMercaderia"] = $nuevacantidad;

            $insercion = $this->InsertarAlmacenMercaderia($data);
            return $insercion;
          }
        }

        function AgregarAlmacenMercaderiaInventarioInicial($data)
        {
          $productoalmacen = $this->mAlmacenMercaderia->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);

          if(count($productoalmacen)>0)
          {
            $nuevacantidad = $productoalmacen[0]["StockMercaderia"] + ($data["CantidadInicial"] - $data["AnteriorInicial"]);
            $nueva_data["IdAlmacenMercaderia"] = $productoalmacen[0]["IdAlmacenMercaderia"];
            $nueva_data["StockMercaderia"] = $nuevacantidad;
            $actualizacion = $this->ActualizarAlmacenMercaderia($nueva_data);
            return $actualizacion;
          }
          else {
            $nuevacantidad = 0 + $data["CantidadInicial"] - $data["AnteriorInicial"];
            $data["StockMercaderia"] = $nuevacantidad;

            $insercion = $this->InsertarAlmacenMercaderia($data);
            return $insercion;
          }
        }

        function InsertarAlmacenMercaderia($data) {
          try {
            $resultadoValidacion = "";

            if($resultadoValidacion == "") {
              $resultado= $this->mAlmacenMercaderia->InsertarAlmacenMercaderia($data);
              return $resultado;
            }
            else {
              $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
              return $resultado;
            }
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function ActualizarAlmacenMercaderia($data)
        {
          try {
            $resultadoValidacion = "";

            if($resultadoValidacion == "")
            {
              $resultado=$this->mAlmacenMercaderia->ActualizarAlmacenMercaderia($data);

              return $resultado;
            }
            else
            {
              throw new Exception(nl2br($resultadoValidacion));
            }
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function BorrarAlmacenMercaderia($data)
        {
          $this->mAlmacenMercaderia->BorrarAlmacenMercaderia($data);
          return "";
        }

        function BorrarAlmacenMercaderiaPorIdProducto($data)
        {
          $this->mAlmacenMercaderia->BorrarAlmacenMercaderiaPorIdProducto($data);
          return "";
        }

        function InsertarInventarioInicialEnAlmacenMercaderia($data)
        {
          $inicial = $this->ValidarProductoInventarioInicial($data);
          if(is_array($inicial))
          {
            $salida = $this->ReordenarMovimientosAlmacenPoProducto($inicial);
            if($salida)
            {
              $salida["SaldoFisico"] = $salida["CantidadInicial"];
              $this->mMercaderia->ActualizarMercaderia($salida);
            }
            return $salida;
          }
          else {
            $resultado = $this->InsertarAlmacenMercaderiaInventarioInicial($data);
            if($resultado)
            {
              $this->mMercaderia->ActualizarMercaderia($resultado);
            }
            return $resultado;
          }
        }


        function ProductosEnAlmacenMercaderia()
        {
          $resultado = $this->mAlmacenMercaderia->ProductosEnAlmacenMercaderia();
          return $resultado;
        }

        function SedesPorProductoEnAlmacenMercaderia($data)
        {
          $resultado = $this->mAlmacenMercaderia->SedesPorProductoEnAlmacenMercaderia($data);
          return $resultado;
        }

        function ValidarProductoInventarioInicial($data)
        {
          $resultado = $this->mAlmacenMercaderia->ConsularProductoInventarioInicial($data);
          if(count($resultado) > 0)
          {
            $this->BorrarAlmacenMercaderia($resultado[0]);
            $nuevo = $this->InsertarAlmacenMercaderiaInventarioInicial($data);
            $data["IdAlmacenMercaderia"] = $nuevo["IdAlmacenMercaderia"];
            return $data;
          }
          else {
            // code...
            return "";
          }
        }

        function ConsultarListasStockPorIdProducto($data) {
          $resultado = $this->mAlmacenMercaderia->ConsultarListasStockPorIdProducto($data);
          return $resultado;
        }


        function AumentarStockAlmacenMercaderia($data) {
          $resultadoAlmacenMercaderia = $this->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);
          
          if(count($resultadoAlmacenMercaderia) > 0) {
            $dataAlmacenMercaderia["IdAlmacenMercaderia"] = $resultadoAlmacenMercaderia[0]["IdAlmacenMercaderia"];            
            $dataAlmacenMercaderia["StockMercaderia"] = $resultadoAlmacenMercaderia[0]["StockMercaderia"] + $data["Cantidad"];
            $resultado = $this->ActualizarAlmacenMercaderia($dataAlmacenMercaderia);
          }
          else {
            $dataAlmacenMercaderia=array_merge($this->AlmacenMercaderia,$data);
            $dataAlmacenMercaderia["StockMercaderia"]=$data["Cantidad"];            
            $resultado = $this->InsertarAlmacenMercaderia($dataAlmacenMercaderia);
          }

          return $resultado;
        }

        function DisminuirStockAlmacenMercaderia($data) {
          $resultadoAlmacenMercaderia = $this->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);

          if(count($resultadoAlmacenMercaderia) > 0) {
            $dataAlmacenMercaderia["IdAlmacenMercaderia"] = $resultadoAlmacenMercaderia[0]["IdAlmacenMercaderia"];
            $dataAlmacenMercaderia["StockMercaderia"] = $resultadoAlmacenMercaderia[0]["StockMercaderia"] - $data["Cantidad"];            
            $resultado = $this->ActualizarAlmacenMercaderia($dataAlmacenMercaderia);
          }
          else {
            $dataAlmacenMercaderia=array_merge($this->AlmacenMercaderia,$data);          
            $dataAlmacenMercaderia["StockMercaderia"]=$data["Cantidad"];
            $resultado = $this->InsertarAlmacenMercaderia($dataAlmacenMercaderia);
          }

          return $resultado;
        }

        function AperturarStockAlmacenMercaderia($data) {
          $resultadoAlmacenMercaderia = $this->ObtenerAlmacenMercaderiaPorProductoAlmacen($data);
          
          if(count($resultadoAlmacenMercaderia) > 0) {
            // $dataAlmacenMercaderia["IdAlmacenMercaderia"] = $resultadoAlmacenMercaderia[0]["IdAlmacenMercaderia"];            
            // $dataAlmacenMercaderia["StockMercaderia"] = $resultadoAlmacenMercaderia[0]["StockMercaderia"] + $data["Cantidad"];
            // $resultado = $this->ActualizarAlmacenMercaderia($dataAlmacenMercaderia);
            $resultado=array();
          }
          else {
            $dataAlmacenMercaderia=array_merge($this->AlmacenMercaderia,$data);
            $dataAlmacenMercaderia["StockMercaderia"]=$data["Cantidad"];            
            $resultado = $this->InsertarAlmacenMercaderia($dataAlmacenMercaderia);
          }

          return $resultado;
        }

}

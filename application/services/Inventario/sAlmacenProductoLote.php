<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAlmacenProductoLote extends MY_Service {

        public $AlmacenProductoLote = array();

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
          $this->load->model('Inventario/mAlmacenProductoLote');
          $this->load->model('Catalogo/mMercaderia');

          $this->AlmacenProductoLote = $this->mAlmacenProductoLote->AlmacenProductoLote;
        }

        function Cargar()
        {

        }

        // function BorrarAlmacenProductoLoteNotaSalida($data)
        // {
        //   $productoalmacen = $this->mAlmacenProductoLote->ObtenerAlmacenProductoLotePorProductoAlmacen($data);
        //
        //   if(count($productoalmacen)>0)
        //   {
        //     $nuevacantidad = $productoalmacen[0]["StockProductoLote"] + $data["CantidadSalida"];
        //     $nueva_data["IdAlmacenProductoLote"] = $productoalmacen[0]["IdAlmacenProductoLote"];
        //     $nueva_data["StockProductoLote"] = $nuevacantidad;
        //     $actualizacion = $this->ActualizarAlmacenProductoLote($nueva_data);
        //     return $actualizacion;
        //   }
        // }
        //
        // function BorrarAlmacenProductoLoteNotaEntrada($data)
        // {
        //   $productoalmacen = $this->mAlmacenProductoLote->ObtenerAlmacenProductoLotePorProductoAlmacen($data);
        //
        //   if(count($productoalmacen)>0)
        //   {
        //     $nuevacantidad = $productoalmacen[0]["StockProductoLote"] - $data["CantidadEntrada"];
        //     $nueva_data["IdAlmacenProductoLote"] = $productoalmacen[0]["IdAlmacenProductoLote"];
        //     $nueva_data["StockProductoLote"] = $nuevacantidad;
        //     $actualizacion = $this->ActualizarAlmacenProductoLote($nueva_data);
        //     return $actualizacion;
        //   }
        // }

        function AgregarAlmacenProductoLoteNotaSalida($data)
        {
          $productoalmacen = $this->mAlmacenProductoLote->ObtenerAlmacenProductoLotePorProductoAlmacen($data);

          if(count($productoalmacen)>0)
          {
            $nuevacantidad = $productoalmacen[0]["StockProductoLote"] - $data["CantidadSalida"];
            $nueva_data["IdAlmacenProductoLote"] = $productoalmacen[0]["IdAlmacenProductoLote"];
            $nueva_data["StockProductoLote"] = $nuevacantidad;
            $actualizacion = $this->ActualizarAlmacenProductoLote($nueva_data);
            return $actualizacion;
          }
          else {
            $nuevacantidad = 0 - $data["CantidadSalida"];
            $data["StockProductoLote"] = $nuevacantidad;

            $insercion = $this->InsertarAlmacenProductoLote($data);
            return $insercion;
          }

        }

        /*Se normaliza para inventario inicial*/
        function ActualizarAlmacenProductoLoteInventarioInicial($data)
        {
          $productoalmacen = $this->mAlmacenProductoLote->ObtenerAlmacenProductoLotePorProductoAlmacen($data);

          if(count($productoalmacen)>0)
          {
            $nuevacantidad = $productoalmacen[0]["StockProductoLote"] - $data["CantidadEntrada"];
            $nueva_data["IdAlmacenProductoLote"] = $productoalmacen[0]["IdAlmacenProductoLote"];
            $nueva_data["StockProductoLote"] = $nuevacantidad;
            $actualizacion = $this->ActualizarAlmacenProductoLote($nueva_data);
          }
        }
        /*Fin Normalizacion*/

        /*Se normaliza para nota entrada*/
        function ActualizarAlmacenProductoLoteNotaEntrada($data)
        {
          $productoalmacen = $this->mAlmacenProductoLote->ObtenerAlmacenProductoLotePorProductoAlmacen($data);

          if(count($productoalmacen)>0)
          {
            $nuevacantidad = $productoalmacen[0]["StockProductoLote"] - $data["CantidadEntrada"];
            $nueva_data["IdAlmacenProductoLote"] = $productoalmacen[0]["IdAlmacenProductoLote"];
            $nueva_data["StockProductoLote"] = $nuevacantidad;
            $actualizacion = $this->ActualizarAlmacenProductoLote($nueva_data);
          }
        }
        /*Fin Normalizacion*/

        function ActualizarAlmacenProductoLoteNotaSalida($data)
        {
          $productoalmacen = $this->mAlmacenProductoLote->ObtenerAlmacenProductoLotePorProductoAlmacen($data);
          if(count($productoalmacen)>0)
          {
            $nuevacantidad = $productoalmacen[0]["StockProductoLote"] + $data["CantidadSalida"];
            $nueva_data["IdAlmacenProductoLote"] = $productoalmacen[0]["IdAlmacenProductoLote"];
            $nueva_data["StockProductoLote"] = $nuevacantidad;
            $actualizacion = $this->ActualizarAlmacenProductoLote($nueva_data);
          }
        }

        /*Se normaliza para Nota Entrada*/
        function AgregarAlmacenProductoLoteNotaEntrada($data)
        {
          $productoalmacen = $this->mAlmacenProductoLote->ObtenerAlmacenProductoLotePorProductoAlmacen($data);

          if(count($productoalmacen)>0)
          {
            $nuevacantidad = $productoalmacen[0]["StockProductoLote"] + $data["CantidadEntrada"];
            $nueva_data["IdAlmacenProductoLote"] = $productoalmacen[0]["IdAlmacenProductoLote"];
            $nueva_data["StockProductoLote"] = $nuevacantidad;

            $actualizacion = $this->ActualizarAlmacenProductoLote($nueva_data);
            $actualizacion["Estado"] = 1; //Actualizacion
            return $actualizacion;
          }
          else {
            $nuevacantidad = 0 + $data["CantidadEntrada"];
            $data["StockProductoLote"] = $nuevacantidad;

            $insercion = $this->InsertarAlmacenProductoLote($data);
            $insercion["Estado"] = 0; //Insercion
            return $insercion;
          }
        }
        /*Fin Normalizacion*/

        /*Se normaliza para inventario inicial*/
        function AgregarAlmacenProductoLoteInventarioInicial($data)
        {
          $productoalmacen = $this->mAlmacenProductoLote->ObtenerAlmacenProductoLotePorProductoAlmacen($data);

          if(count($productoalmacen)>0)
          {
            $nuevacantidad = $productoalmacen[0]["StockProductoLote"] + ($data["CantidadInicial"] - $data["AnteriorInicial"]);
            $nueva_data["IdAlmacenProductoLote"] = $productoalmacen[0]["IdAlmacenProductoLote"];
            $nueva_data["StockProductoLote"] = $nuevacantidad;
            $actualizacion = $this->ActualizarAlmacenProductoLote($nueva_data);
            return $actualizacion;
          }
          else {
            $nuevacantidad = 0 + $data["CantidadInicial"] - $data["AnteriorInicial"];
            $data["StockProductoLote"] = $nuevacantidad;

            $insercion = $this->InsertarAlmacenProductoLote($data);
            return $insercion;
          }
        }
        /*fin inventario inicial*/

        function InsertarAlmacenProductoLote($data)
        {
          try {

            $resultadoValidacion = "";

            if($resultadoValidacion == "")
            {
              $resultado= $this->mAlmacenProductoLote->InsertarAlmacenProductoLote($data);

              return $resultado;
            }
            else
            {
              $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
              return $resultado;
            }
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function ActualizarAlmacenProductoLote($data)
        {
          try {
            $resultadoValidacion = "";

            if($resultadoValidacion == "")
            {
              $resultado=$this->mAlmacenProductoLote->ActualizarAlmacenProductoLote($data);

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

        function BorrarAlmacenProductoLote($data)
        {
          $this->mAlmacenProductoLote->BorrarAlmacenProductoLote($data);

          return "";
        }

        // function BorrarMovimientosAlmacenNotaEntrada($data)
        // {
        //   $resultado = $this->mAlmacenProductoLote->ObtenerMovimientosPorNotaEntrada($data);
        //   if(count($resultado) > 0)
        //   {
        //     foreach ($resultado as $key => $value) {
        //       // code...
        //       $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);
        //
        //       $value["SaldoFisico"] = $mercaderia["SaldoFisico"] - $value["CantidadEntrada"];
        //       $actualizar = $this->mMercaderia->ActualizarMercaderia($value);
        //
        //       $data_movimiento["IdAlmacenProductoLote"] = $value["IdAlmacenProductoLote"];
        //       $this->mAlmacenProductoLote->BorrarAlmacenProductoLote($data_movimiento);
        //     }
        //   }
        //
        //   return $resultado;
        // }

        function BorrarMovimientosAlmacenNotaSalida($data)
        {
          $resultado = $this->mAlmacenProductoLote->ObtenerMovimientosPorNotaSalida($data);

          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              // code...
              $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);

              $value["SaldoFisico"] = $mercaderia["SaldoFisico"] + $value["CantidadSalida"];
              $actualizar = $this->mMercaderia->ActualizarMercaderia($value);

              $data_movimiento["IdAlmacenProductoLote"] = $value["IdAlmacenProductoLote"];
              $this->mAlmacenProductoLote->BorrarAlmacenProductoLote($data_movimiento);
            }
          }

          return $resultado;
        }

        function InsertarInventarioInicialEnAlmacenProductoLote($data)
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
            $resultado = $this->InsertarAlmacenProductoLoteInventarioInicial($data);
            if($resultado)
            {
              $this->mMercaderia->ActualizarMercaderia($resultado);
            }
            return $resultado;
          }
        }


        function ProductosEnAlmacenProductoLote()
        {
          $resultado = $this->mAlmacenProductoLote->ProductosEnAlmacenProductoLote();
          return $resultado;
        }

        function SedesPorProductoEnAlmacenProductoLote($data)
        {
          $resultado = $this->mAlmacenProductoLote->SedesPorProductoEnAlmacenProductoLote($data);
          return $resultado;
        }

        function ValidarProductoInventarioInicial($data)
        {
          $resultado = $this->mAlmacenProductoLote->ConsularProductoInventarioInicial($data);
          if(count($resultado) > 0)
          {
            $this->BorrarAlmacenProductoLote($resultado[0]);
            $nuevo = $this->InsertarAlmacenProductoLoteInventarioInicial($data);
            $data["IdAlmacenProductoLote"] = $nuevo["IdAlmacenProductoLote"];
            return $data;
          }
          else {
            // code...
            return "";
          }
        }

        function ConsultarListasLoteProductoPorIdProducto($data)
        {
          $resultado = $this->mAlmacenProductoLote->ConsultarListasLoteProductoPorIdProducto($data);
          $response = array();
          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              $lista["IdAlmacenProductoLote"] = $value["IdAlmacenProductoLote"];
              $lista["IdAsignacionSede"] = $value["IdAsignacionSede"];
              $lista["IdLoteProducto"] = $value["IdLoteProducto"];
              $lista["StockProductoLote"] = $value["StockProductoLote"];
              $lista["NumeroLote"] = $value["NumeroLote"];
              $lista["FechaVencimiento"] =$value["FechaVencimiento"] ==""?"": convertirFechaES($value["FechaVencimiento"]);
              array_push($response, $lista);
            }
          }
          return $response;
        }

}

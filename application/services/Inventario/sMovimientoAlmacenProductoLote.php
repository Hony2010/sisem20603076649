<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMovimientoAlmacenProductoLote extends MY_Service {

        public $MovimientoAlmacenProductoLote = array();

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
          $this->load->model('Inventario/mMovimientoAlmacenProductoLote');
          $this->load->model('Catalogo/mMercaderia');
          $this->load->service('Inventario/sAlmacenProductoLote');

          $this->MovimientoAlmacenProductoLote = $this->mMovimientoAlmacenProductoLote->MovimientoAlmacenProductoLote;
        }

        function Cargar()
        {

        }

        function InsertarMovimientoAlmacenProductoLoteNotaSalida($data)
        {
          if(is_string($data["Cantidad"])){$data["Cantidad"]= str_replace(',',"",$data["Cantidad"]);}
          $data["CantidadSalida"] = $data["Cantidad"];
          $output = $this->sAlmacenProductoLote->AgregarAlmacenProductoLoteNotaSalida($data);

          $data["SaldoFisico"] = $output["StockProductoLote"];

          $resultado = $this->InsertarMovimientoAlmacenProductoLote($data);

          return $resultado;
        }

        /*Se normaliza para nota entrada*/
        function InsertarMovimientoAlmacenProductoLoteNotaEntrada($data)
        {
          if(is_string($data["Cantidad"])){$data["Cantidad"]= str_replace(',',"",$data["Cantidad"]);}
          $data["CantidadEntrada"] = $data["Cantidad"];
          $output = $this->sAlmacenProductoLote->AgregarAlmacenProductoLoteNotaEntrada($data);

          $data["SaldoFisico"] = $output["StockProductoLote"];

          $resultado = $this->InsertarMovimientoAlmacenProductoLote($data);

          return $resultado;
        }
        /*Fin Normalizacion*/

        function InsertarMovimientoAlmacenProductoLoteInventarioInicial($data)
        {
          if(is_string($data["CantidadInicial"])){$data["CantidadInicial"]= str_replace(',',"",$data["CantidadInicial"]);}
          $data["FechaMovimiento"] = convertToDate($data["FechaInventario"]);
          $data["SaldoFisico"] =$data["CantidadInicial"];
          $data["CantidadEntrada"] = $data["CantidadInicial"];
          $resultado = $this->InsertarMovimientoAlmacenProductoLote($data);

          return $resultado;
        }



        function InsertarMovimientoAlmacenProductoLote($data)
        {
          try {
            $resultadoValidacion = "";

            if($resultadoValidacion == "")
            {
              $resultado= $this->mMovimientoAlmacenProductoLote->InsertarMovimientoAlmacenProductoLote($data);

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

        /*NOTA DE CREDITO*/
        // public function InsertarMovimientoAlmacenProductoLoteDesdeComprobante($data)
        // {
        //   foreach ($data["DetallesComprobanteVenta"] as $key => $value) {
        //     if(is_numeric($value["IdProducto"]))
        //     {
        //       $value["RazonSocial"] = $data["RazonSocial"];
        //       $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
        //       $value["NombreAlmacen"] = $data["NombreAlmacen"];
        //       $value["FechaEmision"] = $data["FechaEmision"];
        //       $value["IdNotaEntrada"] = $data["IdNotaEntrada"];
        //       $this->InsertarMovimientoAlmacenProductoLoteNotaEntrada($value);
        //     }
        //   }
        // }

        /*COSTO AGREGADO*/
        // public function ActualizarMovimientosAlmacenCostoAgregado($data)
        // {
        //   foreach ($data as $key => $value) {
        //       if(is_numeric($value["IdProducto"]))
        //       {
        //         $movimiento = $this->mMovimientoAlmacenProductoLote->ObtenerMovimientoAlmacenProductoLotePorNotaEntradaComprobanteCompra($value);
        //         if(count($movimiento)>0){
        //           $costounitarioagregado = $movimiento[0]["CostoUnitarioAgregado"] + $value["MontoProrrateadoPorUnidad"];
        //           $data_actualizar["IdMovimientoAlmacenProductoLote"] = $movimiento[0]["IdMovimientoAlmacenProductoLote"];
        //           $data_actualizar["CostoUnitarioAgregado"] = $costounitarioagregado;
        //           $this->ActualizarMovimientoAlmacenProductoLote($data_actualizar);
        //         }
        //       }
        //   }
        //
        // }

        function ActualizarMovimientoAlmacenProductoLote($data)
        {
          try {
            $resultadoValidacion = "";

            if($resultadoValidacion == "")
            {
              $resultado=$this->mMovimientoAlmacenProductoLote->ActualizarMovimientoAlmacenProductoLote($data);
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

        function BorrarMovimientoAlmacenProductoLote($data)
        {
          $this->mMovimientoAlmacenProductoLote->BorrarMovimientoAlmacenProductoLote($data);

          return "";
        }

        /*Se normaliza funcion para entrada*/
        function BorrarMovimientosAlmacenProductoLoteNotaEntrada($data)
        {
          $resultado = $this->mMovimientoAlmacenProductoLote->ObtenerMovimientosPorNotaEntrada($data);
          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              // code...
              // $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);
              // $value["SaldoFisico"] = $mercaderia["SaldoFisico"] - $value["CantidadEntrada"];
              // $actualizar = $this->mMercaderia->ActualizarMercaderia($value);

              $this->sAlmacenProductoLote->ActualizarAlmacenProductoLoteNotaEntrada($value);

              $data_movimiento["IdMovimientoAlmacenProductoLote"] = $value["IdMovimientoAlmacenProductoLote"];
              $this->mMovimientoAlmacenProductoLote->BorrarMovimientoAlmacenProductoLote($data_movimiento);
            }
          }

          return $resultado;
        }
        /*Fin Normalizacion*/

        /*Se Normaliza para inventario INICIAL*/
        function BorrarMovimientosAlmacenInventarioInicial($data)
        {
          $resultado = $this->mMovimientoAlmacenProductoLote->ObtenerMovimientosPorInventarioInicial($data);
          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              $this->sAlmacenProductoLote->ActualizarAlmacenProductoLoteInventarioInicial($value);

              $data_movimiento["IdMovimientoAlmacenProductoLote"] = $value["IdMovimientoAlmacenProductoLote"];
              $this->mMovimientoAlmacenProductoLote->BorrarMovimientoAlmacenProductoLote($data_movimiento);
            }
          }

          return $resultado;
        }
        /*FIN NORMALIZACION*/

        function BorrarMovimientosAlmacenNotaSalida($data)
        {
          $resultado = $this->mMovimientoAlmacenProductoLote->ObtenerMovimientosPorNotaSalida($data);

          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              // code...
              $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);

              $value["SaldoFisico"] = $mercaderia["SaldoFisico"] + $value["CantidadSalida"];
              $actualizar = $this->mMercaderia->ActualizarMercaderia($value);

              $this->sAlmacenProductoLote->ActualizarAlmacenProductoLoteNotaSalida($value);

              $data_movimiento["IdMovimientoAlmacenProductoLote"] = $value["IdMovimientoAlmacenProductoLote"];
              $this->mMovimientoAlmacenProductoLote->BorrarMovimientoAlmacenProductoLote($data_movimiento);
            }
          }

          return $resultado;
        }

        /*PARA INVENTARIO INICIAL*/
        function ValidarProductoInventarioInicial($data)
        {
          $resultado = $this->mMovimientoAlmacenProductoLote->ConsultarProductoAlmacenInventarioInicial($data);

          if(count($resultado) > 0)
          {
            // print_r($resultado);
            // exit;
            $this->BorrarMovimientoAlmacenProductoLote($resultado[0]);
            $nuevo = $this->InsertarMovimientoAlmacenProductoLoteInventarioInicial($data);
            $data["IdMovimientoAlmacenProductoLote"] = $nuevo["IdMovimientoAlmacenProductoLote"];
            $data["AnteriorInicial"] = $resultado[0]["CantidadEntrada"];
            return $data;
          }
          else {
            // code...
            return "";
          }
        }

        function InsertarInventarioInicialEnMovimientoAlmacenProductoLote($data)
        {
          $inicial = $this->ValidarProductoInventarioInicial($data);

          if(is_array($inicial))
          {

            // $salida = $this->ReordenarMovimientosAlmacenPorProducto($inicial);

            // if($salida)
            // {
              $data["AnteriorInicial"] = $inicial["AnteriorInicial"];
              $output = $this->sAlmacenProductoLote->AgregarAlmacenProductoLoteInventarioInicial($data);
              // $salida["SaldoFisico"] = $salida["CantidadInicial"];
              // $data["SaldoFisico"] = $salida;
            // }
            return $data;
          }
          else {
            $resultado = $this->InsertarMovimientoAlmacenProductoLoteInventarioInicial($data);

            if($resultado)
            {
              $resultado["CantidadInicial"] = $resultado["CantidadEntrada"];

              // $salida = $this->ReordenarMovimientosAlmacenPorProducto($resultado);
              // if($salida)
              // {
                $data["AnteriorInicial"] = 0;
                $output = $this->sAlmacenProductoLote->AgregarAlmacenProductoLoteInventarioInicial($data);

                // $resultado["SaldoFisico"] = $salida["CantidadInicial"];
                // $resultado["SaldoFisico"] = $salida;
              // }

            }
            return $resultado;
          }
        }
        /*FIN INVENTARIO INICIAL*/

        // function ReordenarMovimientosAlmacenPorProducto($data)
        // {
        //   $sedes = $this->mMovimientoAlmacenProductoLote->SedesPorProductoEnMovimientoAlmacenProductoLote($data);
        //   $cantidadtotal = 0;
        //   foreach ($sedes as $key => $value) {
        //     $data["IdAsignacionSede"] = $value["IdAsignacionSede"];
        //     $response = $this->mMovimientoAlmacenProductoLote->ObtenerMovimientosPorProductoSede($data);
        //     // $cantidadprevia = $value["Entrada"];
        //     $cantidadprevia = 0;
        //     foreach ($response as $key2 => $value2) {
        //       if(is_numeric($value2["CantidadEntrada"]))
        //       {
        //         $value2["SaldoFisico"] = $cantidadprevia + $value2["CantidadEntrada"];
        //       }
        //       else {
        //         $value2["SaldoFisico"] = $cantidadprevia - $value2["CantidadSalida"];
        //       }
        //       $cantidadprevia = $value2["SaldoFisico"];
        //       $this->ActualizarMovimientoAlmacenProductoLote($value2);
        //     }
        //
        //     $cantidadtotal += $cantidadprevia;
        //   }
        //
        //
        //   return $cantidadtotal;
        // }

        //Se recalculan los movimientos en almacen por cantidades
        // function CalcularCantidadesProductoPorMovimientoAlmacenProductoLote($data)
        // {
        //   $producto = $data["IdProducto"];
        //   $sede = $data["IdAsignacionSede"];
        //
        //   if(is_numeric($producto))
        //   {
        //     if(is_numeric($sede)){
        //       $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data);
        //     }
        //     else {
        //       $data1["IdProducto"] = $data["IdProducto"];
        //       $sedes = $this->SedesPorProductoEnMovimientoAlmacenProductoLote($data1);
        //       foreach ($sedes as $key2 => $value2) {
        //         $data1["IdAsignacionSede"] = $value2["IdAsignacionSede"];
        //         $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
        //       }
        //     }
        //   }
        //   else {
        //     $productos = $this->ProductosEnMovimientoAlmacenProductoLote();
        //     foreach ($productos as $key => $value) {
        //       if(is_numeric($sede)){
        //         $data1["IdProducto"] = $value["IdProducto"];
        //         $data1["IdAsignacionSede"] = $sede;
        //         $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
        //       }
        //       else {
        //         $data1["IdProducto"] = $value["IdProducto"];
        //         $sedes = $this->SedesPorProductoEnMovimientoAlmacenProductoLote($data1);
        //         foreach ($sedes as $key2 => $value2) {
        //           $data1["IdAsignacionSede"] = $value2["IdAsignacionSede"];
        //           $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
        //         }
        //       }
        //     }
        //   }
        //
        //   return "";
        // }

        // function RecalcularCantidadesMovimientosAlmacenPorProducto($data)
        // {
        //   $resultado = $this->mMovimientoAlmacenProductoLote->ObtenerMovimientosPorProductoSede($data);
        //
        //   $cantidadprevia = 0;
        //   foreach ($resultado as $key => $value) {
        //     if(is_numeric($value["CantidadEntrada"]))
        //     {
        //       $value["SaldoFisico"] = $cantidadprevia + $value["CantidadEntrada"];
        //     }
        //     else {
        //       $value["SaldoFisico"] = $cantidadprevia - $value["CantidadSalida"];
        //     }
        //     $cantidadprevia = $value["SaldoFisico"];
        //     $this->ActualizarMovimientoAlmacenProductoLote($value);
        //   }
        //
        //   return $cantidadprevia;
        // }

        function ProductosEnMovimientoAlmacenProductoLote()
        {
          $resultado = $this->mMovimientoAlmacenProductoLote->ProductosEnMovimientoAlmacenProductoLote();
          return $resultado;
        }

        function SedesPorProductoEnMovimientoAlmacenProductoLote($data)
        {
          $resultado = $this->mMovimientoAlmacenProductoLote->DocumentosPorProductoSedeEnMovimientoAlmacenProductoLote($data);
          return $resultado;
        }

        //FUNCION PARA ACTUALIZAR FECHAS
        function ActualizarFechaParaInventariosInicial($data)
        {
          $resultado = $this->mMovimientoAlmacenProductoLote->ActualizarFechaParaInventariosInicial($data);
          return $resultado;
        }

}

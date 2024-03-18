<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMovimientoDocumentoDua extends MY_Service {

        public $MovimientoDocumentoDua = array();

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
          $this->load->model('Inventario/mMovimientoDocumentoDua');
          $this->load->model('Catalogo/mMercaderia');
          $this->load->service('Inventario/sDuaProducto');

          $this->MovimientoDocumentoDua = $this->mMovimientoDocumentoDua->MovimientoDocumentoDua;
        }

        function Cargar()
        {

        }

        function InsertarMovimientoDocumentoDuaNotaSalida($data)
        {
          if(is_string($data["Cantidad"])){$data["Cantidad"]= str_replace(',',"",$data["Cantidad"]);}
          $data["CantidadSalida"] = $data["Cantidad"];
          $output = $this->sDuaProducto->AgregarDuaProductoNotaSalida($data);

          $data["SaldoFisico"] = $output["StockDua"];
          $data["IdDuaProducto"] = $output["IdDuaProducto"];
          $data["IdDua"] = $output["IdDua"];

          $resultado = $this->InsertarMovimientoDocumentoDua($data);

          return $resultado;
        }

        /*Se normaliza para nota entrada*/
        function InsertarMovimientoDocumentoDuaNotaEntrada($data)
        {
          if(is_string($data["Cantidad"])){$data["Cantidad"]= str_replace(',',"",$data["Cantidad"]);}
          $data["CantidadEntrada"] = $data["Cantidad"];
          $output = $this->sDuaProducto->AgregarDuaProductoNotaEntrada($data);

          $data["SaldoFisico"] = $output["StockDua"];
          $data["IdDuaProducto"] = $output["IdDuaProducto"];

          $resultado = $this->InsertarMovimientoDocumentoDua($data);

          return $resultado;
        }
        /*Fin Normalizacion*/

        function InsertarMovimientoDocumentoDuaInventarioInicial($data)
        {
          if(is_string($data["CantidadInicial"])){$data["CantidadInicial"]= str_replace(',',"",$data["CantidadInicial"]);}
          $data["FechaMovimiento"] = convertToDate($data["FechaInventario"]);
          $data["SaldoFisico"] =$data["CantidadInicial"];
          $data["CantidadEntrada"] = $data["CantidadInicial"];
          $resultado = $this->InsertarMovimientoDocumentoDua($data);

          return $resultado;
        }

        function InsertarMovimientoDocumentoDua($data)
        {
          try {
            $resultadoValidacion = "";

            if($resultadoValidacion == "")
            {
              $resultado= $this->mMovimientoDocumentoDua->InsertarMovimientoDocumentoDua($data);

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
        // public function InsertarMovimientoDocumentoDuaDesdeComprobante($data)
        // {
        //   foreach ($data["DetallesComprobanteVenta"] as $key => $value) {
        //     if(is_numeric($value["IdProducto"]))
        //     {
        //       $value["RazonSocial"] = $data["RazonSocial"];
        //       $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
        //       $value["NombreAlmacen"] = $data["NombreAlmacen"];
        //       $value["FechaEmision"] = $data["FechaEmision"];
        //       $value["IdNotaEntrada"] = $data["IdNotaEntrada"];
        //       $this->InsertarMovimientoDocumentoDuaNotaEntrada($value);
        //     }
        //   }
        // }

        /*COSTO AGREGADO*/
        // public function ActualizarMovimientosAlmacenCostoAgregado($data)
        // {
        //   foreach ($data as $key => $value) {
        //       if(is_numeric($value["IdProducto"]))
        //       {
        //         $movimiento = $this->mMovimientoDocumentoDua->ObtenerMovimientoDocumentoDuaPorNotaEntradaComprobanteCompra($value);
        //         if(count($movimiento)>0){
        //           $costounitarioagregado = $movimiento[0]["CostoUnitarioAgregado"] + $value["MontoProrrateadoPorUnidad"];
        //           $data_actualizar["IdMovimientoDocumentoDua"] = $movimiento[0]["IdMovimientoDocumentoDua"];
        //           $data_actualizar["CostoUnitarioAgregado"] = $costounitarioagregado;
        //           $this->ActualizarMovimientoDocumentoDua($data_actualizar);
        //         }
        //       }
        //   }
        //
        // }

        function ActualizarMovimientoDocumentoDua($data)
        {
          try {
            $resultadoValidacion = "";

            if($resultadoValidacion == "")
            {
              $resultado=$this->mMovimientoDocumentoDua->ActualizarMovimientoDocumentoDua($data);
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

        function BorrarMovimientoDocumentoDua($data)
        {
          $this->mMovimientoDocumentoDua->BorrarMovimientoDocumentoDua($data);

          return "";
        }

        /*Se normaliza funcion para entrada*/
        function BorrarMovimientosDocumentoDuaInventarioInicial($data)
        {
          $resultado = $this->mMovimientoDocumentoDua->ObtenerMovimientosPorInventarioInicial($data);

          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              $this->sDuaProducto->ActualizarDuaProductoInventarioInicial($value);
              $this->sDuaProducto->BorrarDuasProducto($value);

              $data_movimiento["IdMovimientoDocumentoDua"] = $value["IdMovimientoDocumentoDua"];
              $this->mMovimientoDocumentoDua->BorrarMovimientoDocumentoDua($data_movimiento);
            }
          }

          return $resultado;
        }
        /*Fin Normalizacion*/

        /*Se normaliza funcion para entrada*/
        function BorrarMovimientosDocumentoDuaNotaEntrada($data)
        {
          $resultado = $this->mMovimientoDocumentoDua->ObtenerMovimientosPorNotaEntrada($data);

          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              // code...
              $dataMercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);
              $value["SaldoFisico"] = $dataMercaderia["SaldoFisico"] - $value["CantidadEntrada"];
              $actualizar = $this->mMercaderia->ActualizarMercaderia($value);
              $this->sDuaProducto->ActualizarDuaProductoNotaEntrada($value);
              //$this->sDuaProducto->BorrarDuasProducto($value);

              $data_movimiento["IdMovimientoDocumentoDua"] = $value["IdMovimientoDocumentoDua"];
              $this->mMovimientoDocumentoDua->BorrarMovimientoDocumentoDua($data_movimiento);
            }
          }

          return $resultado;
        }
        /*Fin Normalizacion*/


        function BorrarMovimientosDocumentoDuaNotaSalida($data)
        {
          $resultado = $this->mMovimientoDocumentoDua->ObtenerMovimientosPorNotaSalida($data);

          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              $dataMercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);

              $value["SaldoFisico"] = $dataMercaderia["SaldoFisico"] + $value["CantidadSalida"];
              $actualizar = $this->mMercaderia->ActualizarMercaderia($value);

              $this->sDuaProducto->ActualizarDuaProductoNotaSalida($value);
              // $this->sDuaProducto->BorrarDuasProducto($value);

              $data_movimiento["IdMovimientoDocumentoDua"] = $value["IdMovimientoDocumentoDua"];
              $this->mMovimientoDocumentoDua->BorrarMovimientoDocumentoDua($data_movimiento);
            }
          }

          return $resultado;
        }

        /*PARA INVENTARIO INICIAL*/
        function ValidarProductoInventarioInicial($data)
        {
          $resultado = $this->mMovimientoDocumentoDua->ConsultarProductoDuaInventarioInicial($data);
          if(count($resultado) > 0)
          {
            $this->BorrarMovimientoDocumentoDua($resultado[0]);
            $nuevo = $this->InsertarMovimientoDocumentoDuaInventarioInicial($data);
            $data["IdMovimientoDocumentoDua"] = $nuevo["IdMovimientoDocumentoDua"];
            $data["AnteriorInicial"] = $resultado[0]["CantidadEntrada"];
            return $data;
          }
          else {
            // code...
            return "";
          }
        }

        function InsertarInventarioInicialEnMovimientoDocumentoDua($data)
        {
          $inicial = $this->ValidarProductoInventarioInicial($data);

          if(is_array($inicial))
          {
            // print_r($inicial);
            // exit;
            // $salida = $this->ReordenarMovimientosAlmacenPorProducto($inicial);
            // if($salida)
            // {
              $data["AnteriorInicial"] = $inicial["AnteriorInicial"];
              $output = $this->sDuaProducto->AgregarDuaProductoInventarioInicial($data);
              // $salida["SaldoFisico"] = $salida["CantidadInicial"];
              // $data["SaldoFisico"] = $salida;
            // }
            return $data;
          }
          else {
            $resultado = $this->InsertarMovimientoDocumentoDuaInventarioInicial($data);

            if($resultado)
            {
              $resultado["CantidadInicial"] = $resultado["CantidadEntrada"];

              // $salida = $this->ReordenarMovimientosAlmacenPorProducto($resultado);
              // if($salida)
              // {
              // print_r($data);
              // exit;
                $data["AnteriorInicial"] = 0;
                $output = $this->sDuaProducto->AgregarDuaProductoInventarioInicial($data);

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
        //   $sedes = $this->mMovimientoDocumentoDua->SedesPorProductoEnMovimientoDocumentoDua($data);
        //   $cantidadtotal = 0;
        //   foreach ($sedes as $key => $value) {
        //     $data["IdAsignacionSede"] = $value["IdAsignacionSede"];
        //     $response = $this->mMovimientoDocumentoDua->ObtenerMovimientosPorProductoSede($data);
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
        //       $this->ActualizarMovimientoDocumentoDua($value2);
        //     }
        //
        //     $cantidadtotal += $cantidadprevia;
        //   }
        //
        //
        //   return $cantidadtotal;
        // }

        // Se recalculan los movimientos en almacen por cantidades
        function CalcularCantidadesProductoPorMovimientoDocumentoDua($data)
        {
          $producto = $data["IdProducto"];
          $sede = $data["IdAsignacionSede"];
          $documentozofra = $data["IdDua"];
          // $data1 = $data;
          if (is_numeric($producto)) {
            if(is_numeric($sede)){
              if(is_numeric($documentozofra)){
                // P:1 - S:1 - D:1
                $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data);
              }
              else {
                // P:1 - S:1 - D:%
                $data1["IdProducto"] = $data["IdProducto"];
                $data1["IdAsignacionSede"] = $data["IdAsignacionSede"];
                $documentos = $this->DocumentosPorProductoSedeEnMovimientoDocumentoDua($data1);
                foreach ($documentos as $key3 => $value3) {
                  $data1["IdDua"] = $value3["IdDua"];
                  $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                }
              }
            }
            else {
              $data1["IdProducto"] = $data["IdProducto"];
              $sedes = $this->SedesPorProductoEnMovimientoDocumentoDua($data1);
              foreach ($sedes as $key2 => $value2) {
                $data1["IdAsignacionSede"] = $value2["IdAsignacionSede"];
                if(is_numeric($documentozofra)){
                  // P:1 - S:% - D:1
                  $data1["IdDua"] = $data["IdDua"];
                  $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                }
                else {
                  // P:1 - S:% - D:%
                  $documentos = $this->DocumentosPorProductoSedeEnMovimientoDocumentoDua($data1);
                  foreach ($documentos as $key3 => $value3) {
                    $data1["IdDua"] = $value3["IdDua"];
                    $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                  }
                }
              }
            }
          }
          else {
            $productos = $this->ProductosEnMovimientoDocumentoDua();
            foreach ($productos as $key => $value) {
              $data1["IdProducto"] = $value["IdProducto"];
              if(is_numeric($sede)){
                if(is_numeric($documentozofra)){
                  // P:% - S:1 - D:1
                  $data1["IdAsignacionSede"] = $data["IdAsignacionSede"];
                  $data1["IdDua"] = $data["IdDua"];
                  $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                }
                else {
                  // P:% - S:1 - D:%
                  $data1["IdAsignacionSede"] = $data["IdAsignacionSede"];
                  $documentos = $this->DocumentosPorProductoSedeEnMovimientoDocumentoDua($data1);
                  foreach ($documentos as $key3 => $value3) {
                    $data1["IdDua"] = $value3["IdDua"];
                    $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                  }
                }
              }
              else {
                $sedes = $this->SedesPorProductoEnMovimientoDocumentoDua($data1);
                foreach ($sedes as $key2 => $value2) {
                  $data1["IdAsignacionSede"] = $value2["IdAsignacionSede"];
                  if(is_numeric($documentozofra)){
                    // P:% - S:% - D:1
                    $data1["IdDua"] = $data["IdDua"];
                    $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                  }
                  else {
                    // P:% - S:% - D:%
                    $documentos = $this->DocumentosPorProductoSedeEnMovimientoDocumentoDua($data1);
                    foreach ($documentos as $key3 => $value3) {
                      $data1["IdDua"] = $value3["IdDua"];
                      $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                    }
                  }
                }
              }
            }
          }

          return "";
        }

        function RecalcularCantidadesMovimientosAlmacenPorProducto($data)
        {
          $resultado = $this->mMovimientoDocumentoDua->ObtenerMovimientosPorProductoSedeDocumento($data);

          $cantidadprevia = 0;
          foreach ($resultado as $key => $value) {
            if(is_numeric($value["CantidadEntrada"]))
            {
              $value["SaldoFisico"] = $cantidadprevia + $value["CantidadEntrada"];
            }
            else {
              $value["SaldoFisico"] = $cantidadprevia - $value["CantidadSalida"];
            }
            $cantidadprevia = $value["SaldoFisico"];
            $this->ActualizarMovimientoDocumentoDua($value);
          }

          return $cantidadprevia;
        }

        function ProductosEnMovimientoDocumentoDua()
        {
          $resultado = $this->mMovimientoDocumentoDua->ProductosEnMovimientoDocumentoDua();
          return $resultado;
        }

        function SedesPorProductoEnMovimientoDocumentoDua($data)
        {
          $resultado = $this->mMovimientoDocumentoDua->SedesPorProductoEnMovimientoDocumentoDua($data);
          return $resultado;
        }

        function DocumentosPorProductoSedeEnMovimientoDocumentoDua($data)
        {
          $resultado = $this->mMovimientoDocumentoDua->DocumentosPorProductoSedeEnMovimientoDocumentoDua($data);
          return $resultado;
        }

        //FUNCION PARA ACTUALIZAR FECHAS
        function ActualizarFechaParaInventariosInicial($data)
        {
          $resultado = $this->mMovimientoDocumentoDua->ActualizarFechaParaInventariosInicial($data);
          return $resultado;
        }

}

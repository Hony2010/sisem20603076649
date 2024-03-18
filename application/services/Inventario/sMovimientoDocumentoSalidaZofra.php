<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMovimientoDocumentoSalidaZofra extends MY_Service {

        public $MovimientoDocumentoSalidaZofra = array();

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
          $this->load->model('Inventario/mMovimientoDocumentoSalidaZofra');
          $this->load->model('Catalogo/mMercaderia');
          $this->load->service('Inventario/sDocumentoSalidaZofraProducto');

          $this->MovimientoDocumentoSalidaZofra = $this->mMovimientoDocumentoSalidaZofra->MovimientoDocumentoSalidaZofra;
        }

        function Cargar()
        {

        }

        function InsertarMovimientoDocumentoSalidaZofraNotaSalida($data)
        {
          if(is_string($data["Cantidad"])){$data["Cantidad"]= str_replace(',',"",$data["Cantidad"]);}
          $data["CantidadSalida"] = $data["Cantidad"];
          $output = $this->sDocumentoSalidaZofraProducto->AgregarDocumentoSalidaZofraProductoNotaSalida($data);

          $data["SaldoFisico"] = $output["StockDocumentoSalidaZofra"];
          $data["IdDocumentoSalidaZofraProducto"] = $output["IdDocumentoSalidaZofraProducto"];
          $data["IdDocumentoSalidaZofra"] = $output["IdDocumentoSalidaZofra"];
          $resultado = $this->InsertarMovimientoDocumentoSalidaZofra($data);

          return $resultado;
        }

        /*Se normaliza para nota entrada*/
        function InsertarMovimientoDocumentoSalidaZofraNotaEntrada($data)
        {
          if(is_string($data["Cantidad"])){$data["Cantidad"]= str_replace(',',"",$data["Cantidad"]);}
          $data["CantidadEntrada"] = $data["Cantidad"];
          $output = $this->sDocumentoSalidaZofraProducto->AgregarDocumentoSalidaZofraProductoNotaEntrada($data);
          $data["SaldoFisico"] = $output["StockDocumentoSalidaZofra"];
          $data["IdDocumentoSalidaZofraProducto"] = $output["IdDocumentoSalidaZofraProducto"];

          $resultado = $this->InsertarMovimientoDocumentoSalidaZofra($data);

          return $resultado;
        }
        /*Fin Normalizacion*/

        function InsertarMovimientoDocumentoSalidaZofraInventarioInicial($data)
        {
          if(is_string($data["CantidadInicial"])){$data["CantidadInicial"]= str_replace(',',"",$data["CantidadInicial"]);}
          $data["FechaMovimiento"] = convertToDate($data["FechaInventario"]);
          $data["SaldoFisico"] =$data["CantidadInicial"];
          $data["CantidadEntrada"] = $data["CantidadInicial"];
          $resultado = $this->InsertarMovimientoDocumentoSalidaZofra($data);

          return $resultado;
        }



        function InsertarMovimientoDocumentoSalidaZofra($data)
        {
          try {
            $resultadoValidacion = "";

            if($resultadoValidacion == "")
            {
              $resultado= $this->mMovimientoDocumentoSalidaZofra->InsertarMovimientoDocumentoSalidaZofra($data);

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
        // public function InsertarMovimientoDocumentoSalidaZofraDesdeComprobante($data)
        // {
        //   foreach ($data["DetallesComprobanteVenta"] as $key => $value) {
        //     if(is_numeric($value["IdProducto"]))
        //     {
        //       $value["RazonSocial"] = $data["RazonSocial"];
        //       $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
        //       $value["NombreAlmacen"] = $data["NombreAlmacen"];
        //       $value["FechaEmision"] = $data["FechaEmision"];
        //       $value["IdNotaEntrada"] = $data["IdNotaEntrada"];
        //       $this->InsertarMovimientoDocumentoSalidaZofraNotaEntrada($value);
        //     }
        //   }
        // }

        /*COSTO AGREGADO*/
        // public function ActualizarMovimientosAlmacenCostoAgregado($data)
        // {
        //   foreach ($data as $key => $value) {
        //       if(is_numeric($value["IdProducto"]))
        //       {
        //         $movimiento = $this->mMovimientoDocumentoSalidaZofra->ObtenerMovimientoDocumentoSalidaZofraPorNotaEntradaComprobanteCompra($value);
        //         if(count($movimiento)>0){
        //           $costounitarioagregado = $movimiento[0]["CostoUnitarioAgregado"] + $value["MontoProrrateadoPorUnidad"];
        //           $data_actualizar["IdMovimientoDocumentoSalidaZofra"] = $movimiento[0]["IdMovimientoDocumentoSalidaZofra"];
        //           $data_actualizar["CostoUnitarioAgregado"] = $costounitarioagregado;
        //           $this->ActualizarMovimientoDocumentoSalidaZofra($data_actualizar);
        //         }
        //       }
        //   }
        //
        // }

        function ActualizarMovimientoDocumentoSalidaZofra($data)
        {
          try {
            $resultadoValidacion = "";

            if($resultadoValidacion == "")
            {
              $resultado=$this->mMovimientoDocumentoSalidaZofra->ActualizarMovimientoDocumentoSalidaZofra($data);
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

        function BorrarMovimientoDocumentoSalidaZofra($data)
        {
          $this->mMovimientoDocumentoSalidaZofra->BorrarMovimientoDocumentoSalidaZofra($data);

          return "";
        }

        /*Se normaliza funcion para inventario inicial*/
        function BorrarMovimientosDocumentoSalidaZofraProductoInventarioInicial($data)
        {
          $resultado = $this->mMovimientoDocumentoSalidaZofra->ObtenerMovimientosPorInventarioInicial($data);

          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {

              $this->sDocumentoSalidaZofraProducto->ActualizarDocumentoSalidaZofraProductoInventarioInicial($value);
              $this->sDocumentoSalidaZofraProducto->BorrarDocumentosSalidaZofraProducto($value);
              $data_movimiento["IdMovimientoDocumentoSalidaZofra"] = $value["IdMovimientoDocumentoSalidaZofra"];
              $this->mMovimientoDocumentoSalidaZofra->BorrarMovimientoDocumentoSalidaZofra($data_movimiento);

            }
          }

          return $resultado;
        }
        /*Fin Normalizacion*/

        /*Se normaliza funcion para entrada*/
        function BorrarMovimientosDocumentoSalidaZofraProductoNotaEntrada($data)
        {
          $resultado = $this->mMovimientoDocumentoSalidaZofra->ObtenerMovimientosPorNotaEntrada($data);

          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              // code...
              // $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);
              // $value["SaldoFisico"] = $mercaderia["SaldoFisico"] - $value["CantidadEntrada"];
              // $actualizar = $this->mMercaderia->ActualizarMercaderia($value);
              $this->sDocumentoSalidaZofraProducto->ActualizarDocumentoSalidaZofraProductoNotaEntrada($value);
              $this->sDocumentoSalidaZofraProducto->BorrarDocumentosSalidaZofraProducto($value);
              $data_movimiento["IdMovimientoDocumentoSalidaZofra"] = $value["IdMovimientoDocumentoSalidaZofra"];
              $this->mMovimientoDocumentoSalidaZofra->BorrarMovimientoDocumentoSalidaZofra($data_movimiento);

            }
          }

          return $resultado;
        }
        /*Fin Normalizacion*/


        function BorrarMovimientosDocumentoSalidaZofraProductoNotaSalida($data)
        {
          $resultado = $this->mMovimientoDocumentoSalidaZofra->ObtenerMovimientosPorNotaSalida($data);

          if(count($resultado) > 0)
          {
            foreach ($resultado as $key => $value) {
              $mercaderia = (array) $this->mMercaderia->ConsultarMercaderiaPorIdProducto($value);
              $value["SaldoFisico"] = $mercaderia["SaldoFisico"] + $value["CantidadSalida"];
              $actualizar = $this->mMercaderia->ActualizarMercaderia($value);

              $this->sDocumentoSalidaZofraProducto->ActualizarDocumentoSalidaZofraProductoNotaSalida($value);
              // $this->sDocumentoSalidaZofraProducto->BorrarDocumentosSalidaZofraProducto($value);
              $data_movimiento["IdMovimientoDocumentoSalidaZofra"] = $value["IdMovimientoDocumentoSalidaZofra"];
              $this->mMovimientoDocumentoSalidaZofra->BorrarMovimientoDocumentoSalidaZofra($data_movimiento);
            }
          }

          return $resultado;
        }

        /*PARA INVENTARIO INICIAL*/
        function ValidarProductoInventarioInicial($data)
        {
          $resultado = $this->mMovimientoDocumentoSalidaZofra->ConsultarProductoAlmacenInventarioInicial($data);
          if(count($resultado) > 0)
          {
            // print_r($resultado);
            // exit;
            $this->BorrarMovimientoDocumentoSalidaZofra($resultado[0]);
            $nuevo = $this->InsertarMovimientoDocumentoSalidaZofraInventarioInicial($data);
            $data["IdMovimientoDocumentoSalidaZofra"] = $nuevo["IdMovimientoDocumentoSalidaZofra"];
            $data["AnteriorInicial"] = $resultado[0]["CantidadEntrada"];
            return $data;
          }
          else {
            // code...
            return "";
          }
        }

        function InsertarInventarioInicialEnMovimientoDocumentoSalidaZofra($data)
        {
          $inicial = $this->ValidarProductoInventarioInicial($data);

          if(is_array($inicial))
          {

            // $salida = $this->ReordenarMovimientosAlmacenPorProducto($inicial);

            // if($salida)
            // {
              $data["AnteriorInicial"] = $inicial["AnteriorInicial"];
              $output = $this->sDocumentoSalidaZofraProducto->AgregarDocumentoSalidaZofraProductoInventarioInicial($data);
              // $salida["SaldoFisico"] = $salida["CantidadInicial"];
              // $data["SaldoFisico"] = $salida;
            // }
            return $data;
          }
          else {
            $resultado = $this->InsertarMovimientoDocumentoSalidaZofraInventarioInicial($data);

            if($resultado)
            {
              $resultado["CantidadInicial"] = $resultado["CantidadEntrada"];

              // $salida = $this->ReordenarMovimientosAlmacenPorProducto($resultado);
              // if($salida)
              // {
                $data["AnteriorInicial"] = 0;
                $output = $this->sDocumentoSalidaZofraProducto->AgregarDocumentoSalidaZofraProductoInventarioInicial($data);
                // print_r($output);
                // exit;
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
        //   $sedes = $this->mMovimientoDocumentoSalidaZofra->SedesPorProductoEnMovimientoDocumentoSalidaZofra($data);
        //   $cantidadtotal = 0;
        //   foreach ($sedes as $key => $value) {
        //     $data["IdAsignacionSede"] = $value["IdAsignacionSede"];
        //     $response = $this->mMovimientoDocumentoSalidaZofra->ObtenerMovimientosPorProductoSedeDocumento($data);
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
        //       $this->ActualizarMovimientoDocumentoSalidaZofra($value2);
        //     }
        //
        //     $cantidadtotal += $cantidadprevia;
        //   }
        //
        //
        //   return $cantidadtotal;
        // }

        // Se recalculan los movimientos en almacen por cantidades
        function CalcularCantidadesProductoPorMovimientoDocumentoSalidaZofra($data)
        {
          $producto = $data["IdProducto"];
          $sede = $data["IdAsignacionSede"];
          $documentozofra = $data["IdDocumentoSalidaZofra"];
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
                $documentos = $this->DocumentosPorProductoSedeEnMovimientoDocumentoSalidaZofra($data1);
                foreach ($documentos as $key3 => $value3) {
                  $data1["IdDocumentoSalidaZofra"] = $value3["IdDocumentoSalidaZofra"];
                  $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                }
              }
            }
            else {
              $data1["IdProducto"] = $data["IdProducto"];
              $sedes = $this->SedesPorProductoEnMovimientoDocumentoSalidaZofra($data1);
              foreach ($sedes as $key2 => $value2) {
                $data1["IdAsignacionSede"] = $value2["IdAsignacionSede"];
                if(is_numeric($documentozofra)){
                  // P:1 - S:% - D:1
                  $data1["IdDocumentoSalidaZofra"] = $data["IdDocumentoSalidaZofra"];
                  $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                }
                else {
                  // P:1 - S:% - D:%
                  $documentos = $this->DocumentosPorProductoSedeEnMovimientoDocumentoSalidaZofra($data1);
                  foreach ($documentos as $key3 => $value3) {
                    $data1["IdDocumentoSalidaZofra"] = $value3["IdDocumentoSalidaZofra"];
                    $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                  }
                }
              }
            }
          }
          else {
            $productos = $this->ProductosEnMovimientoDocumentoSalidaZofra();
            foreach ($productos as $key => $value) {
              $data1["IdProducto"] = $value["IdProducto"];
              if(is_numeric($sede)){
                if(is_numeric($documentozofra)){
                  // P:% - S:1 - D:1
                  $data1["IdAsignacionSede"] = $data["IdAsignacionSede"];
                  $data1["IdDocumentoSalidaZofra"] = $data["IdDocumentoSalidaZofra"];
                  $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                }
                else {
                  // P:% - S:1 - D:%
                  $data1["IdAsignacionSede"] = $data["IdAsignacionSede"];
                  $documentos = $this->DocumentosPorProductoSedeEnMovimientoDocumentoSalidaZofra($data1);
                  foreach ($documentos as $key3 => $value3) {
                    $data1["IdDocumentoSalidaZofra"] = $value3["IdDocumentoSalidaZofra"];
                    $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                  }
                }
              }
              else {
                $sedes = $this->SedesPorProductoEnMovimientoDocumentoSalidaZofra($data1);
                foreach ($sedes as $key2 => $value2) {
                  $data1["IdAsignacionSede"] = $value2["IdAsignacionSede"];
                  if(is_numeric($documentozofra)){
                    // P:% - S:% - D:1
                    $data1["IdDocumentoSalidaZofra"] = $data["IdDocumentoSalidaZofra"];
                    $this->RecalcularCantidadesMovimientosAlmacenPorProducto($data1);
                  }
                  else {
                    // P:% - S:% - D:%
                    $documentos = $this->DocumentosPorProductoSedeEnMovimientoDocumentoSalidaZofra($data1);
                    foreach ($documentos as $key3 => $value3) {
                      $data1["IdDocumentoSalidaZofra"] = $value3["IdDocumentoSalidaZofra"];
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
          $resultado = $this->mMovimientoDocumentoSalidaZofra->ObtenerMovimientosPorProductoSedeDocumento($data);

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
            $this->ActualizarMovimientoDocumentoSalidaZofra($value);
          }

          return $cantidadprevia;
        }

        function ProductosEnMovimientoDocumentoSalidaZofra()
        {
          $resultado = $this->mMovimientoDocumentoSalidaZofra->ProductosEnMovimientoDocumentoSalidaZofra();
          return $resultado;
        }

        function SedesPorProductoEnMovimientoDocumentoSalidaZofra($data)
        {
          $resultado = $this->mMovimientoDocumentoSalidaZofra->SedesPorProductoEnMovimientoDocumentoSalidaZofra($data);
          return $resultado;
        }

        function DocumentosPorProductoSedeEnMovimientoDocumentoSalidaZofra($data)
        {
          $resultado = $this->mMovimientoDocumentoSalidaZofra->DocumentosPorProductoSedeEnMovimientoDocumentoSalidaZofra($data);
          return $resultado;
        }

        //FUNCION PARA ACTUALIZAR FECHAS
        function ActualizarFechaParaInventariosInicial($data)
        {
          $resultado = $this->mMovimientoDocumentoSalidaZofra->ActualizarFechaParaInventariosInicial($data);
          return $resultado;
        }

        //AQUI SUMAMOS TODOS LOS PRODUCTOS DE ZOFRA PARA INVENTARIO INICIAL
        function SumarProductosAlmacenZofraParaInventarioPrincipal($data)
        {
          $resultado = 0;
          $productos = $this->mMovimientoDocumentoSalidaZofra->ObtenerMovimientoDocumentoSalidaZofraPorProductoYAlmacen($data);
          
          foreach ($productos as $key => $value) {
            $resultado += $value["CantidadEntrada"];
          }

          return $resultado;
        }

}

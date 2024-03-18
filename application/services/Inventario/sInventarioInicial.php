<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sInventarioInicial extends MY_Service {

        public $InventarioInicial = array();
        public $DetalleInventarioInicial = array();

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
          // $this->load->service('Inventario/sDetalleInventarioInicial');
          $this->load->service("Catalogo/sCliente");
          $this->load->service('Configuracion/General/sParametroSistema');
          $this->load->service('Configuracion/General/sConstanteSistema');
          $this->load->service("Configuracion/General/sTipoCambio");
          $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
          $this->load->service("Configuracion/General/sFormaPago");
          $this->load->service("Configuracion/General/sMoneda");
          $this->load->service("Configuracion/General/sTipoDocumento");
          $this->load->service("Configuracion/Venta/sTipoTarjeta");
          $this->load->service("Configuracion/General/sSede");
      		$this->load->service("Configuracion/Catalogo/sTipoExistencia");
          $this->load->service('Configuracion/General/sAsignacionSede');
          // $this->load->service("Configuracion/Inventario/sMotivoInventarioInicial");
          $this->load->service("Inventario/sMovimientoAlmacen");
          $this->load->service("Inventario/sMovimientoAlmacenProductoLote");
      		$this->load->service("Inventario/sLoteProducto");
          $this->load->service("Inventario/sMovimientoDocumentoDua");
      		$this->load->service("Inventario/sDua");
          $this->load->service("Inventario/sMovimientoDocumentoSalidaZofra");
      		$this->load->service("Inventario/sDocumentoSalidaZofra");
      		// $this->load->service("Inventario/sDocumentoReferenciaInventarioInicial");
          $this->load->model("Base");
          $this->load->model('Inventario/mInventarioInicial');
          $this->load->service('Catalogo/sProducto');
          $this->load->service('Catalogo/sMercaderia');
          $this->load->service("Configuracion/Inventario/sMotivoInventarioInicial");
          $this->load->service("Configuracion/General/sUnidadMedida");
          $this->load->service('Seguridad/sAccesoUsuarioAlmacen');
          $this->load->service('Configuracion/Catalogo/sTipoListaPrecio');        
          $this->load->service('Catalogo/sListaPrecioMercaderia');        
          // $this->load->model('Venta/mComprobanteVenta');

          $this->InventarioInicial = $this->mInventarioInicial->InventarioInicial;

          $this->InventarioInicial["CodigoMercaderia"] = "";
          $this->InventarioInicial["NombreProducto"] = "";
          $this->InventarioInicial["NombreLargoProducto"] = "";
          $this->InventarioInicial["AbreviaturaUnidadMedida"] = "";
          $this->InventarioInicial["CodigoUnidadMedida"] = "";
          $this->InventarioInicial["CantidadInicial"] = "";
          $this->InventarioInicial["ValorUnitario"] = "";
          $this->InventarioInicial["IdUnidadMedida"] = ID_UNIDAD_MEDIDA_UNIDAD;
          $this->InventarioInicial["CodigoTipoAfectacionIGV"] = CODIGO_AFECTACION_IGV_GRAVADO;
          $this->InventarioInicial["IdFabricante"] = ID_FABRICANTE_NO_ESPECIFICADO;
          $this->InventarioInicial["IdTipoPrecio"] = ID_TIPO_PRECIO_UNITARIO;
          $this->InventarioInicial["IdTipoSistemaISC"] = ID_TIPO_SISTEMA_ISC_NO_AFECTO;
          $this->InventarioInicial["Producto"] =  $this->sProducto->Producto;

          $this->InventarioInicial["IdLoteProducto"] = "";
          $this->InventarioInicial["IdMoneda"] = ID_MONEDA_SOLES;
          $this->InventarioInicial["NumeroLote"] = "";
          $this->InventarioInicial["FechaVencimiento"] = "";
          $this->InventarioInicial["NombreSede"] = "";
          $this->InventarioInicial["NombreTipoExistencia"] = "";

          $this->InventarioInicial["NumeroDocumentoSalidaZofra"] = "";
          $this->InventarioInicial["IdDocumentoSalidaZofra"] = "";
          $this->InventarioInicial["NumeroDua"] = "";
          $this->InventarioInicial["FechaEmisionDocumentoSalidaZofra"] = "";
          $this->InventarioInicial["FechaEmisionDua"] = "";
          $this->InventarioInicial["NumeroItemDua"] = "";

          $this->InventarioInicial["SaldoFisico"] = "";
          $this->InventarioInicial["CostoUnitarioCompra"] = "";
          $this->InventarioInicial["PrecioUnitarioCompra"] = "";

          $this->InventarioInicial["Monedas"] = $this->sMoneda->ListarMonedas();

          $this->InventarioInicial["IdOrigenMercaderia"] = (string)ID_ORIGEN_MERCADERIA_GENERAL;

          $this->InventarioInicial["FechaHoy"] = $this->Base->ObtenerFechaServidor("d/m/Y");

          $this->InventarioInicial["ParametroLote"] = $this->sConstanteSistema->ObtenerParametroLote();
          $this->InventarioInicial["ParametroDua"] = $this->sConstanteSistema->ObtenerParametroDua();
          $this->InventarioInicial["ParametroDocumentoSalidaZofra"] = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();

          $this->InventarioInicial["NuevoDetalleInventarioInicial"] = $this->InventarioInicial;
          $DetalleInventarioInicial[] = $this->InventarioInicial;
          $this->InventarioInicial["DetallesInventarioInicial"] = $DetalleInventarioInicial;
        }

        function Cargar()
        {
          $hoy = date("d/m/Y");

          $data["FechaCambio"] = $hoy;
          $data["IdSedeAgencia"] =$this->sesionusuario->obtener_sesion_id_sede();
          $TipoCambio = $this->sTipoCambio->ObtenerTipoCambio($data);

          $this->InventarioInicial["IdTipoExistencia"] = ID_TIPO_EXISTENCIA_MERCADERIA;
          $this->InventarioInicial["IdSedeAlmacen"] = "";
          $this->InventarioInicial["IdAsignacionSede"] = "";
          $this->InventarioInicial["FechaInventario"] = $hoy;
          //$this->InventarioInicial["IdTipoExistencia"] = "";
          $this->InventarioInicial["IdTipoDocumento"] = ID_TIPODOCUMENTO_INVENTARIOINICIAL;
          $this->InventarioInicial["Observacion"] = "";
          $this->InventarioInicial["RutaPlantillaExcel"] = base_url().$this->ObtenerNombrePlantillaInventarioInicial();


          $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_INVENTARIOINICIAL;
          $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
          $this->InventarioInicial["IdSede"] = $parametro['IdSedeAgencia'];
          $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
          $this->InventarioInicial["SerieInventarioInicial"] = $SeriesDocumento[0]['SerieDocumento'];
          $this->InventarioInicial["NumeroInventarioInicial"] = $SeriesDocumento[0]['UltimoDocumento'];
          $this->InventarioInicial["IdCorrelativoDocumento"] = $SeriesDocumento[0]['IdCorrelativoDocumento'];

          $MotivosInventario = $this->sMotivoInventarioInicial->ListarMotivosInventarioInicial();
          $this->InventarioInicial["MotivoMovimiento"] = (count($MotivosInventario) > 0) ? $MotivosInventario[0]["NombreMotivoInventarioInicial"] : "";//"INVENTARIO INICIAL";

          $Monedas = $this->sMoneda->ListarMonedas();
          // $Sedes=$this->sSede->ListarSedesTipoAlmacen();
          $parametro["IdUsuario"]=$this->sesionusuario->obtener_sesion_id_usuario();
          $Sedes=$this->sAccesoUsuarioAlmacen->ConsultarSedesTipoAlmacenPorUsuario($parametro);
          $data2["IdSede"] = $this->InventarioInicial["IdSede"];
          $dataAsignacionSede=$this->sAsignacionSede->ObtenerAsignacionSedeTipoAlmacenPorIdSede($data2);
          $this->InventarioInicial["IdAsignacionSede"] = $dataAsignacionSede[0]["IdAsignacionSede"];
          $this->InventarioInicial["NombreSede"] = $dataAsignacionSede[0]["NombreSede"];
          if(count($Sedes) > 0) {
            //$this->InventarioInicial["IdAsignacionSede"] = $Sedes[0]["IdAsignacionSede"];
          }
          $TiposExistencia = $this->sTipoExistencia->ListarTiposExistencia();
          $this->InventarioInicial["NombreAlmacen"] = (count($Sedes) > 0) ? $Sedes[0]["NombreSede"] : "";//$Sedes[0]["NombreSede"];
          $UnidadesMedida = $this->sUnidadMedida->ListarUnidadesMedida();
          // $Sedes=array();

          $this->InventarioInicial["ParametroCodigoBarrasAutomatico"] = $this->sConstanteSistema->ObtenerParametroCodigoBarraAutomatico();
          $this->InventarioInicial["ParametroVistaPreviaImpresion"] =   $this->sesionusuario->obtener_sesion_indicador_vista_previa_impresion();


          $data =array(
            'NuevoDetalleInventarioInicial'=>$this->InventarioInicial,
            'TiposExistencia'=> $TiposExistencia,
            'Monedas'=>$Monedas,
            'Sedes'=>$Sedes,
            'TipoCambio'=>$TipoCambio,
            'MotivosInventario'=>$MotivosInventario,
            'UnidadesMedida' =>$UnidadesMedida,
            'CopiaSedes' => $Sedes
          );

          $resultado = array_merge($this->InventarioInicial,$data);

          return $resultado;
        }

        function ConsultarInventariosInicial($data,$numeropagina,$numerofilasporpagina)
        {
          $numerofilainicio=$numerofilasporpagina * ($numeropagina - 1);
          $resultado = $this->mInventarioInicial->ConsultarInventarioInicial($data,$numerofilainicio,$numerofilasporpagina);

          foreach ($resultado as $key => $value) {
            $resultado[$key]["FechaInicial"] = convertirFechaES($value["FechaInicial"]);
            $resultado[$key]["FechaVencimiento"] =$value["FechaVencimiento"] =="" ? "" : convertirFechaES($value["FechaVencimiento"]);
            $resultado[$key]["FechaEmisionDocumentoSalidaZofra"] = convertirFechaES($value["FechaEmisionDocumentoSalidaZofra"]);
            $resultado[$key]["FechaEmisionDua"] = convertirFechaES($value["FechaEmisionDua"]);
          }
          return $resultado;
        }

        function ObtenerNumeroFilasPorPagina()
        {
          $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_MERCADERIA;
          $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
          $numerofilasporpagina=$parametro->ValorParametroSistema;
          return $numerofilasporpagina;
        }

        function ObtenerNumeroTotalInventariosInicial($data)
        {
            $resultado = $this->mInventarioInicial->ObtenerNumeroTotalInventariosInicial($data)[0]["Cantidad"];
            return $resultado;
        }

        function ListarInventarioInicial()
        {
          // $data['IdParametroSistema']= NOMBRE_PLANTILLA_EXCEL_INVENTARIO_INICIAL;
          $resultado = $this->mInventarioInicial->ListarInventarioInicial();
          return $resultado;
        }

        function ObtenerNombrePlantillaInventarioInicial()
        {
          $data['IdParametroSistema']= NOMBRE_PLANTILLA_EXCEL_INVENTARIO_INICIAL;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
            $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
            return $ValorParametroSistema;
          }
        }

        public function InsertarInventarioInicial($data)
        {
          try {
            $dataCorrelativo["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
            $resultadoCorrelativo =  $this->sCorrelativoDocumento->IncrementarCorrelativoDocumento($dataCorrelativo);
            $data["NumeroInventarioInicial"] = $resultadoCorrelativo;

            $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();
            $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();
            $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();

            $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
            $data["CodigoSede"] = $asignacionsede[0]["CodigoSede"];

            $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
            $data["CodigoTipoDocumento"] = $tipodocumento[0]["CodigoTipoDocumento"];

            $productosantiguos = array();
            $productosnuevos = array();

            foreach ($data["DetallesInventarioInicial"] as $key => $value) {
              if(is_numeric($value["IdProducto"]))
              {
                array_push($productosantiguos, $value["IdProducto"]);
                if(is_string($value["CantidadInicial"])){$value["CantidadInicial"] = str_replace(',',"",$value["CantidadInicial"]);}
                if(is_string($value["ValorUnitario"])){$value["ValorUnitario"] = str_replace(',',"",$value["ValorUnitario"]);}
                $value["FechaInventario"] = $data["FechaInventario"];
                // $value["FechaInventario"] = "10/08/2018";
                $value["FechaInicial"] = convertToDate($data["FechaInventario"]);
                $value["FechaVencimiento"] = $value["FechaVencimiento"]=="" ? "" : convertToDate($value["FechaVencimiento"]);
                $value["FechaEmisionDua"] = convertToDate($value["FechaEmisionDua"]);
                $value["FechaEmisionDocumentoSalidaZofra"] = convertToDate($value["FechaEmisionDocumentoSalidaZofra"]);
                $value["IdTipoExistencia"] = $data["IdTipoExistencia"];
                $value["IdMoneda"] = $data["IdMoneda"];
                $value["IdAsignacionSede"] = $data["IdAsignacionSede"];
                $value["IdMotivoInventarioInicial"] = $data["IdMotivoInventarioInicial"];
                $value["Observacion"] = $data["Observacion"];
                $value["SerieInventarioInicial"] = $data["SerieInventarioInicial"];
                $value["NumeroInventarioInicial"] = $data["NumeroInventarioInicial"];
                $value["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
                $value["IdOrigenMercaderia"] = $data["IdOrigenMercaderia"];
                $value["IdInventarioInicial"] = '';

                $resultado = $this->InsertarInventario($value);

                if($resultado)
                {
                  $value["IdInventarioInicial"] = $resultado["IdInventarioInicial"];
                  $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
                  $value["NombreAlmacen"] = $data["NombreAlmacen"];
                  $value["CodigoSede"] = $data["CodigoSede"];
                  $value["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];
                  $value["CostoUnitarioAdquisicion"] = $value["ValorUnitario"];
                  
                  if($parametro_lote == 1)
                  {
                    if ($value["FechaVencimiento"]!="")  {
                      $value["FechaVencimiento"] = convertToDate($value["FechaVencimiento"]);
                    }
                    
                    $loteproducto = $this->sLoteProducto->AgregarLoteProducto($value);

                    $value["IdLoteProducto"] = $loteproducto["IdLoteProducto"];

                    $this->sMovimientoAlmacenProductoLote->InsertarInventarioInicialEnMovimientoAlmacenProductoLote($value);
                  }

                  if($parametro_dua == 1 && $data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_DUA)
                  {
                    $value["FechaEmision"] = convertToDate($data["FechaInventario"]);
                    $dua = $this->sDua->AgregarDuaInventarioInicial($value);

                    $value["IdDua"] = $dua["IdDua"];

                    $this->sMovimientoDocumentoDua->InsertarInventarioInicialEnMovimientoDocumentoDua($value);
                  }

                  if($parametro_zofra == 1 && $data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_ZOFRA) {
                    // $value["FechaEmision"] = convertToDate($data["FechaInventario"]);
                    $zofra = $this->sDocumentoSalidaZofra->AgregarDocumentoSalidaZofraInventarioInicial($value);

                    $value["IdDocumentoSalidaZofra"] = $zofra["IdDocumentoSalidaZofra"];
                    // print_r($zofra);
                    // exit;
                    $this->sMovimientoDocumentoSalidaZofra->InsertarInventarioInicialEnMovimientoDocumentoSalidaZofra($value);

                    // $value["CantidadInicial"] = $this->sMovimientoDocumentoSalidaZofra->SumarProductosAlmacenZofraParaInventarioPrincipal($value);
                    $zofra = $this->SumarProductosAlmacenZofra($value);
                    $value["CantidadInicial"] = $zofra["CantidadInicial"];
                    $value["CostoUnitarioAdquisicion"] = $zofra["ValorUnitario"];
                  }

                  //MOVIMIENTO ALMACEN
                  $this->sMovimientoAlmacen->InsertarInventarioInicialEnMovimientoAlmacen($value);

                  $value["IdSede"]=$data["IdSede"];
                  $this->ActualizarPrecioMinimo($value);
                }
              }
              else if($value["IdProducto"] == "-") {
                // code...
                if(is_string($value["CantidadInicial"])){$value["CantidadInicial"] = str_replace(',',"",$value["CantidadInicial"]);}
                if(is_string($value["ValorUnitario"])){$value["ValorUnitario"] = str_replace(',',"",$value["ValorUnitario"]);}
                $value["NombreLargoProducto"] = $value["NombreProducto"];
                $value['CodigoAutomatico'] = 1;
                $mercaderia = $this->sMercaderia->InsertarMercaderia($value);
                
                if(!is_array($mercaderia))
                {
                  throw new Exception($mercaderia, 1);
                }
                array_push($productosnuevos, $mercaderia["IdProducto"]);

                $value["FechaInventario"] = $data["FechaInventario"];
                // $value["FechaInventario"] = "10/08/2018";
                $value["FechaInicial"] = convertToDate($data["FechaInventario"]);
                $value["FechaVencimiento"] = $value["FechaVencimiento"] == "" ? "" : convertToDate($value["FechaVencimiento"]);
                $value["FechaEmisionDua"] = convertToDate($value["FechaEmisionDua"]);
                $value["FechaEmisionDocumentoSalidaZofra"] = convertToDate($value["FechaEmisionDocumentoSalidaZofra"]);
                $value["IdTipoExistencia"] = $data["IdTipoExistencia"];
                $value["IdMoneda"] = $data["IdMoneda"];
                $value["IdAsignacionSede"] = $data["IdAsignacionSede"];
                $value["Observacion"] = $data["Observacion"];
                $value["IdMotivoInventarioInicial"] = $data["IdMotivoInventarioInicial"];
                $value["SerieInventarioInicial"] = $data["SerieInventarioInicial"];
                $value["NumeroInventarioInicial"] = $data["NumeroInventarioInicial"];
                $value["IdInventarioInicial"] = '';
                $value["IdOrigenMercaderia"] = $data["IdOrigenMercaderia"];
                $value["IdProducto"] = $mercaderia["IdProducto"];
                $value["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
                $resultado = $this->InsertarInventario($value);

                if($resultado)
                {
                  $value["IdInventarioInicial"] = $resultado["IdInventarioInicial"];
                  $value["MotivoMovimiento"] = $data["MotivoMovimiento"];
                  $value["NombreAlmacen"] = $data["NombreAlmacen"];
                  $value["CodigoSede"] = $data["CodigoSede"];
                  $value["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];
                  $value["CostoUnitarioAdquisicion"] = $value["ValorUnitario"];
                  
                  if($parametro_lote == 1)
                  {
                    if ($value["FechaVencimiento"]!="")  {
                      $value["FechaVencimiento"] = convertToDate($value["FechaVencimiento"]);
                    }
                    $loteproducto = $this->sLoteProducto->AgregarLoteProducto($value);

                    $value["IdLoteProducto"] = $loteproducto["IdLoteProducto"];

                    $this->sMovimientoAlmacenProductoLote->InsertarInventarioInicialEnMovimientoAlmacenProductoLote($value);
                  }

                  if($parametro_dua == 1 && $data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_DUA)
                  {
                    $value["FechaEmision"] = convertToDate($data["FechaInventario"]);
                    $dua = $this->sDua->AgregarDuaInventarioInicial($value);

                    $value["IdDua"] = $dua["IdDua"];

                    $this->sMovimientoDocumentoDua->InsertarInventarioInicialEnMovimientoDocumentoDua($value);
                  }

                  if($parametro_zofra == 1 && $data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_ZOFRA)
                  {
                    // $value["FechaEmision"] = convertToDate($data["FechaInventario"]);
                    $zofra = $this->sDocumentoSalidaZofra->AgregarDocumentoSalidaZofraInventarioInicial($value);

                    $value["IdDocumentoSalidaZofra"] = $zofra["IdDocumentoSalidaZofra"];
                    // print_r($zofra);
                    // exit;
                    $this->sMovimientoDocumentoSalidaZofra->InsertarInventarioInicialEnMovimientoDocumentoSalidaZofra($value);

                    // $value["CantidadInicial"] = $this->sMovimientoDocumentoSalidaZofra->SumarProductosAlmacenZofraParaInventarioPrincipal($value);
                    $zofra = $this->SumarProductosAlmacenZofra($value);
                    $value["CantidadInicial"] = $zofra["CantidadInicial"];
                    $value["CostoUnitarioAdquisicion"] = $zofra["ValorUnitario"];
                  }

                  //MOVIMIENTO ALMACEN
                  $this->sMovimientoAlmacen->InsertarInventarioInicialEnMovimientoAlmacen($value);
                  $value["IdSede"]=$data["IdSede"];
                  $this->ActualizarPrecioMinimo($value);
                }
              }
            }

            $data["ProductosAntiguos"] = $productosantiguos;
            $data["ProductosNuevos"] = $productosnuevos;
            return $data;
          } catch (Exception $e) {
            return $e;
          }
        }

        public function ValidarProductoInventarioInicialAlmacenProducto($data)
        {
          $validacion = $this->mInventarioInicial->ConsultarInventarioInicialPorIdProductoSede($data);
          if(count($validacion) > 0)
          {
            return "El Producto ya se encuentra registrado con el almacen indicado";
          }
          else {
            return "";
          }
        }

        public function ValidarProductoInventarioInicialAlmacenProductoZofra($data)
        {
          $validacion = $this->mInventarioInicial->ConsultarInventarioInicialPorIdProductoSedeZofra($data);
          if(count($validacion) > 0)
          {
            return "El Producto Zofra ya se encuentra registrado con el almacen indicado";
          }
          else {
            return "";
          }
        }

        public function ValidarProductoInventarioInicialAlmacenProductoDua($data)
        {
          $validacion = $this->mInventarioInicial->ConsultarInventarioInicialPorIdProductoSedeDua($data);
          if(count($validacion) > 0)
          {
            return "El Producto Dua ya se encuentra ingresado";
          }
          else {
            return "";
          }
        }

        public function InsertarMercaderiaInventarioInicial($data)
        {
          try {
            $validacion = "";
            if($data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_GENERAL)
            {
              $validacion = $this->ValidarProductoInventarioInicialAlmacenProducto($data);
            }
            else if($data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_ZOFRA){
              $validacion = $this->ValidarProductoInventarioInicialAlmacenProductoZofra($data);
            }
            else if($data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_DUA){
              $validacion = $this->ValidarProductoInventarioInicialAlmacenProductoDua($data);
            }

            if($validacion != "")
            {
              return $validacion;
            }
            else {
              //ACTUALIZAMOS EL CODIGO DE MERCADERIA SI ESTA ACTIVO EL PARAMETRO
              $parametroCodigoBarraAutomatico = $this->sConstanteSistema->ObtenerParametroCodigoBarraAutomatico();
              if($parametroCodigoBarraAutomatico == 1)
              {
                $mercaderiaUpdate = $this->sMercaderia->ActualizarMercaderiaDesdeInventario($data);
              }

              $dataCorrelativo["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
              $resultadoCorrelativo =  $this->sCorrelativoDocumento->IncrementarCorrelativoDocumento($dataCorrelativo);
              $data["NumeroInventarioInicial"] = $resultadoCorrelativo;

              $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();
              $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();
              $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();

              $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
              $data["CodigoSede"] = $asignacionsede[0]["CodigoSede"];

              $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
              $data["CodigoTipoDocumento"] = $tipodocumento[0]["CodigoTipoDocumento"];

              // code...
              if(is_string($data["CantidadInicial"])){$data["CantidadInicial"] = str_replace(',',"",$data["CantidadInicial"]);}
              if(is_string($data["ValorUnitario"])){$data["ValorUnitario"] = str_replace(',',"",$data["ValorUnitario"]);}

              // $value["FechaInventario"] = $data["FechaInventario"];
              $data["FechaInicial"] = convertToDate($data["FechaInventario"]);
              $data["FechaVencimiento"] = $data["FechaVencimiento"] == "" ? "" : convertToDate($data["FechaVencimiento"]);
              $data["FechaEmisionDua"] = convertToDate($data["FechaEmisionDua"]);
              $data["FechaEmisionDocumentoSalidaZofra"] = convertToDate($data["FechaEmisionDocumentoSalidaZofra"]);
              if(is_string($data["CantidadInicial"])){$data["CantidadInicial"] = str_replace(',',"",$data["CantidadInicial"]);}
              if(is_string($data["ValorUnitario"])){$data["ValorUnitario"] = str_replace(',',"",$data["ValorUnitario"]);}
              $resultado = $this->InsertarInventario($data);

              if($resultado)
              {
                $data["IdInventarioInicial"] = $resultado["IdInventarioInicial"];
                $data["MotivoMovimiento"] = $data["MotivoMovimiento"];
                $data["NombreAlmacen"] = $data["NombreAlmacen"];
                $data["CodigoSede"] = $data["CodigoSede"];
                $data["CodigoTipoDocumento"] = $data["CodigoTipoDocumento"];
                $data["CostoUnitarioAdquisicion"] = $data["ValorUnitario"];
                
                if($parametro_lote == 1)
                {
                  if($data["FechaVencimiento"]!="") {
                    $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
                  }
                  $loteproducto = $this->sLoteProducto->AgregarLoteProducto($data);

                  $data["IdLoteProducto"] = $loteproducto["IdLoteProducto"];

                  $this->sMovimientoAlmacenProductoLote->InsertarInventarioInicialEnMovimientoAlmacenProductoLote($data);
                }

                if($parametro_dua == 1 && $data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_DUA)
                {
                  $data["FechaEmision"] = convertToDate($data["FechaInventario"]);
                  $dua = $this->sDua->AgregarDuaInventarioInicial($data);

                  $data["IdDua"] = $dua["IdDua"];

                  $this->sMovimientoDocumentoDua->InsertarInventarioInicialEnMovimientoDocumentoDua($data);
                }

                if($parametro_zofra == 1 && $data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_ZOFRA)
                {
                  // $value["FechaEmision"] = convertToDate($data["FechaInventario"]);
                  $zofra = $this->sDocumentoSalidaZofra->AgregarDocumentoSalidaZofraInventarioInicial($data);

                  $data["IdDocumentoSalidaZofra"] = $zofra["IdDocumentoSalidaZofra"];
                  // print_r($zofra);
                  // exit;
                  $this->sMovimientoDocumentoSalidaZofra->InsertarInventarioInicialEnMovimientoDocumentoSalidaZofra($data);

                  // $data["CantidadInicial"] = $this->sMovimientoDocumentoSalidaZofra->SumarProductosAlmacenZofraParaInventarioPrincipal($data);
                  $zofra = $this->SumarProductosAlmacenZofra($data);
                  $data["CantidadInicial"] = $zofra["CantidadInicial"];
                  $data["CostoUnitarioAdquisicion"] = $zofra["ValorUnitario"];
                }

                //MOVIMIENTO ALMACEN
                $this->sMovimientoAlmacen->InsertarInventarioInicialEnMovimientoAlmacen($data);
                $value["IdSede"]=$data["IdSede"];
                $this->ActualizarPrecioMinimo($data);
              }

              $resultado = array_merge($data, $resultado);

              return $resultado;
            }
          } catch (Exception $e) {
            return $e;
          }
        }

        function InsertarInventario($data)
        {
          $this->BorrarInventarioAnterior($data);

          $resultado = $this->mInventarioInicial->InsertarInventarioInicial($data);

          return $resultado;
        }

        function BorrarInventarioAnterior($data)
        {
          $inventarioinicial = array();
          if($data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_GENERAL)
          {
            $inventarioinicial = $this->mInventarioInicial->ConsultarInventarioInicialPorIdProductoSede($data);
          }
          else if($data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_ZOFRA){
            $inventarioinicial = $this->mInventarioInicial->ConsultarInventarioInicialPorIdProductoSedeZofra($data);
          }
          else if($data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_DUA){
            $inventarioinicial = $this->mInventarioInicial->ConsultarInventarioInicialPorIdProductoSedeDua($data);
          }

          if(count($inventarioinicial)>0)
          {
            foreach ($inventarioinicial as $key => $value) {
              $this->mInventarioInicial->BorrarInventarioInicial($value);
            }
          }
        }
        
        function ActualizarInventarioInicialLista($data)
        {
          //ACTUALIZAMOS EL CODIGO DE MERCADERIA SI ESTA ACTIVO EL PARAMETRO
          $parametroCodigoBarraAutomatico = $this->sConstanteSistema->ObtenerParametroCodigoBarraAutomatico();
          if($parametroCodigoBarraAutomatico == 1)
          {
            $mercaderiaUpdate = $this->sMercaderia->ActualizarMercaderiaDesdeInventario($data);
          }

          $data["FechaInicial"] = convertToDate($data["FechaInicial"]);
          $data["FechaVencimiento"] = $data["FechaVencimiento"] == "" ? "" : convertToDate($data["FechaVencimiento"]);
          $data["FechaEmisionDua"] = convertToDate($data["FechaEmisionDua"]);
          $data["FechaEmisionDocumentoSalidaZofra"] = convertToDate($data["FechaEmisionDocumentoSalidaZofra"]);

          if(is_string($data["CantidadInicial"])){$data["CantidadInicial"] = str_replace(',',"",$data["CantidadInicial"]);}
          if(is_string($data["ValorUnitario"])){$data["ValorUnitario"] = str_replace(',',"",$data["ValorUnitario"]);}

          $resultado = $this->mInventarioInicial->ActualizarInventarioInicial($data);
          
          $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();
          $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();
          $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
          
          $data["IdTipoDocumento"] = ID_TIPODOCUMENTO_INVENTARIOINICIAL;
          $asignacionsede = $this->sAsignacionSede->ConsultarAsignacionSede($data["IdAsignacionSede"]);
          $data["CodigoSede"] = (count($asignacionsede) > 0) ? $asignacionsede[0]["CodigoSede"] : "";

          $tipodocumento = $this->sTipoDocumento->ObtenerTipoDocumentoPorId($data);
          $data["CodigoTipoDocumento"] = (count($tipodocumento) > 0) ? $tipodocumento[0]["CodigoTipoDocumento"] : "";

          if(is_string($data["CantidadInicial"])){$data["CantidadInicial"] = str_replace(',',"",$data["CantidadInicial"]);}

          // $this->sMovimientoAlmacen->BorrarMovimientosAlmacenInventarioInicial($data);
          $this->BorrarMovimientosInventarioInicial($data, false);
          if($resultado)
          {
            $data["IdInventarioInicial"] = $data["IdInventarioInicial"];
            $MotivosInventario = $this->sMotivoInventarioInicial->ListarMotivosInventarioInicial();
            $data["MotivoMovimiento"] = (count($MotivosInventario) > 0) ? $MotivosInventario[0]["NombreMotivoInventarioInicial"] : "";
            $data["NombreAlmacen"] = $data["NombreSede"];
            $data["FechaInventario"] = $data["FechaInicial"];
            $data["CostoUnitarioAdquisicion"] = $data["ValorUnitario"];

            if($parametro_lote == 1)
            {
              if ($data["FechaVencimiento"]!="")  {
                $data["FechaVencimiento"] = convertToDate($data["FechaVencimiento"]);
              }
              $loteproducto = $this->sLoteProducto->AgregarLoteProducto($data);

              $data["IdLoteProducto"] = $loteproducto["IdLoteProducto"];

              $this->sMovimientoAlmacenProductoLote->InsertarInventarioInicialEnMovimientoAlmacenProductoLote($data);
            }

            if($parametro_dua == 1 && $data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_DUA)
            {
              $data["FechaEmision"] = convertToDate($data["FechaInventario"]);
              $dua = $this->sDua->AgregarDuaInventarioInicial($data);

              $data["IdDua"] = $dua["IdDua"];

              $this->sMovimientoDocumentoDua->InsertarInventarioInicialEnMovimientoDocumentoDua($data);
            }

            if($parametro_zofra == 1 && $data["IdOrigenMercaderia"] == ID_ORIGEN_MERCADERIA_ZOFRA)
            {
              // $value["FechaEmision"] = convertToDate($data["FechaInventario"]);
              $zofra = $this->sDocumentoSalidaZofra->AgregarDocumentoSalidaZofraInventarioInicial($data);

              $data["IdDocumentoSalidaZofra"] = $zofra["IdDocumentoSalidaZofra"];
              // print_r($zofra);
              // exit;
              $this->sMovimientoDocumentoSalidaZofra->InsertarInventarioInicialEnMovimientoDocumentoSalidaZofra($data);
              
              $zofra = $this->SumarProductosAlmacenZofra($data);
              $data["CantidadInicial"] = $zofra["CantidadInicial"];//$this->sMovimientoDocumentoSalidaZofra->SumarProductosAlmacenZofraParaInventarioPrincipal($data);
              $data["CostoUnitarioAdquisicion"] = $zofra["ValorUnitario"];
              // print_r($data);
            }
            // print_r($data);exit;
            //INSERTAMOS MOVIMIENTO DE ALMACEN
            $this->sMovimientoAlmacen->InsertarInventarioInicialEnMovimientoAlmacen($data);
            // $data["IdSede"]=$data["IdSede"];
            $this->ActualizarPrecioMinimo($data);
          }

          // return $data;
          return "";
        }

        function BorrarInventarioInicialLista($data)
        {
          $this->mInventarioInicial->BorrarInventarioInicial($data);

          $this->BorrarMovimientosInventarioInicial($data);

          return "";
        }


        function BorrarMovimientosInventarioInicial($data, $json = true)
        {
          $this->sMovimientoAlmacen->BorrarMovimientosAlmacenInventarioInicial($data);

          $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();
          $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
          $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();

          if($parametro_lote == 1)
          {
            /*Aqui se debe borrar data de movimientoalmacenproductolote*/
            $this->sMovimientoAlmacenProductoLote->BorrarMovimientosAlmacenInventarioInicial($data);
          }

          if($parametro_zofra == 1)
          {
            //SE añade funcion de actualizado
            $this->sMovimientoDocumentoSalidaZofra->BorrarMovimientosDocumentoSalidaZofraProductoInventarioInicial($data);
          }

          if($parametro_dua == 1)
          {
            $this->sMovimientoDocumentoDua->BorrarMovimientosDocumentoDuaInventarioInicial($data);
          }

        }

        //FUNCION PARA ACTUALIZAR LAS FECHAS DE LOS INVENTARIO Y MOVIMIENTOS
        function ActualizarFechaInventariosInicial($data)
        {
          $data["FechaMovimiento"] = convertToDate($data["FechaMovimiento"]);

          $parametro_lote = $this->sConstanteSistema->ObtenerParametroLote();
          $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
          $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();

          $response = $this->mInventarioInicial->ActualizarFechaInventariosInicial($data);
          $this->sMovimientoAlmacen->ActualizarFechaParaInventariosInicial($data);

          if($parametro_lote == 1)
          {
            /*Aqui se debe borrar data de movimientoalmacenproductolote*/
            $this->sMovimientoAlmacenProductoLote->ActualizarFechaParaInventariosInicial($data);
          }

          if($parametro_zofra == 1)
          {
            //SE añade funcion de actualizado
            $this->sMovimientoDocumentoSalidaZofra->ActualizarFechaParaInventariosInicial($data);
          }

          if($parametro_dua == 1)
          {
            $this->sMovimientoDocumentoDua->ActualizarFechaParaInventariosInicial($data);
          }

          return $data;
        }

        //Calcularmos costos y cantidades por inventario inicial
        function SumarProductosAlmacenZofra($data)
        {
          $cantidad = 0;
          $valor = 0;
          $productos = $this->mInventarioInicial->ObtenerProductoZofraPorProductoYAlmacen($data);
          $total = count($productos);
          // print_r($productos);exit;
          foreach ($productos as $key => $value) {
            $cantidad += $value["CantidadInicial"];
            $valor += $value["ValorUnitario"];
          }
          if($valor == 0)
          {
            $valorunitario = 0;
          }
          else
          {
            $valorunitario = $valor / $total;
          }
          
          $resultado["CantidadInicial"] = $cantidad;
          $resultado["ValorUnitario"] = $valorunitario;
          return $resultado;
        }

        function ConsultarInventarioInicialPorIdProductoSede($data) {

          $resultado = $this->mInventarioInicial->ConsultarInventarioInicialPorIdProductoSede($data);
          return $resultado;
        }

        function ActualizarPrecioMinimo($data) {
          $resultadocompra = $this->sProducto->ValidarExistenciaProductoEnDetalleComprobanteCompra($data);
          
          if ($resultadocompra =="") {
            $resultadoPrecio=$this->sListaPrecioMercaderia->ObtenerPrecioMinimoPorIdProducto($data);
            if (count($resultadoPrecio)>0) {
              $resultadoPrecio[0]["Precio"] = $data["ValorUnitario"];//PrecioUnitario
              $this->sListaPrecioMercaderia->ActualizarListaPrecioMercaderia($resultadoPrecio[0]);
            }
            else {
              $resultadoTipoListaPrecio = $this->sTipoListaPrecio->ObtenerTipoListaPrecioMinimo();
              if (count($resultadoTipoListaPrecio) > 0) {
                $resultadoPrecio["IdTipoListaPrecio"]=$resultadoTipoListaPrecio[0]["IdTipoListaPrecio"];
                $resultadoPrecio["IdProducto"]=$data["IdProducto"];
                $resultadoPrecio["Precio"]=$data["ValorUnitario"];//PrecioUnitario
                $this->sListaPrecioMercaderia->InsertarListaPrecioMercaderia($resultadoPrecio);
              }
            }
          }    
        }
}

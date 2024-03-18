<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVistaModeloReporteInventario extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->service("Reporte/Inventario/sStockProducto");
		$this->load->service("Reporte/Inventario/sStockProductoLote");
		$this->load->service("Reporte/Inventario/sStockProductoMarca");
		$this->load->service("Reporte/Inventario/sStockProductoDua");
		$this->load->service("Reporte/Inventario/sStockProductoDocumentoZofra");
		$this->load->service("Reporte/Inventario/sReporteMovimientoDocumentoDua");
		$this->load->service("Reporte/Inventario/sStockProductoDocumentoZofra");
		$this->load->service("Reporte/Inventario/sMovimientoDocumentoZofra");
		$this->load->service("Reporte/Inventario/sStockNegativo");
		$this->load->service("Reporte/Inventario/sMovimientoAlmacenValorado");
		$this->load->service("Reporte/Inventario/sMovimientoMercaderia");
		$this->load->service("Reporte/Inventario/sMovimientoAlmacenDocumentoIngreso");
		$this->load->service("Reporte/Inventario/sReporteDocumentoIngreso");
		$this->load->service("Reporte/Inventario/sReporteInventario");
		$this->load->service("Configuracion/Catalogo/sMarca");
		$this->load->service("Configuracion/General/sSede");
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->helper("date");
    	$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	private function CargarParametros()
	{
			$dataParametros["ParametroDua"] = $this->sConstanteSistema->ObtenerParametroDua();
			$dataParametros["ParametroZofra"] = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
			$dataParametros["ParametroDocumentoIngreso"] = $this->sConstanteSistema->ObtenerParametroDocumentoIngreso();
			$dataParametros["ParametroLote"] = $this->sConstanteSistema->ObtenerParametroLote();

			return $dataParametros;
	 }

	private function CargarReporteStockProducto()
 	{
		$Buscador["FechaHoy_StockProducto"] = convertirFechaES($this->sStockProducto->obtener_fecha_hoy());
		$Buscador["FechaDeterminada_StockProducto"] = "";
		$Buscador["Fecha_StockProducto"] = "0";
		$Buscador["NombreArchivoReporte_StockProducto"] = $this->sStockProducto->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_StockProducto"] = $this->sStockProducto->ObtenerNombreArchivoJasper();
		$Buscador["Producto_StockProducto"] = '0';
		$Buscador["Orden_StockProducto"] = '0';
		$Buscador["Texto_Producto_StockProducto"] = '';
		$Buscador["IdSede_StockProducto"] = '';

		$input["IdModuloSistema"] = FILTRO_TODOS;
		$input["IdTipoDocumento"] = FILTRO_TODOS;
		$Buscador["TiposDocumentoVenta"] = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input,0);
		$Buscador["TiposDocumento"] = array();
		$Buscador["TotalTipoDocumentos"] = count($Buscador["TiposDocumentoVenta"]);
		$Buscador["NumeroDocumentosSeleccionados"] = 0;

		$Almacenes = $this->sSede->ListarSedesTipoAlmacen();

		$dataReporteStockProducto = array("dataReporteStockProducto" =>
					array(
						'Buscador'=>$Buscador,
						'Almacenes'=>$Almacenes
					)
		 );
		return $dataReporteStockProducto;
 	}

	 private function CargarReporteStockProductoLote() {
		$Buscador["FechaHoy_StockProductoLote"] = convertirFechaES($this->sStockProductoLote->obtener_fecha_hoy());
		$Buscador["FechaDeterminada_StockProductoLote"] = "";
		$Buscador["Fecha_StockProductoLote"] = "0";
		$Buscador["NombreArchivoReporte_StockProductoLote"] = $this->sStockProductoLote->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_StockProductoLote"] = $this->sStockProductoLote->ObtenerNombreArchivoJasper();
		$Buscador["Producto_StockProductoLote"] = '0';
		$Buscador["Orden_StockProductoLote"] = '0';
		$Buscador["Texto_Producto_StockProductoLote"] = '';
		$Buscador["IdSede_StockProductoLote"] = '';

		$input["IdModuloSistema"] = FILTRO_TODOS;
		$input["IdTipoDocumento"] = FILTRO_TODOS;
		$Buscador["TiposDocumentoVenta"] = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input,0);
		$Buscador["TiposDocumento"] = array();
		$Buscador["TotalTipoDocumentos"] = count($Buscador["TiposDocumentoVenta"]);
		$Buscador["NumeroDocumentosSeleccionados"] = 0;

		$Almacenes = $this->sSede->ListarSedesTipoAlmacen();

		$dataReporteStockProductoLote = array("dataReporteStockProductoLote" =>
					array(
						'Buscador'=>$Buscador,
						'Almacenes'=>$Almacenes
					)
		 );
		return $dataReporteStockProductoLote;
	 }
	 
 	private function CargarReporteStockProductoMarca()
 	{
		 $Buscador["FechaHoy_StockProductoMarca"] = convertirFechaES($this->sStockProductoMarca->obtener_fecha_hoy());
		 $Buscador["FechaDeterminada_StockProductoMarca"] = "";
		 $Buscador["Fecha_StockProductoMarca"] = "0";
		 $Buscador["NombreArchivoReporte_StockProductoMarca"] = $this->sStockProductoMarca->ObtenerNombreArchivoReporte();
		 $Buscador["NombreArchivoJasper_StockProductoMarca"] = $this->sStockProductoMarca->ObtenerNombreArchivoJasper();
		 $Buscador["Producto_StockProductoMarca"] = '0';
		 $Buscador["Orden_StockProductoMarca"] = '0';
		 $Buscador["Texto_Producto_StockProductoMarca"] = '';
		 $Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		 $Buscador["Marcas"] = $this->sMarca->ListarMarcas();

		 $input["IdModuloSistema"] = FILTRO_TODOS;
		 $input["IdTipoDocumento"] = FILTRO_TODOS;
		 $Buscador["TiposDocumentoVenta"] = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input,0);
		 $Buscador["TiposDocumento"] = array();
		 $Buscador["TotalTipoDocumentos"] = count($Buscador["TiposDocumentoVenta"]);
		 $Buscador["NumeroDocumentosSeleccionados"] = 0;

		 $dataReporteStockProductoMarca = array("dataReporteStockProductoMarca" =>
					 array(
						 'Buscador'=>$Buscador
					 )
			);
		 return $dataReporteStockProductoMarca;
	}

	private function CargarReporteStockProductoDua()
	{
		$Buscador["Fecha_StockProductoDua"] = "0";
		$Buscador["Producto_StockProductoDua"] = '0';
		$Buscador["Dua_StockProductoDua"] = '0';
		$Buscador["Orden_StockProductoDua"] = '0';
		$Buscador["FechaHoy_StockProductoDua"] = convertirFechaES($this->sStockProductoDua->obtener_fecha_hoy());
		$Buscador["NombreArchivoReporte_StockProductoDua"] = $this->sStockProductoDua->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_StockProductoDua"] = $this->sStockProductoDua->ObtenerNombreArchivoJasper();
		$Buscador["FechaInicio_StockProductoDua"] = $this->sStockProductoDua->obtener_primer_dia_mes();
		$Buscador["FechaFin_StockProductoDua"] = $this->sStockProductoDua->obtener_ultimo_dia_mes();;
		$Buscador["FechaDeterminada_StockProductoDua"] = "";
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();

		$dataReporteStockProductoDua = array("dataReporteStockProductoDua" =>
					array(
						'Buscador'=>$Buscador
					)
		 );
		return $dataReporteStockProductoDua;
	}

	private function CargarReporteDocumentoIngreso()
	{
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["DI_DocumentoIngreso"] = '0';
		$Buscador["FechaInicio_DocumentoIngreso"] = $this->sReporteDocumentoIngreso->obtener_primer_dia_mes();
		$Buscador["FechaFin_DocumentoIngreso"] = $this->sReporteDocumentoIngreso->obtener_ultimo_dia_mes();;
		$Buscador["NombreArchivoReporte_DocumentoIngreso"] = $this->sReporteDocumentoIngreso->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_DocumentoIngreso"] = $this->sReporteDocumentoIngreso->ObtenerNombreArchivoJasper();

		$dataReporteDocumentoIngreso = array("dataReporteDocumentoIngreso" =>
					array(
						'Buscador'=>$Buscador
					)
		 );
		return $dataReporteDocumentoIngreso;
	}

	private function CargarReporteMovimientoDocumentoDua()
	{
		$Buscador["Producto_MovimientoDocumentoDua"] = '0';
		$Buscador["Dua_MovimientoDocumentoDua"] = '0';
		$Buscador["NombreArchivoReporte_MovimientoDocumentoDua"] = $this->sReporteMovimientoDocumentoDua->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_MovimientoDocumentoDua"] = $this->sReporteMovimientoDocumentoDua->ObtenerNombreArchivoJasper();
		$Buscador["FechaInicioDua_MovimientoDocumentoDua"] = $this->sReporteMovimientoDocumentoDua->obtener_primer_dia_mes();
		$Buscador["FechaFinDua_MovimientoDocumentoDua"] = $this->sReporteMovimientoDocumentoDua->obtener_ultimo_dia_mes();
		$Buscador["FechaInicio_MovimientoDocumentoDua"] = $this->sReporteMovimientoDocumentoDua->obtener_primer_dia_mes();
		$Buscador["FechaFin_MovimientoDocumentoDua"] = $this->sReporteMovimientoDocumentoDua->obtener_ultimo_dia_mes();
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();

		$dataReporteMovimientoDocumentoDua = array("dataReporteMovimientoDocumentoDua" =>
					array(
						'Buscador'=>$Buscador
					)
		 );
		return $dataReporteMovimientoDocumentoDua;
	}

	private function CargarReporteStockProductoDocumentoZofra()
	{
		$Buscador["Fecha_StockProductoDocumentoZofra"] = "0";
		$Buscador["Producto_StockProductoDocumentoZofra"] = '0';
		$Buscador["DocumentoSalidaZofra_StockProductoDocumentoZofra"] = '0';
		$Buscador["Orden_StockProductoDocumentoZofra"] = '0';
		$Buscador["FechaHoy_StockProductoDocumentoZofra"] = convertirFechaES($this->sStockProductoDocumentoZofra->obtener_fecha_hoy());
		$Buscador["NombreArchivoReporte_StockProductoDocumentoZofra"] = $this->sStockProductoDocumentoZofra->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_StockProductoDocumentoZofra"] = $this->sStockProductoDocumentoZofra->ObtenerNombreArchivoJasper();
		$Buscador["FechaInicio_StockProductoDocumentoZofra"] = $this->sStockProductoDocumentoZofra->obtener_primer_dia_mes();
		$Buscador["FechaFin_StockProductoDocumentoZofra"] = $this->sStockProductoDocumentoZofra->obtener_ultimo_dia_mes();;
		$Buscador["FechaDeterminada_StockProductoDocumentoZofra"] = "";
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();

		$dataReporteStockProductoDocumentoZofra = array("dataReporteStockProductoDocumentoZofra" =>
					array(
						'Buscador'=>$Buscador
					)
		 );
		return $dataReporteStockProductoDocumentoZofra;
	}

	private function CargarReporteMovimientoDocumentoZofra()
	{
		$Buscador["Producto_MovimientoDocumentoZofra"] = '0';
		$Buscador["DocumentoSalidaZofra_MovimientoDocumentoZofra"] = '0';
		$Buscador["NombreArchivoReporte_MovimientoDocumentoZofra"] = $this->sMovimientoDocumentoZofra->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_MovimientoDocumentoZofra"] = $this->sMovimientoDocumentoZofra->ObtenerNombreArchivoJasper();
		$Buscador["FechaInicioDocumentoSalidaZofra_MovimientoDocumentoZofra"] = $this->sMovimientoDocumentoZofra->obtener_primer_dia_mes();
		$Buscador["FechaFinDocumentoSalidaZofra_MovimientoDocumentoZofra"] = $this->sMovimientoDocumentoZofra->obtener_ultimo_dia_mes();
		$Buscador["FechaInicio_MovimientoDocumentoZofra"] = $this->sMovimientoDocumentoZofra->obtener_primer_dia_mes();
		$Buscador["FechaFin_MovimientoDocumentoZofra"] = $this->sMovimientoDocumentoZofra->obtener_ultimo_dia_mes();
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();

		$dataReporteMovimientoDocumentoZofra = array("dataReporteMovimientoDocumentoZofra" =>
					array(
						'Buscador'=>$Buscador
					)
		 );
		return $dataReporteMovimientoDocumentoZofra;
	}

	 private function CargarReporteStockNegativo()
	 {
		 $Buscador["FechaHoy_StockNegativo"] = convertirFechaES($this->sStockNegativo->obtener_fecha_hoy());
		 $Buscador["FechaDeterminada_StockNegativo"] = "";
		 $Buscador["Fecha_StockNegativo"] = "0";
		 $Buscador["NombreArchivoReporte_StockNegativo"] = $this->sStockNegativo->ObtenerNombreArchivoReporte();
		 $Buscador["NombreArchivoJasper_StockNegativo"] = $this->sStockNegativo->ObtenerNombreArchivoJasper();
		 $Buscador["Producto_StockNegativo"] = '0';
		 $Buscador["Orden_StockNegativo"] = '0';
		 $Buscador["Texto_Producto_StockNegativo"] = '';
		 $Buscador["IdSede_StockNegativo"] = '';
		 $Almacenes = $this->sSede->ListarSedesTipoAlmacen();

			 $dataReporteStockNegativo = array("dataReporteStockNegativo" =>
						 array(
							 'Buscador'=>$Buscador,
							 'Almacenes'=>$Almacenes
						 )
				);
			 return $dataReporteStockNegativo;

		}

		private function CargarReporteMovimientoAlmacenValorado()
		{
				$Buscador["FechaInicio_MovimientoAlmacenValorado"] = $this->sMovimientoAlmacenValorado->obtener_primer_dia_mes();
				$Buscador["FechaFin_MovimientoAlmacenValorado"] = $this->sMovimientoAlmacenValorado->obtener_ultimo_dia_mes();;
				$Buscador["NombreArchivoReporte_MovimientoAlmacenValorado"] = $this->sMovimientoAlmacenValorado->ObtenerNombreArchivoReporte();
				$Buscador["NombreArchivoJasper_MovimientoAlmacenValorado"] = $this->sMovimientoAlmacenValorado->ObtenerNombreArchivoJasper();
				$Buscador["Producto_MovimientoAlmacenValorado"] = '0';
				$Buscador["Texto_Movimiento_MovimientoAlmacenValorado"] = '';
				$Buscador["IdSede_MovimientoAlmacenValorado"] = '';
				$Almacenes = $this->sSede->ListarSedesTipoAlmacen();

				$input["IdModuloSistema"] = FILTRO_TODOS;
				$input["IdTipoDocumento"] = FILTRO_TODOS;
				$Buscador["TiposDocumentoVenta"] = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input,0);
				$Buscador["TiposDocumento"] = array();
				$Buscador["TotalTipoDocumentos"] = count($Buscador["TiposDocumentoVenta"]);
				$Buscador["NumeroDocumentosSeleccionados"] = 0;

				$dataReporteMovimientoAlmacenValorado = array("dataReporteMovimientoAlmacenValorado" =>
							array(
								'Buscador'=>$Buscador,
								'Almacenes'=>$Almacenes
							)
				 );
				return $dataReporteMovimientoAlmacenValorado;
		 }

		 private function CargarReporteMovimientoMercaderia()
		 {
				 $Buscador["FechaInicio_MovimientoMercaderia"] = $this->sMovimientoMercaderia->obtener_primer_dia_mes();
				 $Buscador["FechaFin_MovimientoMercaderia"] = $this->sMovimientoMercaderia->obtener_ultimo_dia_mes();;
				 $Buscador["NombreArchivoReporte_MovimientoMercaderia"] = $this->sMovimientoMercaderia->ObtenerNombreArchivoReporte();
				 $Buscador["NombreArchivoJasper_MovimientoMercaderia"] = $this->sMovimientoMercaderia->ObtenerNombreArchivoJasper();
				 $Buscador["Producto_MovimientoMercaderia"] = '0';
				 $Buscador["Texto_Movimiento_MovimientoMercaderia"] = '';
				 $Buscador["IdSede_MovimientoMercaderia"] = '';
				 $Almacenes = $this->sSede->ListarSedesTipoAlmacen();

				 $input["IdModuloSistema"] = FILTRO_TODOS;
				 $input["IdTipoDocumento"] = FILTRO_TODOS;
				 $Buscador["TiposDocumentoVenta"] = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input,0);
				 $Buscador["TiposDocumento"] = array();
				 $Buscador["TotalTipoDocumentos"] = count($Buscador["TiposDocumentoVenta"]);
				 $Buscador["NumeroDocumentosSeleccionados"] = 0;

				 $dataReporteMovimientoMercaderia = array("dataReporteMovimientoMercaderia" =>
							 array(
								 'Buscador'=>$Buscador,
								 'Almacenes'=>$Almacenes
							 )
					);
				 return $dataReporteMovimientoMercaderia;
			}

			private function CargarReporteMovimientoAlmacenDocumentoIngreso()
			{
					$Buscador["FechaInicio_MovimientoAlmacenDocumentoIngreso"] = $this->sMovimientoAlmacenDocumentoIngreso->obtener_primer_dia_mes();
					$Buscador["FechaFin_MovimientoAlmacenDocumentoIngreso"] = $this->sMovimientoAlmacenDocumentoIngreso->obtener_ultimo_dia_mes();;
					$Buscador["NombreArchivoReporte_MovimientoAlmacenDocumentoIngreso"] = $this->sMovimientoAlmacenDocumentoIngreso->ObtenerNombreArchivoReporte();
					$Buscador["NombreArchivoJasper_MovimientoAlmacenDocumentoIngreso"] = $this->sMovimientoAlmacenDocumentoIngreso->ObtenerNombreArchivoJasper();
					$Buscador["Producto_MovimientoAlmacenDocumentoIngreso"] = '0';
					$Buscador["Texto_Movimiento_MovimientoAlmacenDocumentoIngreso"] = '';
					$Buscador["IdSede_MovimientoAlmacenDocumentoIngreso"] = '';
					$Almacenes = $this->sSede->ListarSedesTipoAlmacen();

					$dataReporteMovimientoAlmacenDocumentoIngreso = array("dataReporteMovimientoAlmacenDocumentoIngreso" =>
								array(
									'Buscador'=>$Buscador,
									'Almacenes'=>$Almacenes
								)
					 );
					return $dataReporteMovimientoAlmacenDocumentoIngreso;
			 }

			 private function CargarReporteInventario()
			 {
					 $Buscador["FechaInicio_Inventario"] = $this->sReporteInventario->obtener_primer_dia_mes();
					 $Buscador["FechaFin_Inventario"] = $this->sReporteInventario->obtener_ultimo_dia_mes();;
					 $Buscador["NombreArchivoReporte_Inventario"] = $this->sReporteInventario->ObtenerNombreArchivoReporte();
					 $Buscador["NombreArchivoJasper_Inventario"] = $this->sReporteInventario->ObtenerNombreArchivoJasper();
					 $Buscador["Producto_Inventario"] = '0';
					 $Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();

					 $dataReporteInventario = array("dataReporteInventario" =>
								 array(
									 'Buscador'=>$Buscador
								 )
						);
					 return $dataReporteInventario;
				}


		public function Index()
		{
			// StockProducto
			$view_['view_content_stockproducto'] =  $this->load->View('Reporte/Inventario/StockProducto/view_mainpanel_content_stockproducto','',true);

			// StockProductoMarca
			$view_['view_content_stockproductomarca'] =  $this->load->View('Reporte/Inventario/StockProductoMarca/view_mainpanel_content_stockproductomarca','',true);

			// StockProductoDua
			$view_['view_content_stockproductodua'] =  $this->load->View('Reporte/Inventario/StockProductoDua/view_mainpanel_content_stockproductodua','',true);

			// StockProductoLote
			$view_['view_content_stockproductolote'] =  $this->load->View('Reporte/Inventario/StockProductoLote/view_mainpanel_content_stockproductolote','',true);

			// MovimientoDocumentoDua
			$view_['view_content_movimientodocumentodua'] =  $this->load->View('Reporte/Inventario/MovimientoDocumentoDua/view_mainpanel_content_movimientodocumentodua','',true);

			// StockProductoDocumentoZofra
			$view_['view_content_stockproductodocumentozofra'] =  $this->load->View('Reporte/Inventario/StockProductoDocumentoZofra/view_mainpanel_content_stockproductodocumentozofra','',true);

			// MovimientoDocumentoZofra
			$view_['view_content_movimientodocumentozofra'] =  $this->load->View('Reporte/Inventario/MovimientoDocumentoZofra/view_mainpanel_content_movimientodocumentozofra','',true);

			// StockNegativo
			$view_['view_content_stocknegativo'] =  $this->load->View('Reporte/Inventario/StockNegativo/view_mainpanel_content_stocknegativo','',true);

			// MovimientoAlmacenValorado
			$view_['view_content_movimientoalmacenvalorado'] =  $this->load->View('Reporte/Inventario/MovimientoAlmacenValorado/view_mainpanel_content_movimientoalmacenvalorado','',true);

			// ReporteDocumentoIngreso
			$view_['view_content_reportedocumentoingreso'] =  $this->load->View('Reporte/Inventario/ReporteDocumentoIngreso/view_mainpanel_content_reportedocumentoingreso','',true);

			// MovimientoMercaderia
			$view_['view_content_movimientomercaderia'] =  $this->load->View('Reporte/Inventario/MovimientoMercaderia/view_mainpanel_content_movimientomercaderia','',true);

			// MovimientoAlmacenDocumentoIngreso
			$view_['view_content_movimientoalmacendocumentoingreso'] =  $this->load->View('Reporte/Inventario/MovimientoAlmacenDocumentoIngreso/view_mainpanel_content_movimientoalmacendocumentoingreso','',true);

			// ReporteInventario
			$view_['view_content_reporteinventario'] =  $this->load->View('Reporte/Inventario/ReporteInventario/view_mainpanel_content_reporteinventario','',true);


			$data['dataReporteStockProducto'] = $this->CargarReporteStockProducto();
			$data['dataReporteStockProductoMarca'] = $this->CargarReporteStockProductoMarca();
			$data['dataReporteStockProductoDua'] =  $this->CargarReporteStockProductoDua();
			$data['dataReporteStockProductoLote'] = $this->CargarReporteStockProductoLote();
			$data['dataReporteMovimientoDocumentoDua'] =  $this->CargarReporteMovimientoDocumentoDua();
			$data['dataReporteStockProductoDocumentoZofra'] =  $this->CargarReporteStockProductoDocumentoZofra();
			$data['dataReporteMovimientoDocumentoZofra'] =  $this->CargarReporteMovimientoDocumentoZofra();
			$data['dataReporteStockNegativo'] =  $this->CargarReporteStockNegativo();
			$data['dataReporteMovimientoAlmacenValorado'] = $this->CargarReporteMovimientoAlmacenValorado();
			$data['dataReporteDocumentoIngreso'] = $this->CargarReporteDocumentoIngreso();
			$data['dataReporteMovimientoMercaderia'] = $this->CargarReporteMovimientoMercaderia();
			$data['dataReporteMovimientoAlmacenDocumentoIngreso'] = $this->CargarReporteMovimientoAlmacenDocumentoIngreso();
			$data['dataReporteInventario'] = $this->CargarReporteInventario();
			$data['parametros'] = $this->CargarParametros();
			$view_data_vistamodeloreporteinventario['data']= $data;

			$view_subcontent['view_mainpanel_subcontent_modal_reportevistaprevia'] =  $this->load->View('Reporte/VistaModeloReporteInventario/view_mainpanel_subcontent_modal_reportevistaprevia','',true);
			$view_vistamodelogeneral['view_main_vistamodelogeneralinventario'] = $this->load->View('Reporte/VistaModeloReporteInventario/view_main_vistamodeloreporteinventario',$view_subcontent,true);
			$view_ext['view_footer_extension'] = $this->load->View('Reporte/VistaModeloReporteInventario/view_mainpanel_footer_vistamodeloreporteinventario',$view_data_vistamodeloreporteinventario,true);

			$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
			$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
			$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
			$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
			$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
			$view['view_content'] =  $this->load->View('Reporte/VistaModeloReporteInventario/view_main_vistamodeloreporteinventario',$view_,true);
			$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

		    $this->load->View('.Master/master_view_mainpanel',$view);
		}
}

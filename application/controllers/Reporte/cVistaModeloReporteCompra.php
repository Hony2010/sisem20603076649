<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVistaModeloReporteCompra extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Compra/sReporteProductoProveedor");
		$this->load->service("Reporte/Compra/sCompraDetallado");
		$this->load->service("Reporte/Compra/sCompraGeneral");
		$this->load->service("Reporte/Compra/sProductosMasComprados");
		$this->load->service("Reporte/Compra/sComprasMensuales");
		$this->load->service("Reporte/Compra/sComprasPorMercaderia");
		$this->load->service("Reporte/Compra/sReporteSaldoProveedor");
		$this->load->service("Reporte/Compra/sReporteFormato8_1Compra");
		$this->load->service("Configuracion/Compra/sTipoCompra");
		$this->load->service("Configuracion/General/sPeriodo");
		$this->load->service('Configuracion/General/sConstanteSistema');
    	$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	private function CargarParametros()
	{
			$dataParametros["ParametroCodigoProductoProveedor"] = $this->sConstanteSistema->ObtenerParametroCodigoProductoProveedor();
			return $dataParametros;
	 }

	private function CargarReporteCompraDetallado()
	{
			$Buscador["FechaInicio_Detallado"] = $this->sCompraDetallado->obtener_primer_dia_mes();
			$Buscador["FechaFinal_Detallado"] = $this->sCompraDetallado->obtener_ultimo_dia_mes();
			$Buscador["NombreArchivoReporte_Detallado"] = $this->sCompraDetallado->ObtenerNombreArchivoReporte();
			$Buscador["NombreArchivoJasper_Detallado"] = $this->sCompraDetallado->ObtenerNombreArchivoJasper();
			$Buscador["TiposCompra_Detallado"] = $this->sTipoCompra->ListarTiposCompra();
			$Buscador["IdTipoCompra_Detallado"] = '0';
			$Buscador["CopiaTipoCompra_Detallado"] = '';
			$Buscador["FormaPago_Detallado"] = '0';
			$Buscador["NumeroDocumentoIdentidad_Detallado"] = '0';
			$Buscador["Orden_Detallado"] = '0';
			$Buscador["TextoCliente_Detallado"] = '';

			$Buscador["AñoPeriodo_Detallado"] = $this->sPeriodo->ListarPeriodoAños();
			$data = $Buscador["AñoPeriodo_Detallado"][0];
			$Buscador["MesesPeriodo_Detallado"] = $this->sPeriodo->ListarPeriodoPorAño($data);

			$dataReporteCompraDetallado = array("dataReporteCompraDetallado" =>
						array(
							'Buscador'=>$Buscador
						)
			 );
			return $dataReporteCompraDetallado;

	 }

	private function CargarReporteCompraGeneral()
	{
		$Buscador["FechaInicio_General"] = $this->sCompraGeneral->obtener_primer_dia_mes();
		$Buscador["FechaFinal_General"] = $this->sCompraGeneral->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_General"] = $this->sCompraGeneral->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_General"] = $this->sCompraGeneral->ObtenerNombreArchivoJasper();
		$Buscador["TiposCompra_General"] = $this->sTipoCompra->ListarTiposCompra();
		$Buscador["IdTipoCompra_General"] = '0';
		$Buscador["CopiaTipoCompra_General"] = '';
		$Buscador["NumeroDocumentoIdentidad_General"] = '0';
		$Buscador["TextoCliente_General"] = '';

		$Buscador["AñoPeriodo_General"] = $this->sPeriodo->ListarPeriodoAños();
		$data = $Buscador["AñoPeriodo_General"][0];
		$Buscador["MesesPeriodo_General"] = $this->sPeriodo->ListarPeriodoPorAño($data);

		$dataReporteCompraGeneral = array("dataReporteCompraGeneral" =>
					array(
						'Buscador'=>$Buscador
					)
		 );

		return $dataReporteCompraGeneral;
	}

	private function CargarReporteProductoMasComprado()
	{
		$Buscador["FechaInicio_ProductoMasComprado"] = $this->sProductosMasComprados->obtener_primer_dia_mes();
		$Buscador["FechaFinal_ProductoMasComprado"] = $this->sProductosMasComprados->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_ProductoMasComprado"] = $this->sProductosMasComprados->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_ProductoMasComprado"] = $this->sProductosMasComprados->ObtenerNombreArchivoJasper();
		$Buscador["CantidadFilas_ProductoMasComprado"] = '0';

		$dataReporteProductoMasComprado = array("dataReporteProductoMasComprado" =>
					array(
						'Buscador'=>$Buscador
					)
		 );

		return $dataReporteProductoMasComprado;
	}

	private function CargarReporteComprasMensuales()
	{
		$Buscador["AñoPeriodo_ComprasMensuales"] = $this->sPeriodo->ListarPeriodoAños();
		$data = $Buscador["AñoPeriodo_ComprasMensuales"][0];
		$Buscador["MesesPeriodo_ComprasMensuales"] = $this->sPeriodo->ListarPeriodoPorAño($data);
		$Buscador["NombreArchivoReporte_ComprasMensuales"] = $this->sComprasMensuales->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_ComprasMensuales"] = $this->sComprasMensuales->ObtenerNombreArchivoJasper();

		$dataReporteComprasMensuales = array("dataReporteComprasMensuales" =>
					array(
						'Buscador'=>$Buscador
					)
		 );

		return $dataReporteComprasMensuales;
	}

	private function CargarReporteComprasPorMercaderia()
	{
		$Buscador["FechaInicio_Mercaderia"] = $this->sComprasPorMercaderia->obtener_primer_dia_mes();
		$Buscador["FechaFinal_Mercaderia"] = $this->sComprasPorMercaderia->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_Mercaderia"] = $this->sComprasPorMercaderia->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Mercaderia"] = $this->sComprasPorMercaderia->ObtenerNombreArchivoJasper();
		$Buscador["IdProducto_Mercaderia"] = '0';
		$Buscador["TextoMercaderia_Mercaderia"] = '';

		$dataReporteComprasPorMercaderia = array("dataReporteComprasPorMercaderia" =>
					array(
						'Buscador'=>$Buscador
					)
		 );

		return $dataReporteComprasPorMercaderia;
	}

	private function CargarReporteSaldoProveedor()
	{
		$Buscador["FechaInicio_SaldoProveedor"] = $this->sReporteSaldoProveedor->obtener_primer_dia_mes();
		$Buscador["FechaFinal_SaldoProveedor"] = $this->sReporteSaldoProveedor->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_SaldoProveedor"] = $this->sReporteSaldoProveedor->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_SaldoProveedor"] = $this->sReporteSaldoProveedor->ObtenerNombreArchivoJasper();
		$Buscador["IdPersona_SaldoProveedor"] = '0';

		$dataReporteSaldoProveedor = array("dataReporteSaldoProveedor" =>
					array(
						'Buscador'=>$Buscador
					)
		 );

		return $dataReporteSaldoProveedor;
	}

	private function CargarReporteProductoPorProveedor()
	{
		$Buscador["NombreArchivoReporte_ProductoProveedor"] = $this->sReporteProductoProveedor->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_ProductoProveedor"] = $this->sReporteProductoProveedor->ObtenerNombreArchivoJasper();
		$Buscador["IdProducto_ProductoProveedor"] = '0';
		$Buscador["IdProveedor_ProductoProveedor"] = '0';
		$Buscador["TextoMercaderia_ProductoProveedor"] = '';
		$Buscador["TextoProveedor_ProductoProveedor"] = '';

		$dataReporteProductoPorProveedor = array("dataReporteProductoPorProveedor" =>
					array(
						'Buscador'=>$Buscador
					)
		 );

		return $dataReporteProductoPorProveedor;
	}

	private function CargarReporteFormato8_1Compra() {
		/*
		$Buscador["AñoPeriodo_Formato8"] = $this->sPeriodo->ListarPeriodoAños();
		$data = $Buscador["AñoPeriodo_Formato8"][0];
		$Buscador["MesesPeriodo_Formato8"] = $this->sPeriodo->ListarPeriodoPorAño($data);
		$Buscador["NombreArchivoReporte_Formato8"] = $this->sReporteFormato8_1Compra->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Formato8"] = $this->sReporteFormato8_1Compra->ObtenerNombreArchivoJasper();		
		*/
		//$Buscador["Año"]
		$Buscador=$this->sReporteFormato8_1Compra->Cargar();
		$dataReporteFormato8_1Compra = array("dataReporteFormato8_1Compra" =>
					array(
						'Buscador'=>$Buscador						
					)
		 );

		return $dataReporteFormato8_1Compra;
	}

	public function Index()
	{
		// CompraDetallado
		$view_['view_content_compradetallado'] =  $this->load->View('Reporte/Compra/CompraDetallado/view_mainpanel_content_compradetallado','',true);
		// CompraGeneral
		$view_['view_content_comprageneral'] =  $this->load->View('Reporte/Compra/CompraGeneral/view_mainpanel_content_comprageneral','',true);
		// ProductoMasComprado
		$view_['view_content_productosmascomprados'] =  $this->load->View('Reporte/Compra/ProductosMasComprados/view_mainpanel_content_productosmascomprados','',true);
		// ComprasMensuales
		$view_['view_content_comprasmensuales'] =  $this->load->View('Reporte/Compra/ComprasMensuales/view_mainpanel_content_comprasmensuales','',true);
		// ComprasPorMercaderia
		$view_['view_content_compraspormercaderia'] =  $this->load->View('Reporte/Compra/ComprasPorMercaderia/view_mainpanel_content_compraspormercaderia','',true);
		// ReportesaldoProveedor
		$view_['view_content_reportesaldoproveedor'] =  $this->load->View('Reporte/Compra/ReportesaldoProveedor/view_mainpanel_content_reportesaldoproveedor','',true);
		// ProductoProveedor
		$view_['view_content_productoporproveedor'] =  $this->load->View('Reporte/Compra/ProductoProveedor/view_mainpanel_content_productoproveedor','',true);
		// ReporteFormato8_1Compra
		$view_['view_content_formato8_1compra'] =  $this->load->View('Reporte/Compra/ReporteFormato8_1Compra/view_mainpanel_content_formato8_1compra','',true);


		$data['dataReporteCompraDetallado'] = $this->CargarReporteCompraDetallado();
		$data['dataReporteCompraGeneral'] = $this->CargarReporteCompraGeneral();
		$data['dataReporteProductoMasComprado'] = $this->CargarReporteProductoMasComprado();
		$data['dataReporteComprasMensuales'] = $this->CargarReporteComprasMensuales();
		$data['dataReporteComprasPorMercaderia'] = $this->CargarReporteComprasPorMercaderia();
		$data['dataReporteSaldoProveedor'] = $this->CargarReporteSaldoProveedor();
		$data['dataReporteProductoPorProveedor'] = $this->CargarReporteProductoPorProveedor();
		$data['dataReporteFormato8_1Compra'] = $this->CargarReporteFormato8_1Compra();
		$data['parametros'] = $this->CargarParametros();

		$view_data_vistamodeloreporte['data']= $data;
		$view_subcontent['view_mainpanel_subcontent_modal_reportevistaprevia'] =  $this->load->View('Reporte/VistaModeloReporteCompra/view_mainpanel_subcontent_modal_reportevistaprevia','',true);
		$view_vistamodelogeneral['view_main_vistamodelogeneral'] = $this->load->View('Reporte/VistaModeloReporteCompra/view_main_vistamodeloreportecompra',$view_subcontent,true);
		$view_ext['view_footer_extension'] = $this->load->View('Reporte/VistaModeloReporteCompra/view_mainpanel_footer_vistamodeloreportecompra',$view_data_vistamodeloreporte,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('Reporte/VistaModeloReporteCompra/view_main_vistamodeloreportecompra',$view_,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}
}

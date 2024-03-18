<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVistaModeloReporteCuentaCobranza extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/CuentaCobranza/sReporteDeudasCliente");
		$this->load->service("Reporte/CuentaCobranza/sReporteDetalladoCuentasPorCobrar");
		$this->load->service("Reporte/CuentaCobranza/sReporteDocumentosPorCobrar");
		$this->load->service("Reporte/CuentaCobranza/sReporteModeloMovimientoCuentasPorCobrar");
		$this->load->service("Reporte/CuentaCobranza/sReporteSaldoPorClientes");
		$this->load->service("Reporte/CuentaCobranza/sReporteCobrosPorCobrador");
    	$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('sesionusuario');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$data['dataReporteDeudasCliente'] = $this->sReporteDeudasCliente->Cargar();
		$data['dataReporteDetalladoCuentasPorCobrar'] = $this->sReporteDetalladoCuentasPorCobrar->Cargar();
		$data['dataReporteDocumentosPorCobrar'] = $this->sReporteDocumentosPorCobrar->Cargar();
		$data['dataReporteModeloMovimientoCuentasPorCobrar'] = $this->sReporteModeloMovimientoCuentasPorCobrar->Cargar();
		$data['dataReporteSaldoPorClientes'] = $this->sReporteSaldoPorClientes->Cargar();
		$data['dataReporteCobrosPorCobrador'] = $this->sReporteCobrosPorCobrador->Cargar();

		// ReporteDeudasCliente
		$view_['view_content_reportedeudascliente'] =  $this->load->View('Reporte/CuentaCobranza/ReporteDeudasCliente/view_content_reportedeudascliente','',true);
		// ReporteDetalladoCuentasPorCobrar
		$view_['view_content_reportedetalladocuentasporcobrar'] =  $this->load->View('Reporte/CuentaCobranza/ReporteDetalladoCuentasPorCobrar/view_content_reportedetalladocuentasporcobrar','',true);
		// ReporteDocumentosPorCobrar
		$view_['view_content_reportedocumentosporcobrar'] =  $this->load->View('Reporte/CuentaCobranza/ReporteDocumentosPorCobrar/view_content_reportedocumentosporcobrar','',true);
		// ReporteModeloMovimientoCuentasPorCobrar
		$view_['view_content_reportemodelomovimientocuentasporcobrar'] =  $this->load->View('Reporte/CuentaCobranza/ReporteModeloMovimientoCuentasPorCobrar/view_content_reportemodelomovimientocuentasporcobrar','',true);
		// ReporteSaldoPorClientes
		$view_['view_content_reportesaldoporclientes'] =  $this->load->View('Reporte/CuentaCobranza/ReporteSaldoPorClientes/view_content_reportesaldoporclientes','',true);
		// ReporteDeudasCliente
		$view_['view_content_reportecobrosporcobrador'] =  $this->load->View('Reporte/CuentaCobranza/ReporteCobrosPorCobrador/view_content_reportecobrosporcobrador','',true);

		$view_data_vistamodeloreporte['data']= $data;
		$view_subcontent['view_mainpanel_subcontent_modal_reportevistaprevia'] =  $this->load->View('Reporte/VistaModeloReporteCuentaCobranza/view_mainpanel_subcontent_modal_reportevistaprevia','',true);
		$view_vistamodelogeneral['view_main_vistamodelogeneral'] = $this->load->View('Reporte/VistaModeloReporteCuentaCobranza/view_main_vistamodeloreportecuentacobranza',$view_subcontent,true);
		$view_footer['view_footer_extension'] = $this->load->View('Reporte/VistaModeloReporteCuentaCobranza/view_mainpanel_footer_vistamodeloreportecuentacobranza',$view_data_vistamodeloreporte,true);


		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Reporte/VistaModeloReporteCuentaCobranza/view_main_vistamodeloreportecuentacobranza',$view_,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

   		$this->load->View('.Master/master_view_mainpanel',$view);
	}
}

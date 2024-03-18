<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVistaModeloReporteCuentaPago extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/CuentaPago/sReporteDetalladoCuentasPorPagar");
		$this->load->service("Reporte/CuentaPago/sReporteDocumentosPorPagar");
		$this->load->service("Reporte/CuentaPago/sReporteModeloMovimientoCuentasPorPagar");
		$this->load->service("Reporte/CuentaPago/sReporteSaldoPorProveedor");
    	$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('sesionusuario');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$data['dataReporteDetalladoCuentasPorPagar'] = $this->sReporteDetalladoCuentasPorPagar->Cargar();
		$data['dataReporteDocumentosPorPagar'] = $this->sReporteDocumentosPorPagar->Cargar();
		$data['dataReporteModeloMovimientoCuentasPorPagar'] = $this->sReporteModeloMovimientoCuentasPorPagar->Cargar();
		$data['dataReporteSaldoPorProveedor'] = $this->sReporteSaldoPorProveedor->Cargar();

		// ReporteDetalladoCuentasPorPagar
		$view_['view_content_reportedetalladocuentasporpagar'] =  $this->load->View('Reporte/CuentaPago/ReporteDetalladoCuentasPorPagar/view_content_reportedetalladocuentasporpagar','',true);
		// ReporteDocumentosPorPagar
		$view_['view_content_reportedocumentosporpagar'] =  $this->load->View('Reporte/CuentaPago/ReporteDocumentosPorPagar/view_content_reportedocumentosporpagar','',true);
		// ReporteModeloMovimientoCuentasPorPagar
		$view_['view_content_reportemodelomovimientocuentasporpagar'] =  $this->load->View('Reporte/CuentaPago/ReporteModeloMovimientoCuentasPorPagar/view_content_reportemodelomovimientocuentasporpagar','',true);
		// ReporteSaldoPorProveedor
		$view_['view_content_reportesaldoporproveedor'] =  $this->load->View('Reporte/CuentaPago/ReporteSaldoPorProveedor/view_content_reportesaldoporproveedor','',true);

		$view_data_vistamodeloreporte['data']= $data;
		$view_subcontent['view_mainpanel_subcontent_modal_reportevistaprevia'] =  $this->load->View('Reporte/VistaModeloReporteCuentaPago/view_mainpanel_subcontent_modal_reportevistaprevia','',true);
		$view_vistamodelogeneral['view_main_vistamodelogeneral'] = $this->load->View('Reporte/VistaModeloReporteCuentaPago/view_main_vistamodeloreportecuentapago',$view_subcontent,true);
		$view_footer['view_footer_extension'] = $this->load->View('Reporte/VistaModeloReporteCuentaPago/view_mainpanel_footer_vistamodeloreportecuentapago',$view_data_vistamodeloreporte,true);


		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Reporte/VistaModeloReporteCuentaPago/view_main_vistamodeloreportecuentapago',$view_,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

   		$this->load->View('.Master/master_view_mainpanel',$view);
	}
}

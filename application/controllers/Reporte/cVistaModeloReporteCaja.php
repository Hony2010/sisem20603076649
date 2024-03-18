<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVistaModeloReporteCaja extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Caja/sReporteMovimientoCaja");
    	$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('sesionusuario');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$data['dataReporteMovimientoCaja'] = $this->sReporteMovimientoCaja->Cargar();

		// ReporteMovimientoCaja
		$view_['view_content_reportemovimientocaja'] =  $this->load->View('Reporte/Caja/ReporteMovimientoCaja/view_content_reportemovimientocaja','',true);
			
		$view_data_vistamodeloreporte['data']= $data;
		$view_subcontent['view_mainpanel_subcontent_modal_reportevistaprevia'] =  $this->load->View('Reporte/VistaModeloReporteCaja/view_mainpanel_subcontent_modal_reportevistaprevia','',true);
		$view_vistamodelogeneral['view_main_vistamodelogeneral'] = $this->load->View('Reporte/VistaModeloReporteCaja/view_main_vistamodeloreportecaja',$view_subcontent,true);
		$view_footer['view_footer_extension'] = $this->load->View('Reporte/VistaModeloReporteCaja/view_mainpanel_footer_vistamodeloreportecaja',$view_data_vistamodeloreporte,true);


		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Reporte/VistaModeloReporteCaja/view_main_vistamodeloreportecaja',$view_,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

   		$this->load->View('.Master/master_view_mainpanel',$view);
	}
}

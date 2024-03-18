<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cDashBoard extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		// $this->load->service("Catalogo/sCliente");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->service("Configuracion/General/sConstanteSistema");
	}

	public function Index()
	{	
		$FacturacionElectronica["CantidadFacturas"] = $this->sComprobanteElectronico->ConsultarCantidadFacturasNoEnviadasSunat();
		$FacturacionElectronica["Dias"] = $this->sComprobanteElectronico->ConsultarCantidadFacturasNoEnviadasSunat(true);
		$FacturacionElectronica["RangoFecha"] = $this->sComprobanteElectronico->ConsultarRangoFechasFacturasNoEnviadas(true);
		
		$CertificadoDigital["Vencimiento"] = $this->sComprobanteElectronico->ValidarFechaVencimientoCertificadoDigital();

		$data = array(
			"data" => array(
				'FacturacionElectronica' => $FacturacionElectronica,
				'CertificadoDigital' => $CertificadoDigital
				)
		);

		$view_data['dataInicio'] = $data;
		$view_dashboard['view_main_dashboard']=   $this->load->View('.Master/DashBoard/view_main_dashboard','',true);
		$view_ext['view_footer_extension'] = $this->load->View('.Master/DashBoard/view_mainpanel_footer_dashboard',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('.Master/DashBoard/view_main_dashboard',$view_dashboard,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}
}

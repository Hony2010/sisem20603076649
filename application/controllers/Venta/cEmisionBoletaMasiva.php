<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEmisionBoletaMasiva extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sCliente");
		$this->load->service("Venta/sBoletaMasiva");
		$this->load->service("Catalogo/sMercaderia");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$BoletaMasiva = $this->sBoletaMasiva->CargarBoleta();
		$Cliente=$this->sCliente->Cargar();
		$Mercaderia=$this->sMercaderia->Cargar();
		$data = array(
			"data" => array(
				'BoletaMasiva' => $BoletaMasiva,
				'ComprobantesVenta' => array(),
				'ComprobantesMasivo' => array(),
				'ComprobanteVentaNuevo' => $BoletaMasiva,
				'Cliente'  => $Cliente,
				'Mercaderia'  => $Mercaderia
				)
		);

    	$view_data['data'] = $data;
		$view_extra['view_mainpanel_modal_vehiculocliente'] =  $this->load->View('Catalogo/Cliente/view_mainpanel_modal_vehiculocliente','',true);
		$view_extra['view_form_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_form_cliente','',true);
		$views['view_modal_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_cliente',$view_extra,true);
		$views['view_modal_preview_foto_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_preview_foto_cliente','',true);
		$views['view_modal_form_mercaderia'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_form_mercaderia','',true);

		$view_form['view_form_boletamasiva'] = $this->load->View('Venta/BoletaMasiva/view_form_boletamasiva','',true);
		$view_form['view_panel_header_boletamasiva'] = $this->load->View('Venta/BoletaMasiva/view_panel_header_boletamasiva','',true);

		$views['view_tipoventa_boletamasiva'] = $this->load->View('Venta/EmisionBoletaMasiva/view_tipoventa_boletamasiva','',true);
		$views['view_panel_boletamasiva'] = $this->load->View('Venta/EmisionBoletaMasiva/view_panel_boletamasiva',$view_form,true);

		$view['view_footer_extension'] = $this->load->View('Venta/EmisionBoletaMasiva/view_footer_boletamasiva',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Venta/EmisionBoletaMasiva/view_content_boletamasiva',$views,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

}

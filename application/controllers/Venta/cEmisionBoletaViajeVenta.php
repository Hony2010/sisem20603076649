<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEmisionBoletaViajeVenta extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sCliente");
		$this->load->service("Venta/sBoletaViajeVenta");
		$this->load->service("Catalogo/sMercaderia");
		$this->load->service("Catalogo/sServicio");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$BoletaViajeVenta = $this->sBoletaViajeVenta->CargarBoletaViaje();
		$Cliente=$this->sCliente->Cargar();
		$Mercaderia=$this->sMercaderia->Cargar();
		$Servicio=$this->sServicio->Inicializar();
		$data = array(
			"data" => array(
				'BoletaViajeVenta' => $BoletaViajeVenta,
				'ComprobanteVentaNuevo' => $BoletaViajeVenta,
				'Cliente'  => $Cliente,
				'Mercaderia'  => $Mercaderia,
				'Servicio'  => $Servicio,
				'BusquedaAvanzadaProducto' => array('textofiltro' => ''),
				'Mercaderias' => array()
				)
		);

    	$view_data['data'] = $data;
		$view_extra['view_form_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_form_cliente','',true);
		$views['view_modal_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_cliente',$view_extra,true);
		$views['view_modal_preview_foto_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_preview_foto_cliente','',true);
		$views['view_modal_form_mercaderia'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_form_mercaderia','',true);
		$views['view_modal_buscador_mercaderia'] = $this->load->View('Catalogo/Mercaderia/view_mainpanel_modal_buscador_externo_mercaderia','',true);

		if ($BoletaViajeVenta["ParametroVistaVenta"] == 1 ) {
			$view_form['view_form_boletaventa'] = $this->load->View('Venta/BoletaViajeVenta/view_form_boletaviajeventa','',true);
		} else {
			$view_form['view_form_boletaventa'] = $this->load->View('Venta/BoletaViajeVenta/view_form_boletaviajeventarapida','',true);
		}

		$view_form['view_panel_header_boletaventa'] = $this->load->View('Venta/BoletaViajeVenta/view_panel_header_boletaviajeventa','',true);
		$views['view_tipoventa_boletaventa'] = $this->load->View('Venta/EmisionBoletaViajeVenta/view_tipoventa_boletaviajeventa','',true);
		$views['view_panel_boletaventa'] = $this->load->View('Venta/EmisionBoletaViajeVenta/view_panel_boletaviajeventa',$view_form,true);

		$view['view_footer_extension'] = $this->load->View('Venta/EmisionBoletaViajeVenta/view_footer_boletaviajeventa',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Venta/EmisionBoletaViajeVenta/view_content_boletaviajeventa',$views,true);

    	$this->load->View('.Master/master_view_mainpanel_min',$view);
	}

}

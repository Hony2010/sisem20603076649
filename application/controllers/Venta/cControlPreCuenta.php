<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cControlPreCuenta extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Base");
		$this->load->service("Venta/sControlMesa");
		$this->load->service("Venta/sPreVenta");
		$this->load->service("Venta/sComanda");
		$this->load->service("Venta/sTicket");
		$this->load->service("Catalogo/sFamiliaProducto");
		$this->load->service("Catalogo/sSubFamiliaProducto");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$Comanda = $this->sComanda->CargarComanda();
		$Ticket = $this->sTicket->CargarTicket();
		$PreVenta = $this->sPreVenta->CargarPreVenta();
		$ControlMesa = $this->sControlMesa->Cargar();

		$Cliente = $this->sCliente->Cargar();
		$Mercaderia = $this->sMercaderia->Cargar();
		$FamiliasProducto=$this->sFamiliaProducto->ListarFamiliasProducto();
		$SubFamiliasProducto=$this->sSubFamiliaProducto->ListarTodosSubFamiliasProducto();

		$data = array(
			"data" => array(
				'ControlMesa' => $ControlMesa,
				'Comanda' => $Comanda,
				'Ticket' => $Ticket,
				'PreVenta' => $PreVenta,
				'Cliente' => $Cliente,
				'Mercaderia' => $Mercaderia,
				'Mercaderias' => array(),
				'BusquedaAvanzadaProducto' => array('textofiltro' => '', 'idfamilia' => ''),
				'TecladoVirtual' => array('number' => ''),
				'FamiliasProducto' => $FamiliasProducto,
				'SubFamiliasProducto' => $SubFamiliasProducto,
				'SubFamiliasProductoFiltrado' => array(),
				'AnotacionesPlatoProducto' => array(),
				'Comandas' => array(),
				'PreCuentas' => array()
				)
		);

    $view_data['data'] = $data;

		$views_extra['view_form_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_form_cliente','',true);
		$views['view_modal_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_cliente',$views_extra,true);
		$views['view_modal_preview_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_preview_foto_cliente','',true);

		$view_cmnd['view_form_detail_comanda'] = $this->load->View('Venta/Comanda/view_form_detail_comanda','',true);
		$view_cmnd['view_form_keyboard_comanda'] = $this->load->View('Venta/Comanda/view_form_keyboard_comanda','',true);
		$view_cmnd['view_form_product_comanda'] = $this->load->View('Venta/Comanda/view_form_product_comanda','',true);
		$view_cmnd['view_form_comanda'] = $this->load->View('Venta/Comanda/view_form_comanda',$view_cmnd,true);
		$views['view_modal_comanda'] = $this->load->View('Venta/Comanda/view_modal_comanda',$view_cmnd,true);

		$view_pv['view_form_detail_preventa'] = $this->load->View('Venta/PreVenta/view_form_detail_preventa','',true);
		$view_pv['view_form_keyboard_preventa'] = $this->load->View('Venta/PreVenta/view_form_keyboard_preventa','',true);
		$view_pv['view_form_consulta_preventa'] = $this->load->View('Venta/PreVenta/view_form_consulta_preventa','',true);
		$views['view_form_preventa'] = $this->load->View('Venta/PreVenta/view_form_preventa',$view_pv,true);

		$view_footer['view_footer_extension'] = $this->load->View('Venta/ControlMesa/view_footer_controlmesa',$view_data,true);
		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Venta/ControlMesa/view_content_controlmesa_cajero',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

		$this->load->View('.Master/master_view_mainpanel',$view);

	}


}

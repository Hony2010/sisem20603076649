<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCompraMasiva extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sProveedor");
		$this->load->service("Compra/sCompraMasiva");
		$this->load->library('sesionusuario');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$CompraMasiva =$this->sCompraMasiva->CargarCompraMasiva();
		$Proveedor=$this->sProveedor->Cargar();

		$data = array(
			"data" => array(
				'CompraMasiva' => $CompraMasiva,
				'ComprasMasiva' => array(),
				'ComprobantesCompra' => array(),
				'ComprobanteCompra' => array(),
				'ComprobanteCompraNuevo' => $CompraMasiva,
				'Proveedor'  => $Proveedor//,
				// 'Mercaderia'  => $Mercaderia
				)
		);

    $view_data['data'] = $data;
		$view_subsubcontent_extra['view_subcontent_form_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_form_proveedor','',true);
		$view_subcontent['view_modal_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_proveedor',$view_subsubcontent_extra,true);
		$view_subcontent['view_modal_preview_foto_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_preview_foto_proveedor','',true);
		$view_subcontent_panel['view_form_compramasiva'] = $this->load->View('Compra/CompraMasiva/view_form_compramasiva','',true);
		$view_subcontent_panel['view_panel_header_compramasiva'] = $this->load->View('Compra/CompraMasiva/view_panel_header_compramasiva','',true);
		$view_subcontent['view_panel_compramasiva'] = $this->load->View('Compra/RegistroCompraMasiva/view_panel_compramasiva',$view_subcontent_panel,true);
		$view_footer_extension['view_footer_extension'] = $this->load->View('Compra/RegistroCompraMasiva/view_footer_compramasiva',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Compra/RegistroCompraMasiva/view_content_compramasiva',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer_extension,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

}

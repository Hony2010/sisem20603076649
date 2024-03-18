<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEmisionComprobanteVenta extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sCliente");
		$this->load->service("Venta/sComprobanteVenta");
		$this->load->library('sesionusuario');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
		$parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
		$ComprobanteVenta =$this->sComprobanteVenta->Cargar($parametro);
		$Cliente=$this->sCliente->Cargar();
		$data = array(
			"data" => array(
				'ComprobanteVenta' => $ComprobanteVenta,
				'ComprobanteVentaNuevo' => $ComprobanteVenta,
				'Cliente'  => $Cliente
				)
		);

    $view_data['data'] = $data;
		$view_subsubcontent_extra['view_subcontent_form_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_form_cliente','',true);
		$view_subcontent['view_subcontent_modal_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_cliente',$view_subsubcontent_extra,true);
		$view_subcontent['view_subcontent_modal_preview_foto_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_preview_foto_cliente','',true);
		$view_subcontent_panel['view_subcontent_form_comprobanteventa'] = $this->load->View('Venta/ComprobanteVenta/view_mainpanel_subcontent_form_comprobanteventa','',true);
		$view_subcontent_panel['view_subcontent_panel_header_comprobanteventa'] = $this->load->View('Venta/ComprobanteVenta/view_mainpanel_subcontent_panel_header_comprobanteventa','',true);
		$view_subcontent['view_subcontent_panel_comprobanteventa'] = $this->load->View('Venta/EmisionComprobanteVenta/view_mainpanel_subcontent_panel_comprobanteventa',$view_subcontent_panel,true);
		$view_footer_extension['view_footer_extension'] = $this->load->View('Venta/EmisionComprobanteVenta/view_mainpanel_footer_comprobanteventa',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Venta/EmisionComprobanteVenta/view_mainpanel_content_comprobanteventa',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer_extension,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

}

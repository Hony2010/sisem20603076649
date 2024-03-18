<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cRegistroGastos extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sProveedor");
		$this->load->service("Compra/sComprobanteCompra");
		$this->load->service("Compra/sCompraGasto");
		$this->load->library('sesionusuario');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		// $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
		// $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
		// $CompraGasto =$this->sComprobanteCompra->Cargar($parametro);
		$CompraGasto =$this->sCompraGasto->CargarCompraGasto();
		// $CompraGasto["IdTipoCompra"] = ID_TIPOCOMPRA_GASTO;
		$parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
		$parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
		$Gasto =$this->sComprobanteCompra->Cargar($parametro);
		$Proveedor=$this->sProveedor->Cargar();

		$data = array(
			"data" => array(
				'CompraGasto' => $CompraGasto,
				'CompraGastoNuevo' => $CompraGasto,
				'Gasto' => $Gasto,
				'GastoNuevo' => $Gasto,
				'Proveedor'  => $Proveedor
				)
		);

    $view_data['data'] = $data;
		$view_subsubcontent_extra['view_subcontent_form_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_form_proveedor','',true);
		$view_subcontent['view_subcontent_modal_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_proveedor',$view_subsubcontent_extra,true);
		$view_subcontent['view_subcontent_modal_preview_foto_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_preview_foto_proveedor','',true);
		$view_subcontent_panel['view_subcontent_form_gasto'] = $this->load->View('Compra/CompraGasto/view_mainpanel_subcontent_form_gasto','',true);
		$view_subcontent_panel['view_subcontent_panel_header_gasto'] = $this->load->View('Compra/CompraGasto/view_mainpanel_subcontent_panel_header_gasto','',true);
		$view_subcontent['view_subcontent_panel_registrogasto'] = $this->load->View('Compra/RegistroCompraGasto/view_mainpanel_subcontent_panel_registrogasto',$view_subcontent_panel,true);
		$view_footer_extension['view_footer_extension'] = $this->load->View('Compra/RegistroCompraGasto/view_mainpanel_footer_registrogasto',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Compra/RegistroCompraGasto/view_mainpanel_content_registrogasto',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer_extension,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

}

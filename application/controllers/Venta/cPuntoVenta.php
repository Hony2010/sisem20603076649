<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cPuntoVenta extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Catalogo/sCliente");
		$this->load->service("Venta/sPuntoVenta");
		$this->load->service("Catalogo/sFamiliaProducto");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$FacturacionElectronica["CantidadFacturas"] = $this->sComprobanteElectronico->ConsultarCantidadFacturasNoEnviadasSunat();
		$FacturacionElectronica["Dias"] = $this->sComprobanteElectronico->ConsultarCantidadFacturasNoEnviadasSunat(true);
		$FacturacionElectronica["RangoFecha"] = $this->sComprobanteElectronico->ConsultarRangoFechasFacturasNoEnviadas(true);

		$data2 = array(
			"data" => array(
				'FacturacionElectronica' => $FacturacionElectronica
				)
		);

		$view_data['dataInicio'] = $data2;

		$PuntoVenta = $this->sPuntoVenta->CargarPuntoVenta();
		$Cliente=$this->sCliente->Cargar();
		$FamiliasProducto=$this->sFamiliaProducto->ListarFamiliasProducto();

		$data = array(
			"data" => array(
				'PuntoVenta' => $PuntoVenta,
				'ComprobanteVentaNuevo' => $PuntoVenta,
				'Cliente'  => $Cliente,
				'BusquedaAvanzadaProducto' => array('textofiltro' => '', 'idfamilia' => ''),
				'TecladoVirtual' => array('number' => ''),
				'Mercaderias' => array(),
				'ComprobantesVentaProforma' => array(),
				'FamiliasProducto' => $FamiliasProducto
				)
		);

    $view_data['data'] = $data;
		$view_extra['view_form_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_form_cliente','',true);
		$views['view_modal_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_cliente',$view_extra,true);
		$views['view_modal_preview_foto_cliente'] = $this->load->View('Catalogo/Cliente/view_mainpanel_subcontent_modal_preview_foto_cliente','',true);


		$view_form['view_form_detail_puntoventa'] = $this->load->View('Venta/PuntoVenta/view_form_detail_puntoventa','',true);
		$view_form['view_form_keyboard_puntoventa'] = $this->load->View('Venta/PuntoVenta/view_form_keyboard_puntoventa','',true);
		$view_form['view_form_product_puntoventa'] = $this->load->View('Venta/PuntoVenta/view_form_product_puntoventa','',true);

		$view_cont['view_form_puntoventa'] = $this->load->View('Venta/PuntoVenta/view_form_puntoventa',$view_form,true);
		$view_cont['view_panel_header_puntoventa'] = $this->load->View('Venta/PuntoVenta/view_panel_header_puntoventa','',true);
		$views['view_panel_puntoventa'] = $this->load->View('Venta/PuntoVenta/view_panel_puntoventa',$view_cont,true);

		$view_footer['view_footer_extension'] = $this->load->View('Venta/PuntoVenta/view_footer_puntoventa',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_content'] =  $this->load->View('Venta/PuntoVenta/view_content_puntoventa',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_footer,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVideoTutorial extends CI_Controller  {

	public function __construct() {
		parent::__construct();		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index() {

		// Filtro
		$filtro["TextoFiltro"] = "";
		$filtro["IdModuloSistema"] = "";
		$filtro["ModulosSistema"] = array();

		// Video
		$video["Nombre"] = "";
		$video["Url"] = "";

		$data = array(
			"data" => array(
				'Filtros' => $filtro,
				'Video' => $video,
				'Videos' => array(),
				)
		);

		$view_data['data'] = $data;

		$views['view_form_videotutorial']=   $this->load->View('Ayuda/VideoTutorial/view_form_videotutorial','',true);
		$views['view_modal_videotutorial']=   $this->load->View('Ayuda/VideoTutorial/view_modal_videotutorial','',true);
		$views['view_searcher_videotutorial']=   $this->load->View('Ayuda/VideoTutorial/view_searcher_videotutorial','',true);
		
		$view_ext['view_footer_extension'] = $this->load->View('Ayuda/VideoTutorial/view_footer_videotutorial',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('Ayuda/VideoTutorial/view_content_videotutorial',$views,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}
	

}

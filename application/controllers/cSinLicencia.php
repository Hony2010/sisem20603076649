<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cSinLicencia extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function index() {

		$data["mensaje"] = $this->session->userdata('data_mensaje_licencia_'.LICENCIA_EMPRESA_RUC);

 		if ($this->session->userdata('data_licencia_usuario_'.LICENCIA_EMPRESA_RUC)) {
			if($this->session->userdata('data_licencia_usuario_'.LICENCIA_EMPRESA_RUC) == "S")
				$this->session->sess_destroy();
		}

		$this->load->view('view_sin_licencia', $data);
	}

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cLog extends CI_Controller  {

	public function __construct() {
		parent::__construct();
		$this->load->model('Base');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('logger');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function CrearLog() {
    $data1 = $this->input->post("Data");
		$now = $this->Base->ObtenerFechaServidor("Y-m-d");
		$data["name"] = "error-".$now.".log";
		$data["url"] = APP_PATH."assets/data/facturacionelectronica/error/";
		$data["header"] = $data1["Head"];
		$data["body"] = $data1["Data"];
		$data["footer"] = $data1["Foot"];

		$this->logger->CrearLog($data);

		echo "";
	}


}

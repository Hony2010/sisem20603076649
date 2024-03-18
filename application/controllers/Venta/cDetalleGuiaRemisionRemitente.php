<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cDetalleGuiaRemisionRemitente extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Venta/sDetalleGuiaRemisionRemitente");
    	$this->load->library('json');
	}

	public function Index()
	{

	}

	public function ConsultarDetallesGuiaRemisionRemitente()
	{
		$data = json_decode($this->input->post("Data"), true);
		$resultado = $this->sDetalleGuiaRemisionRemitente->ConsultarDetallesGuiaRemisionRemitente($data);
		echo $this->json->json_response($resultado);
	}

}

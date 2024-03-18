<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	public function index()
	{



		$this->load->view('welcome_message');
		//$this->load->view('index.html');
	}

	public function Products()
	{
		$this->load->view('products.html');
	}

	public function Clientes()
	{
		$this->load->view('clientes.html');
	}

	public function Test()
	{
		$this->load->library('json');
		//$this->load->service('sample_service');
		//echo $this->sample_service->hello_world();
		//$data = array("data1"=>1,'data2'=>2);//
		//$data = $this->input->post("Data");
		$data  = $this->input->post("Data");
		$data["IdFamiliaProducto"] = 1;
		$resultado = $data;
		//echo $data;
		//print_r( $data);
		echo $this->json->json_response($resultado);
	}

}

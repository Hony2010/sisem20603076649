<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEmpresa extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sEmpresa");
		$this->load->service("Configuracion/General/sGiroNegocio");
    	$this->load->service("Configuracion/General/sRegimenTributario");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

  public function ListarFamiliasProducto()
	{
		$resultado = $this->sFamiliaProducto->ListarFamiliasProducto();

		echo $this->json->json_response($resultado);
	}

	public function InsertarEmpresa()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sEmpresa->InsertarEmpresa($data);
		$data["IdProducto"] = $resultado;

		echo $this->json->json_response($data);
	}

	public function ActualizarEmpresa()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sEmpresa->ActualizarEmpresa($data);
		echo $this->json->json_response($resultado);
	}

	public function BorrarEmpresa()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sEmpresa->BorrarEmpresa($data);
		echo $this->json->json_response($resultado);
	}

	public function SubirFoto()
	{
		$IdEmpresa = $this->input->post("IdEmpresa");
		$InputFileName = $this->input->post("InputFileName");

		$patcher = DIR_ROOT_ASSETS.'/img/Empresa/'.$IdEmpresa.'/';
		//$patcher = site_url().'../img/Empresa/';
		//$config['upload_path'] = '../img/Empresa/'.$IdProducto.'/';
		$config['upload_path'] = $patcher;

		$resultado = $this->shared->upload_file($InputFileName,$config);

		//print_r($resultado."\n");
		print_r($resultado);
		print_r($config['upload_path']);
		print_r($config);
	}

}

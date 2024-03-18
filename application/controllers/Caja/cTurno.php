<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cTurno extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->service("Caja/sTurno");
	}

	public function Index()
	{

		$input["textofiltro"]='';
		$input["pagina"]=1;
		$input["numerofilasporpagina"] = "";
		$input["paginadefecto"]=1;
		$input["totalfilas"] = "";

		$Turno =  $this->sTurno->Cargar();
		$Turnos = $this->sTurno->ListarTurnos();

		$data = array(
			"data" => array(
				'Filtros' => $input,
				'Turnos' => $Turnos,
				'Turno' => $Turno
			)
		);

		$view_data['data'] = $data;

		$view_sub_subcontent['view_subcontent_buscador_turnos']=   $this->load->View('Caja/Turnos/view_mainpanel_subcontent_buscador_turnos','',true);
		$view_sub_subcontent['view_subcontent_paginacion_turnos']=   $this->load->View('Caja/turnos/view_mainpanel_subcontent_paginacion_turnos','',true);
		$view_subcontent['view_subcontent_consulta_turnos'] =  $this->load->View('Caja/Turnos/view_mainpanel_subcontent_consulta_turnos',$view_sub_subcontent,true);
		$view_subcontent_panel['view_form_turno'] =  $this->load->View('Caja/Turno/view_mainpanel_subcontent_form_turno','',true);
		$view_subcontent['view_subcontent_modal_turno'] =  $this->load->View('Caja/Turno/view_mainpanel_subcontent_modal_turno',$view_subcontent_panel,true);
		$view_ext['view_footer_extension'] = $this->load->View('Caja/Turnos/view_mainpanel_footer_turnos',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('Caja/Turnos/view_mainpanel_content_turnos',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

		$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ListarTurnos()
	{
		$resultado = $this->sTurno->ListarTurnos();

		echo $this->json->json_response($resultado);
	}

	public function InsertarTurno()
	{
		try {
			$this->db->trans_begin();

			// $data = json_decode($this->input->post("Data"), true);
			$data = $this->input->post("Data");

			$resultado = $this->sTurno->InsertarTurno($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();

				$output["resultado"] = $resultado;

				$output["Filtros"] = array(
					"textofiltro" => "",
					"numerofilasporpagina" => ""	,
					"totalfilas" => "",
					"paginadefecto" => "");

				echo $this->json->json_response($output);
			}
			else {
			 	$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

	public function ActualizarTurno()
	{
		try {
			$this->db->trans_begin();

			// $data = json_decode($this->input->post("Data"), true);
			$data = $this->input->post("Data");

			$resultado = $this->sTurno->ActualizarTurno($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();
				echo $this->json->json_response($resultado);
			}
			else {
			 	$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

	public function BorrarTurno()
	{
		try {
			$this->db->trans_begin();

			// $data = json_decode($this->input->post("Data"), true);
			$data = $this->input->post("Data");

			$resultado = $this->sTurno->BorrarTurno($data);
			if(is_array($resultado)) {
				$this->db->trans_commit();

				$output["msg"] = $resultado;

				$output["Filtros"] = array(
					"textofiltro" => "",
					"numerofilasporpagina" => ""	,
					"totalfilas" => "",
					"paginadefecto" => "");

				echo $this->json->json_response($output);
			}
			else {
			 	$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 echo $this->json->json_response_error($e);
		}
	}

}

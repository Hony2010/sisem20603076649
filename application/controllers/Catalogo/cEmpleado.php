<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEmpleado extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Catalogo/sEmpleado");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index()
	{
		$data =  $this->sEmpleado->Inicializar();

		$view_data['data'] = $data;

		$view_sub_subcontent['view_subcontent_buscador_empleado']=   $this->load->View('Catalogo/Empleado/view_mainpanel_subcontent_buscador_empleado','',true);
		$view_sub_subcontent['view_subcontent_paginacion_empleado']=   $this->load->View('Catalogo/Empleado/view_mainpanel_subcontent_paginacion_empleado','',true);
		$view_subcontent['view_subcontent_preview_empleado'] =  $this->load->View('Catalogo/Empleado/view_mainpanel_subcontent_preview_empleado','',true);
		$view_subcontent['view_subcontent_consulta_empleados'] =  $this->load->View('Catalogo/Empleado/view_mainpanel_subcontent_consulta_empleados',$view_sub_subcontent,true);
		$views_extra['view_subcontent_form_empleado'] =  $this->load->View('Catalogo/Empleado/view_mainpanel_subcontent_form_empleado','',true);
		$view_subcontent['view_modal_empleado'] =  $this->load->View('Catalogo/Empleado/view_modal_empleado',$views_extra,true);


		$view['view_footer_extension'] = $this->load->View('Catalogo/Empleado/view_mainpanel_footer_empleado',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/Empleado/view_mainpanel_content_empleado',$view_subcontent,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}
	public function ConsultarEmpleados()
	{
		$input = $this->input->get("Data");
		$numerofilasporpagina = $this->sEmpleado->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sEmpleado->ObtenerNumeroTotalEmpleados($input);
		$output["resultado"] = $this->sEmpleado->ConsultarEmpleados($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarEmpleadosPorIdEmpleado()
	{
		$q = $this->input->post("Data");
		$data["textofiltro"] = $q;

		$resultado = $this->sEmpleado->ConsultarEmpleadosPorIdEmpleado($data, 1);

		echo $this->json->json_response($resultado);
	}

	public function ListarEmpleadosPorId()
	{
		$q = $this->input->post("Data");
		$data["IdEmpleado"] = $q;

		$resultado = $this->sEmpleado->ListarEmpleadosPorId($data);

		echo $this->json->json_response($resultado);
	}

	public function ConsultarSugerenciaEmpleadosPorRuc()
	{
		$q = $this->input->post("Data");
		$data["textofiltro"] = $q;

		$resultado = $this->sEmpleado->ConsultarSugerenciaEmpleadosPorRuc($data, 1);

		echo $this->json->json_response($resultado);
	}


	public function ListarEmpleados()
	{
		$resultado = $this->sEmpleado->ListarEmpleados();

		echo $this->json->json_response($resultado);
	}

	public function InsertarEmpleado()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sEmpleado->InsertarEnPersonaYEmpleado($data);

			if(is_array($resultado)) {
				$output["resultado"] = $resultado;
				$resultado2 = $this->sEmpleado->InsertarJSONDesdeEmpleado($resultado);

				if(is_array($resultado2)) {
					$this->db->trans_commit();

					$data["textofiltro"] = "%";
					$numerofilasporpagina = $this->sEmpleado->ObtenerNumeroFilasPorPagina();
					$TotalFilas = $this->sEmpleado->ObtenerNumeroTotalEmpleados($data);
					$output["data"] = $data;
					$output["Filtros"] = array(
						"textofiltro" => "",
						"numerofilasporpagina" => $numerofilasporpagina	,
						"totalfilas" => $TotalFilas,
						"paginadefecto" => 2);

					echo $this->json->json_response($output);
				}
				else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($resultado2);
				}
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

	public function ActualizarEmpleado()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sEmpleado->ActualizarEnPersonaYEmpleado($data);

			if ($resultado == "") {
				$resultado2 = $this->sEmpleado->ActualizarJSONDesdeEmpleado($data);

				if(is_array($resultado2)) {
					$this->db->trans_commit();
					$data["IdEmpleado"] = $resultado;
					echo $this->json->json_response($resultado);
				}
				else {
					$this->db->trans_rollback();
					echo $this->json->json_response_error($resultado2);
				}
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

	public function BorrarEmpleado()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sEmpleado->BorrarEmpleado($data);

		/*if($resultado == "")
		{
			$url = DIR_ROOT_ASSETS.'/data/empleado/empleados.json';
			$fila = Array (
				"IdEmpleado" => $data["IdEmpleado"],
				"NombrePersona" => $data["NombreCompleto"].' '.$data["ApellidoCompleto"],
				"NumeroDocumentoIdentidad" => $data["NumeroDocumentoIdentidad"]
			);
			$this->json->EliminarFilaEnArchivoJSON($url, $fila, "IdEmpleado");
		}*/

		echo $this->json->json_response($resultado);
	}

	public function DarBajaEmpleado()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sEmpleado->DarBajaEmpleado($data);

		echo $this->json->json_response($resultado);
	}


	public function SubirFoto()
	{
		$IdEmpleado = $this->input->post("IdPersona");
		$InputFileName = $this->input->post("InputFileName");

		$patcher = DIR_ROOT_ASSETS.'/img/Empleado/'.$IdEmpleado.'/';
		//$patcher = site_url().'../img/empleado/';
		//$config['upload_path'] = '../img/empleado/'.$IdProducto.'/';
		$config['upload_path'] = $patcher;

		$resultado = $this->shared->upload_file($InputFileName,$config);

		//print_r($resultado."\n");
		print_r($resultado);
		print_r($config['upload_path']);
		print_r($config);
	}

	public function ReactivarEmpleado()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sEmpleado->ReactivarEmpleado($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarReniec()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sEmpleado->ConsultarReniec($data);
		echo $this->json->json_response($resultado);
	}

	public function ConsultarEmpleadosPorPagina()
	{
		$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$resultado = $this->sEmpleado->ConsultarEmpleados($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

}

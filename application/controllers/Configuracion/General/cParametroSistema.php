<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cParametroSistema extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/General/sParametroSistema");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}
	public function Index()
	{
	
	}

	public function ListarParametrosSistema()
	{
	
	}

	public function InsertarParametroSistema()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			$resultado = $this->sParametroSistema->InsertarParametroSistema($data);
			if (is_array($resultado)) {
				$this->db->trans_commit();
				echo $this->json->json_response($resultado);
			} else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo $this->json->json_response_error($e);
		}
	}

	public function ActualizarParametroSistema()
	{
		try {
			$this->db->trans_begin();

			$data = $this->input->post("Data");
			
			$resultado = $this->sParametroSistema->ActualizarParametroSistema($data);
			if ($resultado == "") {
				$this->db->trans_commit();	
				echo $this->json->json_response($resultado);
			} else {
				$this->db->trans_rollback();
				echo $this->json->json_response_error($resultado);
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo $this->json->json_response_error($e);
		}
	}

    public function ConsultarParametroSistemaPorIdGrupo() {
        try {
			$data = $this->input->post("Data");
            $resultado = $this->sParametroSistema->ObtenerParametrosSistemaPorIdGrupo($data);

            if (is_array($resultado)) {
                echo $this->json->json_response($resultado);
            } else {
                echo $this->json->json_response_error($resultado);
            }
        } catch (Exception $e) {
            echo $this->json->json_response_error($e);
        }
	}
	
	public function ActualizarParametroSistemaPorGrupo() {
        try {
            $this->db->trans_begin();

            $data = $this->input->post("Data");

            $resultado = $this->sParametroSistema->ActualizarParametroSistemaPorGrupo($data);

            if (is_array($resultado)) {
                $this->db->trans_commit();				
                $resultado = $this->sParametroSistema->ObtenerParametrosSistemaPorIdGrupo($data[0]);
                echo $this->json->json_response($resultado);
            } else {
                $this->db->trans_rollback();
                echo $this->json->json_response_error($resultado);
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo $this->json->json_response_error($e);
        }
    }
	
}

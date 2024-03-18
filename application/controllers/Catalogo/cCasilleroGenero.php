<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cCasilleroGenero extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->service("Catalogo/sCasilleroGenero");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	public function Index() {

	}

    public function ListarCasillerosGenero() {
        $data = $this->input->get("Data");
        $resultado = $this->sCasilleroGenero->ListarCasillerosGenero($data);
		echo $this->json->json_response($resultado);
    }
    
	public function InsertarCasilleroGenero() {
        try {
			$data = $this->input->post("Data");
			$this->db->trans_begin();
			$resultado = $this->sCasilleroGenero->InsertarCasilleroGenero($data);
			if (is_array($resultado)) {
				$output["resultado"] = $resultado;				
				$this->db->trans_commit();					
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

	public function ActualizarCasilleroGenero() {
		try {
			$data = $this->input->post("Data");
			$this->db->trans_begin();			
			$resultado = $this->sCasilleroGenero->ActualizarCasilleroGenero($data);

			if ($resultado == "") {
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

	public function BorrarCasilleroGenero() {
		try {
			$data = $this->input->post("Data");
			$this->db->trans_begin();
			$resultado = $this->sCasilleroGenero->BorrarCasilleroGenero($data);

			if ($resultado == "") {
				$this->db->trans_commit();
				$output["msg"] = $resultado;
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

	public function LiberarCasilleroGenero() {
		try {
			$data = $this->input->post("Data");			
			$this->db->trans_begin();
			$resultado = $this->sCasilleroGenero->LiberarCasilleroGenero($data);

			if ($resultado == "") {
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
}

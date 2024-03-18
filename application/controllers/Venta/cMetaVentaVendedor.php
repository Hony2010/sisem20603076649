<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cMetaVentaVendedor extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Base");
        $this->load->service('Venta/sMetaVentaVendedor');
        $this->load->library('json');
        $this->load->helper('date');
    }

    public function Index()
    {

        $MetaVentaVendedor = $this->sMetaVentaVendedor->Cargar();
        $MetasVentaVendedor = $this->sMetaVentaVendedor->ConsultarMetasVentaVendedor($MetaVentaVendedor);
        $data = array(
            "data" => array(
                'MetaVentaVendedor' => $MetaVentaVendedor,
                'MetasVentaVendedor' => $MetasVentaVendedor
            )
        );

        $view_data['data'] = $data;

        $views['view_tabla_metaventavendedor'] = $this->load->View('Venta/MetaVentaVendedor/view_tabla_metaventavendedor', '', true);
        $view_footer['view_footer_extension'] = $this->load->View('Venta/MetaVentaVendedor/view_footer_metaventavendedor', $view_data, true);
        
        $view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
        $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
        $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
        $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
        $view['view_content'] =  $this->load->View('Venta/MetaVentaVendedor/view_content_metaventavendedor', $views, true);
        $view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer, true);

        $this->load->View('.Master/master_view_mainpanel', $view);
    }

    public function AgregarMetasVentaVendedor()
	{
		try {
			$this->db->trans_begin();

			$data = json_decode($this->input->post("Data"), true);
			$resultado = $this->sMetaVentaVendedor->AgregarMetasVentaVendedor($data);
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
}

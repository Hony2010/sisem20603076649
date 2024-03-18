<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cMetaVentaProducto extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Base");
        $this->load->service('Venta/sMetaVentaProducto');
        $this->load->library('json');
        $this->load->helper('date');
    }

    public function Index()
    {
        $filtro["TextoFiltro"] = '';
        $MetaVentaProducto = $this->sMetaVentaProducto->Cargar();
        $MetasVentaProducto = $this->sMetaVentaProducto->ConsultarMetasVentaProducto();
        $data = array(
            "data" => array(
                'MetaVentaProducto' => $MetaVentaProducto,
                'MetasVentaProducto' => $MetasVentaProducto,
                'Filtros' => $filtro
            )
        );

        $view_data['data'] = $data;

        $views['view_tabla_metaventaproducto'] = $this->load->View('Venta/MetaVentaProducto/view_tabla_metaventaproducto', '', true);
        // $views['view_buscador_metaventaproducto'] = $this->load->View('Venta/MetaVentaProducto/view_buscador_metaventaproducto', '', true);
        $view_footer['view_footer_extension'] = $this->load->View('Venta/MetaVentaProducto/view_footer_metaventaproducto', $view_data, true);

        $view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
        $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
        $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
        $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
        $view['view_content'] =  $this->load->View('Venta/MetaVentaProducto/view_content_metaventaproducto', $views, true);
        $view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer, true);

        $this->load->View('.Master/master_view_mainpanel', $view);
    }

    public function AgregarMetasVentaProducto()
    {
        try {
            $this->db->trans_begin();

            $data = json_decode($this->input->post("Data"), true);
            $resultado = $this->sMetaVentaProducto->AgregarMetasVentaProducto($data);
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

    public function ConsultarMetasVentaProducto()
    {
        try {
            $this->db->trans_begin();

            $data = json_decode($this->input->post("Data"), true);
            $resultado = $this->sMetaVentaProducto->ConsultarMetasVentaProducto($data);
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
}

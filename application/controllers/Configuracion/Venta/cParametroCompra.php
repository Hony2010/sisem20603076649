<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cParametroCompra extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->service('Configuracion/General/sParametroSistema');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('json');
    }

    public function Index()
    {
        $ParametrosSistema = $this->ObtenerParametroSistemaPorIdGrupo();

        $data = array("data" => array(
            'ParametrosSistema' => $ParametrosSistema,
            'NombreCotrolador' => "cParametroCompra"
        ));

        $view_data['data'] = $data;
        $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
        $view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
        $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
        $view['view_content'] = $this->load->View('Configuracion/Venta/ParametroCompra/view_mainpanel_content_parametrocompra', '', true);
        $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
        $view_ext['view_footer_extension'] = $this->load->View('Configuracion/Venta/ParametroCompra/view_mainpanel_footer_parametrocompra', $view_data, true);
        $view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_ext, true);

        $this->load->View('.Master/master_view_mainpanel', $view);
    }

    public function ObtenerParametroSistemaPorIdGrupo()
    {
        $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupo(["IdGrupoParametro" => 15]);

        $parametros = [];
        foreach ($resultado as $key => $value) {
            $parametros[str_replace(' ', '', $value->NombreParametroSistema)] = $value;
        }

        return $parametros;
    }

    public function ActualizarParametroSistemaPorGrupo()
    {
        try {
            $this->db->trans_begin();

            $data = $this->input->post("Data");

            $resultado = $this->sParametroSistema->ActualizarParametroSistemaPorGrupo($data);

            if (is_array($resultado)) {
                $this->db->trans_commit();

                $resultado = $this->ObtenerParametroSistemaPorIdGrupo();
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

    public function ConsultarParametroSistemaPorIdGrupo()
    {
        try {
            $resultado = $this->ObtenerParametroSistemaPorIdGrupo();

            if (is_array($resultado)) {
                echo $this->json->json_response($resultado);
            } else {
                echo $this->json->json_response_error($resultado);
            }
        } catch (Exception $e) {
            echo $this->json->json_response_error($e);
        }
    }
}

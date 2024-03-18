<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cConfiguracionCompra extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->service('Configuracion/General/sParametroSistema');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('json');
    }

    public function Index() {
        $ParametrosSistema = $this->sParametroSistema->ObtenerParametrosSistemaPorIdGrupo(["IdGrupoParametro" => ID_PARAMETRO_CONFIGURACION_COMPRA]);

        $data = array("data" => array(
            'ParametrosSistema' => $ParametrosSistema,
            'NombreCotrolador' => "cConfiguracionCompra",
            "IdGrupoParametro" => ID_PARAMETRO_CONFIGURACION_COMPRA
        ));

        $view_data['data'] = $data;
        $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
        $view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
        $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
        $view['view_content'] = $this->load->View('Configuracion/Compra/view_mainpanel_content_configuracioncompra', '', true);
        $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
        $view_ext['view_footer_extension'] = $this->load->View('Configuracion/Compra/view_mainpanel_footer_configuracioncompra', $view_data, true);
        $view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_ext, true);

        $this->load->View('.Master/master_view_mainpanel', $view);
    }

}

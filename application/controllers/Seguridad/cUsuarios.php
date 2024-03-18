<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cUsuarios extends CI_Controller  {

  public function __construct()
  {
    parent::__construct();
    $this->load->service("Seguridad/sMenu");
    $this->load->service("Seguridad/sUsuario");
		$this->load->service("Configuracion/General/sRol");
    $this->load->service("Catalogo/sEmpleado");
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->library('json');
  }

	public function Index() {
    $data_usuario =  $this->sUsuario->Inicializar();
    $data_empleado =  $this->sEmpleado->Inicializar();

    $Roles = $this->sRol->ListarRoles();
		$dataq["IdRol"] = $Roles[0]["IdRol"];
    $data_nueva = array(
						'AccesosRol' =>array(),
						'AccesoRol' => array(),
            'AccesosUsuario' => array(),
						'AccesoUsuario' => array(),
						'Roles' => $Roles
					);

    $data = array_merge($data_nueva, $data_usuario["data"], $data_empleado["data"]);
    $data = array("data" => $data);

    $view_data['data'] = $data;
    $view_sub_subcontent['view_subcontent_buscador_usuario']=   $this->load->View('Seguridad/Usuario/view_mainpanel_subcontent_buscador_usuario','',true);
    $view_subcontent['view_subcontent_preview_usuario'] =  $this->load->View('Seguridad/Usuario/view_mainpanel_subcontent_preview_usuario','',true);
    $view_subcontent['view_subcontent_consulta_usuarios'] =  $this->load->View('Seguridad/Usuario/view_mainpanel_subcontent_consulta_usuarios',$view_sub_subcontent,true);
    $view_subcontent['view_subcontent_form_usuario'] =  $this->load->View('Seguridad/Usuario/view_mainpanel_subcontent_form_usuario','',true);
    $view_subcontent['view_subcontent_form_accesousuario'] =  $this->load->View('Seguridad/Usuario/view_mainpanel_subcontent_form_accesosistema','',true);
    $view_subcontent['view_subcontent_form_empleado'] =  $this->load->View('Catalogo/Empleado/view_mainpanel_subcontent_form_empleado','',true);
    $view_subcontent['view_subcontent_form_accesorol'] =  $this->load->View('Seguridad/AccesoRol/view_mainpanel_subcontent_accesorol','',true);
    $view_ext['view_footer_extension'] = $this->load->View('Seguridad/Usuario/view_mainpanel_footer_usuario',$view_data,true);

    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
    $view['view_content'] =  $this->load->View('Seguridad/Usuario/view_mainpanel_content_usuario',$view_subcontent,true);
    $view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
  }

  function ListarUsuarios(){
    try {
			//$data = $this->input->get("Data");
			$resultado = $this->sUsuarios->ListarUsuarios();
			echo $this->json->json_response($resultado);
		}
		catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}	
  }

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cRestaurarBase extends CI_Controller  {

  public function __construct() {
    parent::__construct();
    $this->load->service("BaseDatos/sRestaurarBase");
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->library('archivo');
    $this->load->library('json');
  }

  public function Index() {
    $RestaurarBase =  $this->sRestaurarBase->Inicializar();

      $data = array("data" =>
  					array(
              'GeneracionJSON' => $RestaurarBase
  					)
  		 );

    $view_data['data'] = $data;
    $view_ext['view_footer_extension'] = $this->load->View('BaseDatos/RestaurarBase/view_mainpanel_footer_restaurarbase',$view_data,true);

    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
    $view['view_content'] =  $this->load->View('BaseDatos/RestaurarBase/view_mainpanel_content_restaurarbase','',true);
    $view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
  }

  public function RestaurarBase()
  {
    $data["NombreArchivo"] = 'C:\Users\Usuario\Desktop\Base\14.01.2019.sisem.sql';
    // $data = $this->input->post("Data");
    $resultado = $this->sRestaurarBase->RestaurarBase($data);
    echo $this->json->json_response($resultado);
  }


}

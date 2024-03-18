<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cBackupBaseDatos extends CI_Controller  {

  public function __construct() {
    parent::__construct();
    $this->load->service("Seguridad/sBaseDatos");
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->library('archivo');
    $this->load->library('json');
  }

  public function Index() {
    
    $data = array(
      "data" => array (
        'BackupBaseDatos' => array()
      )
    );
    
    $view_data['data'] = $data;
    $view_ext['view_footer_extension'] = $this->load->View('Seguridad/BaseDatos/BackupBaseDatos/view_mainpanel_footer_backupbasedatos',$view_data,true);

    $view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
    $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
    $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
    $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
    $view['view_content'] =  $this->load->View('Seguridad/BaseDatos/BackupBaseDatos/view_mainpanel_content_backupbasedatos','',true);
    $view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
  }
  

  public function GenerarBackupBaseDatos() {
    $data = $this->input->post("Data");
    $resultado = $this->sBaseDatos->GenerarBackup($data);  
    echo $this->json->json_response($resultado);
  }

  public function DescargarBackupBaseDatos() {
    $data = json_decode($this->input->get("Data"), true);
    $resultado = $this->sBaseDatos->DescargarBackup($data);  
    echo $this->json->json_response($resultado);
  }

  public function EnviarCorreoBackupBaseDatos() {
    $data = $this->input->post("Data");
    $resultado = $this->sBaseDatos->EnviarCorreoConBackupBaseDatos($data);
    echo $this->json->json_response($resultado);
  }

}

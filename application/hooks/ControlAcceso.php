<?php
 defined('BASEPATH') OR exit('No direct script access allowed');

class ControlAcceso  {

    public $CI;

    public function __construct() {
        if (!isset($this->CI)) {
            $this->CI =& get_instance();
        }

        $this->CI->load->service("Seguridad/sMenu");
        $this->CI->load->service("Seguridad/sLicencia");
        $this->CI->load->model("Seguridad/mOpcionSistema");
        $this->CI->load->library("sesionusuario");
    }

    public function ValidarAcessoOpcion() {

          $data["NombreControlador"] = $this->CI->router->fetch_class();
          $data["NombreMetodo"]  = $this->CI->router->fetch_method();


          if($data["NombreControlador"] !="AccesoDenegado" && $data["NombreControlador"] !="PaginaNoEncontrada" && $data["NombreControlador"] !="cSinLicencia") {

            if ($data["NombreControlador"]=="cSeguridad" && ($data["NombreMetodo"]=="index" || $data["NombreMetodo"] =="Login" || $data["NombreMetodo"] =="Logout")) {

            }
            else {

              $data["NombreUsuario"] =$this->CI->sesionusuario->obtener_sesion_nombre_usuario();
              $data["IdUsuario"] =$this->CI->sesionusuario->obtener_sesion_id_usuario();
              $licenciaRUC = $this->CI->sLicencia->ValidarLicenciaPorRUC($data);
              $licenciaUsuario ="";// $this->CI->sLicencia->ValidarLicenciaPorUsuario($data);
              $licenciaUsuarios =$this->CI->sLicencia->ValidarLicenciaPorUsuarios();
              $licenciaVentas = (LICENCIA_VENTA_FECHA_PERPETUA == 1) ? "" : $this->CI->sLicencia->ValidarLicenciaPorVentas();

              $mensajeDemo = $this->CI->sLicencia->ValidarMensajeDemo();

              $this->CI->session->set_userdata('data_mensaje_demo_'.LICENCIA_EMPRESA_RUC, $mensajeDemo);

              if($licenciaRUC != "") {
                $this->CI->session->set_userdata('data_mensaje_licencia_'.LICENCIA_EMPRESA_RUC, $licenciaRUC);
                redirect("cSinLicencia/");
              }

              // if($licenciaUsuario != "") {
              //   $this->CI->session->set_userdata('data_licencia_usuario_'.LICENCIA_EMPRESA_RUC, "S");
              //   $this->CI->session->set_userdata('data_mensaje_licencia_'.LICENCIA_EMPRESA_RUC, $licenciaUsuario);
              //   redirect("cSinLicencia/");
              // }

              if($licenciaUsuarios != "") {
                // $this->CI->session->set_userdata('data_licencia_usuario_'.LICENCIA_EMPRESA_RUC, "S");
                $this->CI->session->set_userdata('data_mensaje_licencia_'.LICENCIA_EMPRESA_RUC, $licenciaUsuarios);
                redirect("cSinLicencia/");
              }

              if($licenciaVentas != "") {
                $this->CI->session->set_userdata('data_mensaje_licencia_'.LICENCIA_EMPRESA_RUC, $licenciaVentas);
                redirect("cSinLicencia/");
              }
              $existeMenu = $this->CI->sMenu->ValidarMenu($data);

              if($existeMenu == false) {
                $resultado = $this->CI->sMenu->CrearMenuPorUsuario($data);
              }

              $opcion = $this->CI->mOpcionSistema->ObtenerOpcionPorNombreControlador($data);

              if($data["NombreControlador"] == NOMBRE_CONTROLADOR_DASHBOARD) {
                $this->CI->session->set_userdata("tab_".LICENCIA_EMPRESA_RUC,"");
                $this->CI->session->set_userdata("item_".LICENCIA_EMPRESA_RUC,"0");
              }

              if($opcion != null) {

                $this->CI->session->set_userdata("tab_".LICENCIA_EMPRESA_RUC,$opcion->NameModulo);
                $this->CI->session->set_userdata("item_".LICENCIA_EMPRESA_RUC,$opcion->NameOpcion);

                switch($opcion->TipoAccesoOpcion) {
                    case TIPO_ACCESO_OPCION_PUBLICO:
                        //print("publico : accede ".$data["NombreOpcion"]);
                        //return 0;
                    break;

                    case TIPO_ACCESO_OPCION_PRIVADO:
                        if($this->ValidarSesionActiva()) {
                            $data["IdOpcionSistema"] =$opcion->IdOpcionSistema;
                            $data["NombreUsuario"] =$this->CI->sesionusuario->obtener_sesion_nombre_usuario();
                            $resultado ="";// $this->CI->mAccesoUsuario->ObtenerAccesoOpcionPorUsuario($data);

                            if($resultado== ESTADO_OPCION_USUARIO_HABILITADO) {//->EstadoOpcionUsuario
                               //print("privado : accede ".$data["NombreOpcion"]);
                            }
                            else {
                               //print("privado : acesso denegado x rol");
                               //$data["Usuario"]=$this->CI->session->userdata("Usuario");
                               //$data["CodigoRolUsuario"]=$this->CI->session->userdata("Usuario")["CodigoRol"];
                               //$data["CodigoRol"]=$opcion["CodigoRol"];
                               $this->CI->session->set_userdata('data_acceso_denegado_'.LICENCIA_EMPRESA_RUC, $data);
                              // redirect("AccesoDenegado/");
                            }
                        }
                        else {
                            //print("privado : acesso denegado x sesion");
                            //$data["Usuario"]=$this->CI->session->userdata("Usuario");
                            //$data["CodigoRolUsuario"]=NULL;
                            //$data["CodigoRol"]=$opcion["CodigoRol"];
                            $this->CI->session->set_userdata('data_acceso_denegado_'.LICENCIA_EMPRESA_RUC, $data);

                            //redirect("AccesoDenegado/");
                        }

                    break;

                    case TIPO_ACCESO_PROTEGIDO:
                        if($this->ValidarSesionActiva()) {
                            //if($this->ValidarCoincideRolSesion($opcion["CodigoRol"])) {
                                //print("protegido : acceso ".$opcion["NombreOpcion"]." redirige a  ".$opcion["OpcionReferencia"]);
                              //  redirect($opcion["OpcionReferencia"]);
                            //}
                            //else {
                                //print("protegido : acesso ".$opcion["NombreOpcion"]."x rol");
                            //}
                        }
                        //else {
                            //print("protegido : acesso ".$opcion["NombreOpcion"]." x sesion");
                        //}
                    break;
                  }
              }
              else {
                $this->CI->session->set_userdata('data_acceso_denegado_'.LICENCIA_EMPRESA_RUC, $data);
                //redirect("PaginaNoEncontrada/");
              }
           }
        }
    }


    public function ValidarSesionActiva() {
        $NombreUsuario =$this->CI->sesionusuario->obtener_sesion_nombre_usuario();

        if($NombreUsuario != "")
            return true;
        else
            return false;
    }

}

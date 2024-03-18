<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cUsuario extends CI_Controller  {

  public function __construct() {
    parent::__construct();
    $this->load->service("Seguridad/sUsuario");
    $this->load->service("Seguridad/sMenu");
    $this->load->service("Seguridad/sAccesoUsuario");
    $this->load->service("Seguridad/sParametroSistema");
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->library('archivo');
    $this->load->library('json');
  }

	public function Index() {

  }

  public function InsertarUsuario() {
    try {
      $this->db->trans_begin();
      $data = json_decode($this->input->post("Data"), true);
      $opciones = $data["OpcionesSistema"];

      $resultado = $this->sUsuario->InsertarUsuario($data);
      
      if(is_array($resultado)) {
        $data["IdUsuario"] = $resultado["IdUsuario"];
        $datos = array();
        foreach ($opciones as $key) {
          if(array_key_exists('OpcionesSistema', $key)){
            foreach ($key["OpcionesSistema"] as $value) {
              $value["IdUsuario"] = $resultado["IdUsuario"];
              $value["IdRol"] = $data["IdRol"];
              // $value["EstadoOpcionUsuario"] = $value["EstadoOpcionUsuario"];
              $value["EstadoOpcionUsuario"] = $value["EstadoOpcionRol"];
              array_push($datos, $value);
            }
          }
        }

        if(count($datos) > 0){
          $this->sAccesoUsuario->InsertarAccesosUsuario($datos);
        }

        $almacenes = $this->sUsuario->InsertarAccesoUsuarioAlmacen($data);

        // foreach ($almacenes as $key => $value) {
        //   $seleccionado = filter_var($value["Seleccionado"], FILTER_VALIDATE_BOOLEAN);
        //   if($seleccionado == true)
        //   {
        //     $almacenes[$key]["Seleccionado"] = true;
        //   }
        //   else {
        //     // code...
        //     $almacenes[$key]["Seleccionado"] = false;
        //   }
        // }

        $data["Almacenes"] = $almacenes;

        $this->sMenu->CrearMenuPorUsuario($data);

        $this->db->trans_commit();
        echo $this->json->json_response($data);
      }
      else {
        $this->db->trans_rollback();
        echo $this->json->json_response_error($resultado);
      }
    } catch (Exception $e) {
      $this->db->trans_rollback();
      echo $this->json->json_response_error($e);
    }
  }

  public function ActualizarUsuario() {
    try {
      $this->db->trans_begin();
      $data = json_decode($this->input->post("Data"), true);
      $opciones = $data["OpcionesSistema"];

      $data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
      $DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);
      $nombre = "menu-".$data["NombreUsuario"];

      $resultado = $this->sUsuario->ActualizarUsuario($data);
      if(is_array($resultado)) {
        if($opciones != "") {
          $datos = array();
      		foreach ($opciones as $key) {
            if(array_key_exists('OpcionesSistema', $key)) {
        			foreach ($key["OpcionesSistema"] as $value) {
                $value["IdRol"] = $data["IdRol"];
                $value["IdUsuario"] = $data["IdUsuario"];
                $value["EstadoOpcionRol"] = $value["EstadoOpcionUsuario"];
        				array_push($datos, $value);
        			}
            }
      		}

          if(count($datos) > 0) {
            $accesos = $this->sAccesoUsuario->ActualizarAccesosUsuario($datos);
            if($accesos == "")
            {
              $ruta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_MENU"].$nombre.".json";
              $resultado2=$this->archivo->EliminarArchivo($ruta);
              $this->sMenu->CrearMenuPorUsuario($data);
            }
            else {
              throw new Exception($accesos, 1);
            }
            // return $resultado;
          }
          else {
            if($data["IdPersona"] != $data["AnteriorIdPersona"])
            {
              $response = $this->sMenu->CargarOpcionesPorUsuario($data);
              if(count($response) > 0)
              {
                $datos = array();
                foreach ($response as $key) {
                  if(array_key_exists('OpcionesSistema', $key)) {
                    foreach ($key["OpcionesSistema"] as $value) {
                      $value["IdRol"] = $data["IdRol"];
                      $value["IdUsuario"] = $data["IdUsuario"];
                      $value["EstadoOpcionRol"] = $value["EstadoOpcionUsuario"];
                      array_push($datos, $value);
                    }
                  }
                }

                $this->sAccesoUsuario->ActualizarAccesosUsuario($datos);
              }
              else {
                $this->sAccesoUsuario->BorrarAccesosPorUsuario($data);
              }
              $ruta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_MENU"].$nombre.".json";
              $resultado2=$this->archivo->EliminarArchivo($ruta);
              $this->sMenu->CrearMenuPorUsuario($data);
            }
          }
        }

        $this->db->trans_commit();
        echo $this->json->json_response($resultado);
      }
      else {
        $this->db->trans_rollback();
        echo $this->json->json_response_error($resultado);
      }

    } catch (Exception $e) {
      $this->db->trans_rollback();
      echo $this->json->json_response_error($e->getMessage());
    }
  }

	public function BorrarUsuario() {
    $data = json_decode($this->input->post("Data"), true);

    $resultado = $this->sUsuario->BorrarUsuario($data);

    if($resultado == "")
    {
      $this->sAccesoUsuario->BorrarAccesosPorUsuario($data);

      $data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
      $DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);
      $nombre = "menu-".$data["NombreUsuario"];
      $ruta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_MENU"].$nombre.".json";
      $resultado2=$this->archivo->EliminarArchivo($ruta);
    }

    echo $this->json->json_response($resultado);
  }

}

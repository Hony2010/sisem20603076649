<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMenu extends MY_Service {

        public $OpcionSistema = array();
        public $ModuloSistema = array();

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->service("Seguridad/sModuloSistema");
              $this->load->service("Seguridad/sOpcionSistema");
              $this->load->service("Seguridad/sAccesoUsuario");
              $this->load->service("Seguridad/sAccesoRol");
              $this->load->service('Configuracion/General/sParametroSistema');
              $this->load->library('shared');
              $this->load->library('mapper');

              $this->OpcionSistema = $this->mOpcionSistema->OpcionSistema;
              $this->ModuloSistema = $this->mModuloSistema->ModuloSistema;
        }

        function CrearMenuPorUsuario($data) {

          $modulos = $this->sModuloSistema->ListarModulosSistema();
          $data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
          $DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);

          $dataArchivoValidacion = unserialize(PARAMETRO_MODULOS_SISTEMA);

          $resultado["Modulos"] = array();
          foreach($modulos as $keymodulo =>$valuemodulo) {
            $input["IdUsuario"] = $data["IdUsuario"];
            $input["IdModuloSistema"] = $valuemodulo["IdModuloSistema"];
            $opciones = $this->sOpcionSistema->ObtenerOpcionesSistemaPorModuloSistema($valuemodulo);
            $opcionesusuario =$this->sOpcionSistema->ObtenerOpcionesPorIdUsuarioPorIdModuloSistema($input);

            if (count($opcionesusuario) > 0) {
              // print_r($valuemodulo);
              // print_r($opcionesusuario);
              foreach ($dataArchivoValidacion as $key => $value) {
                if($valuemodulo["IdModuloSistema"] == $value["IdModuloSistema"])
                {
                  if($value["IndicadorEstado"] == 'A')
                  {
                    $resultado["Modulos"][$keymodulo] = $valuemodulo;
                    $resultado["Modulos"][$keymodulo]["Opciones"] =$opcionesusuario;
                  }
                }
              }

            }
          }

          // print_r($dataArchivoValidacion);
          // exit;
          $nombre="menu-".$data["NombreUsuario"];
          $data_json["ruta"] = APP_PATH.$DatosCarpeta["RUTA_CARPETA_MENU"].$nombre.".json";
          $data_json["plantilla"] =APP_PATH.$DatosCarpeta["RUTA_CARPETA_MENU"]."tplmenu.json";
          $data_json["data"] = $resultado;
          // print_r($data_json["data"]);
          // exit;
          clearstatcache(true,$data_json["ruta"]);

          $json = $this->json->CrearJSONPlantillaDetalle($data_json);

          return $resultado;
        }

        function ValidarMenu($data) {
          $data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
          $DatosCarpeta = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);
          $nombre ="menu-".$data["NombreUsuario"];
          $ruta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_MENU"].$nombre.".json";

          if(file_exists($ruta)) {
              return true;
          }
          else {
            return false;
          }
        }

        function CargarOpcionesPorUsuario($data) {
          $resultado=$this->sModuloSistema->ListarModulosSistema();

          $dataArchivoValidacion = unserialize(PARAMETRO_MODULOS_SISTEMA);

          foreach ($resultado as $key => $item) {
            foreach ($dataArchivoValidacion as $key3 => $value3) {
              if($item["IdModuloSistema"] == $value3["IdModuloSistema"])
              {
                if($value3["IndicadorEstado"] == 'A')
                {

                  $opciones=$this->sOpcionSistema->ObtenerOpcionesSistemaPorModuloSistema($item);

                  $item['IdUsuario'] = $data['IdUsuario'];
                  $item['IdRol'] = $data['IdRol'];

                  foreach ($opciones as $key2 => $item2) {
                    $item['IdOpcionSistema'] = $item2['IdOpcionSistema'];
                    $resultado[$key]["OpcionesSistema"][$key2]["IdUsuario"] = $item['IdUsuario'];
                    $resultado[$key]["OpcionesSistema"][$key2]["IdModuloSistema"] = $item['IdModuloSistema'];
                    $resultado[$key]["OpcionesSistema"][$key2]["IdOpcionSistema"] =  $item2["IdOpcionSistema"];
                    $resultado[$key]["OpcionesSistema"][$key2]["NombreOpcionSistema"] =  $item2["NombreOpcionSistema"];
                    $resultado_opcion = $this->sAccesoUsuario->ObtenerAccesosUsuarioPorIdOpcionSistemaPorIdUsuarioPorIdRol($item);

                    if (count($resultado_opcion) != 0) {
                      if( $resultado_opcion[0]['IdAccesoUsuario'] == "") {
                        $resultado[$key]["OpcionesSistema"][$key2]["EstadoOpcionUsuario"] =0;
                        $resultado[$key]["OpcionesSistema"][$key2]["IdAccesoUsuario"] = "";
                      }
                      else {
                        $resultado[$key]["OpcionesSistema"][$key2]["EstadoOpcionUsuario"] = $resultado_opcion[0]['EstadoOpcionUsuario'];
                        $resultado[$key]["OpcionesSistema"][$key2]["IdAccesoUsuario"] = $resultado_opcion[0]['IdAccesoUsuario'];
                      }

                      $resultado[$key]["OpcionesSistema"][$key2]["IdAccesoRol"] = $resultado_opcion[0]['IdAccesoRol'];
                    }
                    else {
                      $resultado[$key]["OpcionesSistema"][$key2]["EstadoOpcionUsuario"] = 0;
                      $resultado[$key]["OpcionesSistema"][$key2]["IdAccesoUsuario"] = "";
                      $resultado[$key]["OpcionesSistema"][$key2]["IdAccesoRol"] = "";
                    }
                  }

                }
                else {
                  unset($resultado[$key]);
                }
              }
            }

          }

          $resultado = array_values($resultado);
          return $resultado;
        }

        function CargarOpcionesPorRol($data) {
          $resultado=$this->sModuloSistema->ListarModulosSistema();

          $dataArchivoValidacion = unserialize(PARAMETRO_MODULOS_SISTEMA);

          foreach ($resultado as $key => $item) {
            foreach ($dataArchivoValidacion as $key3 => $value3) {
              if($item["IdModuloSistema"] == $value3["IdModuloSistema"])
              {

                if($value3["IndicadorEstado"] == 'A')
                {

                  $opciones=$this->sOpcionSistema->ObtenerOpcionesSistemaPorModuloSistema($item);
                  $item['IdRol'] = $data['IdRol'];
                  $resultado[$key]["OpcionesSistema"] = $opciones;
                  foreach ($opciones as $key2 => $item2) {
                    $item['IdOpcionSistema'] = $item2['IdOpcionSistema'];
                    $resultado_opcion =  $this->sAccesoRol->ObtenerAccesosRolPorIdOpcionSistemaPorIdRol($item);
                    if (count($resultado_opcion) != 0) {
                      $resultado[$key]["OpcionesSistema"][$key2]["EstadoOpcionRol"] = $resultado_opcion[0]['EstadoOpcionRol'];
                      $resultado[$key]["OpcionesSistema"][$key2]["IdAccesoRol"] = $resultado_opcion[0]['IdAccesoRol'];
                    }
                    else {
                      $resultado[$key]["OpcionesSistema"][$key2]["EstadoOpcionRol"] = 0;
                      $resultado[$key]["OpcionesSistema"][$key2]["IdAccesoRol"] = "";
                    }
                    $resultado[$key]["OpcionesSistema"][$key2]["EstadoOpcionUsuario"] = 0;
                  }

                }
                else {
                  unset($resultado[$key]);
                }
              }
            }

          }
          $resultado = array_values($resultado);

          return $resultado;
        }
}

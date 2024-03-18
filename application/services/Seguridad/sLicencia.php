<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sLicencia extends MY_Service {

        public function __construct() {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->service("Configuracion/General/sEmpresa");
              $this->load->service("Configuracion/General/sConstanteSistema");
              $this->load->service("Venta/sComprobanteVenta");
              $this->load->service("Seguridad/sUsuario");
              $this->load->library('shared');
              $this->load->library('mapper');
        }

        function ValidarLicenciaPorRUC($data) {
          $idusuario=$data["IdUsuario"];
          $input["IdEmpresa"]=LICENCIA_EMPRESA_ID;
          $dataEmpresa = $this->sEmpresa->ListarEmpresas($input);

          if(count($dataEmpresa) == 0) {
            return "La licencia solo permite el uso del  ruc ".LICENCIA_EMPRESA_RUC.", revise su base de datos.";
          }
          else {
            $ruc =$dataEmpresa[0]["CodigoEmpresa"];
            if ($ruc != LICENCIA_EMPRESA_RUC) {
              return "La licencia solo permite el uso del  ruc ".LICENCIA_EMPRESA_RUC.", revise su base de datos.";
            }
          }

          return "";
        }

        // function ValidarLicenciaPorUsuario($data) {
        //   $idusuario=$data["IdUsuario"];
        //
        //   if($idusuario != LICENCIA_USUARIO_ID) {
        //     return "La licencia solo permite el uso de un usuario, revise su base de datos.";
        //   }
        //
        //   return "";
        // }

        function ValidarLicenciaPorUsuarios() {
          $usuarios=$this->sUsuario->ObtenerTotalUsuariosActivos();

          if($usuarios->Total  > LICENCIA_CANTIDAD_USUARIO) {
            $texto = "usuarios";
            if(LICENCIA_CANTIDAD_USUARIO == 1)
            {
              $texto = "usuario";
            }
            return "La licencia solo permite el uso de ".LICENCIA_CANTIDAD_USUARIO." ".$texto.", revise su base de datos.";
          }

          return "";
        }

        function ValidarLicenciaPorVentas() {
          $resultado= $this->sComprobanteVenta->ObtenerMinimoMaximoFechaEmisionComprobanteVenta();

          $resultado1 =  validateDateDiff(LICENCIA_VENTA_FECHA_EMISION_MINIMO,$resultado->minimofechaemision,"d") >= 0 && validateDateDiff($resultado->minimofechaemision,LICENCIA_VENTA_FECHA_EMISION_MAXIMO,"d") >= 0;
          $resultado2 =  validateDateDiff(LICENCIA_VENTA_FECHA_EMISION_MINIMO,$resultado->maximofechaemision,"d") >= 0 && validateDateDiff($resultado->maximofechaemision,LICENCIA_VENTA_FECHA_EMISION_MAXIMO,"d") >= 0;

          if ($resultado1 == true && $resultado2 == true) {
              return "";
          }
          else {
            return "La licencia solo permite emitir ventas desde ".convertirFechaES(LICENCIA_VENTA_FECHA_EMISION_MINIMO)." al ".convertirFechaES(LICENCIA_VENTA_FECHA_EMISION_MAXIMO)."<BR>Favor de revisar la Base de Datos";
          }
        }

        function ValidarLicenciaPorVenta($data) {
          $fechaemision=convertToDate($data["FechaEmision"]);
          $resultado =  validateDateDiff(LICENCIA_VENTA_FECHA_EMISION_MINIMO,$fechaemision,"d") >= 0 && validateDateDiff($fechaemision,LICENCIA_VENTA_FECHA_EMISION_MAXIMO,"d") >= 0;
          if ($resultado == true) {
              return "";
          }
          else {
            return "La licencia solo permite emitir ventas desde ".convertirFechaES(LICENCIA_VENTA_FECHA_EMISION_MINIMO)." al ".convertirFechaES(LICENCIA_VENTA_FECHA_EMISION_MAXIMO);
          }
        }

        function ValidarMensajeDemo() {
          $mensajeDemo = $this->sConstanteSistema->ObtenerAtributosMensajeDemo();

          if (is_array($mensajeDemo)) {
            $data["ParametroDemo"] = $mensajeDemo[0]->ValorParametroSistema;
            $data["CelularDemo"] = $mensajeDemo[1]->ValorParametroSistema;
            $data["MensajeDemo"] = $mensajeDemo[2]->ValorParametroSistema;
            $data["VersionDemo"] = $mensajeDemo[3]->ValorParametroSistema;
            return $data;
          } else {
            return $mensajeDemo;
          }
        }
}

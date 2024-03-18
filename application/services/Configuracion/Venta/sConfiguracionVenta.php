<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sConfiguracionVenta extends MY_Service {

        public $ConfiguracionVenta = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->service('Configuracion/General/sParametroSistema');

              // $this->load->model('Configuracion/Venta/mConfiguracionVenta');
              // $this->ConfiguracionVenta = $this->mConfiguracionVenta->ConfiguracionVenta;
        }

        function ObtenerCamposVenta()
        {
          $data['IdGrupoParametro']= ID_PARAMETRO_CONFIGURACION_VENTA;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupo($data);
          $data_r = [];
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
            foreach ($resultado as $key => $value) {
              $data_r[$value->NombreParametroSistema] = $value->ValorParametroSistema;
            }
            return $data_r;
          }
        }

        function ObtenerParametrosParaCamposConEnvioYGestion()
        {
          $data['IdParametroSistema']= ID_PARAMETRO_CAMPOS_CON_ENVIO_Y_GESTION;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

          if(is_string($resultado))
          {
            return $resultado;
          }
          else
          {
            $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
            return $ValorParametroSistema;
          }
        }

}

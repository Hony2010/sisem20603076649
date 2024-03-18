<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sInventario extends MY_Service {

        public $Inventario = array();
        
        public function __construct() {
          parent::__construct();
          $this->load->database();
          $this->load->library('shared');
          $this->load->library('sesionusuario');
          $this->load->library('mapper');
          $this->load->library('herencia');
          $this->load->library('reporter');
          $this->load->library('imprimir');
          $this->load->helper("date");
          $this->load->model("Base");
          $this->load->model('Inventario/mInventario');
          $this->load->service('Configuracion/General/sParametroSistema');
        }

        function ObtenerTotalInventarioMercaderiasPorSede($data) {
          $resultado = $this->mInventario->ObtenerTotalInventarioMercaderiasPorSede($data);
          return $resultado;
        }

        function ListarInventarioMercaderiasPorSede($data,$numeropagina,$numerofilasporpagina) {
          $numerofilainicio=$numerofilasporpagina * ($numeropagina - 1);
          $resultado = $this->mInventario->ListarInventarioMercaderiasPorSede($data,$numerofilainicio,$numerofilasporpagina);
          return $resultado;
        }
        
        function ObtenerNumeroFilasPorPagina() {
          $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_COMPROBANTEVENTA;
          $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
          $numerofilasporpagina=$parametro->ValorParametroSistema;
          return $numerofilasporpagina;          
        }    
}


<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoListaPrecio extends MY_Service {

        public $TipoListaPrecio = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/Catalogo/mTipoListaPrecio');
              $this->TipoListaPrecio = $this->mTipoListaPrecio->TipoListaPrecio;
        }

        function ListarTiposListaPrecio() {
          $resultado = $this->mTipoListaPrecio->ListarTiposListaPrecio();
          return $resultado;
        }

        function ObtenerTipoListaPrecioMinimo() {
          $resultado = $this->mTipoListaPrecio->ObtenerTipoListaPrecioMinimo();
          return $resultado;
        }

        function ListarTiposListaPrecioSinTipoPrecioMinimo() {
          $resultado = $this->mTipoListaPrecio->ListarTiposListaPrecioSinTipoPrecioMinimo();
          return $resultado;
        }        

        function ObtenerTipoListaPrecioEspecialCliente() {
          $resultado = $this->mTipoListaPrecio->ObtenerTipoListaPrecioEspecialCliente();
          return $resultado;
        }
}

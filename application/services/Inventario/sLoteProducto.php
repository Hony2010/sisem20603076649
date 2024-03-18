<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sLoteProducto extends MY_Service {

        public $LoteProducto = array();

        public function __construct()
        {
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
          $this->load->model('Inventario/mLoteProducto');
          $this->load->model('Catalogo/mMercaderia');

          $this->LoteProducto = $this->mLoteProducto->LoteProducto;
        }

        function Cargar()
        {

        }

        function ObtenerLoteProductoPorIdLoteProductoAndIdProducto($data)
        {
          $resultado = $this->mLoteProducto->ObtenerLoteProductoPorIdLoteProductoAndIdProducto($data);
          return $resultado;
        }

        function AgregarLoteProducto($data)
        {
          $resultado = $this->ObtenerLoteProductoPorProductoLote($data);

          if(count($resultado) > 0)
          {
            $resultado[0]["NumeroLote"] = $data["NumeroLote"];
            $resultado[0]["FechaVencimiento"] = $data["FechaVencimiento"];
            
            $response = $this->ActualizarLoteProducto($resultado[0]);
            return $response;
          }
          else {
            $data["IdLoteProducto"] = "";
            $response = $this->InsertarLoteProducto($data);
            return $response;
          }
        }

        function InsertarLoteProducto($data)
        {
          try {

            $resultadoValidacion = "";
            if($resultadoValidacion == "")
            {
              $resultado= $this->mLoteProducto->InsertarLoteProducto($data);
              return $resultado;
            }
            else
            {
              $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
              return $resultado;
            }
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function ActualizarLoteProducto($data)
        {
          try {
            $resultadoValidacion = "";
            if($resultadoValidacion == "")
            {
              $resultado=$this->mLoteProducto->ActualizarLoteProducto($data);

              return $resultado;
            }
            else
            {
              throw new Exception(nl2br($resultadoValidacion));
            }
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function BorrarLoteProducto($data)
        {
          $this->mLoteProducto->BorrarLoteProducto($data);

          return "";
        }

        function ObtenerLoteProductoPorProductoLote($data)
        {
          $resultado = $this->mLoteProducto->ObtenerLoteProductoPorProductoLote($data);
          return $resultado;
        }

        function ObtenerLotesProductoPorProductoLote($data)
        {
          $resultado = $this->mLoteProducto->ObtenerLotesProductoPorProductoLote($data);
          return $resultado;
        }

}

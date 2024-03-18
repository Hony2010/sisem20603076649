<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAccesoUsuarioAlmacen extends MY_Service {

        public $AccesoUsuarioAlmacen = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Seguridad/mAccesoUsuarioAlmacen');
              $this->AccesoUsuarioAlmacen = $this->mAccesoUsuarioAlmacen->AccesoUsuarioAlmacen;
        }

        function ConsultarAlmacenesUsuario($data)
        {
            $resultado=$this->mAccesoUsuarioAlmacen->ConsultarAlmacenesUsuario($data);
            return $resultado;
        }

        function ConsultarSedesTipoAlmacenPorUsuario()
        {
            $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
            // $data["IndicadorAlmacenZofra"] = array_key_exists("IndicadorAlmacenZofra", $parametro) ? $parametro["IndicadorAlmacenZofra"] : '%' ;
            $resultado=$this->mAccesoUsuarioAlmacen->ConsultarSedesTipoAlmacenPorUsuario($data);
            return $resultado;
        }

        function AgregarAccesoUsuarioAlmacen($data)
        {
          $response = $this->sAccesoUsuarioAlmacen->ValidarAccesoUsuarioAlmacen($data);

          if(count($response) > 0)
          {
            //AQUI ACTUALIZAMOS POR EL ID
            $response[0]["IndicadorEstado"] = $data["IndicadorEstado"];
            $resultado = $this->ActualizarAccesoUsuarioAlmacen($response[0]);
            return $resultado;
          }
          else {
            //AQUI INSERTAMOS UNO NUEVO
            $data["IdAccesoUsuarioAlmacen"] = "";
            $resultado = $this->InsertarAccesoUsuarioAlmacen($data);
            return $resultado;
          }
        }

        function InsertarAccesoUsuarioAlmacen($data)
        {
            $resultado=$this->mAccesoUsuarioAlmacen->InsertarAccesoUsuarioAlmacen($data);
            $data["IdAccesoUsuarioAlmacen"] = $resultado;
            return $data;
        }

        function ActualizarAccesoUsuarioAlmacen($data)
        {
          $resultado=$this->mAccesoUsuarioAlmacen->ActualizarAccesoUsuarioAlmacen($data);
          return $resultado;
        }

        function BorrarAccesoUsuarioAlmacen($data)
        {
          $resultado=$this->mAccesoUsuarioAlmacen->BorrarAccesoUsuarioAlmacen($data);
          return $resultado;
        }

        function BorrarAccesoUsuarioAlmacenPorUsuario($data)
        {
          $resultado=$this->mAccesoUsuarioAlmacen->BorrarAccesoUsuarioAlmacenPorUsuario($data);
          return $resultado;
        }

        function ValidarAccesoUsuarioAlmacen($data)
        {
            $resultado=$this->mAccesoUsuarioAlmacen->ValidarAccesoUsuarioAlmacen($data);
            return $resultado;
        }
}

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAccesoCajaUsuario extends MY_Service {

        public $AccesoCajaUsuario = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->library('sesionusuario');
              $this->load->model('Seguridad/mAccesoCajaUsuario');
              $this->load->service('Caja/sCaja');
              $this->AccesoCajaUsuario = $this->mAccesoCajaUsuario->AccesoCajaUsuario;
        }

        function Cargar()
        {
          $data =array();
          $resultado = array_merge($this->AccesoCajaUsuario, $data);

          return $resultado;
        }

        function ListarCajasDesdeAccesoCajaUsuario()
        {
          //SE TOMAN LAS CAJAS DE LA PC ASIGNADA
          $cajas = $this->sCaja->ListarCajasPorNumeroCaja();
          foreach ($cajas as $key => $value) {
            $objeto = array_merge($this->AccesoCajaUsuario, $value);
            $cajas[$key] = $objeto;
            $cajas[$key]["Seleccionado"] = false;
          }

          return $cajas;
        }

        function ObtenerAccesosCajaUsuarioPorIdUsuario($data)
        {
          //SE TOMAN LAS CAJAS DE LA PC ASIGNADA
          $listadocajas = $this->sCaja->ListarCajasPorNumeroCaja();
          $cajasusuario = $this->mAccesoCajaUsuario->ConsultarAccesosCajaUsuarioPorIdUsuario($data);
          $cajas = $listadocajas;

          $response["SeleccionarTodosCajas"] = false;
          $response["NumeroCajasSeleccionadas"] = 0;
          $seleccionados = 0;
          
          foreach($cajas  as $key => $value)
          {
            $cajas[$key] = array_merge($this->AccesoCajaUsuario, $value);
            $cajas[$key]["Seleccionado"] = false;

            foreach($cajasusuario  as $key2 =>$value2)
            {
              if($value["IdCaja"] == $value2["IdCaja"])
              {
                $cajas[$key]["IdAccesoCajaUsuario"] = $value2["IdAccesoCajaUsuario"];
                if($value2["EstadoCajaUsuario"] == '1')
                {
                  $cajas[$key]["Seleccionado"] = true;
                  $seleccionados++;
                }
              }
            }

          }

          if(count($cajas) == $seleccionados){
            $response["SeleccionarTodosCajas"] = true;
          }
          $response["NumeroCajasSeleccionadas"] = $seleccionados;
          $response["Cajas"] =$cajas;
          
          return $response;
        }

        function ListarAccesosCajaUsuarioPorIdUsuario()
        {
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $resultado = $this->mAccesoCajaUsuario->ConsultarAccesosCajaUsuarioPorIdUsuario($data);
          return $resultado;
        }

        function InsertarAccesoCajaUsuario($data)
        {
          $resultado = $this->mAccesoCajaUsuario->InsertarAccesoCajaUsuario($data);
          return $resultado;
        }

        function ActualizarAccesoCajaUsuario($data)
        {
          $resultado = $this->mAccesoCajaUsuario->ActualizarAccesoCajaUsuario($data);
          return $resultado;
        }

        function BorrarAccesoCajaUsuario($data)
        {
          $resultado = $this->mAccesoCajaUsuario->BorrarAccesoCajaUsuario($data);
          return $resultado;
        }

        function BorrarAccesosCajaUsuarioPorUsuario($data)
        {
          $resultado=$this->mAccesoCajaUsuario->BorrarAccesosCajaUsuarioPorUsuario($data);
          return $resultado;
        }

        //PARA INSERTAR Y ACTUALIZAR ACCESOUSUARIOCAJA
        function AgregarAccesoCajaUsuario($data)
        {
          $response = $this->mAccesoCajaUsuario->ObtenerAccesoCajaUsuarioPorUsuarioYCaja($data);

          if(count($response) > 0)
          {
            //AQUI ACTUALIZAMOS POR EL ID
            $response[0]["EstadoCajaUsuario"] = $data["EstadoCajaUsuario"];
            $resultado = $this->ActualizarAccesoCajaUsuario($response[0]);
            return $resultado;
          }
          else {
            //AQUI INSERTAMOS UNO NUEVO
            $data["IdAccesoCajaUsuario"] = "";
            $resultado = $this->InsertarAccesoCajaUsuario($data);
            return $resultado;
          }
        }

        //PARA AGREGAR LOS ACCESOS, EN FORMA DE MASIVA [USO PARA INSERTAR - ACTUALIZAR]
        function AgregarAccesosCajaUsuario($data, $id)
        {
          foreach ($data as $key => $value) {
            $value["IdUsuario"] = $id;
            $seleccionado = filter_var($value["Seleccionado"], FILTER_VALIDATE_BOOLEAN);
            ($seleccionado == false) ? $value["EstadoCajaUsuario"] = 0 : $value["EstadoCajaUsuario"] = 1;
            $resultado = $this->AgregarAccesoCajaUsuario($value);
            $response = array_merge($value, $resultado);
            $data[$key] = $response;
          }
          
          return $data;
        }
}

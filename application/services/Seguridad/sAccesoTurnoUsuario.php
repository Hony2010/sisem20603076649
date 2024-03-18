<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sAccesoTurnoUsuario extends MY_Service {

        public $AccesoTurnoUsuario = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Seguridad/mAccesoTurnoUsuario');
              $this->load->service('Caja/sTurno');
              $this->AccesoTurnoUsuario = $this->mAccesoTurnoUsuario->AccesoTurnoUsuario;
        }

        function Cargar()
        {
          $data =array();
          $resultado = array_merge($this->AccesoTurnoUsuario, $data);

          return $resultado;
        }

        function ListarTurnosDesdeAccesoTurnoUsuario()
        {
          $turnos = $this->sTurno->ListarTurnos();
          foreach ($turnos as $key => $value) {
            $objeto = array_merge($this->AccesoTurnoUsuario, $value);
            $turnos[$key] = $objeto;
            $turnos[$key]["Seleccionado"] = false;
          }

          return $turnos;
        }

        function ObtenerAccesosTurnoUsuarioPorIdUsuario($data)
        {
          $listadoturnos = $this->sTurno->ListarTurnos();
          $turnosusuario = $this->mAccesoTurnoUsuario->ConsultarTurnosUsuario($data);
          $turnos = $listadoturnos;

          $response["SeleccionarTodosTurnos"] = false;
          $response["NumeroTurnosSeleccionadas"] = 0;
          $seleccionados = 0;
          
          foreach($turnos  as $key => $value)
          {
            $turnos[$key] = array_merge($this->AccesoTurnoUsuario, $value);
            $turnos[$key]["Seleccionado"] = false;

            foreach($turnosusuario  as $key2 =>$value2)
            {
              if($value["IdTurno"] == $value2["IdTurno"])
              {
                $turnos[$key]["IdAccesoTurnoUsuario"] = $value2["IdAccesoTurnoUsuario"];
                if($value2["EstadoTurnoUsuario"] == '1')
                {
                  $turnos[$key]["Seleccionado"] = true;
                  $seleccionados++;
                }
              }
            }

          }

          if(count($turnos) == $seleccionados){
            $response["SeleccionarTodosTurnos"] = true;
          }
          $response["NumeroTurnosSeleccionadas"] = $seleccionados;
          $response["Turnos"] =$turnos;
          
          return $response;
        }

        function InsertarAccesoTurnoUsuario($data)
        {
          $resultado = $this->mAccesoTurnoUsuario->InsertarAccesoTurnoUsuario($data);
          return $resultado;
        }

        function ActualizarAccesoTurnoUsuario($data)
        {
          $resultado = $this->mAccesoTurnoUsuario->ActualizarAccesoTurnoUsuario($data);
          return $resultado;
        }

        function BorrarAccesoTurnoUsuario($data)
        {
          $resultado = $this->mAccesoTurnoUsuario->BorrarAccesoTurnoUsuario($data);
          return $resultado;
        }

        function BorrarAccesosTurnoUsuarioPorUsuario($data)
        {
          $resultado=$this->mAccesoTurnoUsuario->BorrarAccesosTurnoUsuarioPorUsuario($data);
          return $resultado;
        }
        //PARA INSERTAR Y ACTUALIZAR ACCESOUSUARIOCAJA
        function AgregarAccesoTurnoUsuario($data)
        {
          $response = $this->mAccesoTurnoUsuario->ObtenerAccesoTurnoUsuarioPorUsuarioYTurno($data);

          if(count($response) > 0)
          {
            //AQUI ACTUALIZAMOS POR EL ID
            $response[0]["EstadoTurnoUsuario"] = $data["EstadoTurnoUsuario"];
            $resultado = $this->ActualizarAccesoTurnoUsuario($response[0]);
            return $resultado;
          }
          else {
            //AQUI INSERTAMOS UNO NUEVO
            $data["IdAccesoTurnoUsuario"] = "";
            $resultado = $this->InsertarAccesoTurnoUsuario($data);
            return $resultado;
          }
        }

        //PARA AGREGAR LOS ACCESOS, EN FORMA DE MASIVA [USO PARA INSERTAR - ACTUALIZAR]
        function AgregarAccesosTurnoUsuario($data, $id)
        {
          foreach ($data as $key => $value) {
            $value["IdUsuario"] = $id;
            $seleccionado = filter_var($value["Seleccionado"], FILTER_VALIDATE_BOOLEAN);
            ($seleccionado == false) ? $value["EstadoTurnoUsuario"] = 0 : $value["EstadoTurnoUsuario"] = 1;
            $resultado = $this->AgregarAccesoTurnoUsuario($value);
            $response = array_merge($value, $resultado);
            $data[$key] = $response;
          }
          
          return $data;
        }

        function ObtenerTurnosPorIdUsuario($data)
        {
          $resultado = $this->mAccesoTurnoUsuario->ObtenerTurnosPorIdUsuario($data);
          return $resultado;
        }
}

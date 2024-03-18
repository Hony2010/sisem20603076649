<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMesa extends CI_Model {

        public $Mesa = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->Mesa = $this->Base->Construir("Mesa");
        }

        function InsertarMesa($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $data["SituacionMesa"] = SITUACION_MESA_DISPONIBLE;
          $resultado = $this->mapper->map($data,$this->Mesa);
          $this->db->insert('Mesa', $resultado);
          $resultado["IdMesa"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarMesa($data)
        {
          $id=$data["IdMesa"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->Mesa);
          $this->db->where('IdMesa', $id);
          $this->db->update('Mesa', $resultado);

          return $resultado;
        }

        function BorrarMesa($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $resultado = $this->ActualizarMesa($data);
          return $resultado;
        }

        function ListarMesas()
        {
          $query = $this->db->query("Select *
                                    from Mesa
                                    where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }
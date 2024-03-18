<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDua extends CI_Model {

        public $Dua = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->Dua = $this->Base->Construir("Dua");
        }


        function InsertarDua($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->Dua);
          $this->db->insert('Dua', $resultado);
          $resultado["IdDua"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarDua($data)
        {
          $id=$data["IdDua"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->Dua);
          $this->db->where('IdDua', $id);
          $this->db->update('Dua', $resultado);

          return $resultado;
        }

        function BorrarDua($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarDua($data);
        }

        function ObtenerDuaPorNumeroDua($data)
        {
          $numero=$data["NumeroDua"];
          $query = $this->db->query("Select * from Dua
                                     WHERE NumeroDua = '$numero'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDuaPorIdDua($data)
        {
          $iddocumento=$data["IdDua"];
          $query = $this->db->query("Select * from Dua
                                     WHERE IdDua = '$iddocumento'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }

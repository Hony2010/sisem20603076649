<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDocumentoSalidaZofra extends CI_Model {

        public $DocumentoSalidaZofra = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->DocumentoSalidaZofra = $this->Base->Construir("DocumentoSalidaZofra");
        }

        function ListarDocumentoZofraPorFiltro($data)
        {
          $FechaInicio = $data['FechaInicio'];
          $FechaFinal = $data['FechaFinal'];

          $query = $this->db->query("select * from documentosalidazofra");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarDocumentoSalidaZofra($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DocumentoSalidaZofra);
          $this->db->insert('DocumentoSalidaZofra', $resultado);
          $resultado["IdDocumentoSalidaZofra"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarDocumentoSalidaZofra($data)
        {
          $id=$data["IdDocumentoSalidaZofra"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DocumentoSalidaZofra);
          $this->db->where('IdDocumentoSalidaZofra', $id);
          $this->db->update('DocumentoSalidaZofra', $resultado);

          return $resultado;
        }

        function BorrarDocumentoSalidaZofra($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarDocumentoSalidaZofra($data);
        }

        function ObtenerDocumentoSalidaZofraPorNumeroDocumentoSalidaZofra($data)
        {
          $numero=$data["NumeroDocumentoSalidaZofra"];
          $query = $this->db->query("Select * from DocumentoSalidaZofra
                                     WHERE NumeroDocumentoSalidaZofra = '$numero'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDocumentoSalidaZofraPorIdDocumentoSalidaZofra($data)
        {
          $iddocumento=$data["IdDocumentoSalidaZofra"];
          $query = $this->db->query("Select * from DocumentoSalidaZofra
                                     WHERE IdDocumentoSalidaZofra = '$iddocumento'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }

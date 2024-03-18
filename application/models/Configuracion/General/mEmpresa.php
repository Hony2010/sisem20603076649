<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mEmpresa extends CI_Model {

        public $Empresa = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Empresa = $this->Base->Construir("Empresa");
        }

        function ListarEmpresas($data) {
          $id = $data["IdEmpresa"];
          $query = $this->db->query("
          select e.*,pai.CodigoPaisA3,pai.CodigoPaisA2, pai.NombrePais, dis.NombreDistrito
          from empresa as e
          inner join pais pai on pai.IdPais = e.IdPais
          inner join distrito dis on dis.IdDistrito = e.IdDistrito
          where e.IdEmpresa = '$id'");

          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarEmpresa($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          // $ignore = array("ClavePrivadaCertificado"=>"ClavePrivadaCertificado");
          $resultado = $this->mapper->map_real($data,$this->Empresa);
          $this->db->insert('Empresa', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarEmpresa($data)
        {
          $id=$data["IdEmpresa"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          // $ignore = array("ClavePrivadaCertificado"=>"ClavePrivadaCertificado");
          $resultado = $this->mapper->map_real($data,$this->Empresa);
          $this->db->where('IdEmpresa', $id);
          $this->db->update('Empresa', $resultado);
        }
 }

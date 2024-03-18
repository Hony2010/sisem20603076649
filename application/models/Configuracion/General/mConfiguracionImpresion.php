<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mConfiguracionImpresion extends CI_Model {

        public $ConfiguracionImpresion = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->ConfiguracionImpresion = $this->Base->Construir("ConfiguracionImpresion");
        }

        function ListarConfiguracionImpresion()
        {
          $query = $this->db->query("Select CI.*, TD.NombreTipoDocumento
                                    from ConfiguracionImpresion As CI
                                    Inner Join TipoDocumento As TD on CI.IdTipoDocumento = TD.IdTipoDocumento
                                    Where CI.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ActualizarConfiguracionImpresion($data)
        {
          $id=$data["IdConfiguracionImpresion"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->ConfiguracionImpresion);
          $this->db->where('IdConfiguracionImpresion', $id);
          $this->db->update('ConfiguracionImpresion', $resultado);
        }
 }

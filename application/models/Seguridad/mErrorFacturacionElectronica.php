<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mErrorFacturacionElectronica extends CI_Model {

       public function __construct()
       {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
       }


       public function ObtenerDescripcionErrorCodigo($data)
       {
              $codigo= $data["CodigoErrorFacturacionElectronica"];
              $query = $this->db->query("Select *
                     From ErrorFacturacionElectronica
                     Where CodigoErrorFacturacionElectronica = '$codigo' ");
              $resultado = $query->result_array();
              return $resultado;
       }

       public function ObtenerListadoCodigoErrores()
       {
              $query = $this->db->query("Select *
                     From ErrorFacturacionElectronica
                     Where IndicadorEstado = 'A' ");
              $resultado = $query->result_array();
              return $resultado;
       }

 }

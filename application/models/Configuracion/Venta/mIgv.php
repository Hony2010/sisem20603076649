<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mIgv extends CI_Model {

        public $Igv = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Igv = $this->Base->Construir("ParametroSistema");
        }

        function ListarTiposTarjeta()
        {
          $this->db->select("*")
          ->from('Igv')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdIgv');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarIgv($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->Igv);
          $this->db->insert('Igv', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarIgv($valor)
        {
          $id=ID_TASA_IGV;
          $data['ValorParametroSistema'] = ($valor/100) ;
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->Igv);
          $this->db->where('IdParametroSistema', $id);
          $this->db->update('ParametroSistema', $resultado);
          return $resultado;
        }

        function BorrarIgv($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarIgv($data);
        }

        function ConsultarIgvEnComprobanteVenta($data)
        {
          $id=$data["IdIgv"];
          $this->db->select("ComprobanteVenta.*")
          ->from('ComprobanteVenta')
          ->where("IndicadorEstado='A' AND IdIgv = '$id'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
 }

<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMoneda extends CI_Model {

        public $Moneda = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Moneda = $this->Base->Construir("Moneda");
        }

        function ListarMonedas()
        {
          $this->db->select("Moneda.*")
          ->from('Moneda')
          ->where("IndicadorEstado = 'A'")
          ->order_by('IdMoneda');
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function InsertarMoneda($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->Moneda);
          $this->db->insert('Moneda', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarMoneda($data)
        {
          $id=$data["IdMoneda"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->Moneda);
          $this->db->where('IdMoneda', $id);
          $this->db->update('Moneda', $resultado);
        }

        function BorrarMoneda($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarMoneda($data);
        }

        function ConsultarMonedaEnMercaderia($data)
        {
          $id=$data["IdMoneda"];
          $this->db->select("M.*")
          ->from('Mercaderia As M')
          ->join('Producto as P','M.IdProducto = P.IdProducto')
          ->where("M.IdMoneda = '$id' AND P.IndicadorEstado = 'A'");
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }

        function ObtenerDuplicadoDeCodigoMonedaParaInsertar($data)
        {
          $codigo=$data["CodigoMoneda"];
          $query = $this->db->query("Select *
                                     From Moneda
                                     Where CodigoMoneda = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDuplicadoDeCodigoMonedaParaActualizar($data)
        {
          $id=$data["IdMoneda"];
          $codigo=$data["CodigoMoneda"];
          $query = $this->db->query("Select *
                                     From Moneda
                                     Where (IdMoneda > '$id' Or IdMoneda < '$id' ) and CodigoMoneda = '$codigo' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMonedaPorId($data)
        {
          $id=$data["IdMoneda"];
          $query = $this->db->query("Select *
                                     From Moneda
                                     Where IdMoneda = '$id' and (IndicadorEstado = 'A' OR IndicadorEstado = 'T')");
          $resultado = $query->result_array();
          return $resultado;
        }

 }

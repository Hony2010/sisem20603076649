<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTipoCambio extends CI_Model {

        public $TipoCambio = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->TipoCambio = $this->Base->Construir("TipoCambio");
        }

        function ListarTiposCambio($inicio, $ValorParametroSistema)
        {
          $query = $this->db->query("Select *
                                    From TipoCambio
                                    Where IndicadorEstado ='A'
                                    ORDER  BY (FechaCambio) DESC
                                    LIMIT $inicio,$ValorParametroSistema");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarTipoCambio($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->TipoCambio);
          $this->db->insert('TipoCambio', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarTipoCambio($data)
        {
          $id=$data["IdTipoCambio"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->TipoCambio);
          $this->db->where('IdTipoCambio', $id);
          $this->db->update('TipoCambio', $resultado);
        }

        function BorrarTipoCambio($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarTipoCambio($data);
        }

        function ConsultarTiposCambio($inicio,$ValorParametroSistema,$data)
        {
          $criterio=$data["textofiltro"];
          $query = $this->db->query("Select *
                                     From TipoCambio
                                     Where IndicadorEstado='A' AND FechaCambio like '%$criterio%'
                                     ORDER  BY (FechaCambio) DESC
                                     LIMIT $inicio,$ValorParametroSistema");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarRangoFechasTiposCambio($inicio,$ValorParametroSistema,$data)
        {
          $fechaFin = $data['FechaFin'];
          $fechaInicio = $data['FechaInicio'];
          $query = $this->db->query("Select *
                                    From TipoCambio
                                    Where (FechaCambio BETWEEN '$fechaInicio' and '$fechaFin') And IndicadorEstado = 'A'
                                    Order by FechaCambio DESC
                                    LIMIT $inicio,$ValorParametroSistema");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNumeroTotalTipoCambio($data)
        {
          $criterio=$data["textofiltro"];
          $query = $this->db->query("Select *
                                     From TipoCambio
                                     Where IndicadorEstado='A' AND FechaCambio like '%$criterio%'
                                     ORDER  BY (FechaCambio)DESC ");
          $resultado = $query->num_rows();
          return $resultado;
        }

        function ObtenerDuplicadoDeFechaCambioParaInsertar($data)
        {
          $fecha=$data["FechaCambio"];
          $query = $this->db->query("Select *
                                     From TipoCambio
                                     Where FechaCambio = '$fecha' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDuplicadoDeFechaCambioParaActualizar($data)
        {
          $id=$data["IdTipoCambio"];
          $fecha=$data["FechaCambio"];
          $query = $this->db->query("Select *
                                     From TipoCambio
                                     Where (IdTipoCambio > '$id' Or IdTipoCambio < '$id' ) and FechaCambio = '$fecha' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }


        function ObtenerTipoCambio($data)
        {
          $fecha = $data["FechaCambio"];
          $query = $this->db->query("Select *
                                     From TipoCambio
                                     where FechaCambio = '$fecha' and IndicadorEstado ='A'");
          $resultado = $query->row();
          return $resultado;
        }
 }

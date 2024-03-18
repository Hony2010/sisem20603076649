<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mTransferenciaAlmacen extends CI_Model {

        public $TransferenciaAlmacen = array();

        public function __construct() {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->TransferenciaAlmacen = $this->Base->Construir("TransferenciaAlmacen");
        }

        function ConsultarTransferenciasAlmacen($data,$numerofilainicio,$numerorfilasporpagina) {
          $criterio=$data["TextoFiltro"];
          $fechainicio =$data["FechaInicio"];
          $fechafin =$data["FechaFin"];

          $consulta = "select
          CONCAT(TP.CodigoTipoDocumento,' ',II.SerieTransferencia,' ',II.NumeroTransferencia) AS Documento,
          II.*
          from transferenciaalmacen II
          inner join tipodocumento TP
          on TP.IdTipoDocumento= II.IdTipoDocumento
          where (II.FechaTraslado between '$fechainicio' and '$fechafin')
          AND (II.NombreSedeOrigen like '%$criterio%' OR
              II.NombreSedeDestino like '%$criterio%' OR
              II.SerieTransferencia like '%$criterio%' OR
              II.NumeroTransferencia like '%$criterio%')
          AND (II.IndicadorEstado IN ('A','N'))
          LIMIT $numerofilainicio,$numerorfilasporpagina";

          $query = $this->db->query($consulta);
          $resultado = $query->result_array();

          return $resultado;
        }
      

        function ObtenerNumeroTotalTransferenciaAlmacen($data) {
          $criterio=$data["TextoFiltro"];
          $fechainicio=$data["FechaInicio"];
          $fechafin=$data["FechaFin"];      

          $query = $this->db->query("Select II.*
                                    FROM TransferenciaAlmacen II
                                    WHERE (II.FechaTraslado BETWEEN '$fechainicio'  AND '$fechafin')
                                    AND (II.NombreSedeOrigen like '%$criterio%' OR
                                     II.NombreSedeDestino like '%$criterio%' OR
                                     II.SerieTransferencia like '%$criterio%' OR
                                     II.NumeroTransferencia like '%$criterio%') 
                                    AND II.IndicadorEstado IN ('A','N')");
          $resultado = $query->num_rows();
          return $resultado;
        }

            
        function InsertarTransferenciaAlmacen($data) {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->TransferenciaAlmacen);
          $this->db->insert('TransferenciaAlmacen', $resultado);
          $data["IdTransferenciaAlmacen"] = $this->db->insert_id();

          return $data;
        }

        function ActualizarTransferenciaAlmacen($data) {
          $id = $data["IdTransferenciaAlmacen"];
          $resultado = $this->mapper->map($data,$this->TransferenciaAlmacen);
          $this->db->where('IdTransferenciaAlmacen', $id);
          $this->db->update('TransferenciaAlmacen', $resultado);

          return $resultado;
        }

 }

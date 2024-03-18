<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAsignacionCuotaMensual extends CI_Model {

        public $AsignacionCuotaMensual = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->AsignacionCuotaMensual = $this->Base->Construir("AsignacionCuotaMensual");
        }

        function ConsultarAsignacionesCuotaMensual($data)
        {
          $criterio=$data["TextoFiltro"];

          $consulta = "Select  ACM.*, U.*, P.* 
                        FROM Usuario As U
                        LEFT JOIN Persona As P on P.IdPersona = U.IdPersona
                        LEFT JOIN AsignacionCuotaMensual As ACM on U.IdUsuario = ACM.IdUsuario
                        Where U.IndicadorEstado = 'A' and U.NumeroSerie != 0 and
                        (U.NumeroSerie like '%$criterio%' or
                        P.RazonSocial like '%$criterio%' or
                        P.NumeroDocumentoIdentidad like '%$criterio%')
                        ORDER BY U.NumeroSerie";

          $query = $this->db->query($consulta);
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerAsignacionCuotaMensualPorIdUsuario($data)
        {
          $id=$data["IdUsuario"];
          $query = $this->db->query("Select * from AsignacionCuotaMensual
                                    where IdUsuario = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarAsignacionCuotaMensual($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          // $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->AsignacionCuotaMensual);
          $this->db->insert('AsignacionCuotaMensual', $resultado);
          $resultado["IdAsignacionCuotaMensual"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarAsignacionCuotaMensual($data)
        {
          $id=$data["IdAsignacionCuotaMensual"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->AsignacionCuotaMensual);
          $this->db->where('IdAsignacionCuotaMensual', $id);
          $this->db->update('AsignacionCuotaMensual', $resultado);

          return $resultado;
        }

        function BorrarAsignacionCuotaMensual($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarAsignacionCuotaMensual($data);
        }

        function TotalComprobantesPorUsuario($data){
          $fechainicio =$data["FechaInicio"];
          $fechafin =$data["FechaFin"];
          $idusuario = $data["IdUsuario"];

          $consulta = "Select SUM(CV.Total) as Total FROM comprobanteventa CV
                        WHERE CV.FechaEmision BETWEEN '$fechainicio' AND '$fechafin' 
                        AND CV.IdUsuario = '$idusuario'
                        AND  (CV.IndicadorEstado = 'A' or CV.IndicadorEstado='N')";

          $query = $this->db->query($consulta);
          $resultado = $query->result_array();
          return $resultado;
        }

        function TotalComprobantesPorUsuarioYBoleta($data){
          $fechainicio =$data["FechaInicio"];
          $fechafin =$data["FechaFin"];
          $idusuario = $data["IdUsuario"];
          $documento = ID_TIPO_DOCUMENTO_BOLETA;

          $consulta = "Select SUM(CV.Total) as Total, max(CV.NumeroDocumento) as MaximoNumeroDocumento, MIN(CV.NumeroDocumento) as MinimoNumeroDocumento FROM comprobanteventa CV
                        WHERE CV.FechaEmision BETWEEN '$fechainicio' AND '$fechafin' 
                        AND CV.IdUsuario = '$idusuario'
                        AND  (CV.IndicadorEstado = 'A' or CV.IndicadorEstado='N')
                        AND CV.IdTipoDocumento = '$documento'";

          $query = $this->db->query($consulta);
          $resultado = $query->result_array();
          return $resultado;
        }

        function TotalComprobantesPorUsuarioYFactura($data){
          $fechainicio =$data["FechaInicio"];
          $fechafin =$data["FechaFin"];
          $idusuario = $data["IdUsuario"];
          $documento = ID_TIPO_DOCUMENTO_FACTURA;
          
          $consulta = "Select SUM(CV.Total) as Total,  max(CV.NumeroDocumento) as MaximoNumeroDocumento, MIN(CV.NumeroDocumento) as MinimoNumeroDocumento FROM comprobanteventa CV
                        WHERE CV.FechaEmision BETWEEN '$fechainicio' AND '$fechafin' 
                        AND CV.IdUsuario = '$idusuario'
                        AND  (CV.IndicadorEstado = 'A' or CV.IndicadorEstado='N')
                        AND CV.IdTipoDocumento = '$documento'";

          $query = $this->db->query($consulta);
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerUltimoNumeroDocumentoBoleta($data){
          $idusuario = $data["IdUsuario"];
          $documento = ID_TIPO_DOCUMENTO_BOLETA;

          $consulta = "Select SUM(CV.Total) as Total, max(CV.NumeroDocumento) as MaximoNumeroDocumento FROM comprobanteventa CV
                        WHERE CV.IdUsuario = '$idusuario'
                        AND  (CV.IndicadorEstado = 'A' or CV.IndicadorEstado='N')
                        AND CV.IdTipoDocumento = '$documento'";

          $query = $this->db->query($consulta);
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerUltimoNumeroDocumentoFactura($data){
          $idusuario = $data["IdUsuario"];
          $documento = ID_TIPO_DOCUMENTO_FACTURA;

          $consulta = "Select SUM(CV.Total) as Total, max(CV.NumeroDocumento) as MaximoNumeroDocumento FROM comprobanteventa CV
                        WHERE CV.IdUsuario = '$idusuario'
                        AND  (CV.IndicadorEstado = 'A' or CV.IndicadorEstado='N')
                        AND CV.IdTipoDocumento = '$documento'";

          $query = $this->db->query($consulta);
          $resultado = $query->result_array();
          return $resultado;
        }

        
      }
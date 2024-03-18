<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sVerificacionCorrelatividad extends MY_Service {

        public $VerificacionCorrelatividad = array();

        public function __construct()
        {
          parent::__construct();
          $this->load->database();
          $this->load->library('shared');
          $this->load->library('sesionusuario');
          $this->load->library('mapper');
          $this->load->library('herencia');
          $this->load->library('reporter');
          $this->load->library('imprimir');
          $this->load->helper("date");
          $this->load->model("Base");
          $this->load->model('Reporte/mReporteBaseEXCEL');
          $this->load->model('Reporte/mDescargarArchivo');
          $this->load->model('Venta/mVerificacionCorrelatividad');
          $this->load->service('Configuracion/General/sParametroSistema');
          $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
          $this->load->service('Configuracion/General/sConstanteSistema');
          $this->load->service('Seguridad/sAccesoUsuarioAlmacen');

          $this->VerificacionCorrelatividad = $this->mVerificacionCorrelatividad->VerificacionCorrelatividad;
        }

        function Cargar()
        {
          $VerificacionCorrelatividad["SelectorTodo"] = 0;
          $VerificacionCorrelatividad["DetallesVerificacionCorrelatividad"] = $this->ListarCorrelativosDocumento();

          return $VerificacionCorrelatividad;
        }

        function ListarCorrelativosDocumento()
        {
          $resultado = $this->mVerificacionCorrelatividad->ListarCorrelativosDocumento();

          foreach ($resultado as $key => $value) {
            $resultado[$key]['IndicadorEstadoCheck'] = false;
          }
          return $resultado;
        }

        function VerificarCorrelatividadTipo($data, $filtro)
        {
          $resultado = $this->mVerificacionCorrelatividad->ObtenerCorrelatividadComprobante($data, $filtro);
          $PrimeroNumero = count($resultado) == '0' ? "-" : reset($resultado)['NumeroDocumento'];
          $UltimoNumero = count($resultado) == '0' ? "-" : end($resultado)['NumeroDocumento'];
          $NumerosFaltantes = [];
          if(count($resultado) != 0){
            $i = $resultado[0]['NumeroDocumento'];
            foreach ($resultado as $key => $item) {
              while (intval($item['NumeroDocumento']) != intval($i)) {
                array_push($NumerosFaltantes, $i);
                $i = $i + 1;
              }
              $i = $i + 1;
            }
          }
          $envio['TipoDocumento']=$data['NombreAbreviado'];
          $envio['SerieDocumento']=$data['SerieDocumento'];
          $envio['NroInicial']=$PrimeroNumero;
          $envio['NroFinal']=$UltimoNumero;
          
          if(sizeof($NumerosFaltantes) != 0){
            $NumerosFaltantes = implode(", ", $NumerosFaltantes);
            $envio['NroDisponible']=$NumerosFaltantes;
          } else {
            $envio['NroDisponible']='No hay Correlativos Pendientes';
          }
          return $envio;
        }

        function InsertarVerificadorCorrelatividad($data)
        {
          try {
            $resultado = $this->mVerificacionCorrelatividad->InsertarCorrelatividadComprobanteVenta($data);
            return $resultado;
          }
          catch (Exception $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
          }
        }

        function obtener_primer_dia_mes()
        {
          $resultado = $this->shared->obtener_primer_dia_mes();
          return $resultado;
        }

        function obtener_ultimo_dia_mes()
        {
          $resultado = $this->shared->obtener_ultimo_dia_mes();
          return $resultado;
        }

        function GenerarReporteEXCEL($data)
        {
          $data["FechaInicio"] = convertirFechaES($data["FechaInicio"]);
          $data["FechaFin"] = convertirFechaES($data["FechaFin"]);
          $data["NombreArchivoReporte"] = "Verificacion_correlatividad";
          $data['NombreArchivoJasper'] = NOMBRE_REPORTE_VERIFICACION_CORRELATIVIDAD;
          $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
          return $resultado;
        }

        function BorrarCorrelatividadComprobanteVenta()
        {
          $this->mVerificacionCorrelatividad->BorrarCorrelatividadComprobanteVenta();
        }

        function DescargarArchivo($data)
        {
          $resultado = $this->mDescargarArchivo->DescargarArchivo($data);
          return $resultado;
        }
}
<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sGeneracionArchivoCFC extends MY_Service {

        public $GeneracionArchivoCFC = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->library('json');
              $this->load->helper('date');
              $this->load->service('FacturacionElectronica/sResumenComprobanteFisicoContingencia');
              $this->load->service('FacturacionElectronica/sCorrelativoResumenComprobanteFisicoContingencia');
        }

        function GenerarArchivoCFC($data)
        {
          $plantilla_json = $data["PlantillaJSON"];
          $data_mapeado = array();
          $data_convertido = array();

          foreach ($data["ComprobantesVenta"] as $key => $value) {
            $value = (array) $value;
            $data_json["plantilla"] = $plantilla_json;
            // $value["FechaEmision"] = convertToDate($value["FechaEmision"]);
            $data_json["data"] = $value;
            $parseado = $this->json->MapearJSONDesdePlantilla($data_json);
            // print_r($parseado);
            // exit;
            array_push($data_mapeado, $parseado);
            // $data_mapeado += $parseado;
          }

          foreach ($data_mapeado as $key => $value) {
              $linea = implode(DELIMITADOR_CONTINGENCIA, $value);
              array_push($data_convertido, $linea);
          }

          $texto = implode("\r\n", $data_convertido);
          //Creamos el JSON
      		$archivo = $data["RutaSalida"].$data["NombreArchivo"];

          //VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
      		/*if (!file_exists($archivo)) {
      			$nuevo_archivo = fopen($archivo, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido crear el archivo.");
      			}
      			fclose($nuevo_archivo);
      		}*/

          $file = fopen($archivo, "w+");
          // fwrite($file, $texto);
          // fflush($file);
          // ftruncate($file, 0);
          // file_put_contents($archivo, '');
          // print_r($data_convertido);
          // exit;
          foreach ($data_convertido as $key => $value) {
              $fila_write = trim($value.DELIMITADOR_CONTINGENCIA);
              fwrite($file, $fila_write.PHP_EOL);
          }
          fclose($file);

          return $texto;

          // print_r($data_mapeado);
          // exit;

          //JSON = CARGAR PLANTILLA JSON
          //DATA = MAPEAR(CFC, JSON)
          //$DATA = CONCATENAR(DATA, '|')
          //ARCHIVO, CREAR NUEVO Archivo
          //ARCHIVO AGREGAR DATA POR LINEA

        }

        function InsertarResumenComprobanteFisicoContingencia($data)
        {
          foreach ($data["DetallesCFC"] as $key => $value) {
            $this->sResumenComprobanteFisicoContingencia->InsertarResumenComprobanteFisicoContingencia($value);
          }

          $data_c["FechaComprobanteFisicoContingencia"] = $data["Fecha"];
          $this->sCorrelativoResumenComprobanteFisicoContingencia->IncrementarCorrelativoResumenComprobanteFisicoContingencia($data_c);
          return "";
        }

        function ObtenerNuevoCorrelativo($data)
        {
          $data_c["FechaComprobanteFisicoContingencia"] = $data["Fecha"];
          $numeroenvio = $this->sCorrelativoResumenComprobanteFisicoContingencia->ObtenerNuevoCorrelativoResumenComprobanteFisicoContingencia($data_c);
          return $numeroenvio;
        }

}

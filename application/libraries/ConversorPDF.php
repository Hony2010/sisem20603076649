<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("dompdf/autoload.inc.php");
use Dompdf\Dompdf;

class ConversorPDF {

  public $CI;

  function __construct()
  {
      //parent::__construct();
      /*Additional code which you want to run automatically in every function call */
      if (!isset($this->CI))
      {
          $this->CI =& get_instance();
      }
      //$this->CI->load->library('dompdf/lib/html5lib/parser');
      //$this->CI->load->library('dompdf/src/autoloader');
      //$this->CI->autoloader->register();
  }

  /*AQUI CREAMOS EL JSON PARA IMPRIMIR*/
  public function RenderizacionJSONObjets($data_json)
  {
    $plantilla = $data_json["plantilla"];
    $archivo = $data_json["imprimir"];
    $data = $data_json["data"];

		if (!file_exists($plantilla)) {
			$nuevo_archivo = fopen($plantilla, "w+");
			if($nuevo_archivo == false){
				die("No se ha podido Encontrar la plantilla.");
			}
			fclose($nuevo_archivo);
		}

		$datos_plantilla = file_get_contents($plantilla);
		$plantilla_contenido = json_decode($datos_plantilla, true);

		$array_JSON = array();
		$array_JSON["document-width"] = $plantilla_contenido["document-width"];
		$array_JSON["document-height"] = $plantilla_contenido["document-height"];
    $array_JSON["watermark"] = $plantilla_contenido["watermark"];

    $array_JSON["data"] = array();
    $i = 0;
		foreach ($plantilla_contenido["data"] as $parametro)
     {
        if($parametro["tipo"] == "0")
        {
          $parametro["id"] = $parametro["id"].$i;
        	array_push($array_JSON["data"], $parametro);
        }
        else if($parametro["tipo"] == "1")
        {
        	$cabecera = $parametro["name"];
  	    	$fila = $parametro;
  	    	$fila["value"] = $data[$cabecera];
          $fila["id"] = $parametro["id"].$i;
  	    	array_push($array_JSON["data"], $fila);
        	//$array_JSON += array($parametro["id"] => $fila); //SERIA DATA EN VEZ DE PARAMETRO
        }
        else if($parametro["tipo"] == "4")
        {
  	    	$fila = $parametro;
          $fila["id"] = $parametro["id"].$i;
  	    	array_push($array_JSON["data"], $fila);
        }
        $i++;
     }

    $i = 0;
    foreach ($data["detalle"] as $datos)
    {
      $fila = null;
      if($i != 0)
      {
      	$next_top = $linea + 5;
      }
      else{
      	$linea = 0;
      }
      //$array_JSON[$cabecera] += $array_JSON_datos[$cabecera];
      foreach ($plantilla_contenido["detalle"] as $estructura)
      {
      	$cabecera = $estructura["name"];
      	$fila = $estructura;
      	$fila["id"] = "d_".$estructura["id"].$i;
      	$fila["value"] = $datos[$cabecera];

      	if($i != 0)
        {
        	$fila["clase"]["top"] = (string)$next_top;
        	$linea = (int)$estructura["clase"]["height"]  +  (int)$next_top;
        }else{
        	$linea = (int) $estructura["clase"]["height"] + (int)$estructura["clase"]["top"];
        }

    	  array_push($array_JSON["data"], $fila);
      }
      //print_r($datos);
      $i++;
    }

    //VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
		if (!file_exists($archivo)) {
			$nuevo_archivo = fopen($archivo, "w+");
			if($nuevo_archivo == false){
				die("No se ha podido crear el archivo.");
			}
			fclose($nuevo_archivo);
		}

    //Creamos el JSON
		$json_string = json_encode($array_JSON);
		file_put_contents($archivo, $json_string);

    //return $json_string;
    return $array_JSON;
  }

  /*AQUI CREAMOS EL PDF A PARTIR DEL JSON*/
  public function CrearPDFJSON($data)
  {
    $dompdf = new Dompdf();

    $json = $data["json"];
    $archivo = $data["archivo"];

    if (!file_exists($json)) {
    	$nuevo_archivo = fopen($json, "w+");
    	if($nuevo_archivo == false){
    		die("No se ha podido Encontrar la json.");
    	}
    	fclose($nuevo_archivo);
    }

    $datos_json = file_get_contents($json);
    $data_json = json_decode($datos_json, true);

    $html_body = '<body>';
    /*$html_body .= '
    <div id="content_watermark">
      <span id="span_watermark">SISEM PERU</span>
    </div>
    ';*/
    foreach ($data_json["data"] as $parametro)
    {
      if($parametro["tipo"] != "4")
      {
    	   $html_body .= '<span id="'.$parametro["id"].'">'.$parametro["value"].'</span>';
      }
      else
      {
        $html_body .= '<img id="'.$parametro["id"].'" src="'.$parametro["clase"]["src"].'" />';
      }
    }
    $html_body .= '</body>';

    $watermark = "";
    if($data_json["watermark"] != "")
    {
      $watermark = "
      background-image: url('".$data_json["watermark"]."');
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
      ";
    }

    $html_css = '
    <style>
    @page {
    	size: '.$data_json["document-width"].'px '.$data_json["document-height"].'px;
    	margin: 0px !important;
       	padding: 0;
    }
    *{margin: 0px;}
    html {margin: 0px;}
    body {
      margin: 0px;
      font-family: "Times New Roman";
      position: relative;
      '.$watermark.'
    }
    span{
    	position: absolute;
    }
    img{
    	position: absolute;
    }';

    /*$html_css .= '
    #content_watermark {
      position: absolute;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #span_watermark {
      text-align: center;
      font-size: 30px;
      position: absolute;
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
    }
    ';*/
    foreach ($data_json["data"] as $parametro)
    {

      if($parametro["tipo"] != "4")
      {
        $decoration = "none";
        if($parametro["clase"]["underline"] != "")
        {
          $decoration = "underline";
        }
        else if($parametro["clase"]["linethrough"] != "")
        {
          $decoration = "line-through";
        }
        else if($parametro["clase"]["overline"] != "")
        {
          $decoration = "overline";
        }

        $html_css .= '
      		#'.$parametro["id"].'{
      			top: '.$parametro["clase"]["top"].'px;
      			left: '.$parametro["clase"]["left"].'px;
      			width: '.$parametro["clase"]["width"].'px;
      			height: '.$parametro["clase"]["height"].'px;
      			font-size: '.$parametro["clase"]["fontSize"].'px;
      			font-weight: '.$parametro["clase"]["fontWeight"].';
            text-decoration: '.$decoration.';
            color: '.$parametro["clase"]["fill"].';
            font-family: '.$parametro["clase"]["fontFamily"].';
            transform: rotate('.$parametro["clase"]["angle"].'deg);
      		}
      	';
      }
      else
      {
        $html_css .= '
      		#'.$parametro["id"].'{
      			top: '.$parametro["clase"]["top"].'px;
      			left: '.$parametro["clase"]["left"].'px;
      			width: '.$parametro["clase"]["width"].'px;
      			height: '.$parametro["clase"]["height"].'px;
            transform: rotate('.$parametro["clase"]["angle"].'deg);
      		}
      	';
      }

    }

    $html_css.= '</style>';

    $html = $html_body.$html_css;

    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation
    //$dompdf->setPaper('A5', 'landscape');
    /*PERSONALIZATE MEDIDAS */
    //$customPaper = array(0,0,360,360);
    //$dompdf->set_paper($customPaper);
    // Render the HTML as PDF
    $dompdf->render();
    // Output the generated PDF to Browser    
    $output = $dompdf->output();
    file_put_contents($archivo, $output);

    return $html;
  }


}

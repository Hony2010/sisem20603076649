<?php

// require_once(APPPATH.'libraries\ConsultaSUNAT\HTML5.php');
	namespace Sunat;
	use thiagoalessio\TesseractOCR\TesseractOCR;
	class Sunat{
		var $cc;
		var $_legal=false;
		var $_trabs=false;
		var $Captcha = "";
		function __construct( $representantes_legales=false, $cantidad_trabajadores=false )
		{
			$this->_legal = $representantes_legales;
			$this->_trabs = $cantidad_trabajadores;

			$this->cc = new \Sunat\cURL();
			$this->cc->setReferer( "http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/frameCriterioBusqueda.jsp" );
			$this->cc->useCookie( true );
			$this->cc->setCookiFileLocation( __DIR__ . "/cookie.txt" );
		}

		function getCaptcha()
		{
			$url = "http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=image";
			$captcha = $this->cc->send($url);
			return $captcha;
		}
		
		function getNumRand()
		{
			$url = "http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=random";
			$numRand = $this->cc->send($url);
			return $numRand;
		}

		function ObtenerTextoDeImagen()
		{
			$captcha = $this->getCaptcha();
			file_put_contents("captcha.png", $captcha);	

			$im = imagecreatefromjpeg('captcha.png');
			imagefilter($im, IMG_FILTER_GRAYSCALE);
			// imagefilter($im, IMG_FILTER_NEGATE);
			imagepng($im, 'captcha.png');
    		imagedestroy($im);
			
			$a = new TesseractOCR("captcha.png");	
			$a->tempDir(XAMPP_PATH.'tmp');		
			$numRand =$a->run(); //$this->getNumRand();
			return is_string($numRand) ? strtoupper(str_replace(" ", "", $numRand)) : "XTRB";
		}

		function getDataRUC( $ruc )
		{
			$numRand = $this->ObtenerTextoDeImagen();
			// print_r($numRand.PHP_EOL);
			$this->Captcha = $numRand;
			if(strlen($numRand) != 4)
			{
				while(strlen($numRand) != 4)
				{
					$numRand = $this->ObtenerTextoDeImagen();
					$this->Captcha = $this->Captcha."-".$numRand;
					if(strlen($numRand) == 4)
					{
						break;
					}
				}
			}


			if($ruc != "" && $numRand!=false)
			{
				$data = array(
					"nroRuc" => $ruc,
					"accion" => "consPorRuc",
					 //"numRnd" => $numRand
					 "codigo" =>$numRand
				);

				$url = "http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias";
				$Page = $this->cc->send( $url, $data );
				
				//RazonSocial
				$patron='/<input type="hidden" name="desRuc" value="(.*)">/';
				$output = preg_match_all($patron, $Page, $matches, PREG_SET_ORDER);

				if(count($matches) <= 0)
				{
					return false;
				}

				if(isset($matches[0]))
				{
					$RS = utf8_encode(str_replace('"','', ($matches[0][1])));
					$rtn = array("NumeroDocumentoIdentidad"=>$ruc,"RazonSocial"=>trim($RS));
				}

				//Telefono
				$patron='/<td class="bgn" colspan=1>Tel&eacute;fono\(s\):<\/td>[ ]*-->\r\n<!--\t[ ]*<td class="bg" colspan=1>(.*)<\/td>/';
				$output = preg_match_all($patron, $Page, $matches, PREG_SET_ORDER);
				if( isset($matches[0]) )
				{
					$rtn["Telefono"] = trim($matches[0][1]);
				}

				// Condicion Contribuyente
				$patron='/<td class="bgn"[ ]*colspan=1[ ]*>Condici&oacute;n del Contribuyente:[ ]*<\/td>\r\n[\t]*[ ]+<td class="bg" colspan=[1|3]+>[\r\n\t[ ]+]*(.*)[\r\n\t[ ]+]*<\/td>/';
				$output = preg_match_all($patron, $Page, $matches, PREG_SET_ORDER);
				if( isset($matches[0]) )
				{
					$rtn["CondicionContribuyente"] = strip_tags(trim($matches[0][1]));
				}

				if(array_key_exists("NumeroDocumentoIdentidad",$rtn) == false) {
					return false;
				}

				// Tipo Documento
				$numeroDocumento = $rtn["NumeroDocumentoIdentidad"];
				if (substr($numeroDocumento,0,2) == 20) {
					$rtn["TipoPersona"] = 1;
				}
				else {
					libxml_use_internal_errors(true);

					$dom = new \DOMDocument();
					$dom->strictErrorChecking = FALSE;
					$dom->loadHTML($Page);
					libxml_use_internal_errors(false);

					$xml = simplexml_import_dom($dom);
					foreach( $dom->getElementsByTagName('td') as $node)
					{
						$array[] = $node->nodeValue;
					}
					
					if (substr($numeroDocumento,0,2) == 17 || substr($numeroDocumento,0,2) == 15) {
						$result = explode(" - ",$array[1]);
						$datos_nombres = explode(" ", $result[1]);
						$Nombres="";
						for($i=2;$i < count($datos_nombres);$i++) {
							$Nombres=$Nombres." ".$datos_nombres[$i];
						}	
						$rtn["ApellidoCompleto"] = trim($datos_nombres[0]." ".$datos_nombres[1]);
						$rtn["NombreCompleto"] = trim($Nombres);
						$rtn["TipoPersona"] = 2;
					}
					else{
						$result = explode("-",$array[5]);
						$nombres = explode(",",$result[1]);
						$rtn["ApellidoCompleto"] = trim($nombres[0]);
						$rtn["NombreCompleto"] = trim($nombres[1]);
						$rtn["TipoPersona"] = 2;
					}					
				}
				//print_r($array);
				//exit;
				$busca=array(
					"NombreComercial" 		=> "Nombre Comercial",
					"Tipo" 					=> "Tipo Contribuyente",
					"Inscripcion" 			=> "Fecha de Inscripci&oacute;n",
					"EstadoContribuyente" 				=> "Estado del Contribuyente",
					"Direccion" 			=> "Direcci&oacute;n del Domicilio Fiscal",
					"SistemaEmision" 		=> "Sistema de Emisi&oacute;n de Comprobante",
					"ActividadExterior"		=> "Actividad de Comercio Exterior",
					"SistemaContabilidad" 	=> "Sistema de Contabilidad",
					"Oficio" 				=> "Profesi&oacute;n u Oficio",
					"ActividadEconomica" 	=> "Actividad\(es\) Econ&oacute;mica\(s\)",
					"EmisionElectronica" 	=> "Emisor electr&oacute;nico desde",
					"PLE" 					=> "Afiliado al PLE desde"
				);

				foreach($busca as $i=>$v)
				{
					$patron='/<td class="bgn"[ ]*colspan=1[ ]*>'.$v.':[ ]*<\/td>\r\n[\t]*[ ]+<td class="bg" colspan=[1|3]+>(.*)<\/td>/';
					$output = preg_match_all($patron, $Page, $matches, PREG_SET_ORDER);
				
					
					if(isset($matches[0]))
					{
						$rtn[$i] = trim(utf8_encode( preg_replace( "[\s+]"," ", ($matches[0][1]) ) ) );
					}
				}
			}
		
			if( count($rtn) > 2 )
			{
				$legal = array();
				if($this->_legal)
				{
					$legal = $this->RepresentanteLegal( $ruc );
				}
				if (count($legal) > 0) {
					$rtn["RepresentanteLegal"] = $legal[0]['nombre'];
				}
				else {
					$rtn["RepresentanteLegal"] = $legal;
				}

				// $trabs = array();
				// if($this->_trabs)
				// {
				// 	$trabs = $this->numTrabajadores( $ruc );
				// }
				// $rtn["cantidad_trabajadores"] = $trabs;

				return $rtn;
			}
			return false;
		}
		function numTrabajadores( $ruc )
		{
			$url = "http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias";
			$data = array(
				"accion" 	=> "getCantTrab",
				"nroRuc" 	=> $ruc,
				"desRuc" 	=> ""
			);
			$rtn = $this->cc->send( $url, $data );
			if( $rtn!="" && $this->cc->getHttpStatus()==200 )
			{
				$patron = "/<td align='center'>(.*)-(.*)<\/td>[\t|\s|\n]+<td align='center'>(.*)<\/td>[\t|\s|\n]+<td align='center'>(.*)<\/td>[\t|\s|\n]+<td align='center'>(.*)<\/td>/";
				$output = preg_match_all($patron, $rtn, $matches, PREG_SET_ORDER);
				if( count($matches) > 0 )
				{
					$cantidad_trabajadores = array();
					//foreach( array_reverse($matches) as $obj )
					foreach( $matches as $obj )
					{
						$cantidad_trabajadores[]=array(
							"periodo" 				=> $obj[1]."-".$obj[2],
							"anio" 					=> $obj[1],
							"mes" 					=> $obj[2],
							"total_trabajadores" 	=> $obj[3],
							"pensionista" 			=> $obj[4],
							"prestador_servicio" 	=> $obj[5]
						);
					}
					return $cantidad_trabajadores;
				}
			}
			return array();
		}
		function RepresentanteLegal( $ruc )
		{
			$url = "http://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias";
			$data = array(
				"accion" 	=> "getRepLeg",
				"nroRuc" 	=> $ruc,
				"desRuc" 	=> ""
			);
			$rtn = $this->cc->send( $url, $data );
			if( $rtn!="" && $this->cc->getHttpStatus()==200 )
			{
				$patron = '/<td class=bg align="left">[\t|\s|\n]+(.*)<\/td>[\t|\s|\n]+<td class=bg align="center">[\t|\s|\n]+(.*)<\/td>[\t|\s|\n]+<td class=bg align="left">[\t|\s|\n]+(.*)<\/td>[\t|\s|\n]+<td class=bg align="left">[\t|\s|\n]+(.*)<\/td>[\t|\s|\n]+<td class=bg align="left">[\t|\s|\n]+(.*)<\/td>/';
				$output = preg_match_all($patron, $rtn, $matches, PREG_SET_ORDER);
				if( count($matches) > 0 )
				{
					$representantes_legales = array();
					foreach( $matches as $obj )
					{
						$representantes_legales[]=array(
							"tipodoc" 				=> trim($obj[1]),
							"numdoc" 				=> trim($obj[2]),
							"nombre" 				=> utf8_encode(trim($obj[3])),
							"cargo" 				=> utf8_encode(trim($obj[4])),
							"desde" 				=> trim($obj[5]),
						);
					}
					return $representantes_legales;
				}
			}
			return array();
		}
		function dnitoruc($dni)
		{
			if ($dni!="" || strlen($dni) == 8)
			{
				$suma = 0;
				$hash = array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2);
				$suma = 5; // 10[NRO_DNI]X (1*5)+(0*4)
				for( $i=2; $i<10; $i++ )
				{
					$suma += ( $dni[$i-2] * $hash[$i] ); //3,2,7,6,5,4,3,2
				}
				$entero = (int)($suma/11);

				$digito = 11 - ( $suma - $entero*11);

				if ($digito == 10)
				{
					$digito = 0;
				}
				else if ($digito == 11)
				{
					$digito = 1;
				}
				return "10".$dni.$digito;
			}
			return false;
		}
		function valid($valor) // Script SUNAT
		{
			$valor = trim($valor);
			if ( $valor )
			{
				if ( strlen($valor) == 11 ) // RUC
				{
					$suma = 0;
					$x = 6;
					for ( $i=0; $i<strlen($valor)-1; $i++ )
					{
						if ( $i == 4 )
						{
							$x = 8;
						}
						$digito = $valor[$i];
						$x--;
						if ( $i==0 )
						{
							$suma += ($digito*$x);
						}
						else
						{
							$suma += ($digito*$x);
						}
					}
					$resto = $suma % 11;
					$resto = 11 - $resto;
					if ( $resto >= 10)
					{
						$resto = $resto - 10;
					}
					if ( $resto == $valor[strlen($valor)-1] )
					{
						return true;
					}
				}
			}
			return false;
		}
		function search( $ruc_dni, $inJSON = false )
		{
			if( strlen(trim($ruc_dni))==8 )
			{
				$ruc_dni = $this->dnitoruc($ruc_dni);
			}
			if( strlen($ruc_dni)==11 && $this->valid($ruc_dni) )
			{
				$info = $this->getDataRUC($ruc_dni);
				if( $info!=false )
				{
					$rtn = array(
						"success" 	=> true,
						"result" 	=> $info,
						"codigo"    => $this->Captcha
					);
				}
				else
				{
					$rtn = array(
						"success" 	=> false,
						"message" 	=> "No se puede acceder a la página de SUNAT.",
						"codigo"    => $this->Captcha

					);
				}
				return ($inJSON==true)?json_encode($rtn, JSON_PRETTY_PRINT):$rtn;
			}

			$rtn = array(
				"success" 	=> false,
				"message" 	=> "El número de documento ingresado no existe"
			);
			return ($inJSON==true)?json_encode($rtn, JSON_PRETTY_PRINT):$rtn;
		}
	}

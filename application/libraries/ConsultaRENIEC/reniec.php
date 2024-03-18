<?php
	namespace Reniec;
	class Reniec
	{
		var $cc;
		var $list_error = array();
		function __construct()
		{
			$this->cc = new cURL();
			$this->cc->setReferer("http://clientes.reniec.gob.pe/padronElectoral2012/padronPEMDistrito.htm");
		}

		/* getCode
		 *
		 * retorna codigo de verificacion de un DNI o CUI
		 *
		 * @param : string $dni 		CUI o numero de DNI
		 *
		 * @return: string 				Codigo de verificacion
		 * */
		function getCode( $dni )
		{
			if ($dni!="" || strlen($dni) == 8)
			{
				$suma = 0;
				$hash = array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2);
				$suma = 5;
				for( $i=2; $i<10; $i++ )
				{
					$suma += ( $dni[$i-2] * $hash[$i] );
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
				return $digito;
			}
			return "";
		}

		/* SearchReniec
		 *
		 * Realiza busqueda de datos enviado peticiones a la pagina de reniec
		 *
		 * @param : string $dni 		CUI o numero de DNI
		 *
		 * @return: array|false 		Array con datos encontrados en Reniec o false
		 * */
		function searchReniec( $dni )
		{
			$rtn=array();
			if( $dni != "" && strlen( $dni ) == 8 )
			{

				$data = array(
					"hTipo" 	=> "2",
					"hDni" 		=> $dni,
					"hApPat" 	=> "",
					"hApMat" 	=> "",
					"hNombre" 	=> ""
				);
				//$url = "http://clientes.reniec.gob.pe/padronElectoral2012/consulta.htm";				
				//$response = $this->cc->send($url, $data);

				if($response)
				{
					libxml_use_internal_errors(true);

					$doc = new \DOMDocument();
					$doc->strictErrorChecking = FALSE;
					$doc->loadHTML( $response );
					libxml_use_internal_errors(false);

					$xml = simplexml_import_dom($doc);
					$result = $xml->xpath("//table");
					if( isset($result[4]) )
					{
						$result = $result[4];
						$rtn = array(
							"DNI" 				=> trim( (string)$dni ),
							"CodVerificacion" 	=> $this->getCode( trim( (string)$dni ) ),
							"NombreCompleto" 			=> trim( explode(",",(string)$result->tr[0]->td[1])[1] ),
							"ApellidoCompleto" 		=> trim( explode(",",(string)$result->tr[0]->td[1])[0] ),
							"gvotacion" 		=> trim( (string)$result->tr[2]->td[1] ),
							"Distrito" 			=> trim( (string)$result->tr[3]->td[1] ),
							"Provincia" 		=> trim( (string)$result->tr[4]->td[1] ),
							"Departamento" 		=> trim( (string)$result->tr[5]->td[1] ),
							"Direccion"		=> trim((string)$result->tr[5]->td[1]).' - '.trim((string)$result->tr[4]->td[1]).' - '.trim((string)$result->tr[3]->td[1]),
							"RazonSocial"		=>	trim( explode(",",(string)$result->tr[0]->td[1])[0] ).' '.trim( explode(",",(string)$result->tr[0]->td[1])[1] ),
						);
						return $rtn;
					}
				}
			}
			return false;
		}

		function searchReniecCloud( $dni ) {
			
			$rtn = array();

			if( $dni != "" && strlen( $dni ) == 8 ) {
				$url  = "https://api.reniec.cloud/dni/".$dni;
				$json = $this->cc->send($url);
				$response = json_decode("[".$json."]",true);
				
				if($response) {
					$rtn = array(
						"DNI" 				=> $response[0]["dni"],
						"CodVerificacion" 	=> $response[0]["cui"],
						"NombreCompleto" 	=> $response[0]["nombres"],
						"ApellidoCompleto" 	=> $response[0]["apellido_paterno"]." ".$response[0]["apellido_materno"],
						"gvotacion" 		=> "",
						"Distrito" 			=> "",
						"Provincia" 		=> "",
						"Departamento" 		=> "",
						"Direccion"			=> "",
						"RazonSocial"		=>$response[0]["apellido_paterno"]." ".$response[0]["apellido_materno"]." ".$response[0]["nombres"]
					);

					return $rtn;
				}
			}
			
			return false;
		}

		function searchReniecOptimizePeru($dni) {
			$rtn = array();

			if( $dni != "" && strlen( $dni ) == 8 ) {
				$url  = "https://dni.optimizeperu.com/api/persons/".$dni;
				$json = $this->cc->send($url);
				$response = json_decode("[".$json."]",true);
				
				if($response) {
					$rtn = array(
						"DNI" 				=> $response[0]["dni"],
						"CodVerificacion" 	=> "",
						"NombreCompleto" 	=> $response[0]["name"],
						"ApellidoCompleto" 	=> $response[0]["first_name"]." ".$response[0]["last_name"],
						"gvotacion" 		=> "",
						"Distrito" 			=> "",
						"Provincia" 		=> "",
						"Departamento" 		=> "",
						"Direccion"			=> "",
						"RazonSocial"		=>$response[0]["first_name"]." ".$response[0]["last_name"]." ".$response[0]["name"]
					);

					return $rtn;
				}
			}
			
			return false;
			
		}

		function searchReniecAteneaPeru($dni) {
			//https://api.ateneaperu.com/Home/ConsultarDni
			$rtn = array();
			$data = array(
					"sNroDocumento" => $dni					
				);
				
			if( $dni != "" && strlen( $dni ) == 8 ) {
				$url  = "https://api.ateneaperu.com/Home/BuscarDni";
				$Page = $this->cc->send($url,$data);
				
				$patron="|<[^>]+>(.*) - (.*)</[^>]+>|U";				
				$output = preg_match_all($patron, $Page, $matches, PREG_SET_ORDER);
				
				if(count($matches) <= 0) {
					return false;
				}

				if(isset($matches[0])) {
					$datos_dni =explode(" ", $matches[0][2]);
					$Nombres="";
					for($i=2;$i<count($datos_dni);$i++) {
						$Nombres=$Nombres." ".$datos_dni[$i];
					}
					$rtn = array(
						"DNI" 				=> $matches[0][1],
						"CodVerificacion" 	=> "",
						"NombreCompleto" 	=> $Nombres,
						"ApellidoCompleto" 	=> $datos_dni[0]." ".$datos_dni[1],
						"gvotacion" 		=> "",
						"Distrito" 			=> "",
						"Provincia" 		=> "",
						"Departamento" 		=> "",
						"Direccion"			=> "",
						"RazonSocial"		=>$matches[0][2]
					);
				}
				
				return $rtn;
			}
			
			return false;
		}
		/* search
		 *
		 * @param : string $dni 		CUI o numero de DNI
		 * @param : booleam $inJSON 	Cambia a true para retornar un string json
		 *
		 * @return: object|string json 	Object o string JSON con datos encontrados
		 * */
		function search( $dni, $inJSON = false )
		{
			$dni = trim($dni);
			if( strlen( $dni )==8 && $dni!="" )
			{
				//$result =$this->searchReniecCloud($dni); //$this->searchReniec($dni);
				$result =$this->searchReniecAteneaPeru($dni); //$this->searchReniec($dni);
				
				if( $result!=false )
				{
					$rtn = (object)array(
						"success"	=> true,
						"result"	=> (object)$result
					);
					return ($inJSON==true)?json_encode($rtn,JSON_PRETTY_PRINT):$rtn;
				}
			}
			$rtn = (object)array(
				"success" 	=> false,
				"message" 	=> "El número de documento ingresado no existe o no se puede acceder a la página de ATENEA PERU.",
				"error" 	=> $this->list_error
			);
			return ($inJSON==true) ? json_encode($rtn,JSON_PRETTY_PRINT) : $rtn;
		}
		
	}
?>

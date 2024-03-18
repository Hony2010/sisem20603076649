<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Json {

        public function json_response($data)
        {
            //header('Content-Type: application/json');
            return json_encode($data);
        }

        public function json_response_error($data)
        {
            //header('Content-Type: application/json');
            if (is_string($data))
            {
              $data = new Exception($data);
              $error = array (
                'error' => array(
                  'msg' => $data->getMessage(),
                  'code' => $data->getCode()
                )
              );
              return json_encode($error);
            }
            else
            {
              $error = array (
                'error' => array(
                  'msg' => $data->getMessage(),
                  'code' => $data->getCode()
                )
              );
              return json_encode($error);
            }

        }

        public function ValidarExistenciaArchivo($url)
        {
          $crl = curl_init($url);
    			curl_setopt($crl, CURLOPT_NOBODY, true);
    			curl_exec($crl);

    			$ret = curl_getinfo($crl, CURLINFO_HTTP_CODE);
    			curl_close($crl);
    			if($ret == 200)
    			{
    				return true;
    			}
    			else {
    				return false;
    			}
        }

        public function InsertarNuevaFilaEnArchivoJSON($url_archivo, $nueva_fila)
      	{
      		$archivo = $url_archivo;
      		$fila = $nueva_fila;
      		//VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
      		if (file_exists($archivo)) {
      		    //echo "El fichero $archivo existe";
      		}
          else {
      			$nuevo_archivo = fopen($archivo, "w+");
      			if($nuevo_archivo == false) {
      				//die("No se ha podido crear el archivo.");
              return "No se ha podido crear el archivo json.";
      			}

      			fclose($nuevo_archivo);
      		  //echo "El fichero $archivo no existe";
      		}

      		$datos_clientes = file_get_contents($archivo);
      		$json_clientes = json_decode($datos_clientes, true);

          if ($json_clientes === null&& json_last_error() !== JSON_ERROR_NONE) {
            return "Error al recuperar los datos del catalogo (Decodificación de JSON Inválido).";
          }
          else if($json_clientes == null){
      			$json_clientes = Array();
      		}

      		if($nueva_fila != null){
      			array_push($json_clientes, $fila);
      		}

      		//Creamos el JSON
      		$json_string = json_encode($json_clientes);
          if ($json_string === false) {
            return "Error al actualizar los datos del catalogo (Codificación de JSON Inválido).";
          }

      		$resultado =file_put_contents($archivo, $json_string);

          if ($resultado === false) {
            return "Error al confirmar los datos del catalogo (Escritura a JSON Inválido).";
          }

          return $fila;
      	}

        public function ActualizarFilaEnArchivoJSON($url_archivo, $fila_a_cambiar, $id_attribute) {
      		$archivo = $url_archivo;
      		$fila = $fila_a_cambiar;
      		$id_atributo = $id_attribute;
      		//VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
      		if (!file_exists($archivo)) {
      			$nuevo_archivo = fopen($archivo, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido crear el archivo.");
      			}
      			fclose($nuevo_archivo);
      		}

      		$datos_clientes = file_get_contents($archivo);
      		$json_clientes = json_decode($datos_clientes, true);

          if ($json_clientes === null&& json_last_error() !== JSON_ERROR_NONE) {
            return "Error al recuperar los datos del catalogo (Decodificación de JSON Inválido).";
          }
      		else if($json_clientes == null) {
            // return "Error al recuperar los datos del catalogo (Decodificación de JSON Inválido).";
      			$json_clientes = Array();
      		}
      		else {

      			$posicion = null;
      			foreach($json_clientes as $key=>$value) {
      				if($json_clientes[$key][$id_atributo] == $fila[$id_atributo]) {
                $json_clientes[$key] = $fila;
      					//$posicion = $key;
      				}
      			}
      			//array_splice($json_clientes, $posicion, 1);
      			//unset($json_clientes[$posicion]);
      		}

      		//Creamos el JSON
      		$json_string = json_encode($json_clientes);
          if ($json_string === false) {
            return "Error al actualizar los datos del catalogo (Codificación de JSON Inválido).";
          }

      		$resultado =file_put_contents($archivo, $json_string);

          if ($resultado === false) {
            return "Error al confirmar los datos del catalogo (Escritura a JSON Inválido).";
          }

          return $fila;
      	}

        public function EliminarFilaEnArchivoJSON($url_archivo, $fila_a_eliminar, $id_attribute)
      	{
      		$archivo = $url_archivo;
      		$fila = $fila_a_eliminar;
      		$id_atributo = $id_attribute;
      		//VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
      		if (!file_exists($archivo)) {
      			$nuevo_archivo = fopen($archivo, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido crear el archivo.");
      			}
      			fclose($nuevo_archivo);
      		}

      		$datos_clientes = file_get_contents($archivo);
      		$json_clientes = json_decode($datos_clientes, true);

          if ($json_clientes === null&& json_last_error() !== JSON_ERROR_NONE) {
            return "Error al recuperar los datos del catalogo (Decodificación de JSON Inválido).";
          }
      		else if($json_clientes == null){
            return "El array se encuentra vacio.";//$json_clientes = Array();
      		}
      		else {
      			$posicion = null;
      			foreach($json_clientes as $key=>$value)
      			{
      				if($json_clientes[$key][$id_atributo] == $fila[$id_atributo])
      				{
      					$posicion = $key;
      				}
              // if($objeto[$key][$id_atributo] == $fila[$id_atributo])
      				// {
      				// 	$posicion = $key;
      				// }
            }
            
            if(is_numeric($posicion))
            {
              array_splice($json_clientes, $posicion, 1);
            }
      			//unset($json_clientes[$posicion]);
      		}

      		//Creamos el JSON
      		$json_string = json_encode($json_clientes);
          if ($json_string === false) {
            return "Error al actualizar los datos del catalogo (Codificación de JSON Inválido).";
          }

          $resultado = file_put_contents($archivo, $json_string);

          if ($resultado === false) {
            return "Error al confirmar los datos del catalogo (Escritura a JSON Inválido).";
          }

          return $fila;
      	}

        function CrearArchivoJSONData($url_archivo, $data)
        {
          $archivo = $url_archivo;
      		$data_archivo = $data;

      		//VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
      		if (!file_exists($archivo)) {
      			$nuevo_archivo = fopen($archivo, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido crear el archivo.");
      			}
      			fclose($nuevo_archivo);
      		}
          else {
            unlink($archivo);
            $nuevo_archivo = fopen($archivo, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido crear el archivo.");
      			}
      			fclose($nuevo_archivo);
            // code...
          }

      		//Creamos el JSON
      		$json_string = json_encode($data_archivo);
          if ($json_string === false) {
            return "Error al actualizar los datos del catalogo (Codificación de JSON Inválido).";
          }

          $resultado = file_put_contents($archivo, $json_string);

          if ($resultado === false) {
            return "Error al confirmar los datos del catalogo (Escritura a JSON Inválido).";
          }

          return $data_archivo;
        }

        public function ActualizarArchivoJSONValidado($url_archivo, $fila, $id_attribute)
        {

          $validar = $this->ValidarFilaArchivoJSONByAtributo($url_archivo, $fila, $id_attribute);
          if($validar == true)
          {
            $eliminar = $this->EliminarFilaEnArchivoJSON($url_archivo, $fila, $id_attribute);
            if(is_array($eliminar)){
              $insercion = $this->InsertarNuevaFilaEnArchivoJSON($url_archivo, $fila);
              return $insercion;
            }
          }
          else {
            $insercion =  $this->InsertarNuevaFilaEnArchivoJSON($url_archivo, $fila);
            return $insercion;
          }
        }

        public function ValidarFilaArchivoJSONByAtributo($url_archivo, $fila, $id_attribute)
      	{
      		$archivo = $url_archivo;
      		$id_atributo = $id_attribute;
      		//VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
      		if (!file_exists($archivo)) {
      			$nuevo_archivo = fopen($archivo, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido crear el archivo.");
      			}
      			fclose($nuevo_archivo);
      		}

      		$datos_clientes = file_get_contents($archivo);
      		$json_clientes = json_decode($datos_clientes, true);

      		if($json_clientes == null){
      			$json_clientes = Array();
            return false;
      		}
      		else{
      			$posicion = null;

      			foreach($json_clientes as $key=>$value)
      			{
      				if($value[$id_atributo] == $fila[$id_atributo])
      				{
      					return true;
                break;
      				}
              // if($objeto[$key][$id_atributo] == $fila[$id_atributo])
      				// {
      				// 	return true;
              //   break;
      				// }
      			}
      			return false;
      		}
      	}

        public function CrearArchivoJSONFactura($url_archivo, $data)
        {
          $archivo = $url_archivo;

          $array_JSON = Array();
          $array_JSON_detalle = Array();

          //CREANDO CABECERAS
          $array_JSON["CustomizationIdSwf"] = "1.0";
          $array_JSON["ublVersionIdSwf"] = "2.0";
          $array_JSON["nroCdpSwf"] = $data["Documento"]; //Juntar NumeroSerie + NumeroDocumento = F001-002
          $array_JSON["fechaEmision"] = $data["FechaEmision"];
          $array_JSON["moneda"] = $data["CodigoMoneda"];
          $array_JSON["nroRucEmisorSwf"] = $data["CodigoEmpresa"];
          $array_JSON["identificadorFacturadorSwf"] = "Elaborado por SISEM PERU 1.0.0";
          $array_JSON["codigoFacturadorSwf"] = "471156";
          $array_JSON["nombreComercialSwf"] = $data["NombreComercial"];
          $array_JSON["razonSocialSwf"] = $data["RazonSocialEmisor"]; //Cambiar datos en data de Empresa
          $array_JSON["identificadorFirmaSwf"] = "SIGN";
          $array_JSON["tipDocuEmisorSwf"] = "6";
          $array_JSON["ubigeoDomFiscalSwf"] = $data["Ubigeo"];
          $array_JSON["direccionDomFiscalSwf"] = $data["DomicilioFiscal"];
          $array_JSON["paisDomFiscalSwf"] = $data["CodigoPaisA3"]; //Create tabla pais y variable

          $array_JSON["nroDocumento"] = $data["NumeroDocumentoIdentidad"];
          $array_JSON["tipoDocumento"] = $data["CodigoDocumentoIdentidad"];
          $array_JSON["razonSocialUsuario"] = $data["RazonSocialCliente"]; //Cambiar datos en data de Cliente
          $array_JSON["direccionUsuario"] = $data["Direccion"];

          $array_JSON["sumaIgv"] = $data["IGV"];
          $array_JSON["idIgv"] = "1000";
          $array_JSON["codIgv"] = "IGV";
          $array_JSON["codExtIgv"] = "VAT";

          $array_JSON["sumaIsc"] = $data["ISC"];
          $array_JSON["idIsc"] = "2000";
          $array_JSON["codIsc"] = "ISC";
          $array_JSON["codExtIsc"] = "EXC";

          $array_JSON["sumaOtros"] = $data["OtroTributo"];
          $array_JSON["idOtr"] = "9999";
          $array_JSON["codOtr"] = "OTROS";
          $array_JSON["codExtOtr"] = "OTH";

          $array_JSON["descuentoGlobal"] = $data["DescuentoGlobal"];
          $array_JSON["sumaOtrosCargos"] = $data["OtroCargo"];
          $array_JSON["sumaImporteVenta"] = $data["Total"];
          $array_JSON["tipoCodigoMonedaSwf"] = $data["CodigoTipoPrecio"]; //

          $array_JSON["codigoMontoDescuentosSwf"] = "2005";
          $array_JSON["totalDescuento"] = $data["DescuentoTotalItem"];
          $array_JSON["codigoMontoOperGravadasSwf"] = "1001";
          $array_JSON["montoOperGravadas"] = $data["ValorVentaGravado"];
          $array_JSON["codigoMontoOperInafectasSwf"] = "1002";
          $array_JSON["montoOperInafectas"] = $data["ValorVentaInafecto"];
          $array_JSON["codigoMontoOperExoneradasSwf"] = "1003";
          $array_JSON["montoOperExoneradas"] = $data["ValorVentaNoGravado"];
          $array_JSON["tipCdpSwf"] = "01";
          //$array_JSON["tipoOperacion"] = $data["CodigoTipoOperacion"];

          $array_JSON["listaDetalle"] = Array();
          foreach ($data["DetallesComprobanteVenta"] as $parametro)
          {
            $array_JSON_detalle["unidadMedida"] = $parametro["CodigoUnidadMedida"];
            $array_JSON_detalle["cantItem"] = $parametro["Cantidad"];
            $array_JSON_detalle["codiProducto"] = $parametro["CodigoMercaderia"]; //Revisar Henry
            $array_JSON_detalle["codiSunat"] = $parametro["CodigoMercaderia"];
            $array_JSON_detalle["desItem"] = $parametro["NombreProducto"];
            $array_JSON_detalle["valorUnitario"] = $parametro["ValorUnitario"];
            $array_JSON_detalle["descuentoItem"] = $parametro["DescuentoItem"];
            $array_JSON_detalle["montoIgvItem"] = $parametro["IGVItem"];
            $array_JSON_detalle["afectaIgvItem"] = $parametro["CodigoTipoAfectacionIGV"]; //Revisar Henry
            $array_JSON_detalle["montoIscItem"] = $parametro["ISCItem"];
            $array_JSON_detalle["monto"] = "0";
            $array_JSON_detalle["tipoSistemaIsc"] = $parametro["CodigoTipoSistemaISC"]; //Revisar Henry
            $array_JSON_detalle["precioVentaUnitarioItem"] = $parametro["PrecioUnitario"];
            $array_JSON_detalle["valorVentaItem"] = $parametro["SubTotal"];
            $array_JSON_detalle["lineaSwf"] = $parametro["NumeroItem"];
            $array_JSON_detalle["tipoCodiMoneGratiSwf"] = $data["CodigoTipoPrecio"];

            array_push($array_JSON["listaDetalle"], $array_JSON_detalle);
          }

          $array_JSON["listaRelacionado"] = Array();
          $array_JSON["listaLeyendas"] = Array();

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

          return $json_string;
        }

        public function CrearArchivoJSONRA($url_archivo, $data)
        {
          $archivo = $url_archivo;

          $array_JSON = Array();
          $array_JSON_detalle = Array();
          //CREANDO CABECERAS
          $array_JSON["ublVersionIdSwf"] = "2.0"; //De la tabla Comunicacion de Baja
          $array_JSON["CustomizationIdSwf"] = "1.0";//De la tabla Comunicacion de Baja
          $array_JSON["idComunicacion"] = $data["CodigoComunicacion"];//De la tabla Comunicacion de Baja
          $array_JSON["fechaDocumentoBaja"] = $data["FechaEmisionDocumento"];//De la tabla Comunicacion de Baja
          $array_JSON["fechaComunicacioBaja"] = $data["FechaComunicacionBaja"];//De la tabla Comunicacion de Baja
          $array_JSON["nroRucEmisorSwf"] = $data["CodigoEmpresa"]; //De la tabla Empresa
          $array_JSON["identificadorFacturadorSwf"] = "Elaborado por SISEM PERU 1.0.0";
          $array_JSON["codigoFacturadorSwf"] = "471156";
          $array_JSON["nombreComercialSwf"] = $data["NombreComercial"];
          $array_JSON["razonSocialSwf"] = $data["RazonSocial"];
          $array_JSON["identificadorFirmaSwf"] = "SIGN";
          $array_JSON["tipDocuEmisorSwf"] = "6";

          $array_JSON["listaResumen"] = Array();
          foreach ($data["DetallesComunicacionBaja"] as $parametro)
          {
            $array_JSON_detalle["linea"] = $parametro["NumeroItem"];//Tabla Detalle Comunicacion Baja
            $array_JSON_detalle["tipoDocumentoBaja"] = $parametro["CodigoTipoDocumento"];//Tabla Detalle Comunicacion Baja
            $array_JSON_detalle["serieDocumentoBaja"] = $parametro["SerieDocumento"];//Tabla Detalle Comunicacion Baja
            $array_JSON_detalle["nroDocumentoBaja"] = $parametro["NumeroDocumento"];//Tabla Detalle Comunicacion Baja
            $array_JSON_detalle["motivoBajaDocumento"] = $parametro["MotivoBaja"];//Tabla Detalle Comunicacion Baja

            array_push($array_JSON["listaResumen"], $array_JSON_detalle);
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

          return $json_string;
        }

        public function CrearArchivoJSONRC($url_archivo, $data)
        {
          $archivo = $url_archivo;

          $array_JSON = Array();
          $array_JSON_detalle = Array();
          //CREANDO CABECERAS
          $array_JSON["ublVersionIdSwf"] = $data["VersionUbl"];
          $array_JSON["CustomizationIdSwf"] = $data["Personalizacion"];

          $array_JSON["idResumenDiario"] = $data["CodigoResumenDiario"];
          $array_JSON["fechaGeneracion"] = $data["FechaResumenDiario"];
          $array_JSON["fechaEmision"] = $data["FechaEmisionDocumento"];
          $array_JSON["nroRucEmisorSwf"] = $data["CodigoEmpresa"];
          $array_JSON["identificadorFacturadorSwf"] = "Elaborado por SISEM PERU 1.0.0";
          $array_JSON["codigoFacturadorSwf"] = $data["CodigoFacturador"];
          $array_JSON["nombreComercialSwf"] = $data["NombreComercial"];

          $array_JSON["URIidentificadorFirmaSwf"] = $data["URIIdentificadorFirma"];
          $array_JSON["razonSocialSwf"] = $data["RazonSocialEmisor"];
          $array_JSON["identificadorFirmaSwf"] = $data["IndicadorFirmaDigital"];
          $array_JSON["tipDocuEmisorSwf"] = $data["TipoDocumentoEmisor"];
          $array_JSON["moneda"] = $data["CodigoMoneda"];//--


          $array_JSON["listaResumen"] = Array();
          foreach ($data["DetalleComprobanteVenta"] as $parametro)
          {
            $array_JSON_detalle["linea"] = $parametro["NumeroItem"];
            $array_JSON_detalle["tipoDocumento"] = $parametro["TipoDocumento"];
            $array_JSON_detalle["serieNumeroDocumento"] = $parametro["SerieDocumento"];
            $array_JSON_detalle["montoTotal"] = $parametro["MontoTotal"];

            $array_JSON_detalle["numeroDocumentoCliente"] = $parametro["NumeroDocumentoCliente"];
            $array_JSON_detalle["codigoTipoIdentidadCliente"] = $parametro["CodigoTipoIdentidadCliente"];
            $array_JSON_detalle["serieNumeroDocumentoReferencia"] = $parametro["SerieNumeroDocumentoReferencia"];
            $array_JSON_detalle["tipoDocumentoReferencia"] = $parametro["TipoDocumentoReferencia"];
            $array_JSON_detalle["indicadorPercepcion"] = $parametro["IndicadorPercepcion"];
            $array_JSON_detalle["montoPercepcion"] = $parametro["MontoPercepcion"];
            $array_JSON_detalle["codigoPercepcion"] = $parametro["CodigoPercepcion"];
            $array_JSON_detalle["porcentajePercepcion"] = $parametro["PorcentajePercepcion"];
            $array_JSON_detalle["montoTotalCobrado"] = $parametro["MontoTotalCobrado"];
            $array_JSON_detalle["montoBaseImponiblePercepcion"] = $parametro["MontoBaseImponiblePercepcion"];
            $array_JSON_detalle["codigoEstado"] = $parametro["CodigoEstado"];

            $array_JSON_detalle["montoPagadoOpGravada"] = $parametro["MontoOperacionGravada"];
            $array_JSON_detalle["instructionIDOpGravada"] = $parametro["CodigoOperacionGravado"];
            $array_JSON_detalle["montoPagadoOpExonerada"] = $parametro["MontoOperacionExonerada"];
            $array_JSON_detalle["instructionIDOpExonerada"] = $parametro["CodigoOperacionExonerada"];
            $array_JSON_detalle["montoPagadoOpInafecto"] = $parametro["MontoOperacionInafecta"];
            $array_JSON_detalle["instructionIDOpInafecto"] = $parametro["CodigoOperacionInafecta"];
            $array_JSON_detalle["montoPagadoOpGratuita"] = $parametro["MontoOperacionGratuita"];
            $array_JSON_detalle["instructionIDOpGratuita"] = $parametro["CodigoOperacionGratuita"];

            $array_JSON_detalle["indicadorCargo"] = $parametro["IndicadorCargo"];
            $array_JSON_detalle["montoCargo"] = $parametro["MontoCargo"];

            $array_JSON_detalle["montoTasaIGV"] = $parametro["MontoTasaIGV"];
            $array_JSON_detalle["idTasaIGV"] = $parametro["IdTasaIGV"];
            $array_JSON_detalle["nombreTasaIGV"] = $parametro["NombreTasaIGV"];
            $array_JSON_detalle["codigoTasaIGV"] = $parametro["CodigoTasaIGV"];

            $array_JSON_detalle["montoTasaISC"] = $parametro["MontoTasaISC"];
            $array_JSON_detalle["idTasaISC"] = $parametro["IdTasaISC"];
            $array_JSON_detalle["nombreTasaISC"] = $parametro["NombreTasaISC"];
            $array_JSON_detalle["codigoTasaISC"] = $parametro["CodigoTasaISC"];

            $array_JSON_detalle["montoTasaOTROS"] = $parametro["MontoTasaOtro"];
            $array_JSON_detalle["idTasaOTROS"] = $parametro["IdTasaOtro"];
            $array_JSON_detalle["nombreTasaOTROS"] = $parametro["NombreTasaOtro"];
            $array_JSON_detalle["codigoTasaOTROS"] = $parametro["CodigoTasaOtro"];

            array_push($array_JSON["listaResumen"], $array_JSON_detalle);
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

          return $json_string;
        }

        public function CrearArchivoJSONDesdePlantilla($data)
        {
          $archivo = $data["ruta"];
          $plantilla = $data["plantilla"];
          $data = $data["data"];

          $array_JSON = Array();
          $array_JSON_detalles = Array();
          $array_JSON_datos = Array();
          //CREANDO CABECERAS
      		//VALIDANDO Y ABRIENDO PLANTILLA
      		if (!file_exists($plantilla)) {
      			$nuevo_archivo = fopen($plantilla, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido Encontrar la plantilla.");
      			}
      			fclose($nuevo_archivo);
      		}

          $datos_plantilla = file_get_contents($plantilla);
      		$plantilla_contenido = json_decode($datos_plantilla, true);

          foreach($plantilla_contenido as $parametro)
          {

            if($parametro["tipo"] == 0)
            {
              $array_JSON += array($parametro["id"] => $parametro["value"]);
            }
            else if($parametro["tipo"] == 1)
            {
              $nombre = $parametro["value"];
              $array_JSON += array($parametro["id"] => $data[$nombre]); //SERIA DATA EN VEZ DE PARAMETRO
            }
            else
            {
              if($parametro["value"] != "")
              {
                //Creando data para
                $array_JSON += array($parametro["id"] => Array());

                $cabecera = array();
                $cabecera["id"] = $parametro["id"];
                $cabecera["value"] = $parametro["value"];
                array_push($array_JSON_detalles, $cabecera);

                $array_JSON_datos += array($parametro["value"] => $parametro["datos"]);
              }
              else {
                $array_JSON += array($parametro["id"] => Array());
              }
            }

            //array_push($array_JSON, $parametro->value);
          }

          foreach ($array_JSON_detalles as $cabecera)
          {
            $json = $cabecera["id"];
            $indice = $cabecera["value"];
            
          
            if( array_key_exists($indice,$data)) {
              foreach ($data[$indice] as $datos) {               
                $fila = array();
                //$array_JSON[$cabecera] += $array_JSON_datos[$cabecera];
                foreach ($array_JSON_datos[$indice] as $estructura) {
                  if($estructura["tipo"] == 0) {
                    $fila += array($estructura["id"] => $estructura["value"]);
                  }
                  else if($estructura["tipo"] == 1) {
                    $nombre = $estructura["value"];
                    $fila += array($estructura["id"] => $datos[$nombre]);
                  }
                  else {
                    $nombre = $estructura["value"];
                    $fila += array($estructura["id"] => $data[$nombre]);
                  }
                }
                array_push($array_JSON[$json], $fila);
              }
            }
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

          return $json_string;
        }

        public function MapearJSONDesdePlantilla($data)
        {
          $plantilla = $data["plantilla"];
          $data = $data["data"];

          $array_JSON = Array();
          $array_JSON_detalles = Array();
          $array_JSON_datos = Array();

          //CREANDO CABECERAS
      		//VALIDANDO Y ABRIENDO PLANTILLA
      		if (!file_exists($plantilla)) {
      			$nuevo_archivo = fopen($plantilla, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido Encontrar la plantilla.");
      			}
      			fclose($nuevo_archivo);
      		}

      		$datos_plantilla = file_get_contents($plantilla);
      		$plantilla_contenido = json_decode($datos_plantilla, true);

          foreach($plantilla_contenido as $parametro)
          {

            if($parametro["tipo"] == 0)
            {
              $array_JSON += array($parametro["id"] => $parametro["value"]);
            }
            else if($parametro["tipo"] == 1)
            {
              $nombre = $parametro["value"];
              $array_JSON += array($parametro["id"] => $data[$nombre]); //SERIA DATA EN VEZ DE PARAMETRO
            }
            else
            {
              if($parametro["value"] != "")
              {
                //Creando data para
                $array_JSON += array($parametro["id"] => Array());

                $cabecera = array();
                $cabecera["id"] = $parametro["id"];
                $cabecera["value"] = $parametro["value"];
                array_push($array_JSON_detalles, $cabecera);

                $array_JSON_datos += array($parametro["value"] => $parametro["datos"]);
              }
              else {
                $array_JSON += array($parametro["id"] => Array());
              }
            }

            //array_push($array_JSON, $parametro->value);
          }

          foreach ($array_JSON_detalles as $cabecera)
          {
            $json = $cabecera["id"];
            $indice = $cabecera["value"];

            foreach ($data[$indice] as $datos)
            {
              $fila = array();
              //$array_JSON[$cabecera] += $array_JSON_datos[$cabecera];
              foreach ($array_JSON_datos[$indice] as $estructura)
              {
                if($estructura["tipo"] == 0)
                {
                  $fila += array($estructura["id"] => $estructura["value"]);
                }
                else if($estructura["tipo"] == 1){
                  $nombre = $estructura["value"];
                  $fila += array($estructura["id"] => $datos[$nombre]);
                }
                else
                {
                  $nombre = $estructura["value"];
                  $fila += array($estructura["id"] => $data[$nombre]);
                }

              }
              array_push($array_JSON[$json], $fila);
            }
          }

          //Creamos el JSON
      		// $json_string = json_encode($array_JSON);

          return $array_JSON;
        }

        public function CrearJSONPlantillaDetalle($data)
        {
          $archivo = $data["ruta"];
          $plantilla = $data["plantilla"];
          $data = $data["data"];

          //CREANDO CABECERAS
      		//VALIDANDO Y ABRIENDO PLANTILLA
      		if (!file_exists($plantilla)) {
      			$nuevo_archivo = fopen($plantilla, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido Encontrar la plantilla.");
      			}
      			fclose($nuevo_archivo);
      		}

      		$datos_plantilla = file_get_contents($plantilla);
      		$plantilla_contenido = json_decode($datos_plantilla, true);

          $resultado = $this->MapearJSONDetalle($plantilla_contenido, $data);

          //VALIDANDO Y CREANDO EL ARCHIVO POR SI NO EXISTE
      		if (!file_exists($archivo)) {
      			$nuevo_archivo = fopen($archivo, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido crear el archivo.");
      			}
      			fclose($nuevo_archivo);
      		}

          //Creamos el JSON
      		$json_string = json_encode($resultado);
      		file_put_contents($archivo, $json_string);

          return $json_string;
        }

        public function MapearJSONDetalle($plantilla, $data)
        {
          $plantilla = $plantilla;
          $data = $data;

          $array_JSON = Array();
          $array_JSON_detalles = Array();
          $array_JSON_datos = Array();

      		$plantilla_contenido = $plantilla;

          foreach($plantilla_contenido as $parametro)
          {
            if($parametro["tipo"] == 0)
            {
              $array_JSON += array($parametro["id"] => $parametro["value"]);
            }
            else if($parametro["tipo"] == 1)
            {
              $nombre = $parametro["value"];
              $array_JSON += array($parametro["id"] => $data[$nombre]); //SERIA DATA EN VEZ DE PARAMETRO
            }
            else if($parametro["tipo"] == 2)
            {
              if($parametro["value"] != "")
              {
                $id = $parametro["id"];
                //Creando data para objetos
                // $array_detalle = new stdClass();
                $value = $parametro["value"];
                $response = $this->MapearJSONDetalle($parametro["datos"], $data[$value]);
                // foreach ($data[$value] as $key => $value2) {
                //   array_push($array_detalle, $response);
                // }

                $array_JSON += array($id => $response);
              }
              else {
                $array_JSON += array($parametro["id"] => new stdClass());
              }
            }
            else
            {
              if($parametro["value"] != "")
              {
                $id = $parametro["id"];
                //Creando data para
                $array_detalle = array();
                $value = $parametro["value"];
                foreach ($data[$value] as $key => $value2) {
                  $response = $this->MapearJSONDetalle($parametro["datos"], $value2);
                  array_push($array_detalle, $response);
                }

                $array_JSON += array($id => $array_detalle);
              }
              else {
                $array_JSON += array($parametro["id"] => Array());
              }
            }

          }

          return $array_JSON;
        }

        public function CrearArchivoJSONNotaCredito($url_archivo, $data)
        {
          $archivo = $url_archivo;

          $array_JSON = Array();
          $array_JSON_detalle = Array();
          //CREANDO CABECERAS
          $array_JSON["ublVersionIdSwf"] = "2.0";
          $array_JSON["CustomizationIdSwf"] = "1.0";
          $array_JSON["idComunicacion"] = $data["CodigoComunicacion"];
          $array_JSON["fechaDocumentoBaja"] = $data["FechaEmisionDocumento"];
          $array_JSON["fechaComunicacioBaja"] = $data["FechaComunicacioBaja"];
          $array_JSON["nroRucEmisorSwf"] = $data["CodigoEmpresa"];
          $array_JSON["identificadorFacturadorSwf"] = "Elaborado por SISEM PERU 1.0.0";
          $array_JSON["codigoFacturadorSwf"] = "0";
          $array_JSON["nombreComercialSwf"] = $data["NombreComercial"];
          $array_JSON["razonSocialSwf"] = $data["RazonSocialEmisor"];
          $array_JSON["identificadorFirmaSwf"] = "SIGN";
          $array_JSON["tipDocuEmisorSwf"] = "6";

          $array_JSON["listaResumen"] = Array();
          foreach ($data["DetalleComprobanteVenta"] as $parametro)
          {
            $array_JSON_detalle["linea"] = $parametro["NumeroItem"];
            $array_JSON_detalle["tipoDocumentoBaja"] = $parametro["CodigoTipoDocumento"];
            $array_JSON_detalle["serieDocumentoBaja"] = $parametro["SerieDocumento"];
            $array_JSON_detalle["nroDocumentoBaja"] = $parametro["NumeroDocumento"];
            $array_JSON_detalle["motivoBajaDocumento"] = $parametro["MotivoBaja"];

            array_push($array_JSON["listaResumen"], $array_JSON_detalle);
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

          return $json_string;
        }

        public function CrearArchivoJSONNotaDebito($url_archivo, $data)
        {
          $archivo = $url_archivo;

          $array_JSON = Array();
          $array_JSON_detalle = Array();

          //CREANDO CABECERAS
          $array_JSON["CustomizationIdSwf"] = "1.0";
          $array_JSON["ublVersionIdSwf"] = "2.0";
          $array_JSON["nroCdpSwf"] = $data["Documento"]; //Juntar NumeroSerie + NumeroDocumento = F001-002
          $array_JSON["fechaEmision"] = $data["FechaEmision"];
          $array_JSON["nroRucEmisorSwf"] = $data["CodigoEmpresa"];
          $array_JSON["identificadorFacturadorSwf"] = "Elaborado por SISEM PERU 1.0.0";
          $array_JSON["codigoFacturadorSwf"] = "471154";
          $array_JSON["nombreComercialSwf"] = $data["NombreComercial"];
          $array_JSON["razonSocialSwf"] = $data["RazonSocialEmisor"]; //Cambiar datos en data de Empresa
          $array_JSON["identificadorFirmaSwf"] = "SIGN";
          $array_JSON["tipDocuEmisorSwf"] = "6";
          $array_JSON["ubigeoDomFiscalSwf"] = $data["Ubigeo"];
          $array_JSON["direccionDomFiscalSwf"] = $data["DomicilioFiscal"];
          $array_JSON["paisDomFiscalSwf"] = $data["CodigoPaisA3"]; //Create tabla pais y variable
          $array_JSON["moneda"] = $data["CodigoMoneda"];

          $array_JSON["nroDocuModifica"] = "";
          $array_JSON["codigoMotivo"] = "";
          $array_JSON["descripcionMotivo"] = "";
          $array_JSON["tipoDocuModifica"] = "";

          $array_JSON["nroDocuIdenti"] = ""; //$data["NumeroDocumentoIdentidad"];
          $array_JSON["tipoDocuIdenti"] = ""; //$data["CodigoDocumentoIdentidad"];

          $array_JSON["codigoUbigeoCliente"] = "";
          $array_JSON["direccionCliente"] = "";
          $array_JSON["codigoPaisCliente"] = "";
          $array_JSON["razonSocialUsuario"] = "";//$data["RazonSocialCliente"]; //Cambiar datos en data de Cliente

          $array_JSON["sumaIgv"] = $data["IGV"];
          $array_JSON["idIgv"] = "1000";
          $array_JSON["codIgv"] = "IGV";
          $array_JSON["codExtIgv"] = "VAT";

          $array_JSON["sumaIsc"] = $data["ISC"];
          $array_JSON["idIsc"] = "2000";
          $array_JSON["codIsc"] = "ISC";
          $array_JSON["codExtIsc"] = "EXC";

          $array_JSON["sumaOtros"] = $data["OtroTributo"];
          $array_JSON["idOtr"] = "9999";
          $array_JSON["codOtr"] = "OTROS";
          $array_JSON["codExtOtr"] = "OTH";

          $array_JSON["sumaOtrosCargos"] = $data["OtroCargo"];
          $array_JSON["sumaImporteVenta"] = $data["Total"];
          $array_JSON["tipoCodigoMonedaSwf"] = $data["CodigoTipoPrecio"]; //

          $array_JSON["codigoMontoOperGravadasSwf"] = "1001";
          $array_JSON["montoOperGravadas"] = $data["ValorVentaGravado"];
          $array_JSON["codigoMontoOperInafectasSwf"] = "1002";
          $array_JSON["montoOperInafectas"] = $data["ValorVentaInafecto"];
          $array_JSON["codigoMontoOperExoneradasSwf"] = "1003";
          $array_JSON["montoOperExoneradas"] = $data["ValorVentaNoGravado"];

          $array_JSON["codRegiPercepcion"] = "";
          $array_JSON["codigoPercepSwf"] = "";
          $array_JSON["baseImponiblePercepcion"] = "";
          $array_JSON["montoPercepcion"] = "";
          $array_JSON["montoTotalSumPercepcion"] = "";
          $array_JSON["codigoGratuitoSwf"] = "";
          $array_JSON["totalVentaOperGratuita"] = "";

          $array_JSON["listaDetalle"] = Array();
          foreach ($data["DetalleComprobanteVenta"] as $parametro)
          {
            $array_JSON_detalle["unidadMedida"] = $parametro["CodigoUnidadMedida"];
            $array_JSON_detalle["cantItem"] = $parametro["Cantidad"];
            $array_JSON_detalle["codiProducto"] = $parametro["CodigoMercaderia"]; //Revisar Henry
            $array_JSON_detalle["codiSunat"] = $parametro["CodigoMercaderia"];
            $array_JSON_detalle["desItem"] = $parametro["NombreProducto"];
            $array_JSON_detalle["valorUnitario"] = $parametro["ValorUnitario"];
            $array_JSON_detalle["valorVentaItem"] = $parametro["SubTotal"];
            $array_JSON_detalle["monto"] = "";
            $array_JSON_detalle["tipoCodigoMonedaSwf"] = $data["CodigoTipoPrecio"];
            $array_JSON_detalle["precioVentaUnitarioItem"] = $parametro["PrecioUnitario"];
            $array_JSON_detalle["montoIgvItem"] = $parametro["IGVItem"];
            $array_JSON_detalle["afectaIgvItem"] = $parametro["CodigoTipoAfectacionIGV"]; //Revisar Henry
            $array_JSON_detalle["montoIscItem"] = $parametro["ISCItem"];
            $array_JSON_detalle["tipoSistemaIsc"] = $parametro["CodigoTipoAfectacionISC"]; //Revisar Henry
            $array_JSON_detalle["lineaSwf"] = $parametro["NumeroItem"];

            array_push($array_JSON["listaDetalle"], $array_JSON_detalle);
          }

          if($data["GuiaRemision"] != "")
          {
            $array_JSON["listaRelacionado"] = Array(
              "indDocuRelacionado" => "3",
              "nroDocuRelacionado" => $data["GuiaRemision"],
              "tipDocuRelacionado" => "09"
            );
          } else {
            $array_JSON["listaRelacionado"] = Array();
          }

          $array_JSON["listaLeyendas"] = Array();
          /*$array_JSON["listaLeyendas"] = Array(
            "codigo" => "",
            "descripcion" => ""
          );*/

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

          return $json_string;
        }



        public function CrearArchivoJSON($data)
        {
          $archivo = $data["archivo"];
          $data = $data["data"];

          if (file_exists($archivo)) {
        	    //echo "El fichero $archivo existe";
        	} else {
        		$nuevo_archivo = fopen($archivo, "w+");
        		if($nuevo_archivo == false){
        			die("No se ha podido crear el archivo.");
        		}
        		fclose($nuevo_archivo);
        	  //echo "El fichero $archivo no existe";
        	}
          //Creamos el JSON
        	//$json_string = json_encode($json_canvas);
        	file_put_contents($archivo, $data);

          return "";
        }

        public function CrearJSONCanvas($data) {
          $data = $data["data"];
          $archivo = $data["archivoimpresion"];

          $json_canvas = null;
        	$json_canvas["data"] = array();
        	$json_canvas["detalle"] = array();

        	$json_canvas["document-width"] = $data["witdh"];
        	$json_canvas["document-height"] = $data["heigth"];
          $json_canvas["watermark"] = $data["watermark"];

          if(!array_key_exists("objects", $data)){
            $response["data"] = $data;
            $response["msg"] = "No hay ningun objeto";
            echo $this->json->json_response($response);
            exit;
          }

        	foreach($data["objects"] as $parametro) {
            $fila = array();

            $fila["id"] = $parametro["id"];
            $fila["name"] = $parametro["name"];
            $fila["tipo"] = $parametro["tipo"];
            $fila["clase"] = $parametro;

            if($parametro["tipo"] != "4")
            {
              $fila["id"] = $parametro["id"];
              $fila["name"] = $parametro["name"];
              $fila["tipo"] = $parametro["tipo"];
              $fila["_comment"] = $parametro["text"];
              $fila["value"] = $parametro["text"];

          		if($parametro["tipo"] != "2")//CUANDO NO ES DETALLE
          		{
          			//$json_canvas += array($parametro["text"] => $parametro["text"]);
          			array_push($json_canvas["data"], $fila);
          		}
          		else //CUANDO ES DETALLE
          		{
          			array_push($json_canvas["detalle"], $fila);
          		}
            }
            else {
              array_push($json_canvas["data"], $fila);
            }

        	}

        	if (file_exists($archivo)) {
        	    //echo "El fichero $archivo existe";
        	} else {
        		$nuevo_archivo = fopen($archivo, "w+");
        		if($nuevo_archivo == false){
        			die("No se ha podido crear el archivo.");
        		}
        		fclose($nuevo_archivo);
        	  //echo "El fichero $archivo no existe";
        	}
          //Creamos el JSON
        	$json_string = json_encode($json_canvas);
        	file_put_contents($archivo, $json_string);

          return $json_string;
        }


        public function MapearJSONEstructuraDesdePlantilla($url, $estructuras)
        {
          $plantilla = $url;
          $data = $estructuras;

          $array_JSON = Array();

          //CREANDO CABECERAS
      		//VALIDANDO Y ABRIENDO PLANTILLA
      		if (!file_exists($plantilla)) {
      			$nuevo_archivo = fopen($plantilla, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido Encontrar la plantilla.");
      			}
      			fclose($nuevo_archivo);
      		}

      		$datos_plantilla = file_get_contents($plantilla);
      		$plantilla_contenido = json_decode($datos_plantilla, true);

          foreach($plantilla_contenido as $indice => $parametro)
          {
            $estructura = array();
            // $estructura = $parametro;
            foreach ($parametro as $key => $value) {
              // array_push($estructura, $value)
              if($value["tipo"] == 0)
              {
                // array_push($estructura, {$value["name"] => $value["value"]});
                $estructura += array($value["name"] => $value["value"]);
              }
              else {
                $indice = $value["value"];
                // array_push($estructura, {$value["name"] => $data[$indice]});
                $estructura += array($value["name"] => $data[$indice]);
              }
            }
            array_push($array_JSON, $estructura);
          }


          return $array_JSON;
        }

        public function ObtenerConfigImpresion($id,$seriedocumento = null,$rutaplantilla = null) {

          $rutaplantillabase = RUTA_CONFIG_IMPRESION;
          
          if ($rutaplantilla == null ) {
            if (!file_exists($rutaplantillabase)) {
              $nuevo_archivo = fopen($rutaplantillabase, "w+");
              if($nuevo_archivo == false) {
                die("No se ha podido Encontrar la plantilla  base.");
              }
              fclose($nuevo_archivo);
            }
          }
          else {
            //echo $rutaplantilla;
            if (!file_exists($rutaplantilla)) {
              // $nuevo_archivo = fopen($rutaplantilla, "w+");
              // if($nuevo_archivo == false) {
              //   die("No se ha podido Encontrar la plantilla del dispositivo.");
              // }
              // fclose($nuevo_archivo);
            }
            else {
              $rutaplantillabase = $rutaplantilla;
            }
          }

      		$datos_plantilla = file_get_contents($rutaplantillabase);
      		$plantilla_contenido = json_decode($datos_plantilla, true);

          $response = false;
            
          foreach($plantilla_contenido as $indice => $parametro) {
            if($id == $parametro["Indicador"]) {
              
              if ($seriedocumento != null && array_key_exists("Series",$parametro) == true) {
                $response = $parametro;  
                foreach($parametro["Series"] as $key => $value) {                  
                  
                  if ($seriedocumento == $value["SerieDocumento"]) {
                    $response = $value;    
                  }
                }              
              }
              else {
                $response = $parametro;
              }
            }
          }

          return $response;
        }

        public function ObtenerConfigCorrelativo($idusuario, $tipodocumento)
        {
          $plantilla = RUTA_CONFIG_CORRELATIVO;
          //CREANDO CABECERAS
      		//VALIDANDO Y ABRIENDO PLANTILLA
      		if (!file_exists($plantilla)) {
      			$nuevo_archivo = fopen($plantilla, "w+");
      			if($nuevo_archivo == false){
      				die("No se ha podido Encontrar la plantilla.");
      			}
      			fclose($nuevo_archivo);
      		}

      		$datos_plantilla = file_get_contents($plantilla);
          $plantilla_contenido = json_decode($datos_plantilla, true);
          if($plantilla_contenido == NULL)
          {
            return false;
          }
          
          $response = false;
          
          foreach($plantilla_contenido as $indice => $parametro)
          {
            if($idusuario == $parametro["IdUsuario"])
            {
              foreach ($parametro["Series"] as $key => $value) {
                if($tipodocumento['IdTipoDocumento'] == $value["IdTipoDocumento"])
                {
                  $response = $value;
                  if(array_key_exists("IdSubTipoDocumento", $tipodocumento))
                  {
                    if(array_key_exists("IdSubTipoDocumento", $value))
                    {
                      if($tipodocumento["IdSubTipoDocumento"] == $value['IdSubTipoDocumento'])
                      {
                        break;
                      }
                    }
                  }
                  else
                  {
                    if(!array_key_exists("IdSubTipoDocumento", $value))
                    {
                      break;
                    }
                    else
                    {
                      if($value["IdSubTipoDocumento"] == null || $value["IdSubTipoDocumento"] == "")
                      {
                        break;
                      } 
                    }
                  }
                }
              }
            }
          }

          return $response;
        }

        public function jsontoarray($data) {
          $input = urldecode($data);			
          $datajson = json_decode($input,true);						
          return $datajson;
        }

}

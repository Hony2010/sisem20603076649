<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sImportacionVenta extends MY_Service {

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('json');
    $this->load->helper("date");
    $this->load->service("Catalogo/sCliente");
    $this->load->service("Catalogo/sMercaderia");
    $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
    $this->load->service('Venta/sComprobanteVenta');
    $this->load->service('Seguridad/sUsuario');
		$this->load->service("Venta/sVenta");
		$this->load->service("FacturacionElectronica/sComprobanteElectronico");
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
		$this->load->library('RestApi/Catalogo/RestApiCliente');
  }

  public function ValidarListadoCliente($data)
  {
    $ClientesBase = array();
    $ClientesImportacion = array();
    $ClientesImportacionNuevo = array();
    // print_r($data);exit;
    foreach ($data as $key => $value) {
      # code...
      $response = $this->sCliente->ValidarDataJSONCliente($value);
      if($response["Codigo"] == 0)//NUEVO
      {
        $value["Estado"] = "NUEVO";
        $value["CodigoEstado"] = "0";
        array_push($ClientesImportacionNuevo, $value);
      }
      else if($response["Codigo"] == 1)//RUC DISTINTOS
      {
        $value["Estado"] = "MODIFICADO";
        $value["CodigoEstado"] = "1";
        array_push($ClientesBase, $response["Data"]);
        array_push($ClientesImportacion, $value);
      }
      else if($response["Codigo"] == 2)//RAZON SOCIAL DISTINTOS
      {
        $value["Estado"] = "MODIFICADO";
        $value["CodigoEstado"] = "2";
        array_push($ClientesBase, $response["Data"]);
        array_push($ClientesImportacion, $value);
      }
    }
    $ClientesImportacion = array_merge($ClientesImportacion, $ClientesImportacionNuevo);
    $resultado["ClientesBase"] = $ClientesBase;
    $resultado["ClientesImportacion"] = $ClientesImportacion;
    return $resultado;
  }

  public function ValidarListadoProducto($data)
  {
    $ProductosBase = array();
    $ProductosImportacion = array();
    $ProductosImportacionNuevo = array();
    // print_r($data);exit;
    foreach ($data as $key => $value) {
      # code...
      $response = $this->sMercaderia->ValidarDataJSONProducto($value);
      if($response["Codigo"] == 0)//NUEVO
      {
        $value["Estado"] = "NUEVO";
        $value["CodigoEstado"] = "0";
        array_push($ProductosImportacionNuevo, $value);
      }
      else if($response["Codigo"] == 1)//RUC DISTINTOS
      {
        $value["Estado"] = "MODIFICADO";
        $value["CodigoEstado"] = "1";
        array_push($ProductosBase, $response["Data"]);
        array_push($ProductosImportacion, $value);
      }
      else if($response["Codigo"] == 2)//RAZON SOCIAL DISTINTOS
      {
        $value["Estado"] = "MODIFICADO";
        $value["CodigoEstado"] = "2";
        array_push($ProductosBase, $response["Data"]);
        array_push($ProductosImportacion, $value);
      }
    }
    $ProductosImportacion = array_merge($ProductosImportacion, $ProductosImportacionNuevo);
    $resultado["ProductosBase"] = $ProductosBase;
    $resultado["ProductosImportacion"] = $ProductosImportacion;
    return $resultado;
  }

  public function ObtenerDataComprobanteParaValidarJSON($data)
  {
    $comprobante = $this->sComprobanteVenta->ValidarComprobanteVentaJSONPorSerieYNumero($data);
    return $comprobante;
  }

  //PARA LAS VENTAS QUE SE IMPORTARAN
  public function ValidarComprobanteVentaJSON($data)
  {
    $comprobante = $this->sComprobanteVenta->ValidarComprobanteVentaJSONPorSerieYNumero($data);
    if(count($comprobante) > 0)
    {
      return "El Comprobante Venta ya esta registrado.";
    }
    else
    {
      //VALIDAMOS SERIE DOCUMENTO EN CORRELATIVO DOCUMENTO
      $correlativo = (array) $this->sCorrelativoDocumento->ValidarCorrelativoDocumentoJSONPorSerieYTipo($data);
      if(empty($correlativo))
      {
        return "No existe correlativo para el comprobante de venta";
      }
      else
      {
        $data["NombreUsuario"] = $data["UsuarioRegistro"];
        //Validamos Usuario
        $usuario = $this->sUsuario->ValidarUsuarioParaVentaJSON($data);
        
        if(empty($usuario)){
          return "El usuario no esta registrado";
        }
        else
        {
          //Validamos el cliente
          $cliente = (array) $this->sCliente->ConsultarClienteEnVentasJSON($data["Cliente"]);
          if(empty($cliente))
          {
            return "El cliente no esta registrado";
          }
          else
          {
            $detalle = $this->ValidarDetallesComprobanteVentaJSON($data["DetallesComprobanteVenta"]);
  
            if(!is_array($detalle))
            {
              return $detalle;
            }
            else
            {
              $data["IdComprobanteVenta"] = "";
              $data["IdCliente"] = $cliente["IdPersona"];
              $data["IdCorrelativoDocumento"] = $correlativo["IdCorrelativoDocumento"];
              $data["IdUsuario"] = $usuario["IdUsuario"];
              $data["DetallesComprobanteVenta"] = $detalle;
              
              // print_r($data);exit;
              return $data;
            }
          }
        }

      }
    }
  }

  public function ValidarDetallesComprobanteVentaJSON($data)
  {
    $respuesta = "";
    foreach ($data as $key => $value) {
      $producto = (array) $this->sMercaderia->ConsultarProductoEnVentasJSONParaImportacion($value["Producto"]);
      
      if(empty($producto))
      {
        $respuesta .= "Linea Detalle Nro. ".$key.", no existe el producto.".PHP_EOL;
      }
      else
      {
        $data[$key]["IdDetalleComprobanteVenta"] = "";
        $data[$key]["IdProducto"] = $producto["IdProducto"];
      }
    }
    
    if($respuesta == "")
    {
      return $data;
    }
    else
    {
      return $respuesta;
    }
  }

  //PARA API DE COMPROBANTES DE VENTA
  public function ValidarDataComprobanteVenta($data)
  {
    $comprobante = $this->sComprobanteVenta->ValidarComprobanteVentaJSONPorSerieYNumero($data);
    if(count($comprobante) > 0)
    {
      return "El Comprobante Venta ya esta registrado.";
      // $data["IdComprobanteVenta"] = $comprobante[0]["IdComprobanteVenta"];
      // $data["Estado"] = 1; //ACTUALIZACION
      // $response = $this->ValidarComprobanteVentaParaData($data);
      // return $response;
    }
    else
    {
      $data["IdComprobanteVenta"] = "";
      $data["Estado"] = 0;//INSERCION
      $response = $this->ValidarComprobanteVentaParaData($data);
      return $response;
    }
  }

  public function ValidarComprobanteVentaParaData($data)
  {
    //VALIDAMOS SERIE DOCUMENTO EN CORRELATIVO DOCUMENTO
    $correlativo = (array) $this->sCorrelativoDocumento->ValidarCorrelativoDocumentoJSONPorSerieYTipo($data);
    if(empty($correlativo))
    {
      return "No existe correlativo para el comprobante de venta";
    }
    else
    {
      $data["NombreUsuario"] = $data["UsuarioRegistro"];
      //Validamos Usuario
      $usuario = $this->sUsuario->ValidarUsuarioParaVentaJSON($data);
      
      if(empty($usuario)){
        return "El usuario no esta registrado";
      }
      else
      {
        //Validamos el cliente
        $cliente = (array) $this->sCliente->ConsultarClienteEnVentasJSON($data["Cliente"]);
        if(empty($cliente))
        {
          //CREAMOS EL PRODUCTO
          $clienteData = $this->restapicliente->InsertarClienteJSON($data["Cliente"]);
          if(is_array($clienteData))
          {
            $detalle = $this->ValidarDataDetallesComprobanteVenta($data);

            if(!is_array($detalle))
            {
              return $detalle;
            }
            else
            {
              $data["IdCliente"] = $clienteData["IdPersona"];
              $data["IdCorrelativoDocumento"] = $correlativo["IdCorrelativoDocumento"];
              $data["IdUsuario"] = $usuario["IdUsuario"];
              $data["DetallesComprobanteVenta"] = $detalle;
              
              return $data;
            }
          }
          else
          {
            return $clienteData;
          }
        }
        else
        {
          $detalle = $this->ValidarDataDetallesComprobanteVenta($data);

          if(!is_array($detalle))
          {
            return $detalle;
          }
          else
          {
            $data["IdCliente"] = $cliente["IdPersona"];
            $data["IdCorrelativoDocumento"] = $correlativo["IdCorrelativoDocumento"];
            $data["IdUsuario"] = $usuario["IdUsuario"];
            $data["DetallesComprobanteVenta"] = $detalle;
            
            return $data;
          }
        }
      }
    }
  }

  public function ValidarDataDetallesComprobanteVenta($data)
  {
    $respuesta = "";
    $idcomprobanteventa = $data["IdComprobanteVenta"];
    $data = $data["DetallesComprobanteVenta"];
    foreach ($data as $key => $value) {
      $producto = (array) $this->sMercaderia->ConsultarProductoEnVentasJSONParaImportacion($value["Producto"]);
      
      if(empty($producto))
      {
        //CREAMOS EL PRODUCTO
        $mercaderia = $this->restapimercaderia->InsertarMercaderiaJSON($value["Producto"]);
        if(is_array($mercaderia))
        {
          $data[$key]["IdDetalleComprobanteVenta"] = "";
          $data[$key]["IdComprobanteVenta"] = $idcomprobanteventa;
          $data[$key]["IdProducto"] = $mercaderia["IdProducto"];
        }
        else
        {
          $respuesta .= "Linea Detalle Nro. ".$key.": ".$mercaderia.PHP_EOL;
        }
        // $respuesta .= "Linea Detalle Nro. ".$key.", no existe el producto.".PHP_EOL;
      }
      else
      {
        $data[$key]["IdDetalleComprobanteVenta"] = "";
        $data[$key]["IdComprobanteVenta"] = $idcomprobanteventa;
        $data[$key]["IdProducto"] = $producto["IdProducto"];
      }
    }
    
    if($respuesta == "")
    {
      return $data;
    }
    else
    {
      return $respuesta;
    }
  }

  //VALIDACION PARA DETALLES ELIMNAR - ANULAR
  public function ValidarComprobanteVentAParaAnulacion($data)
  {
    $comprobante = $this->sComprobanteVenta->ValidarComprobanteVentaJSONPorSerieYNumero($data);
    if(count($comprobante) > 0)
    {
      return $comprobante[0];
    }
    else
    {
      return "El comprobante no esta registrado";
    }
  }

  public function ValidarComprobanteVentAParaEliminacion($data)
  {
    $comprobante = $this->sComprobanteVenta->ValidarComprobanteVentaEliminadoJSONPorSerieYNumero($data);
    if(count($comprobante) > 0)
    {
      return $comprobante[0];
    }
    else
    {
      return "El comprobante no esta registrado";
    }
  }

  //INSECION DE VENTAS
  public function InsertarVenta($data)
	{
		try {
			$this->db->trans_begin();
			$resultado = $this->sVenta->InsertarVenta($data);
      
			if(is_array($resultado)) {

				$CodigoSerie=substr($data["SerieDocumento"], 0,1);
				if ($CodigoSerie == 'F' || $CodigoSerie == 'B') {
					$resultado2=$this->sComprobanteElectronico->GenerarXMLComprobanteElectronico($resultado);

					$resultado["NombreArchivoComprobante"] = $resultado2["NombreArchivoComprobante"];
					$resultado["NombreAbreviado"] = $resultado2["NombreAbreviado"];
					$resultado["NombreTipoDocumento"] = $resultado2["NombreTipoDocumento"];
					$resultado["CodigoHash"] = $resultado2["CodigoHash"];
				}
				else {
					$resultado2 = $resultado;
				}

				if(is_array($resultado2)) {
					$this->db->trans_commit();

					if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
						$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['DetallesComprobanteVenta'], true);
					}

					return $resultado;
				}
				else {
					$this->db->trans_rollback();
					return $resultado2;
				}
			}
			else {
			 	$this->db->trans_rollback();
				return $resultado;
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 return $e->getMessage();
		}
  }
  
  //METODOS PARA MODIFICAR, ANULAR, ELIMINAR VENTA
  public function ActualizarVenta($data)
	{
		try {
			$this->db->trans_begin();
      
			$resultado = $this->sVenta->ActualizarVenta($data);
			if(is_array($resultado)) {
				$CodigoSerie=substr($data["SerieDocumento"], 0,1);
				if ($CodigoSerie == 'F' || $CodigoSerie == 'B') {
          $resultado=$this->sComprobanteElectronico->GenerarXMLComprobanteElectronico($resultado);
          $resultado["CodigoHash"] = $resultado["CodigoHash"];
				}
				if(is_array($resultado)) {
					$this->db->trans_commit();
					if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
						$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($data['DetallesComprobanteVenta'], true);
					}
					return $resultado;
				}
				else {
					$this->db->trans_rollback();
					return $resultado;
				}
			}
			else {
				$this->db->trans_rollback();
				return $resultado;
			}
		}
		catch (Exception $e) {
			 $this->db->trans_rollback();
			 return $e->getMessage();
		}
	}

	public function AnularVenta($data) {
		try {
			$resultado = $this->sVenta->AnularVenta($data);

			if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
				$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($resultado['DetallesComprobanteVenta'], true);
			}
			return $resultado;
		}
		catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function BorrarVenta($data) {
		$resultado = $this->sVenta->EliminarVenta($data);
		if (!is_string($resultado)) {

			if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
				$jsonMercaderia = $this->restapimercaderia->ActualizarProductosJSON($resultado['DetallesComprobanteVenta'], true);
			}

			$resultado = $data;

			return $resultado;
		}
		else {
			$response["error"] = $resultado;
			return $response;
		}
  }
  
}

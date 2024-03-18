<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sVenta extends sComprobanteVenta {

  public function __construct() {
    parent::__construct();
    
    $this->load->model('Venta/mVenta');
    $this->load->service('Inventario/sNotaEntrada');
    $this->load->service('Configuracion/General/sConstanteSistema');
    $this->load->service('Configuracion/Inventario/sMotivoNotaSalida');
    $this->load->service('Configuracion/Catalogo/sTipoListaPrecio');
    $this->load->service('Inventario/sNotaSalida');
    $this->load->service('Venta/sNotaCredito');
    $this->load->service('Venta/sNotaDebito');
    $this->load->service('Catalogo/sVehiculo');
    $this->load->service('Catalogo/sMercaderia');    
    $this->load->service('Catalogo/sCliente');    
    $this->load->service('Catalogo/sVehiculoCliente');
    $this->load->service('Catalogo/sCasilleroGenero');
    $this->load->service('Venta/sComprobanteVenta');
  }

  public function InsertarVenta($data) {
    //ANTES DE LA VENTA - PARA PLACA
    //AGREGAMOS LO QUE VA DEL ACTUALIZADO DEL VEHICULO
    $parametroRubroLubricante = $this->sConstanteSistema->ObtenerParametroRubroLubricante();
    $parametroSauna = $this->sConstanteSistema->ObtenerParametroSauna();
    
    if($parametroRubroLubricante == 1)
    {
      $vehiculo = $this->sVehiculoCliente->AgregarVehiculoClienteDesdeVenta($data);
      $data["IdVehiculo"] = $vehiculo["IdVehiculo"];
      $vehiculo = $this->sVehiculo->ActualizarVehiculoDesdeVenta($data);
    }

    $resultado = parent::InsertarComprobanteVenta($data);
    if(!is_string($resultado))
    {
      if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
        if(is_array($resultado)) {
          $resultado["RazonSocial"] =$data["RazonSocial"];
          $resultado["NombreAlmacen"] =$data["NombreSedeAlmacen"];
          $resultado["IdPersona"] =$data["IdCliente"];

          if($resultado["EstadoPendienteNota"] == CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE) {
            $resultado["EstadoPendienteComprobante"] ="0";
            $this->CargarSaldosPendientesACero($resultado["DetallesComprobanteVenta"]);

            $resultado['IdSubTipoDocumento'] = $data['IdSubTipoDocumento'];
            $resultadoNS = $this->sNotaSalida->InsertarNotaSalidaDesdeComprobante($resultado);
          }
          else if($resultado["EstadoPendienteNota"] == CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE_ENTREGA_NOTA_SALIDA) {
            $this->CargarSaldosPendientesSalidaIgualCantidad($resultado["DetallesComprobanteVenta"]);
          }
          else if ($resultado["EstadoPendienteNota"] == CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE_ENTREGA_NOTA_ENTRADA) {
            $this->CargarSaldosPendientesEntradaIgualCantidad($resultado["DetallesComprobanteVenta"]);
          }
          else if($resultado["EstadoPendienteNota"] == CODIGO_ESTADO_COMPROBANTE_CON_NOTA_SALIDA) {
            $resultado["IdNotaSalida"] = $data["IdNotaSalida"];                              
            $resultado2=$this->sNotaSalida->VincularComprobanteVentaConNotaSalida($resultado);
            if(!is_array($resultado2)) {
              return $resultado2;
            }
          }
        }
      }

      //Si esta activado parametro de sauna
      if ($parametroSauna == 1) {
        $resultadoCasilleroGenero = $this->sCasilleroGenero->MarcarCasilleroGenero($data);
      }
    }
    return $resultado;
  }

  public function ActualizarVenta($data) {

    //AGREGAMOS LO QUE VA DEL ACTUALIZADO DEL VEHICULO
    $parametroRubroLubricante = $this->sConstanteSistema->ObtenerParametroRubroLubricante();
    $parametroSauna = $this->sConstanteSistema->ObtenerParametroSauna();
    
    if($parametroRubroLubricante == 1) {
      $vehiculo = $this->sVehiculoCliente->AgregarVehiculoClienteDesdeVenta($data);
      $data["IdVehiculo"] = $vehiculo["IdVehiculo"];
      $vehiculo = $this->sVehiculo->ActualizarVehiculoDesdeVenta($data);
    }

    $resultado = parent::ActualizarComprobanteVenta($data);
    if(is_array($resultado)) {
      if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
        if(is_array($resultado)) {
          $data["NombreAlmacen"] =$data["NombreSedeAlmacen"];
          $data["IdPersona"] =$data["IdCliente"];
          
          if($resultado["EstadoPendienteNota"] == CODIGO_ESTADO_COMPROBANTE_CON_NOTA_SALIDA) {
            // NO HACER NADA DE MOVIMIENTOS
          }
          else {
            $this->sNotaSalida->ActualizarNotaSalidaDesdeComprobanteVenta($data);
          }
        }
      }

      //Si esta activado parametro de sauna
      if ($parametroSauna == 1) {
        $resultadoCasilleroGenero = $this->sCasilleroGenero->MarcarCasilleroGenero($data);
      } 

    }

    return $resultado;
  }

  public function CargarSaldosPendientesACero($data) {

    foreach ($data as $key => $value) {
      if($value["IdProducto"] != null)
      {
        $data[$key]["SaldoPendienteSalida"] = 0;
        $data[$key]["SaldoPendienteEntrada"] = 0;
        $resultado = parent::ActualizarDetalleComprobanteVenta($data[$key]);
      }
    }

    return $data;
  }

  public function CargarSaldosPendientesSalidaIgualCantidad($data) {
    foreach ($data as $key => $value) {
      if($value["IdProducto"] != null)
      {
        $data[$key]["SaldoPendienteSalida"] = $data[$key]["Cantidad"];
        $data[$key]["SaldoPendienteEntrada"] = 0;
        $resultado = parent::ActualizarDetalleComprobanteVenta($data[$key]);
      }
    }

    return $data;
  }

  public function CargarSaldosPendientesEntradaIgualCantidad($data) {
    foreach ($data as $key => $value) {
      if($value["IdProducto"] != null)
      {
        $data[$key]["SaldoPendienteSalida"] = 0;
        $data[$key]["SaldoPendienteEntrada"] = $data[$key]["Cantidad"];
        $resultado = parent::ActualizarDetalleComprobanteVenta($data[$key]);
      }
    }

    return $data;
  }

  public function AnularVenta($data) {
    $resultado = parent::AnularComprobanteVenta($data);
    $parametroSauna = $this->sConstanteSistema->ObtenerParametroSauna();

    if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION)
    {
      $this->sNotaCredito->BorrarNotaCreditoDesdeServicioVenta($data);
    }
    else if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEBITO)
    {
      $this->sNotaDebito->BorrarNotaDebitoDesdeServicioVenta($data);
    }
    
    if(is_array($resultado)) {
      if ($data['IdTipoVenta'] == TIPO_VENTA_MERCADERIA) {
        $resultado["RazonSocial"] =$data["RazonSocial"];
        // $resultado["NombreAlmacen"] =$data["NombreSedeAlmacen"];
        $resultado["IdPersona"] =$data["IdCliente"];

        if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION)
        {
          $response = $this->sNotaEntrada->BorrarNotasEntradasDesdeComprobanteVenta($resultado);
        }
        else {
          if($data["EstadoPendienteNota"] == CODIGO_ESTADO_COMPROBANTE_CON_NOTA_SALIDA)
          {
            $this->sNotaSalida->DesvincularComprobanteVentaConNotaSalida($resultado);
          }
          else
          {
            $response = $this->sNotaSalida->BorrarNotasSalidasDesdeComprobanteVenta($resultado);
          }
        }
        // $resultado = $this->sNotaSalida->ActualizarNotaSalidaDesdeComprobanteVenta($data);
      }

      //Si esta activado parametro de sauna
      if ($parametroSauna == 1) {
        $resultadoCasilleroGenero = $this->sCasilleroGenero->LiberarCasilleroGenero($data);
      } 
  
    }

    return $resultado;
  }

  public function EliminarVenta($data) {
    $resultado = parent::BorrarComprobanteVenta($data);
    $parametroSauna = $this->sConstanteSistema->ObtenerParametroSauna();

    if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION)
    {
      $this->sNotaCredito->BorrarNotaCreditoDesdeServicioVenta($data);
    }
    else if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEBITO)
    {
      $this->sNotaDebito->BorrarNotaDebitoDesdeServicioVenta($data);
    }

    if(is_array($resultado)) {
      if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEVOLUCION)
      {
        $response = $this->sNotaEntrada->BorrarNotasEntradasDesdeComprobanteVenta($resultado);
      }
      else {
        if($data["EstadoPendienteNota"] == CODIGO_ESTADO_COMPROBANTE_CON_NOTA_SALIDA)
        {
          $this->sNotaSalida->DesvincularComprobanteVentaConNotaSalida($resultado);
        }
        else
        {
          $response = $this->sNotaSalida->BorrarNotasSalidasDesdeComprobanteVenta($resultado);
        }
      }

      //Si esta activado parametro de sauna
      if ($parametroSauna == 1) {
        $resultadoCasilleroGenero = $this->sCasilleroGenero->LiberarCasilleroGenero($data);
      } 
    }


    return $resultado;
  }

  public function ListarRankingClientesMayoresVentas($data) {
    $resultado = $this->mVenta->ListarRankingClientesMayoresVentas($data);
    return $resultado;    
  }

  function ValidarClienteEstaRankingClientesMayoresVentas($data) {
    $data["NumeroMaximoClientesRanking"] = $this->sConstanteSistema->ObtenerParametroNumeroMaximoClientesRankingClientesMayoresVentas();                
    //Criterio 1 : Buscar si el cliente esta en el ranking de los 10 clientes que mas compran
    $dataClientes = $this->ListarRankingClientesMayoresVentas($data);

    foreach($dataClientes as $key =>$value) {
      if ($value["IdCliente"] == $data["IdCliente"]) {
        return true;
      }
    }

    return false;
  }

  public function AplicarPrecioEspecialCliente($data) {
    $resultadoValidacion=$this->ValidarClienteEstaRankingClientesMayoresVentas($data);
    if ($resultadoValidacion == true) {
      $dataTipoListaPrecio = $this->sTipoListaPrecio->ObtenerTipoListaPrecioEspecialCliente();
      if (count($dataTipoListaPrecio) > 0) {
        return $dataTipoListaPrecio[0]["IdTipoListaPrecio"];  
      }
      else {
        return "";
      }      
    }
    else {
      return "";
    }
  }

  public function ConsultarItemsVenta($data) {
    $resultado = $this->mVenta->ConsultarItemsVentas($data);
    
    foreach ($resultado as $key => $value) {
      $resultado[$key]["Producto"] = $this->sMercaderia->ConsultarMercaderiaPorIdProductoParaJSON($value);
    }

    return $resultado;
  }

  public function ConsultarVentas($data) {    
    $resultado = $this->mVenta->ConsultarVentas($data);
    
    $tasaigv = $this->sComprobanteVenta->ObtenerTasaIGV();

    foreach ($resultado as $key => $value) {      
      $value["DetallesComprobanteVenta"] = $this->ConsultarItemsVenta($value);
      $value["Cliente"] = $this->sCliente->ConsultarClienteParaJSONExportacion($value);
      $value["FechaFormateada"] =convertirFechaES($value["FechaEmision"]);
      $value["TasaIGV"] = $tasaigv;
      $value["TasaISC"] = 0.00;
      $value["NombreSedeAlmacen"] = $resultado[$key]["NombreSedeAgencia"];
      $value["NombreDispositivo"] = NOMBRE_DISPOSITIVO;
      $value["FechaExpedicion"] = "";
      $resultado[$key] = $value;
    }

    return $resultado;
  }

  public function PrepararVentas($data) {
    $data["FechaInicio"] = convertToDate($data["FechaInicio"]);
    $data["FechaFin"] = convertToDate($data["FechaFin"]);
    
    $dataVentas =  $this->ConsultarVentas($data);
    
    $clientes = array();
    $productos = array();

    foreach ($dataVentas as $key => $value) {
      array_push($clientes, $value["IdCliente"]);
      foreach ($value["DetallesComprobanteVenta"] as $key2 => $value2) {
        array_push($productos, $value2["IdProducto"]);
      }
    }

    $clientes = array_values(array_unique($clientes));
    $productos = array_values(array_unique($productos));

    $response["Clientes"] = $this->sCliente->ConsultarClientesEnVentasJSON($clientes);
    $response["Productos"] = $this->sMercaderia->ConsultarMercaderiasEnVentasJSON($productos);
    $response["Ventas"] = $dataVentas;
    
    return $response;
  }

  public function ConsultarComprobanteVentaPorId($data) {
    $resultado = $this->sComprobanteVenta->ConsultarComprobanteVentaPorId($data);
    return $resultado;
  }

}

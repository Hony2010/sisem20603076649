<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Compra\sComprobanteCompra.php');

class sCompra extends sComprobanteCompra {

  public function __construct() {
    parent::__construct();
    $this->load->service('Inventario/sNotaEntrada');
    $this->load->service('Inventario/sNotaSalida');
    $this->load->service('Configuracion/Inventario/sMotivoNotaEntrada');
    $this->load->service('Compra/sDetalleComprobanteCompra');
    $this->load->service('Configuracion/General/sMoneda');
    $this->load->service('Compra/sNotaDebitoCompra');
    $this->load->service('Compra/sNotaCreditoCompra');
  }

  public function InsertarCompra($data) {
    $resultado = parent::InsertarComprobanteCompra($data);
    //$parametroActivacionKardex = "N";
    if(is_array($resultado)) {
      //if ($parametroActivacionKardex =="S") {
        $resultado["RazonSocial"] =$data["RazonSocial"];
        $resultado["NombreAlmacen"] =$data["NombreSedeAlmacen"];
        $resultado["IdPersona"] =$data["IdProveedor"];

        $resultado["IdMotivoNotaEntrada"]=ID_MOTIVO_NOTA_ENTRADA_COMPRA_CON_DOCUMENTO;
        $resultadoMotivoEntrada = $this->sMotivoNotaEntrada->ObtenerMotivoNotaEntrada($resultado);
        $resultado["MotivoMovimiento"]=$resultadoMotivoEntrada->NombreMotivoNotaEntrada;

        $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
        $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();
        $parametro_tipodocumento_zofra = $this->sConstanteSistema->ObtenerParametroTipoDocumentoSalidaZofra();
        $parametro_tipodocumento_dua_alternativo = $this->sConstanteSistema->ObtenerParametroTipoDocumentoDuaAlternativo();
        if($resultado["EstadoPendienteNota"] == CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE) {
          $resultado["EstadoPendienteComprobante"] ="0";
          $this->CargarSaldosPendientesACero($resultado["DetallesComprobanteCompra"]);
          $resultado["IdAsignacionSede"] = $data["IdAsignacionSede"];

          if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_DOCUMENTOINGRESO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_DOCUMENTOCONTROL)
          {
            foreach ($resultado["DetallesComprobanteCompra"] as $key => $value) {
              $resultado["DetallesComprobanteCompra"][$key]["IndicadorDocumentoIngresoZofra"] = '1';
            }

            //CODIGO AQUI PARA DOCUMENTOS DE INGRESO
            $this->CargarSaldosDocumentoIngreso($resultado["DetallesComprobanteCompra"]);
          }

          $CheckDocumentoSalidaZofra = filter_var($data["CheckDocumentoSalidaZofra"], FILTER_VALIDATE_BOOLEAN);
          if(($parametro_zofra == 1 && $data["IdTipoDocumento"] == $parametro_tipodocumento_zofra && $CheckDocumentoSalidaZofra == true) || ($parametro_dua == 1 && ($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_DUA || $data["IdTipoDocumento"] == $parametro_tipodocumento_dua_alternativo) && $CheckDocumentoSalidaZofra == true))
          {
            $copia_data = $resultado;
            $copia_data["IdComprobanteCompra"] = $copia_data["IdDocumentoIngresoZofra"];
            $documentosalida =parent::ObtenerComprobanteCompra($copia_data);
            $copia_data["IdComprobanteCompra"] = $resultado["IdComprobanteCompra"];
            $copia_data["IdAsignacionSede"] = $resultado["IdAsignacionSede"];
            
            foreach ($copia_data["DetallesComprobanteCompra"] as $key => $value) {
              $copia_data["DetallesComprobanteCompra"][$key]["IndicadorDocumentoIngresoZofra"] = '1';
            }

            $resultadoNS = $this->sNotaSalida->InsertarNotaSalidaDesdeComprobanteCompra($copia_data);
            $this->ActualizarSaldosDocumentoIngreso($resultado);
          }
          $resultadoNE = $this->sNotaEntrada->InsertarNotaEntradaDesdeComprobanteCompra($resultado);
        }
        else if ($resultado["EstadoPendienteNota"] == CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE_ENTREGA_NOTA_ENTRADA) {
          $this->CargarSaldosPendientesEntradaIgualCantidad($resultado["DetallesComprobanteCompra"]);
        }
        /*else if($resultado["EstadoPendienteNota"] == CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE_ENTREGA_NOTA_SALIDA) {
          $this->CargarSaldosPendientesSalidaIgualCantidad($resultado["DetallesComprobanteCompra"]);
        }*/
      //}
    }

    return $resultado;
  }

  public function ActualizarCompra($data) {

    $resultado = parent::ActualizarComprobanteCompra($data);
    //$parametroActivacionKardex = "N";
    if(is_array($resultado)) {
      //if ($parametroActivacionKardex =="S") {
        $resultado["RazonSocial"] =$data["RazonSocial"];
        $resultado["NombreAlmacen"] =$data["NombreSedeAlmacen"];
        $resultado["IdPersona"] =$data["IdProveedor"];

        $resultado["IdMotivoNotaEntrada"]=ID_MOTIVO_NOTA_ENTRADA_COMPRA_CON_DOCUMENTO;
        $resultadoMotivoEntrada = $this->sMotivoNotaEntrada->ObtenerMotivoNotaEntrada($resultado);
        $resultado["MotivoMovimiento"]=$resultadoMotivoEntrada->NombreMotivoNotaEntrada;

        $moneda = $this->sMoneda->ObtenerMonedaPorId($resultado);
        if(count($moneda)>0){$resultado["SimboloMoneda"] = $moneda[0]["SimboloMoneda"];}
        $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
        $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();
        $parametro_tipodocumento_zofra = $this->sConstanteSistema->ObtenerParametroTipoDocumentoSalidaZofra();
        $parametro_tipodocumento_dua_alternativo = $this->sConstanteSistema->ObtenerParametroTipoDocumentoDuaAlternativo();
        if($resultado["EstadoPendienteNota"] == CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE) {
          $resultado["EstadoPendienteComprobante"] ="0";
          $this->CargarSaldosPendientesACero($resultado["DetallesComprobanteCompra"]);
          if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_DOCUMENTOINGRESO || $data["IdTipoDocumento"] == ID_TIPODOCUMENTO_DOCUMENTOCONTROL)
          {
            foreach ($resultado["DetallesComprobanteCompra"] as $key => $value) {
              $resultado["DetallesComprobanteCompra"][$key]["IndicadorDocumentoIngresoZofra"] = '1';
            }
            
            //CODIGO AQUI PARA DOCUMENTOS DE INGRESO
            $this->CargarSaldosDocumentoIngreso($resultado["DetallesComprobanteCompra"]);
          }
          $resultado["IdAsignacionSede"] = $data["IdAsignacionSede"];

          $CheckDocumentoSalidaZofra = filter_var($data["CheckDocumentoSalidaZofra"], FILTER_VALIDATE_BOOLEAN);
          if(($parametro_zofra == 1 && $data["IdTipoDocumento"] == $parametro_tipodocumento_zofra && $CheckDocumentoSalidaZofra == true) || ($parametro_dua == 1 && ($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_DUA || $data["IdTipoDocumento"] == $parametro_tipodocumento_dua_alternativo) && $CheckDocumentoSalidaZofra == true))
          {
            $copia_data = $resultado;
            $copia_data["IdComprobanteCompra"] = $copia_data["IdDocumentoIngresoZofra"];
            $documentosalida =parent::ObtenerComprobanteCompra($copia_data);
            $copia_data["IdComprobanteCompra"] = $resultado["IdComprobanteCompra"];
            $copia_data["IdAsignacionSede"] = $resultado["IdAsignacionSede"];
            
            foreach ($copia_data["DetallesComprobanteCompra"] as $key => $value) {
              $copia_data["DetallesComprobanteCompra"][$key]["IndicadorDocumentoIngresoZofra"] = '1';
            }

            $resultadoNS = $this->sNotaSalida->InsertarNotaSalidaDesdeComprobanteCompra($copia_data);
            $this->ActualizarSaldosDocumentoIngreso($resultado);
            $resultado["BloquearDocumentoZofra"] = true;
            // print_r($data);
            // exit;
            $resultado["DocumentosIngreso"] = array();
          }

          $resultadoNE = $this->sNotaEntrada->ActualizarNotaEntradaDesdeComprobanteCompra($resultado);
        }
        else if ($resultado["EstadoPendienteNota"] == CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE_ENTREGA_NOTA_ENTRADA) {
          //BORRAMOS LOS CARDEX
          $this->sNotaEntrada->BorrarNotasEntradasDesdeComprobanteCompra($resultado);
          $this->CargarSaldosPendientesEntradaIgualCantidad($resultado["DetallesComprobanteCompra"]);
        }
        /*else if($resultado["EstadoPendienteNota"] == CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE_ENTREGA_NOTA_SALIDA) {
          $this->CargarSaldosPendientesSalidaIgualCantidad($resultado["DetallesComprobanteCompra"]);
        }*/
      //}
    }

    return $resultado;
  }

  public function ActualizarCompraAlternativo($data) {

    $resultado = parent::ActualizarComprobanteCompraAlternativo($data);
    if(is_array($resultado)) {
        $resultado["RazonSocial"] =$data["RazonSocial"];
        $resultado["NombreAlmacen"] =$data["NombreSedeAlmacen"];
        $resultado["IdPersona"] =$data["IdProveedor"];
    }

    return $resultado;
  }

  public function CargarSaldosPendientesACero($data) {
    foreach ($data as $key => $value) {
      if( $data[$key]["IdProducto"] != null) {
        $data[$key]["SaldoPendienteSalida"] = 0;
        $data[$key]["SaldoPendienteEntrada"] = 0;
        $resultado = parent::ActualizarDetalleComprobanteCompra($data[$key]);
      }
    }
    return $data;
  }

  public function CargarSaldosDocumentoIngreso($data) {
    foreach ($data as $key => $value) {
      if( $data[$key]["IdProducto"] != null) {
        $data[$key]["SaldoDocumentoIngreso"] = $data[$key]["Cantidad"];
        $resultado = parent::ActualizarDetalleComprobanteCompra($data[$key]);
      }
    }
    return $data;
  }

  public function ActualizarSaldosDocumentoIngreso($data) {
    $data_extra = $data;
    $data_extra["IdComprobanteCompra"] = $data["IdDocumentoIngresoZofra"];
    $consulta = $this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($data_extra);

    $detalles = $data["DetallesComprobanteCompra"];
    if(count($consulta) > 0)
    {
      foreach ($consulta as $key => $value) {

        foreach ($detalles as $key2 => $value2) {
          if($consulta[$key]["IdProducto"] == $value2["IdProducto"])
          {
            $consulta[$key]["SaldoDocumentoIngreso"] = $consulta[$key]["SaldoDocumentoIngreso"] - $value2["Cantidad"];
          }
        }

        $resultado = parent::ActualizarDetalleComprobanteCompra($consulta[$key]);
      }
    }

    return $data;
  }

  public function BorrarSaldosDocumentoIngreso($data) {
    $data_extra = $data;
    $data_extra["IdComprobanteCompra"] = $data["IdDocumentoIngresoZofra"];
    $consulta = $this->sDetalleComprobanteCompra->ConsultarDetallesComprobanteCompra($data_extra);

    $movimientos = $this->sNotaEntrada->ConsultarMovimientoAlmacenNotasEntradaDesdeComprobanteCompra($data);

    if(count($consulta) > 0)
    {
      foreach ($consulta as $key => $value) {

        foreach ($movimientos as $key2 => $value2) {
          if($consulta[$key]["IdProducto"] == $value2["IdProducto"])
          {
            $consulta[$key]["SaldoDocumentoIngreso"] = $consulta[$key]["SaldoDocumentoIngreso"] + $value2["CantidadEntrada"];
          }
        }
        $resultado = parent::ActualizarDetalleComprobanteCompra($consulta[$key]);
      }
    }

    return $data;
  }

  public function CargarSaldosPendientesSalidaIgualCantidad($data) {
    foreach ($data as $key => $value) {
      if( $data[$key]["IdProducto"] != null) {
        $data[$key]["SaldoPendienteSalida"] = $data[$key]["Cantidad"];
        $data[$key]["SaldoPendienteEntrada"] = 0;
        $resultado = parent::ActualizarDetalleComprobanteCompra($data[$key]);
      }
    }

    return $data;
  }

  public function CargarSaldosPendientesEntradaIgualCantidad($data) {
    foreach ($data as $key => $value) {
      if( $data[$key]["IdProducto"] != null) {
        $data[$key]["SaldoPendienteSalida"] = 0;
        $data[$key]["SaldoPendienteEntrada"] = $data[$key]["Cantidad"];
        $resultado = parent::ActualizarDetalleComprobanteCompra($data[$key]);
      }
    }

    return $data;
  }

  public function EliminarCompra($data) {

    $parametro_zofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
    $parametro_dua = $this->sConstanteSistema->ObtenerParametroDua();
    $parametro_tipodocumento_zofra = $this->sConstanteSistema->ObtenerParametroTipoDocumentoSalidaZofra();
    $parametro_tipodocumento_dua_alternativo = $this->sConstanteSistema->ObtenerParametroTipoDocumentoDuaAlternativo();
    $CheckDocumentoSalidaZofra = filter_var($data["CheckDocumentoSalidaZofra"], FILTER_VALIDATE_BOOLEAN);
    if(($parametro_zofra == 1 && $data["IdTipoDocumento"] == $parametro_tipodocumento_zofra && $CheckDocumentoSalidaZofra == true) || ($parametro_dua == 1 && ($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_DUA || $data["IdTipoDocumento"] == $parametro_tipodocumento_dua_alternativo) && $CheckDocumentoSalidaZofra == true))
    {
      $this->BorrarSaldosDocumentoIngreso($data);
      $response = $this->sNotaSalida->BorrarNotasSalidasDesdeComprobanteCompra($data);
    }

    if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO)
    {
      $this->sNotaCreditoCompra->BorrarNotaCreditoCompraDesdeServicioCompra($data);
    }
    else if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEBITO)
    {
      $this->sNotaDebitoCompra->BorrarNotaDebitoCompraDesdeServicioCompra($data);
    }

    $resultado = parent::BorrarComprobanteCompra($data);
    if(is_array($resultado))
    {
      if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTACREDITO)
      {
        // $this->sNotaCreditoCompra->BorrarNotaCreditoCompraDesdeServicioCompra($data);
        $response = $this->sNotaSalida->BorrarNotasSalidasDesdeComprobanteCompra($resultado);
      }
      // else if($data["IdTipoDocumento"] == ID_TIPODOCUMENTO_NOTADEBITO)
      // {
      //   $this->sNotaDebitoCompra->BorrarNotaDebitoCompraDesdeServicioCompra($data);
      // }
      else {
        $response = $this->sNotaEntrada->BorrarNotasEntradasDesdeComprobanteCompra($resultado, true);
      }
    }
    return $resultado;
  }


}

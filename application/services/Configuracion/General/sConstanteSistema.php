<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sConstanteSistema extends MY_Service
{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->service('Configuracion/General/sParametroSistema');
  }

  /*Se obtiene el parametro para trabajar con lotes*/
  function ObtenerParametroLote()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_LOTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCodigoProductoProveedor()
  {
    $data['IdParametroSistema'] = ID_CODIGO_PRODUCTO_PROVEEDOR;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerAtributosMensajeDemo()
  {
    $data['IdGrupoParametro'] = ID_ATRIBUTOS_MENSAJE_DEMO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupo($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      return $resultado;
    }
  }

  /*Se obtiene el parametro para trabajar con restriccion de cantidades por almacenes*/
  function ObtenerParametroRestriccionCantidad()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_RESTRICCION_CANTIDAD;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroDocumentoSalidaZofra()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_DOCUMENTO_SALIDA_ZOFRA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroTipoDocumentoSalidaZofra()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_TIPO_DOCUMENTO_SALIDA_ZOFRA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroDua()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_DOCUMENTO_SALIDA_DUA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroDocumentoIngreso()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_DOCUMENTO_INGRESO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroDescuentoUnitarioVenta()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_DESCUENTO_UNITARIO_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroStockProductoVenta()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_STOCK_PRODUCTO_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroDescuentoItemVenta()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_DESCUENTO_ITEM_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerCarpetasSUNAT()
  {
    $data['IdGrupoParametro'] = ID_GRUPO_CARPETA_SUNAT;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data);
    //print_r($resultado);
    //exit;
    if (is_string($resultado)) {
      return $resultado;
    } else {
      return $resultado;
    }
  }

  function ObtenerParametroCodigoBarras()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_CODIGO_BARRAS;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroListaVendedor()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_LISTA_VENDEDOR;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroEnvioEmail()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_ENVIO_EMAIL;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroTipoCambioActual()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_TIPO_CAMBIO_ACTUAL;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametrosParaCampoACuenta()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_CAMPO_A_CUENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametrosParaCampoMontoPendienteVenta()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_CAMPO_MONTO_PENDIENTE_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametrosParaCantidadCaja()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_CANTIDAD_CAJA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroTranporte()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_TRANSPORTES;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }


  function ObtenerParametroAlumno()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_ALUMNO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroTamanoSerieCompra()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_TAMANO_SERIE_COMPRA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCaja()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_CAJA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroBannerTipoVenta()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_BANNER_TIPO_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroTipoVentaPorDefecto()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_TIPO_VENTA_DEFECTO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroObservacionDetalle()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_OBSERVACION_DETALLE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroVistaVenta()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_VISTA_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  function ObtenerParametroPrecioCompra()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_PRECIO_COMPRA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroBetaSUNAT()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_SISTEMA_BETA_SUNAT;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroIdMonedaBoletaZofra()
  {
    $data['IdParametroSistema'] = PARAMETRO_ID_MONEDA_BOLETA_ZOFRA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCantidadImpresion()
  {
    $data['IdParametroSistema'] = PARAMETRO_ID_CANTIDAD_IMPRESION;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCostoOPrecioPromedio()
  {
    $data['IdParametroSistema'] = PARAMETRO_COSTO_O_PRECIO_PROMEDIO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroMarcaVenta()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_MARCA_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroVentaStockNegativo()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_VENTA_STOCK_NEGATIVO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroProductoDuplicado()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_PRODUCTO_DUPLICADO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroAvanzadoListaPrecios()
  {
    $data['IdParametroSistema'] = PARAMETRO_LISTA_PRECIO_AVANZADO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroGuardarClienteVenta()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_GUARDAR_CLIENTE_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroGuardarProductoVenta()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_GUARDAR_PRODUCTO_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroSieteDiasSunat()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_SIETE_DIAS_SUNAT;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCalcularCantidad()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_CALCULAR_CANTIDAD;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroClienteSinRuc()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_CLIENTE_SIN_RUC;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametrosComprobantesAutomaticos()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_COMPROBANTE_AUTOMATICO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametrosMaxComprobantesAutomaticos()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_MAX_COMPROBANTE_AUTOMATICO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCantidadComprobanteLibreSerie()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_CANTIDAD_COMPROBANTE_LIBRE_SERIE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroMostrarAfiliacionTarjetaSiete()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_AFILIACION_TARJETA_SIETE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroPesoChileno()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_PESO_CHILENO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroPorcentajePorFactura()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_PORCENTAJE_POR_FACTURA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroRestaurante()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_RESTAURANTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  // function ObtenerParametroServidorCliente()
  // {
  //   $data['IdParametroSistema']= ID_PARAMETRO_SERVIDOR_CLIENTE;
  //   $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
  //
  //   if(is_string($resultado))
  //   {
  //     return $resultado;
  //   }
  //   else
  //   {
  //     $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
  //     return $ValorParametroSistema;
  //   }
  // }

  /**PARA ENVIO SUNAT BETA U PRODUCCION | SUNAT U OSE */
  function ObtenerParametroEnvioSunatBeta()
  {
    $data['IdParametroSistema'] = ID_URL_WSDL_SUNAT;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroEnvioSunatOSE()
  {
    $data['IdParametroSistema'] = ID_URL_WSDL_OSE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroHoraReporte()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_HORA_REPORTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCalculoIGVDesdeTotal()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_CALCULO_IGV_DESDE_TOTAL;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroTipoDocumentoDuaAlternativo()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_TIPO_DOCUMENTO_DUA_ALTERNATIVO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroOrdenPedidoDua()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_ORDEN_PEDIDO_DUA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroFacturacionElectronica()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_FACTURACION_ELECTRONICA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCajaSaldoNegativo()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_CAJA_SALDO_NEGATIVO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroZona()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_ZONA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroBonificacion()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_BONIFICACION;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroNumeroDiasExpiracionCertificado()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_NUMERO_DIAS_EXPIRACION_CERTIFICADO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroRubroLubricante()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_RUBRO_LUBRICANTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroRubroTransporte()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_RUBRO_TRANSPORTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroRubroRepuesto()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_RUBRO_REPUESTO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroRubroClinica()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_RUBRO_CLINICA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroProforma()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_PROFORMA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCodigoBarraAutomatico()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_CODIGO_BARRA_AUTOMATICO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroFormaCalculoVenta()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_FORMA_CALCULO_VENTA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroObservacionGuia()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_OBSERVACION_GUIA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroTipoCambioBusquedaAvanzadaProducto()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_TIPO_CAMBIO_BUSQUEDA_AVANZADA_PRODUCTO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroMargenUtilidadBusquedaAvanzadaProducto()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_MARGEN_UTILIDAD_BUSQUEDA_AVANZADA_PRODUCTO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroDireccionCliente()
  {
    $data['IdParametroSistema'] = ID_PARAMETRO_DIRECCION_CLIENTE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroComisionMetaVentaProducto() {
    $data['IdParametroSistema'] = ID_PARAMETRO_COMISION_META_VENTA_PRODUCTO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  
  function ObtenerParametroSauna() {
    $data['IdParametroSistema'] = ID_PARAMETRO_SAUNA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroTransporteMercancia() {
    $data['IdParametroSistema'] = ID_PARAMETRO_TRANSPORTE_MERCANCIA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroMostrarCampoMontoRecibido() {
    $data['IdParametroSistema'] = ID_PARAMETRO_MOSTRAR_CAMPO_MONTO_RECIBIDO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCarpetaURLCPE() {
    $data['IdParametroSistema'] =   ID_PARAMETRO_CARPETA_URL_CPE;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  
  function ObtenerParametroNumeroMaximoClientesRankingClientesMayoresVentas() {
    $data['IdParametroSistema'] =   ID_PARAMETRO_NUMERO_MAXIMO_CLIENTES_RANKING_CLIENTES_MAYORES_VENTAS;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }  

  function ObtenerParametroSeleccionUnaProformaVentas() {
    $data['IdParametroSistema'] =   ID_PARAMETRO_SELECCION_UNA_PROFORMA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }  

  function ObtenerParametroAplicaPrecioEspecial() {
    $data['IdParametroSistema'] =     ID_PARAMETRO_APLICA_PRECIO_ESPECIAL;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }  

  function ObtenerParametroAplicarOrdenCorrelatividad() {
    $data['IdParametroSistema'] =     ID_PARAMETRO_APLICA_ORDEN_CORRELATIVIDAD;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  } 
  
  function ObtenerParametroCalculoPrecioSolesDolares() {
    $data['IdParametroSistema'] =     ID_PARAMETRO_CALCULOLO_PRECIO_SOLES_DOLARES;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  } 

  function ObtenerParametroModificarUnidadMedida() {
    $data['IdParametroSistema'] =     ID_PARAMETRO_MODIFICAR_UNIDAD_MEDIDA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  } 
  function ObtenerParametroNumeroDetraccionBancoNacion() {
    $data['NombreParametroSistema'] = "ParametroNumeroDetraccionBancoNacion";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCodigoBienProductoDetraccionSUNAT() {
    $data['NombreParametroSistema'] = "ParametroCodigoBienProductoDetraccionSUNAT";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroPorcentajeDetraccion() {
    $data['NombreParametroSistema'] = "ParametroPorcentajeDetraccion";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCantidadDecimalesVentaCampoCantidad() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesVentaCampoCantidad";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

function ObtenerParametroCantidadDecimalesVentaCampoPrecioUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesVentaCampoPrecioUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCantidadDecimalesVentaCampoDescuentoUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesVentaCampoDescuentoUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCantidadDecimalesVentaCampoSubTotal() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesVentaCampoSubTotal";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCantidadDecimalesVentaCampoDescuentoValorUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesVentaCampoDescuentoValorUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  function ObtenerParametroCantidadDecimalesCompraCampoCantidad() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesCompraCampoCantidad";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesCompraCampoCostoUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesCompraCampoCostoUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesCompraCampoPrecioUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesCompraCampoPrecioUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesCompraCampoSubTotal() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesCompraCampoSubTotal";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesCompraCampoDescuentoUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesCompraCampoDescuentoUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesCompraCampoCostoUnitarioCalculado() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesCompraCampoCostoUnitarioCalculado";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesGastoCampoCostoUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesGastoCampoCostoUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesGastoCampoPrecioUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesGastoCampoPrecioUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesGastoCampoCantidad() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesGastoCampoCantidad";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesCostoAgregadoCampoPrecioUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesCostoAgregadoCampoPrecioUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesCostoAgregadoCampoCostoUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesCostoAgregadoCampoCostoUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesCostoAgregadoCampoCantidad() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesCostoAgregadoCampoCantidad";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesCostoAgregadoCampoCostoUnitarioCalculado() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesCostoAgregadoCampoCostoUnitarioCalculado";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesNotaCreditoCompraCampoDescuentoUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesNotaCreditoCompraCampoDescuentoUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesGuiaRemisionRemitenteCampoCantidad() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesGuiaRemisionRemitenteCampoCantidad";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesGuiaRemisionRemitenteCampoPeso() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesGuiaRemisionRemitenteCampoPeso";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesGuiaRemisionRemitenteCampoPendiente() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesGuiaRemisionRemitenteCampoPendiente";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCantidadDecimalesInventarioCampoValorUnitario() {
    $data['NombreParametroSistema'] = "ParametroCantidadDecimalesInventarioCampoValorUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroCarpetaImagenes() {
    $data['NombreParametroSistema'] = "ParametroCarpetaImagenes";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCarpetaCliente() {
    $data['NombreParametroSistema'] = "ParametroCarpetaCliente";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCarpetaProveedor() {
    $data['NombreParametroSistema'] = "ParametroCarpetaProveedor";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCarpetaTransportista() {
    $data['NombreParametroSistema'] = "ParametroCarpetaTransportista";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCarpetaMercaderia() {
    $data['NombreParametroSistema'] = "ParametroCarpetaMercaderia";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCarpetaEmpresa() {
    $data['NombreParametroSistema'] = "ParametroCarpetaEmpresa";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCarpetaEmpleado() {
    $data['NombreParametroSistema'] = "ParametroCarpetaEmpleado";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroSubCarpetaCodigoBarras() {
    $data['NombreParametroSistema'] = "ParametroSubCarpetaCodigoBarras";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroCarpetaServicio() {
    $data['NombreParametroSistema'] = "ParametroCarpetaServicio";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonMercaderias() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonMercaderias";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlRutaProductos() {
    $data['NombreParametroSistema'] = "ParametroUrlRutaProductos";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonServicios() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonServicios";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonActivosFijos() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonActivosFijos";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonOtrasVentas() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonOtrasVentas";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonCostosAgregados() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonCostosAgregados";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonGastos() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonGastos";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonEmpleados() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonEmpleados";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonClientes() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonClientes";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonProveedores() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonProveedores";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonTransportistas() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonTransportistas";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonRadioTaxi() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonRadioTaxi";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroUrlJsonVehiculos() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonVehiculos";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonUsuarios() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonUsuarios";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonPendienteCobranzaCliente() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonPendienteCobranzaCliente";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroUrlJsonComprobanteVenta() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonComprobanteVenta";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroUrlJsonProforma() {
    $data['NombreParametroSistema'] = "ParametroUrlJsonProforma";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroNumeroItemsBusquedaJsonImagenes() {
    $data['NombreParametroSistema'] = "ParametroNumeroItemsBusquedaJsonImagenes";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroTamanioSerieCompra() {
    $data['NombreParametroSistema'] = "ParametroTamanoSerieCompra";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroValorIgv() {
    $data['NombreParametroSistema'] = "ParametroValorIgv";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroMaximoDigitosDni() {
    $data['NombreParametroSistema'] = "ParametroMaximoDigitosDni";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroMaximoDigitosRuc() {
    $data['NombreParametroSistema'] = "ParametroMaximoDigitosRuc";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroNumeroDecimalesVenta() {
    $data['NombreParametroSistema'] = "ParametroNumeroDecimalesVenta";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroNumeroDecimalesValorUnitarioVenta() {
    $data['NombreParametroSistema'] = "ParametroNumeroDecimalesValorUnitarioVenta";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroNumeroDecimalesCompra() {
    $data['NombreParametroSistema'] = "ParametroNumeroDecimalesCompra";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroNumeroDecimalesCostoUnitario() {
    $data['NombreParametroSistema'] = "ParametroNumeroDecimalesCostoUnitario";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroUsoTableroCubitaje() {
    $data['NombreParametroSistema'] = "ParametroUsoTableroCubitaje";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
  
  function ObtenerParametroVerDatosControlSuscripcion() {
    $data['NombreParametroSistema'] = "ParametroVerDatosControlSuscripcion";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerRutaReporteControlClientesPorSuscripcion() {
    $data['NombreParametroSistema'] = "ParametroRutaReporteControlClientesPorSuscripcion";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
           
  function ObtenerParametroFilasPorPaginaCPEsPendientesPublicacionWeb() {
    $data['NombreParametroSistema'] = "ParametroFilasPorPaginaCPEsPendientesPublicacionWeb";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroTiempoFrecuenciaEjecucionTareasAutomaticas() {
    $data['NombreParametroSistema'] = "ParametroTiempoFrecuenciaEjecucionTareasAutomaticas";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroRetencionIGV() {
    $data['NombreParametroSistema'] = "ParametroRetencionIGV";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroPorcentajeRetencionIGV() {
    $data['NombreParametroSistema'] = "ParametroPorcentajeRetencionIGV";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroDetraccion() {
    $data['NombreParametroSistema'] = "ParametroDetraccion";
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorNombreParametroSistema($data);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerParametroHoraConsultaVenta() {
  $data['IdParametroSistema'] = ID_PARAMETRO_HORA_CONSULTA_VENTA;
  $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

  if (is_string($resultado)) {
    return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
      }
  }

  function ObtenerParametroURLServidor() {    
		$resultado=$this->sParametroSistema->ObtenerValorParametroSistemaPorNombreParametroSistema("BASE_URL_SERVIDOR");

    if($resultado =="") {
      $nuevoParametro = 
      array("NombreParametroSistema"=>"BASE_URL_SERVIDOR",
      "ValorParametroSistema"=>"http://www.meganperu.com/".APP_NAME."/index.php/",
      "IdEntidadSistema"=>7,
      "IdGrupoParametro"=>ID_PARAMETRO_CONFIGURACION_VENTA);
      
      $dataParametroSistema = $this->sParametroSistema->Nuevo($nuevoParametro);

      $this->sParametroSistema->InsertarParametroSistema($dataParametroSistema);
    }

    return $resultado;    
  }   
}

ModeloComprobanteVenta = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showComprobanteVenta = ko.observable(false);
  self.SeriesDocumentoFiltrado = ko.observable([]);
  self.IndicadorCambioMontoRecibido = ko.observable(false);
  self.TituloComprobante = ko.observable("");
  self._IdCliente = ko.observable("");
  self.IndicadorEstadoPreVenta = ko.observable("");
  self.MontoDebe = ko.observable("0.00");

  self.CasilleroSeleccionado = {};

  self.ShowCasillerosPorGenero = ko.observable(false);
  self.showCuotasPago = ko.observable(false);

  if (self.hasOwnProperty('TipoVentaDefecto')) {
    self.TipoVenta = ko.observable(String(self.TipoVentaDefecto()));
  }

  var $form = $(self.Options.IDForm);

  self.InicializarModelo = function (event) {
    if (event) {
      self.CalcularTotales(event);
    }
  }

  self.NuevoComprobanteVenta = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesComprobanteVenta" });
      self.ComprobanteVentaInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.Direccion("");
      
      switch (String(self.IdTipoDocumento())) {
        case ID_TIPO_DOCUMENTO_FACTURA:
          self.titulo = "Emisión de Comprobante de Venta";
          break;
        case ID_TIPO_DOCUMENTO_BOLETA:
          self.titulo = "Emisión de Boleta";
          break;
        case ID_TIPO_DOCUMENTO_ORDEN_PEDIDO:
          self.titulo = "Emisión de Orden de Pedido";
          break;
        case ID_TIPO_DOCUMENTO_COMANDA:
          self.titulo = "Registro de Comanda";
          break;
        case ID_TIPO_DOCUMENTO_PROFORMA:
          self.titulo = "Registro de Proforma";
          break;
        default:
          self.titulo = "Emisión de Comprobante de Venta";
      }
    }
  }

  self.EditarComprobanteVenta = function (data, event) {
    if (event) {
      self.EstaProcesado(false);

      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      // self.Direccion(copia.Direccion());
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesComprobanteVenta" });

      self.ComprobanteVentaInicial = ko.mapping.toJS(data, mappingfinal);

      self.opcionProceso(opcionProceso.Edicion);
      switch (String(self.IdTipoDocumento())) {
        case ID_TIPO_DOCUMENTO_FACTURA:
          self.titulo = "Edición de Comprobante de Venta";
          break;
        case ID_TIPO_DOCUMENTO_BOLETA:
          self.titulo = "Edición de Boleta";
          break;
        case ID_TIPO_DOCUMENTO_ORDEN_PEDIDO:
          self.titulo = "Edición de Orden de Pedido";
          break;
        case ID_TIPO_DOCUMENTO_COMANDA:
          self.titulo = "Registro de Comanda";
          break;
        case ID_TIPO_DOCUMENTO_PROFORMA:
          self.titulo = "Edición de Proforma";
          break;
        default:
          self.titulo = "Edición de Comprobante de Venta";
      }
    }
  }

  self.CalcularSumatoriaICBP = function (data, event) {
    if (event) {
      var total = 0;
      ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
        if (item.IdProducto() != null && item.IdProducto() != "") {
          total = total + parseFloatAvanzado(item.SumatoriaICBP());
        }
      });
      self.ICBPER(total.toFixed(2));
    }
  }


  self.RecalcularICBPERDetalle = function (data, event) {
    if (event) {
      ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
        if (item.IdProducto() != null && item.IdProducto() != "") {
          item.CalcularICBPER(data, event);
        }
      });
      self.CalcularSumatoriaICBP(data, event)
    }
  }
  
  self.CalcularTotalOperacionGravada = function () {
    var total = 0;
    ko.utils.arrayForEach(self.DetallesComprobanteVenta(), function (item) {
      if (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_GRAVADO) total += parseFloatAvanzado(item.ValorVentaItem());
    });
    return parseFloatAvanzado(total.toFixed(NUMERO_DECIMALES_VENTA));
  }

  self.CalcularTotalOperacionNetoGravada = function () {
    var total = 0;
    ko.utils.arrayForEach(self.DetallesComprobanteVenta(), function (item) {
      if (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_GRAVADO) total += parseFloatAvanzado(item.ValorVentaNetoItem());
    });
    return parseFloatAvanzado(total.toFixed(NUMERO_DECIMALES_VENTA));
  }

  self.CalcularTotalOperacionNoGravada = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_EXONERADO) total += parseFloatAvanzado(item.ValorVentaItem());
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularTotalOperacionNetoNoGravada = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_EXONERADO) total += parseFloatAvanzado(item.ValorVentaNetoItem());
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularTotalOperacionInafecto = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_INAFECTO) total += parseFloatAvanzado(item.ValorVentaItem());
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularTotalOperacionNetoInafecto = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_INAFECTO) total += parseFloatAvanzado(item.ValorVentaNetoItem());
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularDescuentoTotalValorItem = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.IdProducto() != null && item.IdProducto() != "") total += parseFloatAvanzado(item.DescuentoValorItem());
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularDescTotalValorItemNoGrabado = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if ((item.IdProducto() != null && item.IdProducto() != "") && (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_INAFECTO || item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_EXONERADO)) {
        total += parseFloatAvanzado(item.DescuentoValorItem());
      } 
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularDescuentoTotalItem = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.IdProducto() != null && item.IdProducto() != "") total += parseFloatAvanzado(item.DescuentoItem());
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularIGV = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.IdProducto() != null && item.IdProducto() != "") total += parseFloatAvanzado(item.IGVItem());
    });
    return parseFloatAvanzado(total.toFixed(2));
  }
  
  self.CalcularTotal = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.IdProducto() != null && item.IdProducto() != "") total += parseFloatAvanzado(item.SubTotal());
    });

    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularTotalIgvPorItem = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.IdProducto() != "" && item.IdProducto() != null) {
        total = total + parseFloatAvanzado(item.IGVItem());
      }
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularDescuentoGlobal = function () {
    return;
  }

  self.CalcularMontoIGV = function (event) {
    if (event) {
      var monto = parseFloatAvanzado(base.ValorVentaGravado()) * parseFloatAvanzado(base.TasaIGV());
      return parseFloatAvanzado(monto.toFixed(2));
    }
  }

  self.CalcularTotalDescuento = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.CalcularDescuentoItem() != "") {
        total += parseFloatAvanzado(item.CalcularDescuentoItem());
      }
    });
    return total.toFixed(2);
  }

  self.CalcularValorVentaOperacionGratuita = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.IdProducto() != null && item.IdProducto() != "") {
        total += parseFloatAvanzado(item.ValorReferencialItem());
      }
    });
    return total.toFixed(2);
  }

  self.CalcularIGVOperacionGratuita = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteVenta(), function (item) {
      if (item.IdProducto() != null && item.IdProducto() != "") {
        total += parseFloatAvanzado(item.IGVReferencialItem());
      }
    });
    return total.toFixed(2);
  }
  
  self.CalcularTotalConEnvioGestion = function () {
    if (base) {
      var resultado = "";
      resultado = parseFloatAvanzado(self.MontoEnvioGestion()) + parseFloatAvanzado(base.CalculoTotalVenta());
      return resultado.toFixed(2);
    }
  }

  self.CalcularTotalACuenta = function (event) {
    if (event) {
      if (self.ParametroCampoACuenta() == 1) {
        if (self.IdFormaPago() == ID_FORMA_PAGO_CONTADO) {
          if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
            if (self.CalcularMontoTotalACuenta() == true) {
              self.MontoACuenta(parseFloatAvanzado(self.MontoACuenta()) > 0 ? self.MontoACuenta() : self.CalcularTotalConEnvioGestion());
            }
          } else {
            if (self.CalcularMontoTotalACuenta() == true) {
              self.MontoACuenta(parseFloatAvanzado(self.CalculoTotalVenta()));
            }
          }
        }
      }
    }
  }

  self.CalcularTotalPendientePago = function (event) {
    if (event) {
      self.MontoPendientePago(parseFloatAvanzado(self.CalculoTotalVenta()));
    }
  }

  self.CalcularTotales = function (event) {
    if (event) {
      self.CalculoTotales(event);

      // if (self.ParametroCalculoIGVDesdeTotal() == '0') {
      //   self.CalculoDeTotalTradicional(data, event);
      // } else {
      //   self.CalculoIGVDesdeTotal(data, event);
      // }
      self.ValorVentaOperacionGratuita(self.CalcularValorVentaOperacionGratuita());
      self.IGVOperacionGratuita(self.CalcularIGVOperacionGratuita());
      self.ValorVentaTotal(self.CalcularValorVentaTotal(event));
      
    }
  }

  self.CalcularValorVentaTotal = function (event) {
    if (event) {
      var valorVentaGravado = parseFloatAvanzado(self.ValorVentaGravado());
      var valorVentaNoGravado  = parseFloatAvanzado(self.ValorVentaNoGravado());
      var valorVentaInafecto = parseFloatAvanzado(self.ValorVentaInafecto()); 
      var descuentoTotalValorItem = parseFloatAvanzado(self.DescuentoTotalValorItem()); 
      var total = (valorVentaGravado + valorVentaNoGravado + valorVentaInafecto) - descuentoTotalValorItem;
      return total.toFixed(NUMERO_DECIMALES_VENTA);
    }
  }

  self.CalculoDeTotalTradicional = function (data, event) {
    if (event) {
      var tasaigv = parseFloatAvanzado(VALOR_IGV);

      var valorventainfecto = self.CalcularTotalOperacionInafecto();
      base.ValorVentaInafecto(valorventainfecto);

      var valorventanogravado = self.CalcularTotalOperacionNoGravada();
      base.ValorVentaNoGravado(valorventanogravado);

      var total = self.CalcularTotal();
      var totalmanual = total - (valorventanogravado + valorventainfecto);
      var valorventagravado = (totalmanual / (1 + tasaigv)).toFixed(NUMERO_DECIMALES_VENTA);//self.CalcularTotalOperacionGravada();
      var igvmanual = (totalmanual - valorventagravado).toFixed(NUMERO_DECIMALES_VENTA);//self.CalcularMontoIGV();

      var icbper = parseFloatAvanzado(base.ICBPER());

      base.Total(total + icbper);

      if (valorventagravado > 0) {
        base.IGV(igvmanual);
        base.ValorVentaGravado(valorventagravado);
      } else {
        base.IGV(0);
        base.ValorVentaGravado(0);
      }

      self.TotalConEnvioGestion(self.CalcularTotalConEnvioGestion());
      self.OnChangeMontoRecibido(data, event)
    }
  }


  self.CalculoTotales = function (event) {
    if (event) {
      var valorventainfecto = parseFloatAvanzado(self.CalcularTotalOperacionInafecto());
      base.ValorVentaInafecto(valorventainfecto);

      var valorventanetoinfecto = parseFloatAvanzado(self.CalcularTotalOperacionNetoInafecto());
      base.ValorVentaNetoInafecto(valorventanetoinfecto);

      var valorventanogravado = parseFloatAvanzado(self.CalcularTotalOperacionNoGravada());
      base.ValorVentaNoGravado(valorventanogravado);

      var valorventanonetogravado = parseFloatAvanzado(self.CalcularTotalOperacionNetoNoGravada());
      base.ValorVentaNetoNoGravado(valorventanonetogravado);

      var valorVentaGravado = parseFloatAvanzado(self.CalcularTotalOperacionGravada());
      base.ValorVentaGravado(valorVentaGravado);

      var valorVentaNetoGravado = parseFloatAvanzado(self.CalcularTotalOperacionNetoGravada());
      base.ValorVentaNetoGravado(valorVentaNetoGravado);

      var descuentoTotalValorItem = parseFloatAvanzado(self.CalcularDescuentoTotalValorItem())
      base.DescuentoTotalValorItem(descuentoTotalValorItem);
      
      var descTotalValorItemNoGrabado = parseFloatAvanzado(self.CalcularDescTotalValorItemNoGrabado())
      base.DescTotalValorItemNoGrabado(descTotalValorItemNoGrabado);
      
      var descuentoTotalItem = parseFloatAvanzado(self.CalcularDescuentoTotalItem())
      base.DescuentoTotalItem(descuentoTotalItem);

      var igv = parseFloatAvanzado(self.CalcularIGV())
      base.IGV(igv);

      var total = parseFloatAvanzado(self.CalcularTotal())

      var icbper = parseFloatAvanzado(self.ICBPER());

      var total = (total + icbper);
      base.Total(total.toFixed(NUMERO_DECIMALES_VENTA));
    }
  }

  self.AgregarDiferenciaIGV = function (event) {    
    if (event) {
      if (base.IGV() > 0) {
        
        var numeroitem = base.DetallesComprobanteVenta().length - 1;
        var condicionitem = true;
        while (condicionitem) {
          var ultimoitem = base.DetallesComprobanteVenta()[numeroitem];

          if (ultimoitem.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_GRAVADO) {
            var descuentoglobal =parseFloatAvanzado(base.DescuentoGlobal());
            var tasaigv = parseFloatAvanzado(base.TasaIGV());
            var igvDesdeDescuentoTotal = (descuentoglobal/(1+ tasaigv)) * tasaigv;
            var igvdsctoglobal = parseFloatAvanzado(igvDesdeDescuentoTotal.toFixed(NUMERO_DECIMALES_VENTA));
            var baseigv = parseFloatAvanzado(base.IGV());
            var totalcalculoigvitem = parseFloatAvanzado(parseFloatAvanzado(self.CalcularTotalIgvPorItem()).toFixed(NUMERO_DECIMALES_VENTA));
            var diferenciaigv = baseigv + igvdsctoglobal - totalcalculoigvitem;//var diferenciaigv = (parseFloatAvanzado(base.IGV()) - self.CalcularTotalIgvPorItem()).toFixed(NUMERO_DECIMALES_VENTA);

            ultimoitem.IGVItem(parseFloatAvanzado(ultimoitem.IGVItem()) + parseFloatAvanzado(diferenciaigv));
            ultimoitem.ValorVentaItem(parseFloatAvanzado(ultimoitem.ValorVentaItem()) - parseFloatAvanzado(diferenciaigv));
            condicionitem = false;
            break;
          } else {
            numeroitem = numeroitem - 1;
            if (numeroitem < 0) {
              condicionitem = false;
              break;
            }
          }
        }
      }
    }
  }

  self.GuardarComprobanteVenta = function (event, callback) {
    if (event) {
      self.CalcularTotales(event);
      if (self.ParametroCalculoIGVDesdeTotal() == 0) { self.AgregarDiferenciaIGV(event); }

      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: ["DetallesComprobanteVenta","CuotasPagoClienteComprobanteVenta"]});
      var copia_objeto = ko.mapping.toJS(base, mappingfinal);
      copia_objeto.IdCaja = self.ParametroCaja() == 0 ? null : copia_objeto.IdCaja;
      var datajs = { Data: JSON.stringify(copia_objeto) };


      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Insertar(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            ko.mapping.fromJS($data, MappingVenta, self);
            if (self.IdFormaPago() == ID_FORMA_PAGO_CONTADO) {
              self.FechaVencimiento("");
            }

            self.mensaje = "Se registró el comprobante  " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.\n";
            self.mensaje = self.mensaje + " ¿Desea imprimir el documento?\n";
            self.mensaje = self.mensaje.replace(/\n/g, "<br />");
            callback($data, $event);
          }
        });
      }
      else {
        self.Actualizar(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            self.mensaje = "Se actualizó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.\n";
            self.mensaje = self.mensaje + " ¿Desea imprimir el documento?\n";
            self.mensaje = self.mensaje.replace(/\n/g, "<br />");
            callback($data, $event);
          }
        });
      }
    }
  }

  self.Insertar = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Venta/cVenta/InsertarVenta',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.InsertarClienteEnVenta = function (data, event, callback) {
    data = { "Data": data };
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cCliente/InsertarClienteVenta',
        success: function (data) {
          if (data["error"] != undefined) {
            $("#loader").hide();
            alertify.alert(data["error"]["msg"]);
          } else {
            callback(data, event);
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert(jqXHR.responseText);
        }
      });
    }
  }

  self.Actualizar = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Venta/cVenta/ActualizarVenta',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }
  self.AnularComprobanteVenta = function (data, event, callback) {
    if (event) {
      self.opcionProceso(opcionProceso.Anulacion);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesComprobanteVenta" });
      var objeto = ko.mapping.toJS(data, mappingfinal);
      var datajs = { Data: JSON.stringify(objeto) };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cVenta/AnularVenta',
        success: function (data) {
          if (data.error) {
            $("#loader").hide();
            alertify.alert(data.error.msg);
          }
          else
            callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert(jqXHR.responseText);
        }
      });
    }
  }

  self.EliminarComprobanteVenta = function (data, event, callback) {
    if (event) {
      var resultado = { data: null, error: "" };
      var objeto = Knockout.CopiarObjeto(data);
      var objeto = ko.mapping.toJS(data, mappingIgnore);
      var datajs = { Data: JSON.stringify(objeto) };
      self.opcionProceso(opcionProceso.Eliminacion);

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cVenta/BorrarVenta',
        success: function (data) {
          callback(data, event);//resultado
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.ValidarEstadoComprobanteVenta = function (data, event) {
    if (event) {
      if (data.IndicadorEstado != undefined) {
        if (data.IndicadorEstado() == "E") return false;
        if ((data.IndicadorEstado() == "A") && (data.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO || data.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO)) return false;
        if (data.IndicadorEstado() == "N") {
          if (self.opcionProceso() == opcionProceso.Anulacion) return false;
          if ((self.opcionProceso() != opcionProceso.Anulacion) && (data.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO || data.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO)) return false;
        }
      }

      return true;
    }
  }

  self.ValidarEstadoComprobanteVenta2 = function (data, event) {
    if (event) {
      if (data.IndicadorEstado != undefined) {
        if (data.IndicadorEstado() == "E") return false;
        if (data.IndicadorEstado() == "A")
          if (data.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO)
            return true;
        if (data.IndicadorEstado() == "N") {
          if (self.opcionProceso() == opcionProceso.Anulacion) return false;
          if ((self.opcionProceso() != opcionProceso.Anulacion) && (data.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO || data.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO)) return false;
        }
      }

      return true;
    }
  }

  if (self.DetallesComprobanteVenta != undefined) {
    self.DetallesComprobanteVenta.Remover = function (data, event) {
      if (event) {
        this.remove(data);
        self.CalcularTotales(event);
        self.TotalItems(self.DetallesComprobanteVenta().length - 1);
        // self.OnBloquearComboAlmacen(event);

      }
    }

    self.DetallesComprobanteVenta.Agregar = function (data, event) {
      if (event) {
        var objeto = null;
        if (data == undefined) {
          objeto = Knockout.CopiarObjeto(base.NuevoDetalleComprobanteVenta);
        }
        else {
          objeto = Knockout.CopiarObjeto(data);
        }

        var resultado = new VistaModeloDetalleComprobanteVenta(objeto, self);

        var idMaximo = 0;

        if (this().length > 0) idMaximo = Math.max.apply(null, ko.utils.arrayMap(this(), function (e) { return e.IdDetalleComprobanteVenta(); }));

        if (resultado.IdDetalleComprobanteVenta() == "" || resultado.IdDetalleComprobanteVenta() == null || resultado.IdDetalleComprobanteVenta() == undefined || resultado.IdDetalleComprobanteVenta() == 0) {
          resultado.IdDetalleComprobanteVenta(idMaximo + 1);
        }

        this.push(resultado);

        self.TotalItems(self.DetallesComprobanteVenta().length - 1);
        // self.OnBloquearComboAlmacen(event);

        self.CalcularTotales(event);
        return resultado;
      }
    }

    self.DetallesComprobanteVenta.Obtener = function (data, event) {
      if (event) {
        var objeto = ko.utils.arrayFirst(this(), function (item) {
          return data == item.IdDetalleComprobanteVenta();
        });

        //if(objeto != null)
        objeto.__ko_mapping__ = undefined;
        return objeto;
      }
    }

  }

  self.GenerarXML = function (data, event, callback) {
    if (event) {
      var datajs = ko.mapping.toJS({ "Data": data });

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/FacturacionElectronica/cComprobanteElectronico/GenerarXMLComprobanteElectronico',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.Imprimir = function (data, event, callback) {
    if (event) {
      var datajs = ko.mapping.toJS({ "Data": data });

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL_BASE + '/Venta/ComprobanteVenta/cComprobanteVenta/ImprimirComprobanteVenta',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.ConsultarDetallesComprobanteVenta = function (data, event, callback) {
    if (event) {
      console.log("ConsultarDetallesComprobanteVenta");
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdComprobanteVenta": $data.IdComprobanteVenta(), "IdTipoVenta": $data.IdTipoVenta(), "IdAsignacionSede": $data.IdAsignacionSede() } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/ComprobanteVenta/cDetalleComprobanteVenta/ConsultarDetallesComprobanteVenta',
        success: function (data) {
          self.DetallesComprobanteVenta([]);
          ko.utils.arrayForEach(data, function (item) {
            self.DetallesComprobanteVenta.Agregar(new VistaModeloDetalleComprobanteVenta(item, base), event);
          });

          callback(self, event);
        }
      });
    }
  }

  self.EnviarMail = function (data, event, callback) {
    if (event) {
      var datajs = ko.mapping.toJS({ "Data": data });

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cVenta/EnviarEmail',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.RUCDNICliente = ko.computed(function () {
    var resultado = "";
    if (self.NumeroDocumentoIdentidad == undefined) {
      resultado = "";
    }
    else {
      if (self.RazonSocial() == "") {
        resultado = "";
      } else if (self.NumeroDocumentoIdentidad() == "") {
        resultado = self.RazonSocial();
      }
      else {
        resultado = self.NumeroDocumentoIdentidad() + ' - ' + self.RazonSocial();
      }
    }

    return resultado;
  }, this);

  self.Numero = ko.computed(function () {
    var resultado = "";
    if (self.NombreAbreviado == undefined) {

    }
    else {
      resultado = self.NombreAbreviado() + ' ' + self.SerieDocumento() + '-' + self.NumeroDocumento();
    }

    return resultado;
  }, this);

  self.TotalComprobante = ko.computed(function () {
    var resultado = "";

    if (self.SimboloMoneda == undefined) {

    }
    else {
      var total = accounting.formatNumber(self.Total(), NUMERO_DECIMALES_VENTA);
      if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_NOTA_CREDITO) {
        resultado = self.SimboloMoneda() + ' -' + total;

      } else {
        resultado = self.SimboloMoneda() + ' ' + total;
      }
    }

    return resultado;
  }, this);

  self.EstadoComprobante = ko.computed(function () {
    var resultado = "";
    if (self.IndicadorEstado == undefined) {

    }
    else {
      if (self.IndicadorEstado() == "A") resultado = "EMITIDO";
      if (self.IndicadorEstado() == "N") resultado = "ANULADO";
      if (self.IndicadorEstado() == "E") resultado = "ELIMINADO";
    }

    return resultado;
  }, this);

  self.ObtenerFilaMercaderiaJSON = function (data, event) {
    if (event) {
      codigo = data.CodigoMercaderia();
      url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      _busqueda = "CodigoMercaderia";
      codigo = codigo.toUpperCase();
      var json = ObtenerJSONCodificadoDesdeURL(url_json);
      var rpta = JSON.search(json, '//*[' + _busqueda + '="' + codigo + '"]');

      if (rpta.length > 0) {
        var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
        var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
        return producto[0];
      } else {
        return null;
      }
    }
  }

  self.ObtenerProductoPorIdProducto = function (data, event) {
    if (event) {
      var idproducto = data.IdProducto();
      var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + idproducto + '.json';
      var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
      return producto[0];
    }
  }


  self.ObtenerFilaClienteJSON = function (data, event) {
    if (event) {
      codigo = data.IdCliente;//data.IdPersona;
      url_json = SERVER_URL + URL_JSON_CLIENTES;
      _busqueda = "IdPersona";

      var json = ObtenerJSONCodificadoDesdeURL(url_json);
      var rpta = JSON.search(json, '//*[' + _busqueda + '="' + codigo + '"]');

      if (rpta.length > 0) {
        return rpta[0];
      } else {
        return null;
      }
    }
  }

  self.ObtenerTipoCambioActual = function (data, event, callback) {
    if (event) {
      var copiaObjeto = ko.mapping.toJS(data, mappingIgnore);
      var datajs = { Data: JSON.stringify(copiaObjeto) };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Configuracion/General/cTipoCambio/ConsultarTipoCambioVentaActual',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.ObtenerNotaSalidaVentaSinDocumento = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(data) };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/NotaSalida/cNotaSalida/ObtenerNotaSalidaVentaSinDocumento',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.GuardarPreVenta = function (event, callback) {
    if (event) {
      self.CalcularTotales(event);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesComprobanteVenta" });
      var copia_objeto = ko.mapping.toJS(base, mappingfinal);
      copia_objeto.IdCaja = self.ParametroCaja() == 0 ? null : copia_objeto.IdCaja;
      var datajs = { Data: JSON.stringify(copia_objeto) };
      if (self.IdComprobanteVenta() == "" || self.IdComprobanteVenta() == null) {
        var url = self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_COMANDA || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO ? "InsertarPreVenta" : "InsertarVentaDesdePreVenta";

        self.InsertarPreVenta(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            ko.mapping.fromJS($data, MappingVenta, self);
            callback($data, $event);
          }
        }, url);
      }
      else {
        var url = self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_COMANDA || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO ? "ActualizarPreVenta" : "ActualizarPreVenta";
        self.ActualizarPreVenta(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            callback($data, $event);
          }
        }, url);
      }
    }
  }

  self.InsertarPreVenta = function (data, event, callback, url) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Venta/cPreVenta/' + url,
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.ActualizarPreVenta = function (data, event, callback, url) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Venta/cPreVenta/' + url,
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.ImprimirComanda = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data)) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL_BASE + '/Venta/cPreVenta/ImprimirComanda',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.ImprimirPreVenta = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data)) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL_BASE + '/Venta/cPreVenta/ImprimirVentaDesdePreVenta',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.EliminarItemComanda = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data)) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL_BASE + '/Venta/cPreVenta/ImprimirItemAnuladoComanda',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.EliminarPreVenta = function (event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(self, mappingIgnore)) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cPreVenta/BorrarPreVenta',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.EliminarVentaDesdePreVenta = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cPreVenta/BorrarVentaDesdePreVenta',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.AnularVentaDesdePreVenta = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cPreVenta/AnularVentaDesdePreVenta',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.CancelarPreCuenta = function (event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(self, mappingIgnore)) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cPreVenta/CancelarPreCuenta',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.ConsultarNumeroDocumentoReniec = function (data, event, callback) {
    if (event) {
      var $data = {};
      $data.NumeroDocumentoIdentidad = data.DniPasajero();
      var datajs = { "Data": $data };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cCliente/ConsultarReniec',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.ConsultarComprobantesVentaReferencia = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: {},
        dataType: "json",
        url: SITE_URL + '/Venta/ComprobanteVenta/cComprobanteVenta/ConsultarComprobantesVentaReferencia',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.GuardarProformaVenta = function (event, callback) {
    if (event) {
      self.CalcularTotales(event);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesComprobanteVenta" });
      var copia_objeto = ko.mapping.toJS(base, mappingfinal);
      copia_objeto.IdCaja = self.ParametroCaja() == 0 ? null : copia_objeto.IdCaja;
      var datajs = { Data: JSON.stringify(copia_objeto) };
      if (self.IdComprobanteVenta() == "" || self.IdComprobanteVenta() == null) {
        self.InsertarProforma(datajs, event, function ($data, $event) {
          if ($data.error) {
            callback($data, $event);
          }
          else {
            ko.mapping.fromJS($data, MappingVenta, self);
            self.mensaje = `Se registró el comprobante ${self.SerieDocumento()} - ${self.NumeroDocumento()} correctamente.<br> ¿Desea imprimir el documento?`
            callback($data, $event);
          }
        });
      }
      else {
        self.ActualizarProforma(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            self.mensaje = `Se actualizó el comprobante ${self.SerieDocumento()} - ${self.NumeroDocumento()} correctamente.<br> ¿Desea imprimir el documento?`
            callback($data, $event);
          }
        });
      }
    }
  }
  self.InsertarProforma = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Venta/cProforma/InsertarProforma',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.ActualizarProforma = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Venta/cProforma/ActualizarProforma',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.AnularProformaVenta = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cProforma/AnularProforma',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.EliminarProformaVenta = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cProforma/BorrarProforma',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.ConsultarComprobantesVentaProforma = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: {},
        dataType: "json",
        url: SITE_URL + '/Venta/ComprobanteVenta/cComprobanteVenta/ConsultarComprobantesVentaProforma',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.ListarCasillerosGenero = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'GET',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cCasilleroGenero/ListarCasillerosGenero',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.LiberarCasilleroGenero = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cCasilleroGenero/LiberarCasilleroGenero',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.EnviarFacturaSunat = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/FacturacionElectronica/cEnvioFactura/EnviarXMLSUNAT',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.GenerarResumen = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/FacturacionElectronica/cResumenDiario/GenerarResumen',
        success: function (data) {
          callback(data, event)
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event)
        },
      });

    }
  }

  self.ConsultarDetallesComprobante = function (data, event, callback) {
    if (event) {
      console.log("ConsultarDetallesComprobante");
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdComprobanteVenta": $data.IdComprobanteVenta(), "IdTipoVenta": $data.IdTipoVenta(), "IdAsignacionSede": $data.IdAsignacionSede() , "IdCorrelativoDocumento" : $data.IdCorrelativoDocumento() } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/ComprobanteVenta/cDetalleComprobanteVenta/ConsultarDetallesComprobanteVenta',
        success: function (data) {
          callback(data, event);
        }
      });
    }
  }

  self.AplicarPrecioEspecialCliente = function (data, event, callback) {
    if (event) {
      var datajs = { Data: ko.mapping.toJS(data, mappingIgnore) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cVenta/AplicarPrecioEspecialCliente',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

}

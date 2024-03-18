ModeloComprobanteCompra = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showComprobanteCompra = ko.observable(false);

  var $form = $(self.Options.IDForm);

  self.InicializarModelo = function (event) {
    if (event) {
      self.CalcularTotales(event);
    }
  }

  self.TipoDocumentoCompra = ko.computed(function () {
    var resultado = self.IdTipoDocumento();
    return resultado;
  }, this);

  self.NuevoComprobanteCompra = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesComprobanteCompra" });
      self.ComprobanteCompraInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo = "Registro de Comprobante de Compra";
    }
  }

  self.EditarComprobanteCompra = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesComprobanteCompra" });
      self.ComprobanteCompraInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo = "Edición de Comprobante de Compra";
    }
  }

  self.EditarComprobanteCompraAlternativo = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = ko.mapping.toJS(data, mappingIgnore);
      copia = ko.mapping.toJS(copia, { ignore: ["DetallesComprobanteCompra"] });
      ko.mapping.fromJS(copia, {}, self);
      self.ComprobanteCompraInicial = ko.mapping.toJS(data, mappingIgnore);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo = "Edición de Comprobante de Compra";
    }
  }

  self.CalcularTotalOperacionGravada = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteCompra(), function (item) {
      if (item.AfectoIGV() == 1) {
        if (base.IndicadorTipoCalculoIGV() == 1) {
          total += parseFloatAvanzado(item.PrecioItem() == null || item.PrecioItem() == "" ? 0 : item.PrecioItem());
        } else {
          total += parseFloatAvanzado(item.CostoItem() == null || item.CostoItem() == "" ? 0 : item.CostoItem());
        }
      }
    });

    if (base.IndicadorTipoCalculoIGV() == 1) {
      total = total - parseFloatAvanzado(self.DescuentoGlobal());
    }

    return parseFloat(total.toFixed(2));
  }

  self.CalcularTotalOperacionNoGravada = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteCompra(), function (item) {
      if (item.AfectoIGV() == '0') {
        if (base.IndicadorTipoCalculoIGV() == 1) {
          total += parseFloatAvanzado(item.PrecioItem() == null || item.PrecioItem() == "" ? 0 : item.PrecioItem());
        } else {
          total += parseFloatAvanzado(item.CostoItem() == null || item.CostoItem() == "" ? 0 : item.CostoItem());
        }
      }
    });
    return parseFloat(total.toFixed(2));
  }

  self.CalcularTotalOperacionInafecto = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteCompra(), function (item) {
      if (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_INAFECTO) {
        if (base.IndicadorTipoCalculoIGV() == 1) {
          total += parseFloatAvanzado(item.PrecioItem());
        } else {
          total += parseFloatAvanzado(item.CostoItem());
        }
      }
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularTotalDecuentoItem = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteCompra(), function (item) {
      if (item.DescuentoItem() !== "" && item.DescuentoItem() !== null) {
        total += parseFloatAvanzado(item.DescuentoItem());
      }
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularIgvItem = function () {
    var igv = 0;
    ko.utils.arrayForEach(base.DetallesComprobanteCompra(), function (item) {
      if (item.CostoItem() != null) {
        igv = item.CostoItem() * base.TasaIGV();
        item.IGVItem(igv);
      }
    });
  }

  self.CalcularMontoIGV = function () {
    var gravado = parseFloatAvanzado(self.CalcularTotalOperacionGravada());
    var tasaIGV = parseFloatAvanzado(base.TasaIGV());
    var descuentoGlobal = parseFloatAvanzado(self.DescuentoGlobal());

    if (base.IndicadorTipoCalculoIGV() == 1) {
      var monto = (gravado / (1 + tasaIGV)) * tasaIGV;
    } else {
      var monto = (gravado - descuentoGlobal) * tasaIGV;
    }
    return parseFloat(monto.toFixed(2));
  }

  self.CalcularTotal = function () {
    var total = 0;
    var opGravado = parseFloatAvanzado(base.ValorCompraGravado());
    var opNoGravado = parseFloatAvanzado(base.ValorCompraNoGravado());
    var opNoInafecto = parseFloatAvanzado(base.ValorCompraInafecto());
    var igv = parseFloatAvanzado(base.IGV());
    var descuentoGlobal = parseFloatAvanzado(base.DescuentoGlobal());
    var baseimponible = 0;

    if (base.IndicadorTipoCalculoIGV() == 0) {
      baseimponible = opGravado + opNoGravado + opNoInafecto;
      subtotal = (baseimponible - descuentoGlobal);
      total = subtotal + igv;
    } else {
      total = opGravado + opNoGravado + opNoInafecto + igv;
    }

    return parseFloat(total.toFixed(2));
  }

  self.CalcularTotalACuenta = function (event) {
    if (event) {
      if (self.ParametroCampoACuenta() == 1) {
        if (self.IdFormaPago() == ID_FORMA_PAGO_CONTADO) {
          if (self.CalcularMontoTotalACuenta() == true) {
            self.MontoACuenta(self.CalcularTotal());
          }
        }
      }
    }
  }

  self.CalcularTotales = function (event) {
    if (event) {
      var valorcompranogravado = self.CalcularTotalOperacionNoGravada();
      base.ValorCompraNoGravado(valorcompranogravado);

      var valorventainfecto = self.CalcularTotalOperacionInafecto();
      base.ValorCompraInafecto(valorventainfecto);

      var igv = self.CalcularMontoIGV();
      base.IGV(igv);

      var valorcompragravado = parseFloatAvanzado(self.CalcularTotalOperacionGravada());
      if (base.IndicadorTipoCalculoIGV() == 1) {
        base.ValorCompraGravado(valorcompragravado - parseFloatAvanzado(igv));
      } else {
        base.ValorCompraGravado(valorcompragravado);
      }
      var descuentoGlobal = self.CalcularTotalDecuentoItem();
      base.DescuentoGlobal(descuentoGlobal);

      var total = self.CalcularTotal();
      base.Total(total);
    }
  }

  self.GuardarComprobanteCompraAlternativo = function (event, callback) {
    if (event) {
      // var _mappingIgnore  =ko.toJS(mappingIgnore);
      // var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesComprobanteCompra" });
      var copia_objeto = ko.mapping.toJS(base, mappingIgnore);
      var datajs = { Data: JSON.stringify(copia_objeto) };

      self.ActualizarAlternativo(datajs, event, function ($data, $event) {
        if ($data.error)
          callback($data, $event);
        else {
          ko.mapping.fromJS($data, {}, self);
          self.mensaje = "Se actualizó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.\n";
          callback($data, $event);
        }
      });
    }
  }

  self.ActualizarAlternativo = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Compra/cCompra/ActualizarCompraAlternativo',
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

  self.GuardarComprobanteCompra = function (event, callback) {
    if (event) {
      self.CalcularTotales(event);
      var detraccion = (base.CheckDetraccion() == true) ? DETRACCION.CON_DETACCION : DETRACCION.SIN_DETRACCION;
      base.IndicadorDetraccion(detraccion);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesComprobanteCompra" });
      var copia_objeto = ko.mapping.toJS(base, mappingfinal);
      var datajs = { Data: JSON.stringify(copia_objeto) };

      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Insertar(datajs, event, function ($data, $event) {

          if ($data.error)
            callback($data, $event);
          else {
            delete $data.DetallesComprobanteCompra;
            ko.mapping.fromJS($data, MappingCompra, self);
            self.mensaje = "Se registró el comprobante  " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.\n";
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
            delete $data.DetallesComprobanteCompra;
            ko.mapping.fromJS($data, {}, self);
            self.mensaje = "Se actualizó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.\n";
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
        url: SITE_URL + '/Compra/cCompra/InsertarCompra',
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

  self.Actualizar = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Compra/cCompra/ActualizarCompra',
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

  self.AnularComprobanteCompra = function (data, event, callback) {
    if (event) {
      self.opcionProceso(opcionProceso.Anulacion);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesComprobanteCompra" });
      var datajs = ko.mapping.toJS({ "Data": data }, mappingfinal);

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Compra/ComprobanteCompra/cComprobanteCompra/AnularComprobanteCompra',
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

  self.EliminarComprobanteCompra = function (data, event, callback) {
    if (event) {
      var resultado = { data: null, error: "" };
      var objeto = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS(data, mappingIgnore);
      datajs = { Data: JSON.stringify(datajs) };
      self.opcionProceso(opcionProceso.Eliminacion);

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Compra/cCompra/BorrarCompra',
        success: function (data) {
          if (data.error == "")
            resultado.data = data.resultado;
          else
            resultado.error = data.error;

          callback(resultado, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          resultado.error = jqXHR.responseText;
          callback(resultado, event);
        }
      });
    }
  }

  self.ValidarEstadoComprobanteCompra = function (data, event) {
    if (event) {
      if (data.IndicadorEstado != undefined) {
        if (data.IndicadorEstado() == "E") return false;
        if (data.IndicadorEstado() == "A") return false;
        // if((data.IndicadorEstado() == "A") && (data.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO || data.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO)) return false;
        // if(data.IndicadorEstado() == "N") {
        //    if(self.opcionProceso() == opcionProceso.Anulacion) return false;
        //    if((self.opcionProceso() != opcionProceso.Anulacion) &&  (data.IndicadorEstadoCPE()==ESTADO_CPE.ACEPTADO || data.IndicadorEstadoCPE()==ESTADO_CPE.RECHAZADO)) return false;
        // }
      }

      return true;
    }
  }

  self.BorrarDetallesComprobanteCompra = function (data, event) {
    if (event) {
      self.DetallesComprobanteCompra([]);
      var ultimaFila = self.DetallesComprobanteCompra.Agregar(undefined, event);
      ultimaFila.InicializarVistaModelo(event);
      $(ultimaFila.InputOpcion()).hide();
      $(ultimaFila.OpcionMercaderia()).hide();
    }
  }

  if (self.DetallesComprobanteCompra != undefined) {
    self.DetallesComprobanteCompra.Remover = function (data, event) {
      if (event) {
        this.remove(data);
        self.CalcularTotales(event);
      }
    }

    self.DetallesComprobanteCompra.Agregar = function (data, event) {
      if (event) {
        var objeto = null;
        if (data == undefined) {
          objeto = Knockout.CopiarObjeto(base.NuevoDetalleComprobanteCompra);
        }
        else {
          objeto = Knockout.CopiarObjeto(data);
        }

        var resultado = new VistaModeloDetalleComprobanteCompra(objeto, self);

        var idMaximo = 0;

        if (this().length > 0) idMaximo = Math.max.apply(null, ko.utils.arrayMap(this(), function (e) { return e.IdDetalleComprobanteCompra(); }));

        resultado.IdDetalleComprobanteCompra(idMaximo + 1);
        this.push(resultado);

        self.CalcularTotales(event);
        self.CalcularMontosPercepcion(undefined, event);
        return resultado;
      }
    }

    self.DetallesComprobanteCompra.AgregarInverso = function (data, event) {
      if (event) {
        var objeto = null;
        if (data == undefined) {
          objeto = Knockout.CopiarObjeto(base.NuevoDetalleComprobanteCompra);
        }
        else {
          objeto = Knockout.CopiarObjeto(data);
        }

        var resultado = new VistaModeloDetalleComprobanteCompra(objeto, self);

        var idMaximo = 0;

        if (this().length > 0) idMaximo = Math.max.apply(null, ko.utils.arrayMap(this(), function (e) { return e.IdDetalleComprobanteCompra(); }));

        resultado.IdDetalleComprobanteCompra(idMaximo + 1);
        this.unshift(resultado);

        self.CalcularTotales(event);
        return resultado;
      }
    }

    self.DetallesComprobanteCompra.Obtener = function (data, event) {
      if (event) {
        var objeto = ko.utils.arrayFirst(this(), function (item) {
          return data == item.IdDetalleComprobanteCompra();
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
        url: SITE_URL + '/Compra/ComprobanteCompra/cComprobanteCompra/ImprimirComprobanteCompra',
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

  self.ConsultarDetallesComprobanteCompra = function (data, event, callback) {
    if (event) {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdComprobanteCompra": $data.IdComprobanteCompra(), "IdAsignacionSede": $data.IdAsignacionSede(), "IdProveedor": $data.IdProveedor(), "IdSede" : $data.IdSede() } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Compra/ComprobanteCompra/cDetalleComprobanteCompra/ConsultarDetallesComprobanteCompra',
        success: function (data) {
          self.DetallesComprobanteCompra([]);
          ko.utils.arrayForEach(data, function (item) {
            self.DetallesComprobanteCompra.Agregar(new VistaModeloDetalleComprobanteCompra(item), event);
          });

          callback(self, event);
        }
      });
    }
  }

  self.RUCDNIProveedor = ko.computed(function () {
    var resultado = "";
    if (self.NumeroDocumentoIdentidad() == "" || self.RazonSocial() == "") {
      resultado = "";
    }
    else {
      resultado = self.NumeroDocumentoIdentidad() + ' - ' + self.RazonSocial();
    }
    return resultado;
  }, this);

  self.Numero = ko.computed(function () {
    var resultado = self.NombreAbreviado() + ' ' + self.SerieDocumento() + '-' + self.NumeroDocumento();
    return resultado;
  }, this);

  self.TotalComprobante = ko.computed(function () {
    var resultado = "";
    if (self.SimboloMoneda == undefined) {

    }
    else {
      var total = accounting.formatNumber(self.Total(), NUMERO_DECIMALES_COMPRA);
      resultado = self.SimboloMoneda() + ' ' + total;
    }

    return resultado;
  }, this);

  self.EstadoComprobante = ko.computed(function () {
    var resultado = "";
    if (self.IndicadorEstado() == "A") resultado = "EMITIDO";
    if (self.IndicadorEstado() == "N") resultado = "ANULADO";
    if (self.IndicadorEstado() == "E") resultado = "ELIMINADO";
    return resultado;
  }, this);

  self.ObtenerTipoCambioActual = function (data, event, callback) {
    if (event) {
      var copiaObjeto = ko.mapping.toJS(data, mappingIgnore);
      var datajs = { Data: JSON.stringify(copiaObjeto) };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Configuracion/General/cTipoCambio/ConsultarTipoCambioCompraActual',
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


  self.ObtenerProductoPorIdProducto = function (data, event) {
    if (event) {
      var idproducto = data.IdProducto();
      var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + idproducto + '.json';
      var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
      return producto[0];
    }
  }

  self.CalcularMontosPercepcion = function (data, event) {
    if (event) {

      var total = parseFloatAvanzado(self.Total());
      var tasaPercepcion = parseFloatAvanzado(parseFloatAvanzado(self.TasaPercepcionPorcentaje()) / 100);
      var montoPercepcion = (tasaPercepcion * total).toFixed(NUMERO_DECIMALES_COMPRA);
      var totalMasPercepcion = parseFloatAvanzado(total + parseFloatAvanzado(montoPercepcion)).toFixed(NUMERO_DECIMALES_COMPRA);

      self.TasaPercepcion(tasaPercepcion);
      self.MontoPercepcion(montoPercepcion);
      self.TotalMasPercepcion(totalMasPercepcion);
    }
  }
}

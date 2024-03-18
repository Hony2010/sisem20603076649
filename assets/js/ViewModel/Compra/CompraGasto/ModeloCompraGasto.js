ModeloCompraGasto = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showCompraGasto = ko.observable(false);

  self.InicializarModelo = function (event) {
    if (event) {
      self.CalcularTotales(event);
    }
  }

  self.NuevoCompraGasto = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesCompraGasto" });
      self.CompraGastoInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo = "Registro de Comprobante de Compra";
    }
  }

  self.EditarCompraGasto = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesCompraGasto" });
      self.CompraGastoInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo = "Edición de Comprobante de Compra";
    }
  }

  self.CalcularTotalOperacionGravada = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesCompraGasto(), function (item) {
      if (item.AfectoIGV() == '1') {
        if (base.IndicadorTipoCalculoIGV() == 1) {
          total += parseFloatAvanzado(item.PrecioItem() == null || item.PrecioItem() == "" ? 0 : item.PrecioItem());
        } else {
          total += parseFloatAvanzado(item.CostoItem() == null || item.CostoItem() == "" ? 0 : item.CostoItem());
        }
      }
    });
    return parseFloat(total.toFixed(2));
  }

  self.CalcularTotalOperacionNoGravada = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesCompraGasto(), function (item) {
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
    ko.utils.arrayForEach(base.DetallesCompraGasto(), function (item) {
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

  self.CalcularMontoIGV = function () {
    if (base.IndicadorTipoCalculoIGV() == 1) {
      var gravado = parseFloatAvanzado(self.CalcularTotalOperacionGravada());
      var tasaIGV = parseFloatAvanzado(base.TasaIGV());
      var monto = (gravado / (1 + tasaIGV)) * tasaIGV;
    } else {
      var monto = parseFloat(self.CalcularTotalOperacionGravada()) * parseFloat(base.TasaIGV());
    }
    return parseFloat(monto.toFixed(2));
  }

  self.CalcularTotal = function () {
    var total = 0;
    if (base.DetallesCompraGasto().length > 0) {
      if (base.IndicadorTipoCalculoIGV() == 1) {
        ko.utils.arrayForEach(base.DetallesCompraGasto(), function (item) {
          total += parseFloatAvanzado(item.PrecioItem() == null || item.PrecioItem() == "" ? 0 : item.PrecioItem());
        });
        var descuentoglobal = base.DescuentoGlobal();
        var total = total - parseFloatAvanzado(descuentoglobal);

      } else {
        ko.utils.arrayForEach(base.DetallesCompraGasto(), function (item) {
          total += parseFloatAvanzado(item.CostoItem() == null || item.CostoItem() == "" ? 0 : item.CostoItem());
        });
        total = total + parseFloat(base.IGV());
      }
    }
    return parseFloat(total.toFixed(2));
  }

  self.CalcularTotales = function (event) {
    if (event) {
      var valorcompranogravado = self.CalcularTotalOperacionNoGravada();
      base.ValorCompraNoGravado(valorcompranogravado);

      var valorventainfecto = self.CalcularTotalOperacionInafecto();
      base.ValorCompraInafecto(valorventainfecto);

      var total = self.CalcularTotal();
      base.Total(total);

      var igv = self.CalcularMontoIGV();
      base.IGV(igv);

      if (base.IndicadorTipoCalculoIGV() == 1) {
        var valorcompragravado = parseFloatAvanzado(self.CalcularTotalOperacionGravada());
        base.ValorCompraGravado(valorcompragravado - parseFloatAvanzado(igv));
      } else {
        var valorcompragravado = self.CalcularTotalOperacionGravada();
        base.ValorCompraGravado(valorcompragravado);
      }
    }
  }

  self.GuardarCompraGasto = function (event, callback) {
    if (event) {
      self.CalcularTotales(event);

      var detraccion = (base.CheckDetraccion() == true) ? DETRACCION.CON_DETACCION : DETRACCION.SIN_DETRACCION;
      base.IndicadorDetraccion(detraccion);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesCompraGasto" });
      var copia_objeto = ko.mapping.toJS(base, mappingfinal);
      var datajs = { Data: JSON.stringify(copia_objeto) };

      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Insertar(datajs, event, function ($data, $event) {

          if ($data.error)
            callback($data, $event);
          else {
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
        url: SITE_URL + '/Compra/cCompraGasto/InsertarCompraGasto',
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
        url: SITE_URL + '/Compra/cCompraGasto/ActualizarCompraGasto',
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

  self.AnularCompraGasto = function (data, event, callback) {
    if (event) {
      self.opcionProceso(opcionProceso.Anulacion);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesCompraGasto" });
      var datajs = ko.mapping.toJS({ "Data": data }, mappingfinal);

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Compra/CompraGasto/cCompraGasto/AnularComprobanteCompra',
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

  self.EliminarCompraGasto = function (data, event, callback) {
    if (event) {
      var resultado = { data: null, error: "" };
      var objeto = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": data }, mappingIgnore);
      self.opcionProceso(opcionProceso.Eliminacion);

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Compra/CompraGasto/cCompraGasto/BorrarComprobanteCompra',
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

  self.ValidarEstadoCompraGasto = function (data, event) {
    if (event) {
      if (data.IndicadorEstado != undefined) {
        if (data.IndicadorEstado() == "E") return false;
        if (data.IndicadorEstado() == "A") return false;

      }

      return true;
    }
  }

  if (self.DetallesCompraGasto != undefined) {
    self.DetallesCompraGasto.Remover = function (data, event) {
      if (event) {
        this.remove(data);
        self.CalcularTotales(event);
      }
    }

    self.DetallesCompraGasto.Agregar = function (data, event) {
      if (event) {
        var objeto = null;
        if (data == undefined) {
          objeto = Knockout.CopiarObjeto(base.NuevoDetalleCompraGasto);
        }
        else {
          objeto = Knockout.CopiarObjeto(data);
        }

        var resultado = new VistaModeloDetalleCompraGasto(objeto);

        var idMaximo = 0;

        if (this().length > 0) idMaximo = Math.max.apply(null, ko.utils.arrayMap(this(), function (e) { return e.IdDetalleComprobanteCompra(); }));

        resultado.IdDetalleComprobanteCompra(idMaximo + 1);
        this.push(resultado);

        self.CalcularTotales(event);
        return resultado;
      }
    }

    self.DetallesCompraGasto.Obtener = function (data, event) {
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
        url: SITE_URL + '/Compra/CompraGasto/cCompraGasto/ImprimirComprobanteCompra',
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

  self.ConsultarDetallesCompraGasto = function (data, event, callback) {
    if (event) {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdComprobanteCompra": $data.IdComprobanteCompra() } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Compra/ComprobanteCompra/cDetalleComprobanteCompra/ConsultarDetallesCompraGasto',
        success: function (data) {
          self.DetallesCompraGasto([]);
          ko.utils.arrayForEach(data, function (item) {
            self.DetallesCompraGasto.Agregar(new VistaModeloDetalleCompraGasto(item), event);
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


}

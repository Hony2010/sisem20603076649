ModeloNotaCreditoCompra = function (data) {
  var self = this;
  var base = data;

  self.TipoCompra = ko.observable(base.IdTipoCompra());

  self.showNotaCreditoCompra = ko.observable(false);
  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);

  self.InicializarModelo = function (event) {
    if (event) {
      self.CalcularTotales(event);
    }
  }

  self.CalcularTotalOperacionInafecto = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaCreditoCompra(), function (item) {
      if (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_INAFECTO) {
        total += parseFloatAvanzado(item.SubTotal());
      }
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.CalcularTotalOperacionNoGravada = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaCreditoCompra(), function (item) {
      if (item.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_EXONERADO) {
        total += parseFloatAvanzado(item.SubTotal());
      }
    });
    return parseFloatAvanzado(total.toFixed(2));
  }

  self.RUCDNICliente = ko.computed(function () {
    var resultado = "";
    if (self.NumeroDocumentoIdentidad() == "" || self.RazonSocial() == "") {
      resultado = "";
    }
    else {
      resultado = self.NumeroDocumentoIdentidad() + ' - ' + self.RazonSocial();
    }
    return resultado;
  }, this);

  self.NuevoComprobanteCompra = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetalleNotaCreditoCompra" });
      self.ComprobanteCompraInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo = "Registro de Nota Crédito de Compra";
    }
  }

  self.NuevoNotaCreditoCompra = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaCreditoCompra" });
      self.ComprobanteCompraInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulonotacreditocompra = "Emisión de Nota Crédito de Compra";
    }
  }

  self.EditarNotaCreditoCompra = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = ko.mapping.toJS(data, { ignore: ['DetallesNotaCreditoCompra'] });
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaCreditoCompra" });
      self.ComprobanteCompraInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo = "Edición de Nota Crédito de Compra";
    }
  }

  self.GuardarNotaCreditoCompra = function (event, callback) {
    if (event) {

      var _mappingIgnore = ko.toJS(ignore_array_data);//ko.toJS(mappingIgnore);//ignore_array_data
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaCreditoCompra" });
      console.log(mappingfinal);
      var datajs = ko.mapping.toJS(base, mappingfinal);
      datajs.MotivoNotaCreditoCompra = window.Motivo;
      if (window.Motivo.Reglas.BorrarDetalles == 1) {
        var nuevo_detalle = ko.mapping.toJS(self.NuevoDetalleComprobanteCompra, mappingIgnore);
        nuevo_detalle.IdProducto = datajs.Concepto;
        nuevo_detalle.CodigoTipoAfectacionIGV = "40";
        nuevo_detalle.CodigoTipoSistemaISC = "01";
        nuevo_detalle.CodigoUnidadMedida = "NIU";
        nuevo_detalle.Cantidad = "1.0";
        nuevo_detalle.IGVItem = "0";
        nuevo_detalle.CodigoTipoPrecio = "01";
        nuevo_detalle.NombreProducto = $("#combo-conceptonotacreditocompra :selected").text();
        nuevo_detalle.CostoItem = datajs.Total;
        nuevo_detalle.ValorUnitario = "0";
        datajs.DetallesNotaCreditoCompra[0] = nuevo_detalle;
      }

      datajs = { Data: JSON.stringify(datajs) };

      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Insertar(datajs, event, function ($data, $event) {
          //debugger;
          if ($data.error)
            callback($data, $event);
          else {
            ko.mapping.fromJS($data, MappingCompra, self);
            self.mensaje = "Se registró el comprobante  " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.\n";
            //AGREGAMOS LOS MOTIVOS
            $data.CodigoMotivoNotaCreditoCompra = window.Motivo.Data.CodigoMotivoNotaCreditoCompra;
            $data.NombreMotivoNotaCreditoCompra = window.Motivo.Data.NombreMotivoNotaCreditoCompra;
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
            $data.CodigoMotivoNotaCreditoCompra = window.Motivo.Data.CodigoMotivoNotaCreditoCompra;
            $data.NombreMotivoNotaCreditoCompra = window.Motivo.Data.NombreMotivoNotaCreditoCompra;
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
        url: SITE_URL + '/Compra/NotaCreditoCompra/cNotaCreditoCompra/InsertarNotaCreditoCompra',
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
        url: SITE_URL + '/Compra/NotaCreditoCompra/cNotaCreditoCompra/ActualizarNotaCreditoCompra',
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

  self.CalcularTotalOperacionGravada = function () {
    var total = 0;

    ko.utils.arrayForEach(base.DetallesNotaCreditoCompra(), function (item) {

      if (item.AfectoIGV() == 1) {
        if (base.ParametroPrecioCompra() == 1) {
          total += parseFloatAvanzado(item.PrecioItem() == null || item.PrecioItem() == "" ? 0 : item.PrecioItem());
        } else {
          total += parseFloatAvanzado(item.CostoItem() == null || item.CostoItem() == "" ? 0 : item.CostoItem());
        }
        /*var costoItem = parseFloatAvanzado(item.CostoItem());
        if(!isNaN(costoItem)){
          costoItem = (costoItem == "") ? 0.00 : costoItem;
          total += parseFloatAvanzado(costoItem);
        }*/
      }
    });

    return parseFloatAvanzado(total.toFixed(NUMERO_DECIMALES_COMPRA));
  }

  self.CalcularTotalOperacionNoGravada = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaCreditoCompra(), function (item) {
      if (item.AfectoIGV() == 0) {
        if (base.ParametroPrecioCompra() == 1) {
          total += parseFloatAvanzado(item.PrecioItem() == null || item.PrecioItem() == "" ? 0 : item.PrecioItem());
        } else {
          total += parseFloatAvanzado(item.CostoItem() == null || item.CostoItem() == "" ? 0 : item.CostoItem());
        }
      }
    });

    return parseFloat(total.toFixed(NUMERO_DECIMALES_COMPRA));
    /*
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaCreditoCompra(), function(item) {
        if(item.AfectoIGV() == '0')
        {
          var costoItem = parseFloatAvanzado(item.CostoItem());
          if(!isNaN(costoItem)){
            costoItem = (costoItem == "") ? 0.00 : costoItem;
            total += parseFloatAvanzado(costoItem);
          }
        }
    });
    return parseFloatAvanzado(total.toFixed(NUMERO_DECIMALES_COMPRA));
    */
  }

  self.CalcularDescuentoGlobal = function () {
    return;
  }

  self.CalcularMontoIGV = function () {
    //var monto=parseFloatAvanzado(base.ValorCompraGravado()) * parseFloatAvanzado(base.TasaIGV());
    //return parseFloatAvanzado(monto.toFixed(NUMERO_DECIMALES_COMPRA));
    if (base.ParametroPrecioCompra() == 1) {
      var gravado = parseFloatAvanzado(self.CalcularTotalOperacionGravada());
      var tasaIGV = parseFloatAvanzado(base.TasaIGV());
      var monto = (gravado / (1 + tasaIGV)) * tasaIGV;
    } else {
      var monto = parseFloat(self.CalcularTotalOperacionGravada()) * parseFloat(base.TasaIGV());
    }
    return parseFloat(monto.toFixed(NUMERO_DECIMALES_COMPRA));
  }

  self.CalcularMontoISC = function () {
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaCreditoCompra(), function (item) {
      if (item.AfectoISC() == 1) {
        total += parseFloatAvanzado(item.ISCItem());
      }
    });

    return parseFloatAvanzado(total.toFixed(NUMERO_DECIMALES_COMPRA));
  }

  self.CalcularTotal = function () {
    /*
    var total = 0;
    ko.utils.arrayForEach(base.DetallesNotaCreditoCompra(), function(item) {
        total += parseFloatAvanzado(item.CostoItem());
    });

    var total = total + parseFloatAvanzado(base.IGV());
    return parseFloatAvanzado(total.toFixed(NUMERO_DECIMALES_COMPRA));
    */
    var total = 0;
    if (base.DetallesNotaCreditoCompra().length > 0) {
      if (base.ParametroPrecioCompra() == 1) {
        ko.utils.arrayForEach(base.DetallesNotaCreditoCompra(), function (item) {
          total += parseFloatAvanzado(item.PrecioItem() == null || item.PrecioItem() == "" ? 0 : item.PrecioItem());
        });
        var descuentoglobal = base.DescuentoGlobal();
        var total = total - parseFloatAvanzado(descuentoglobal);

      } else {
        ko.utils.arrayForEach(base.DetallesNotaCreditoCompra(), function (item) {
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
      var total = self.CalcularTotal();
      base.Total(total);
      var igv = self.CalcularMontoIGV();
      base.IGV(igv);

      if (base.ParametroPrecioCompra() == 1) {
        var valorcompragravado = parseFloatAvanzado(self.CalcularTotalOperacionGravada());
        base.ValorCompraGravado(valorcompragravado - parseFloatAvanzado(igv));
      } else {
        var valorcompragravado = self.CalcularTotalOperacionGravada();
        base.ValorCompraGravado(valorcompragravado);
      }
    }
  }


  if (self.DetallesNotaCreditoCompra != undefined) {
    self.DetallesNotaCreditoCompra.RemoverDetalle = function (data, event) {
      if (event) {
        this.remove(data);
      }
    }

    self.DetallesNotaCreditoCompra.Obtener = function (data, event) {
      if (event) {
        var objeto = ko.utils.arrayFirst(this(), function (item) {
          return data == item.IdDetalleComprobanteCompra();
          // return data == item.IdReferenciaDCV();
        });

        //if(objeto != null)
        objeto.__ko_mapping__ = undefined;
        return objeto;
      }
    }

  }


  self.ValidarEstadoComprobanteCompra = function (data, event) {
    if (event) {
      if (data.IndicadorEstado != undefined) {
        if (data.IndicadorEstado() == "E") return false;
        if (data.IndicadorEstado() == "A") return false;
      }

      return true;
    }
  }

  self.DetallesNotaCreditoCompra.AgregarDetalle = function (data, event) {
    if (event) {
      var objeto = null;
      if (data == undefined) {
        objeto = Knockout.CopiarObjeto(base.NuevoDetalleComprobanteCompra);
      }
      else {
        objeto = Knockout.CopiarObjeto(data);
      }

      var resultado = new VistaModeloDetalleNotaCreditoCompra(objeto, self);

      var idMaximo = 0;

      if (this().length > 0) idMaximo = Math.max.apply(null, ko.utils.arrayMap(this(), function (e) { return e.IdDetalleComprobanteCompra(); }));

      resultado.IdDetalleComprobanteCompra(idMaximo + 1);
      this.push(resultado);

      resultado.OcultarCamposTipoCompra(self, event);

      //resultado.CalcularTotales(data,event);
      self.CalcularTotales(event);
      return resultado;
    }
  }

  self.ActualizarConceptos = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: { "Data": data },
        dataType: "json",
        url: SITE_URL + '/Compra/NotaCreditoCompra/cNotaCreditoCompra/ObtenerConceptosPorMotivo',
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

  self.ConsultarDetalleNotaCreditoCompra = function (data, event, callback) {
    if (event) {
      var $data = ko.mapping.toJS(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdComprobanteCompra": $data.IdComprobanteCompra, "IdProveedor": $data.IdProveedor, "IdAsignacionSede": $data.IdAsignacionSede, "IdTipoCompra": $data.IdTipoCompra,"IdSede" : $data.IdSede } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Compra/ComprobanteCompra/cDetalleComprobanteCompra/ConsultarDetallesComprobanteCompra',
        success: function (data) {
          self.DetallesNotaCreditoCompra([]);
          ko.utils.arrayForEach(data, function (item) {
            self.DetallesNotaCreditoCompra.AgregarDetalle(new VistaModeloDetalleNotaCreditoCompra(item, self), event);
          });

          callback(self, event);
        }
      });
    }
  }

  self.ConsultarDocumentosReferencia = function (data, event, callback) {
    if (event) {
      var $data = ko.mapping.toJS(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdComprobanteCompra": $data.IdComprobanteCompra } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Compra/NotaCreditoCompra/cNotaCreditoCompra/ConsultarDocumentosReferencia',
        success: function (data) {
          self.MiniComprobantesCompraNC([]);
          ko.utils.arrayForEach(data, function (item) {
            self.MiniComprobantesCompraNC.push(new MiniComprobantesCompraNCModel(item));
          });

          callback(self, event);
        }
      });
    }
  }

  self.ObtenerDataFiltro = function (data, event) {
    if (event) {
      var item = data;
      var data_json = window.DataMotivosNotaCreditoCompra;
      var rpta = JSPath.apply('.{.Data{.IdMotivoNotaCredito == $Texto}}', data_json, { Texto: item });
      if (rpta.length > 0) {
        return rpta[0];
      } else {
        return null;
      }
    }
  }

}

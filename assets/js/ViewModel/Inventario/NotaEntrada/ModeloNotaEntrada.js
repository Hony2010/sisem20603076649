ModeloNotaEntrada = function (data) {
  var self = this;
  var base = data;

  self.iddetalleNotaEntrada;
  self.DetalleNotaEntradaInicial;
  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);

  self.InicializarModelo = function (event) {
    if (event) {
      // self.CalcularTotales(event);
    }
  }

  self.NuevoNotaEntrada = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaEntrada" });
      self.NotaEntradaInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo = "Emision de Nota de Entrada";
    }
  }

  self.EditarNotaEntrada = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      // var copia = Knockout.CopiarObjeto(data);
      var copia = ko.mapping.toJS(data, { ignore: ["DetallesNotaEntrada"] });
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaEntrada" });
      self.NotaEntradaInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo = "Edición de Nota de Entrada";
    }
  }

  self.SeleccionarDetalleNotaEntrada = function (data, event) {
    if (event) {
      self.iddetalleNotaEntrada = data.IdDetalleNotaEntrada();
      self.DetalleNotaEntradaInicial = ko.mapping.toJS(data, mappingIgnore);
    }
  }

  self.GuardarNotaEntrada = function (event, callback) {
    if (event) {

      var _mappingIgnore = ko.toJS(ignore_array_data);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaEntrada" });//var objeto =ko.mapping.toJS({"Data" : base },mappingfinal);
      var copia_objeto = ko.mapping.toJS(base, mappingfinal);
      var datajs = { Data: JSON.stringify(copia_objeto) };
      // self.CalcularTotales(event);
      //debugger;
      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Insertar(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            ko.mapping.fromJS($data, MappingInventario, self);
            self.mensaje = "Se registró la nota de entrada  " + self.SerieNotaEntrada() + " - " + self.NumeroNotaEntrada() + " correctamente.\n";
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
            self.mensaje = "Se actualizó la nota de credito " + self.SerieNotaEntrada() + " - " + self.NumeroNotaEntrada() + " correctamente.\n";
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
        url: SITE_URL + '/Inventario/NotaEntrada/cNotaEntrada/InsertarNotaEntrada',
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
        url: SITE_URL + '/Inventario/NotaEntrada/cNotaEntrada/ActualizarNotaEntrada',
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

  self.AnularNotaEntrada = function (data, event, callback) {
    if (event) {
      self.opcionProceso(opcionProceso.Anulacion);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaEntrada" });
      var datajs = ko.mapping.toJS({ "Data": data }, mappingfinal);

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/NotaEntrada/cNotaEntrada/AnularNotaEntrada',
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

  self.EliminarNotaEntrada = function (data, event, callback) {
    if (event) {
      var resultado = { data: null, error: "" };
      var objeto = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": data }, mappingIgnore);
      self.opcionProceso(opcionProceso.Eliminacion);

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/NotaEntrada/cNotaEntrada/BorrarNotaEntrada',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          resultado.error = jqXHR.responseText;
          callback(resultado, event);
        }
      });
    }
  }

  self.ValidarEstadoNotaEntrada = function (data, event) {
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

  if (self.DetallesNotaEntrada != undefined) {
    self.DetallesNotaEntrada.Remover = function (data, event) {
      if (event) {
        this.remove(data);
        // self.CalcularTotales(event);
      }
    }

    self.DetallesNotaEntrada.Agregar = function (data, event) {
      if (event) {
        var objeto = null;
        if (data == undefined) {
          // objeto = Knockout.CopiarObjeto(base.NuevoDetalleNotaEntrada);
          objeto = ko.mapping.toJS(base.NuevoDetalleNotaEntrada);
        }
        else {
          // objeto = Knockout.CopiarObjeto(data);
          objeto = ko.mapping.toJS(data);
        }

        var resultado = new VistaModeloDetalleNotaEntrada(objeto);

        var idMaximo = 0;

        if (this().length > 0) idMaximo = Math.max.apply(null, ko.utils.arrayMap(this(), function (e) { return e.IdDetalleNotaEntrada(); }));

        resultado.IdDetalleNotaEntrada(idMaximo + 1);
        this.push(resultado);

        // self.CalcularTotales(event);
        return resultado;
      }
    }

    self.DetallesNotaEntrada.Obtener = function (data, event) {
      if (event) {
        var objeto = ko.utils.arrayFirst(this(), function (item) {
          return data == item.IdDetalleNotaEntrada();
        });

        //if(objeto != null)
        objeto.__ko_mapping__ = undefined;
        return objeto;
      }
    }

    self.DetallesNotaEntrada.ExisteProductoVacio = function () {
      var objeto = ko.utils.arrayFirst(this(), function (item) {
        return item.NombreProducto() == null || item.NombreProducto() == "";
      }
      );

      return objeto;
    }

    self.DetallesNotaEntrada.Actualizar = function (data, event) {
      if (event) {
        // self.CalcularTotales(event);
      }
    }
  }


  self.Imprimir = function (data, event, callback) {
    if (event) {
      var datajs = ko.mapping.toJS({ "Data": data });

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/NotaEntrada/cNotaEntrada/ImprimirNotaEntrada',
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

  self.ConsultarDetallesNotaEntrada = function (data, event, callback) {
    if (event) {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdNotaEntrada": $data.IdNotaEntrada() } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/NotaEntrada/cDetalleNotaEntrada/ConsultarDetallesNotaEntrada',
        success: function (data) {
          self.DetallesNotaEntrada([]);
          ko.utils.arrayForEach(data, function (item) {
            self.DetallesNotaEntrada.Agregar(new VistaModeloDetalleNotaEntrada(item), event);
          });

          callback(self, event);
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

  self.RUCProveedor = ko.computed(function () {
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
    var resultado = self.NombreAbreviado() + ' ' + self.SerieNotaEntrada() + '-' + self.NumeroNotaEntrada();
    return resultado;
  }, this);

  self.EstadoComprobante = ko.computed(function () {
    var resultado = "";
    if (self.IndicadorEstado() == "A") resultado = "EMITIDO";
    if (self.IndicadorEstado() == "N") resultado = "ANULADO";
    if (self.IndicadorEstado() == "E") resultado = "ELIMINADO";
    return resultado;
  }, this);

  self.ConsultarDocumentosReferencia = function (data, event, callback) {
    if (event) {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdNotaEntrada": $data.IdNotaEntrada() } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/NotaEntrada/cNotaEntrada/ConsultarDocumentosReferencia',
        success: function (data) {
          self.MiniComprobantesVenta([]);
          ko.utils.arrayForEach(data, function (item) {
            self.MiniComprobantesVenta.push(new MiniComprobantesVentaModel(item));
          });

          callback(self, event);
        }
      });
    }
  }



}

ModeloNotaSalida = function (data) {
  var self = this;
  var base = data;

  self.iddetalleNotaSalida;
  self.DetalleNotaSalidaInicial;
  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);

  self.InicializarModelo = function (event) {
    if (event) {
      // self.CalcularTotales(event);
    }
  }

  self.NuevoNotaSalida = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaSalida" });
      self.NotaSalidaInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo = "Emision de Nota de Salida";
    }
  }

  self.EditarNotaSalida = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaSalida" });
      self.NotaSalidaInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo = "Edición de Nota de Salida";
    }
  }

  self.SeleccionarDetalleNotaSalida = function (data, event) {
    if (event) {
      self.iddetalleNotaSalida = data.IdDetalleNotaSalida();
      self.DetalleNotaSalidaInicial = ko.mapping.toJS(data, mappingIgnore);
    }
  }

  self.GuardarNotaSalida = function (event, callback) {
    if (event) {

      var _mappingIgnore = ko.toJS(ignore_array_data);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaSalida" });
      var datajs = ko.mapping.toJS({ "Data": base }, mappingfinal);

      // self.CalcularTotales(event);

      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Insertar(datajs, event, function ($data, $event) {
          //debugger;
          if ($data.error)
            callback($data, $event);
          else {
            ko.mapping.fromJS($data, MappingInventario, self);
            self.mensaje = "Se registró el comprobante  " + self.SerieNotaSalida() + " - " + self.NumeroNotaSalida() + " correctamente.\n";
            //self.GenerarXML($data,event,function($data2,$evento2) {
            // self.mensaje = self.mensaje+" ¿Desea imprimir el documento?\n";
            self.mensaje = self.mensaje.replace(/\n/g, "<br />");
            callback($data, $event);
            //});
          }
        });
      }
      else {
        self.Actualizar(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            self.mensaje = "Se actualizó el comprobante " + self.SerieNotaSalida() + " - " + self.NumeroNotaSalida() + " correctamente.\n";

            self.GenerarXML(data, event, function ($data, $evento) {
              //self.mensaje = self.mensaje+"y también se generó el XML N° "+$data.NombreArchivoComprobante+".";
              self.mensaje = self.mensaje + " ¿Desea imprimir el documento?\n";
              self.mensaje = self.mensaje.replace(/\n/g, "<br />");
              callback($data, $event);
            });
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
        url: SITE_URL + '/Inventario/NotaSalida/cNotaSalida/InsertarNotaSalida',
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
        url: SITE_URL + '/Inventario/NotaSalida/cNotaSalida/ActualizarNotaSalida',
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

  self.AnularNotaSalida = function (data, event, callback) {
    if (event) {
      self.opcionProceso(opcionProceso.Anulacion);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesNotaSalida" });
      var datajs = ko.mapping.toJS({ "Data": data }, mappingfinal);

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/NotaSalida/cNotaSalida/AnularNotaSalida',
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

  self.EliminarNotaSalida = function (data, event, callback) {
    if (event) {
      var resultado = { data: null, error: "" };
      var objeto = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": data }, mappingIgnore);
      self.opcionProceso(opcionProceso.Eliminacion);

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/NotaSalida/cNotaSalida/BorrarNotaSalida',
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

  self.ValidarEstadoNotaSalida = function (data, event) {
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

  if (self.DetallesNotaSalida != undefined) {
    self.DetallesNotaSalida.Remover = function (data, event) {
      if (event) {
        this.remove(data);
        // self.CalcularTotales(event);
      }
    }

    self.DetallesNotaSalida.Agregar = function (data, event) {
      if (event) {
        var objeto = null;
        if (data == undefined) {
          objeto = Knockout.CopiarObjeto(base.NuevoDetalleNotaSalida);
        }
        else {
          objeto = Knockout.CopiarObjeto(data);
        }

        var resultado = new VistaModeloDetalleNotaSalida(objeto);

        var idMaximo = 0;

        if (this().length > 0) idMaximo = Math.max.apply(null, ko.utils.arrayMap(this(), function (e) { return e.IdDetalleNotaSalida(); }));

        resultado.IdDetalleNotaSalida(idMaximo + 1);
        this.push(resultado);

        // self.CalcularTotales(event);
        return resultado;
      }
    }

    self.DetallesNotaSalida.Obtener = function (data, event) {
      if (event) {
        var objeto = ko.utils.arrayFirst(this(), function (item) {
          return data == item.IdDetalleNotaSalida();
        });

        //if(objeto != null)
        objeto.__ko_mapping__ = undefined;
        return objeto;
      }
    }

    self.DetallesNotaSalida.ExisteProductoVacio = function () {
      var objeto = ko.utils.arrayFirst(this(), function (item) {
        return item.NombreProducto() == null || item.NombreProducto() == "";
      }
      );

      return objeto;
    }

    self.DetallesNotaSalida.Actualizar = function (data, event) {
      if (event) {
        // self.CalcularTotales(event);
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
        url: SITE_URL + '/Inventario/NotaSalida/cNotaSalida/ImprimirNotaSalida',
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

  self.ConsultarDetallesNotaSalida = function (data, event, callback) {
    if (event) {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdNotaSalida": $data.IdNotaSalida() } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/NotaSalida/cDetalleNotaSalida/ConsultarDetallesNotaSalida',
        success: function (data) {
          self.DetallesNotaSalida([]);
          ko.utils.arrayForEach(data, function (item) {
            self.DetallesNotaSalida.Agregar(new VistaModeloDetalleNotaSalida(item), event);
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
    var resultado = self.NombreAbreviado() + ' ' + self.SerieNotaSalida() + '-' + self.NumeroNotaSalida();
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

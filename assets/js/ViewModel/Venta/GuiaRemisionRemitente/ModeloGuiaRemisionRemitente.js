ModeloGuiaRemisionRemitente = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showGuiaRemisionRemitente = ko.observable(false);

  self.NuevaGuiaRemisionRemitente = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);

      self.GuiaRemisionRemitenteInicial = ko.mapping.toJS(data, mappingIgnore);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo = "EMISIÓN DE GUIA REMISION REMITENTE";
    }
  }

  self.EditarGuiaRemisionRemitente = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);

      self.GuiaRemisionRemitenteInicial = ko.mapping.toJS(data, mappingIgnore);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo = "EDICIÓN DE GUIA REMISION REMITENTE";
    }
  }

  self.GuardarGuiaRemisionRemitente = function (event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(self, mappingIgnore)) };

      $("#loader").show();
      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Insertar(datajs, event, function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            $data.DetallesGuiaRemisionRemitente = [];
            ko.mapping.fromJS($data, MappingGuiaRemisionRemitente, self);
            self.mensaje = `Se registró el comprobante ${self.SerieDocumento()} - ${self.NumeroDocumento()} correctamente.`
            self.mensaje = self.mensaje+" ¿Desea imprimir el documento?\n";
            self.mensaje = self.mensaje.replace(/\n/g, "<br />");
            callback($data, $event);
          } else {
            callback($data, $event);
          }
        });
      }
      else {
        self.Actualizar(datajs, event, function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            self.mensaje = `Se actualizó el comprobante ${self.SerieDocumento()} - ${self.NumeroDocumento()} correctamente.`
            self.mensaje = self.mensaje+" ¿Desea imprimir el documento?\n";
            self.mensaje = self.mensaje.replace(/\n/g, "<br />");
            callback($data, $event);
          } else {
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
        url: SITE_URL + '/Venta/cGuiaRemisionRemitente/InsertarGuiaRemisionRemitente',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
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
        url: SITE_URL + '/Venta/cGuiaRemisionRemitente/ActualizarGuiaRemisionRemitente',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.EliminarGuiaRemisionRemitente = function (data, event, callback) {
    if (event) {

      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };
      self.opcionProceso(opcionProceso.Eliminacion);

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cGuiaRemisionRemitente/BorrarGuiaRemisionRemitente',
        success: function (data) {
          callback(data, event);//resultado
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.AnularGuiaRemisionRemitente = function (data, event, callback) {
    if (event) {

      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };
      self.opcionProceso(opcionProceso.Eliminacion);

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cGuiaRemisionRemitente/AnularGuiaRemisionRemitente',
        success: function (data) {
          callback(data, event);//resultado
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.ConsultarComprobanteVenta = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cGuiaRemisionRemitente/ConsultarComprobanteVenta',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.ConsultarDetallesGuiaRemision = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cDetalleGuiaRemisionRemitente/ConsultarDetallesGuiaRemisionRemitente',
        success: function (data) {
          self.DetallesGuiaRemisionRemitente([]);
          ko.utils.arrayForEach(data, function (item) {
            self.AgregarDetalleGuiaRemisionRemitente(item, event);
          });

          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }


  self.Imprimir = function (data, event, callback) {
    if (event) {
      //var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };
      var datajs = ko.mapping.toJS({"Data":data});
      
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL_BASE + '/Venta/cGuiaRemisionRemitente/ImprimirGuiaRemisionRemitente',
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


}

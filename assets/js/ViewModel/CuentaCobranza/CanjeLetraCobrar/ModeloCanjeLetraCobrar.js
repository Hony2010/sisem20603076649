ModeloCanjeLetraCobrar = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showCanjeLetraCobrar = ko.observable(false);

  self.InicializarModelo = function () {

  }

  self.NuevoCanjeLetraCobrar = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.CanjeLetraCobrarInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
    }
  }

  self.EditarCanjeLetraCobrar = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.CanjeLetraCobrarInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
    }
  }

  self.GuardarCanjeLetraCobrar = function (event, callback) {
    if (event) {
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      var datajs = ko.mapping.toJS(base, mappingfinal);
      datajs = { "Data": JSON.stringify(datajs) };
      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Insertar(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            ko.mapping.fromJS($data, MappingCuentaCobranza, self);
            self.mensaje = "Se registró el comprobante  " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.";
            callback($data, $event);

          }
        });
      }
      else {
        self.Actualizar(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            self.mensaje = "Se actualizó el comprobante  " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.";
            callback($data, $event);
          }
        });
      }
    }
  }

  self.EliminarCanjeLetraCobrar = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)), Filtro: data.filtro };
      self.Eliminar(datajs, event, function ($data, $event) {
        callback($data, $event);
      });
    }
  }

  self.AnularCanjeLetraCobrar = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };
      self.Anular(datajs, event, function ($data, $event) {
        callback($data, $event);
      });
    }
  }

  self.Insertar = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/CuentaCobranza/cCanjeLetraCobrar/InsertarCanjeLetraCobrar',
        success: function (data) {
          self.EstaProcesado(true);
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
        url: SITE_URL + '/CuentaCobranza/cCanjeLetraCobrar/ActualizarCanjeLetraCobrar',
        success: function (data) {
          self.EstaProcesado(true);
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.Eliminar = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/CuentaCobranza/cCanjeLetraCobrar/BorrarCanjeLetraCobrar',
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

  self.Anular = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/CuentaCobranza/cCanjeLetraCobrar/AnularCanjeLetraCobrar',
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

  self.ConsultarPendientesCobranzaCliente = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(data) };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/CuentaCobranza/cCanjeLetraCobrar/ConsultarPendientesCobranzaClienteVentaParaLetra',
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

  self.ConsultarPendientesLetraCobrarPorCanje = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(data) };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/CuentaCobranza/cCanjeLetraCobrar/ConsultarPendientesLetraCobrarPorCanje',
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

  self.ConsultarPendientesCobranzaClientePorCanje = function (data, event, callback) {
    if (event) {
      var datajs = { Data: JSON.stringify(data) };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/CuentaCobranza/cCanjeLetraCobrar/ConsultarPendientesCobranzaClientePorCanje',
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
}

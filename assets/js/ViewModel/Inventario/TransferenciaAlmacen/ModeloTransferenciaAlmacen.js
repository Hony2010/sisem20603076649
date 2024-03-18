ModeloTransferenciaAlmacen = function (data) {
  var self = this;
  var base = data;

  self.iddetalleTransferenciaAlmacen;
  self.DetalleTransferenciaAlmacen;
  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);

  self.InicializarModelo = function (event) {
    if (event) {
      // self.CalcularTotales(event);
    }
  }

  self.NuevoTransferenciaAlmacen = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesTransferenciaAlmacen" });
      self.TransferenciaAlmacenInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo = "Registro de Transferencia Almacen";
    }
  }

  self.EditarTransferenciaAlmacen = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesTransferenciaAlmacen" });
      self.TransferenciaAlmacenInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo = "Edición de Transferencia Almacen";
    }
  }

  self.SeleccionarDetalleTransferenciaAlmacen = function (data, event) {
    if (event) {
      self.iddetalleTransferenciaAlmacen = data.IdDetalleTransferenciaAlmacen();
      self.DetalleTransferenciaAlmacen = ko.mapping.toJS(data, mappingIgnore);
    }
  }


  self.GuardarTransferenciaAlmacen = function (event, callback) {
    if (event) {

      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesTransferenciaAlmacen" });
      var datajs = ko.mapping.toJS({ "Data": base }, mappingfinal);
      datajs = { Data: JSON.stringify(datajs) };

      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Insertar(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            ko.mapping.fromJS($data, MappingTransferenciaAlmacen, self);
            callback($data, $event);
          }
        });
      }
      else {
        self.Actualizar(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            self.mensaje = "Se actualizó el comprobante " + self.SerieTransferencia() + " - " + self.NumeroTransferencia() + " correctamente.\n";
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
        url: SITE_URL + '/Inventario/TransferenciaAlmacen/cTransferenciaAlmacen/InsertarTransferenciaAlmacen',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
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
        url: SITE_URL + '/Inventario/TransferenciaAlmacen/cTransferenciaAlmacen/ActualizarTransferenciaAlmacen',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.AnularTransferenciaAlmacen = function (data, event, callback) {
    if (event) {
      self.opcionProceso(opcionProceso.Anulacion);      
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesTransferenciaAlmacen" });
      var datajs = ko.mapping.toJS({ "Data": data }, mappingfinal);
      datajs = { Data: JSON.stringify(datajs) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/TransferenciaAlmacen/cTransferenciaAlmacen/AnularTransferenciaAlmacen',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.EliminarTransferenciaAlmacen = function (data, event, callback) {
    if (event) {
      //var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) };
      self.opcionProceso(opcionProceso.Eliminacion);     
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesTransferenciaAlmacen" });
      var datajs = ko.mapping.toJS({ "Data": data }, mappingfinal);
      datajs = { Data: JSON.stringify(datajs) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/TransferenciaAlmacen/cTransferenciaAlmacen/EliminarTransferenciaAlmacen',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }


  if (self.DetallesTransferenciaAlmacen != undefined) {
    self.DetallesTransferenciaAlmacen.Remover = function (data, event) {
      if (event) {
        this.remove(data);
        // self.CalcularTotales(event);
      }
    }

    self.DetallesTransferenciaAlmacen.Agregar = function (data, event) {
      if (event) {
        var objeto = null;
        if (data == undefined) {
          objeto = ko.mapping.toJS(base.NuevoDetalleTransferenciaAlmacen);// Knockout.CopiarObjeto(base.NuevoDetalleTransferenciaAlmacen);
        }
        else {
          objeto = ko.mapping.toJS(data);//Knockout.CopiarObjeto(data);
        }

        var resultado = new VistaModeloDetalleTransferenciaAlmacen(objeto, self);

        var idMaximo = 0;

        if (this().length > 0) idMaximo = Math.max.apply(null, ko.utils.arrayMap(this(), function (e) { return e.IdDetalleTransferenciaAlmacen(); }));

        resultado.IdDetalleTransferenciaAlmacen(idMaximo + 1);
        this.push(resultado);

        // self.CalcularTotales(event);
        return resultado;
      }
    }

    self.DetallesTransferenciaAlmacen.Obtener = function (data, event) {
      if (event) {
        var objeto = ko.utils.arrayFirst(this(), function (item) {
          return data == item.IdDetalleTransferenciaAlmacen();
        });

        //if(objeto != null)
        objeto.__ko_mapping__ = undefined;
        return objeto;
      }
    }

    self.DetallesTransferenciaAlmacen.ExisteProductoVacio = function () {
      var objeto = ko.utils.arrayFirst(this(), function (item) {
        return item.NombreProducto() == null || item.NombreProducto() == "";
      }
      );

      return objeto;
    }

    self.DetallesTransferenciaAlmacen.Actualizar = function (data, event) {
      if (event) {
        // self.CalcularTotales(event);
      }
    }
  }


  self.ConsultarDetallesTransferenciaAlmacen = function (data, event, callback) {
    if (event) {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdTransferenciaAlmacen": $data.IdTransferenciaAlmacen() } });

      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/TransferenciaAlmacen/cTransferenciaAlmacen/ConsultarDetallesTransferenciaAlmacen',
        success: function (data) {
          callback(data, event);
        }
      });
    }
  }


}

ModeloCliente = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showCliente = ko.observable(false);
  self.showDirecciones = ko.observable(false);
  self.showVehiculos = ko.observable(false);
  self.EstadoCondicion = ko.observable();
  self.copiatextofiltroguardar = ko.observable("");
  self._IndicadorAfiliacionTarjeta = ko.observable(false);
  self.InicializarModelo = function () {

  }

  self.ValidarNumeroDocumentoIdentidad = function (value, event) {
    if (event) {
      var idtipo = base.IdTipoDocumentoIdentidad();
      if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI) { //dni
        if ($.isNumeric(value) === true && value.length === MAXIMO_DIGITOS_DNI)
          return true;
        else
          return false;
      }
      else if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_RUC) {//ruc
        if ($.isNumeric(value) === true && value.length === MAXIMO_DIGITOS_RUC)
          return true;
        else
          return false;
      }
      else if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_SIN_DOC || idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_OTROS) {
        return true;
      }
      else {
        if (value !== "") {
          return true;
        }
        else {
          return false;
        }
      }
    }
  }

  self.ObtenerClientePorIdPersona = function (data, event, callback) {
    if (event) {
      if (callback) {
        var datajs = ko.toJS({ "Data": { "IdPersona": data.IdPersona } });
        $.ajax({
          method: 'GET',
          data: datajs,
          dataType: 'json',
          url: SITE_URL + '/Catalogo/cCliente/ObtenerClientePorIdPersona',
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

  self.ObtenerRutaFoto = function () {
    var src = "";
    if (self.IdPersona() == "" || self.IdPersona() == null || self.Foto() == null || self.Foto() == "")
      src = BASE_URL + CARPETA_IMAGENES + "nocover.png";
    else
      src = SERVER_URL + CARPETA_IMAGENES + CARPETA_CLIENTE + self.IdPersona() + SEPARADOR_CARPETA + self.Foto();

    return src;
  }

  self.NuevoCliente = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.ClienteInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.IdTipoDocumentoIdentidad(ID_TIPO_DOCUMENTO_IDENTIDAD_RUC);
      self._IndicadorAfiliacionTarjeta(data.IndicadorAfiliacionTarjeta() == '1' ? true : false);
    }
  }

  self.EditarCliente = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.ClienteInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self._IndicadorAfiliacionTarjeta(copia.IndicadorAfiliacionTarjeta() == '1' ? true : false);
    }
  }

  self.GuardarCliente = function (event, callback) {
    if (event) {
      base.RazonSocial(self.RazonSocial().trim());
      base.NombreCompleto(self.NombreCompleto().trim());
      base.ApellidoCompleto(self.ApellidoCompleto().trim());
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      var datajs = ko.mapping.toJS({ "Data": base }, mappingfinal);
      datajs.Data.IndicadorAfiliacionTarjeta = self._IndicadorAfiliacionTarjeta() == true ? 1 : 0;

      if (self.opcionProceso() == opcionProceso.Nuevo) {
        datajs = { "Data": datajs.Data, "Filtro": self.copiatextofiltroguardar() };
        self.Insertar(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            ko.mapping.fromJS($data.resultado, MappingCatalogo, self);
            self.mensaje = "Se registró el cliente  " + self.NumeroDocumentoIdentidad() + " - " + self.NombreCompleto() + " correctamente.";
            self.SubirFoto($data, $event, callback);
          }
        });
      }
      else {
        self.Actualizar(datajs, event, function ($data, $event) {
          if ($data.error)
            callback($data, $event);
          else {
            self.mensaje = "Se actualizó el cliente " + self.NumeroDocumentoIdentidad() + " - " + self.RazonSocial() + " correctamente.";
            self.SubirFoto($data, $event, callback);
          }
        });
      }
    }
  }

  self.EliminarCliente = function (data, event, callback) {
    if (event) {
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      var datajs = ko.mapping.toJS({ "Data": base }, mappingfinal);
      datajs = { "Data": datajs.Data, "Filtro": data.filtro };
      self.Eliminar(datajs, event, function ($data, $event) {
        callback($data, $event);
      });
    }
  }

  self.Insertar = function (data, event, callback) {
    if (event) {
      data.Data = JSON.stringify(data.Data);
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cCliente/InsertarCliente',
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
        url: SITE_URL + '/Catalogo/cCliente/ActualizarCliente',
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

  self.SubirFoto = function (data, event, callback) {
    if (event) {
      var modal = document.getElementById("formcliente");
      var dataform = new FormData(modal);

      $.ajax({
        type: 'POST',
        data: dataform,
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData: false,        // To send DOMDocument or non processed data file it is set to false
        mimeType: "multipart/form-data",
        url: SITE_URL + '/Catalogo/cCliente/SubirFoto',
        success: function (result) {
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
        url: SITE_URL + '/Catalogo/cCliente/BorrarCliente',
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

  self.ConsultarReniec = function (data, event, callback) {
    if (event) {
      var datajs = ko.toJS({ "Data": { 'NumeroDocumentoIdentidad': data.NumeroDocumentoIdentidad() } });
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

  self.ConsultarSunat = function (data, event, callback) {
    if (event) {
      var datajs = ko.toJS({ "Data": { 'NumeroDocumentoIdentidad': data.NumeroDocumentoIdentidad() } });
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cCliente/ConsultarSunat',
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

  self.ObtenerClientePorServicioExterno = function (data, event, callback) {
    if (event) {
      var idtipo = data.IdTipoDocumentoIdentidad();
      var incluye = {
        'include': ["RazonSocial", "NombreCompleto", "ApellidoCompleto", "Direccion", "NombreComercial", "RepresentanteLegal", "Email", "Celular", "TelefonoFijo"]
      };

      if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI) {
        self.ConsultarReniec(data, event, function ($data, $event) {
          if ($data.success == false) {
            callback($data, event);
          }
          else {
            if ($data.success == true) {
              var objetoJS = ko.mapping.toJS(self.ClienteNuevo);
              var extra = leaveJustIncludedProperties(objetoJS, incluye.include);
              ko.mapping.fromJS(extra, {}, self);
              ko.mapping.fromJS($data.result, {}, self);
              self.IdTipoPersona(2);
              self.OnSetDireccionEnDirecciones($data.result, event)

              callback($data.result, event);
            }
            else {
              var $datajs = { error: { msg: $data.message } };
              callback($datajs, event);
            }
          }
        });
      }
      else if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_RUC) {
        self.ConsultarSunat(data, event, function ($data, $event) {
          if ($data.success == false) {
            callback($data, event);
          }
          else {
            if ($data.success == true) {
              var objetoJS = ko.mapping.toJS(self.ClienteNuevo);
              var extra = leaveJustIncludedProperties(objetoJS, incluye.include);
              ko.mapping.fromJS(extra, {}, self);
              ko.mapping.fromJS($data.result, {}, self);
              if ($data.result.TipoPersona == 1) self.IdTipoPersona(1);
              if ($data.result.TipoPersona == 2) self.IdTipoPersona(2);
              self.OnSetDireccionEnDirecciones($data.result, event);

              callback($data.result, event);
            }
            else {
              var $datajs = { error: { msg: $data.message } };
              callback($datajs, event);
            }
          }
        });
      }
      else {
        callback(data, event);
      }
    }
  }

  self.OnSetDireccionEnDirecciones = function (data, event) {
    if (event) {
      var direccion = ko.mapping.toJS(self.NuevaDireccionCliente)
      direccion.Direccion = data.Direccion == "" ? '-' : data.Direccion;
      var nuevaDireccion = new VistaModeloDireccionCliente(direccion, self);
      nuevaDireccion.EstadoDireccion(ESTADO_DIRECCION_CLIENTE.INSERTADO);
      var condicion = ko.utils.arrayFilter(self.DireccionesCliente(), function (item) {
        return item.Direccion() == data.Direccion;
      })

      if (condicion.length == 0) {
        self.DireccionesCliente.push(nuevaDireccion)
      }
    }
  }

  self.ObtenerDetalleCliente = function (data, event, callback) {
    if (event) {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": { "IdPersona": $data.IdPersona() } });


      $.ajax({
        type: 'GET',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cCliente/ObtenerDetalleCliente',
        success: function (data) {

          self.DireccionesClienteBorrado([]);
          self.DireccionesCliente([]);
          self.VehiculosCliente([]);

          ko.utils.arrayForEach(data.DireccionesCliente, function (item) {
            var direccion = new VistaModeloDireccionCliente(item, self);
            self.DireccionesCliente.push(direccion);
          });

          ko.utils.arrayForEach(data.VehiculosCliente, function (item) {
            var vehiculo = new VistaModeloVehiculoCliente(item, self);
            self.VehiculosCliente.push(vehiculo);
          });


          callback(self, event);
        }
      });
    }
  }

  self.GuardarVehiculosCliente = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cCliente/GuardarVehiculosCliente',
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

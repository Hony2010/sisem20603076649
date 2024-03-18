ModeloPagoProveedor = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showPagoProveedor = ko.observable(false);
  self.EstadoCondicion = ko.observable();
  self.copiatextofiltroguardar = ko.observable("");

  self.InicializarModelo = function () {

  }

  self.NuevoPagoProveedor = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.PagoProveedorInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
    }
  }

  self.EditarPagoProveedor = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.PagoProveedorInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
    }
  }

  self.GuardarPagoProveedor = function (event,callback) {
      if(event) {
        var _mappingIgnore  =ko.toJS(mappingIgnore);
        var mappingfinal = Object.assign(_mappingIgnore);
        var datajs =ko.mapping.toJS(base,mappingfinal);
        datajs = {"Data" : JSON.stringify(datajs)};
        if (self.opcionProceso() == opcionProceso.Nuevo)  {
          self.Insertar(datajs,event,function($data,$event) {
            if($data.error)
              callback($data,$event);
            else {
              ko.mapping.fromJS($data, MappingCuentaPago, self);
              self.IdComprobanteCaja($data.IdComprobanteCaja);
              self.mensaje = "Se registró el comprobante  " +self.SerieDocumento() + " - " + self.NumeroDocumento()+ " correctamente.";
              callback($data,$event);

            }
          });
        }
        else {
          self.Actualizar(datajs,event,function($data,$event) {
            if($data.error)
              callback($data,$event);
            else {
              self.mensaje ="Se actualizó el comprobante  " +self.SerieDocumento() + " - " + self.NumeroDocumento()+ " correctamente.";
              callback($data,$event);
            }
          });
        }
      }
  }

  self.EliminarPagoProveedor = function (data,event,callback) {
    if(event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(base, mappingIgnore)), Filtro: data.filtro };
      self.Eliminar(datajs,event,function($data,$event){
        callback($data,$event);
      });
    }
  }

  self.AnularPagoProveedor = function (data,event,callback) {
    if(event) {
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(base, mappingIgnore)) };
      self.Anular(datajs,event,function($data,$event){
        callback($data,$event);
      });
    }
  }

  self.Insertar = function(data,event,callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/CuentaPago/cPagoProveedor/InsertarPagoProveedor',
        success: function (data) {
          self.EstaProcesado(true);
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.Actualizar = function(data,event,callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/CuentaPago/cPagoProveedor/ActualizarPagoProveedor',
        success: function (data) {
          self.EstaProcesado(true);
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.Eliminar = function (data,event,callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/CuentaPago/cPagoProveedor/BorrarPagoProveedor',
        success: function (data) {
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.Anular = function (data,event,callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/CuentaPago/cPagoProveedor/AnularPagoProveedor',
        success: function (data) {
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.ConsultarPendientesPagoProveedorPorIdProveedor = function (data,event,callback) {
    if (event) {
      var datajs ={Data: JSON.stringify(data)};
      $.ajax({
        type: 'POST',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/CuentaPago/cPagoProveedor/ConsultarPendientesPagoProveedorPorIdProveedorYFiltro',
        success: function (data) {
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.ConsultarDetallesPagoPorPago = function (data,event,callback) {
    if (event) {
      var datajs ={Data: JSON.stringify(data)};
      $.ajax({
        type: 'POST',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/CuentaPago/cPagoProveedor/ConsultarDetallesPagoPorPago',
        success: function (data) {
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }
}

ModeloAperturaCaja = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showAperturaCaja = ko.observable(false);
  self.EstadoCondicion = ko.observable();
  self.copiatextofiltroguardar = ko.observable("");

  self.InicializarModelo = function () {

  }
  self.NuevoAperturaCaja = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.AperturaCajaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
    }
  }

  self.EditarAperturaCaja = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.AperturaCajaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
    }
  }

  self.GuardarAperturaCaja = function (event,callback) {
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
              ko.mapping.fromJS($data, MappingCaja, self);
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
              self.mensaje = "Se actualizó el comprobante  " +self.SerieDocumento() + " - " + self.NumeroDocumento()+ " correctamente.";
              callback($data,$event);
            }
          });
        }
      }
  }

  self.EliminarAperturaCaja = function (data,event,callback) {
    if(event) {
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      var datajs = ko.mapping.toJS({"Data": base},mappingfinal);
      datajs = {"Data" : datajs.Data, "Filtro" : data.filtro};
      self.Eliminar(datajs,event,function($data,$event){
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
        url: SITE_URL+'/Caja/cAperturaCaja/InsertarAperturaCaja',
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
        url: SITE_URL+'/Caja/cAperturaCaja/ActualizarAperturaCaja',
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
        url: SITE_URL+'/Caja/cAperturaCaja/BorrarAperturaCaja',
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

  self.ConsultarAperturaCaja = function (data,event,callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Caja/cAperturaCaja/ConsultarAperturaCaja',
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

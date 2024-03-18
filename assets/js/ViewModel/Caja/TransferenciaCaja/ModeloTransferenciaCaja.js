ModeloTransferenciaCaja = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showTransferenciaCaja = ko.observable(false);
  self.EstadoCondicion = ko.observable();
  self.CopiaCajasDestino = ko.observable(self.CajasDestino());//ko.observable([]);
  self.IdMoneda = ko.observable('');

  self.InicializarModelo = function () {

  }
  self.DataCajasDestino = function(idMoneda){
    if (self.hasOwnProperty("CajasDestino")) {
      var nuevaLista = [],
      listaCajas = ko.mapping.toJS(self.CopiaCajasDestino);
      listaCajas.forEach(function(entry, key) {
        if (entry.IdMoneda == idMoneda && entry.IdCaja != self.IdCajaOrigen()) nuevaLista.push(Knockout.CopiarObjeto(entry));
      })
      return nuevaLista;
    } else {
      return [];
    }
  }

  self.NuevoTransferenciaCaja = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.TransferenciaCajaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
    }
  }

  self.EditarTransferenciaCaja = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.TransferenciaCajaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
    }
  }

  self.GuardarTransferenciaCaja = function (event,callback) {
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

  self.EliminarTransferenciaCaja = function (data,event,callback) {
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
        url: SITE_URL+'/Caja/cTransferenciaCaja/InsertarTransferenciaCaja',
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
        url: SITE_URL+'/Caja/cTransferenciaCaja/ActualizarTransferenciaCaja',
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
        url: SITE_URL+'/Caja/cTransferenciaCaja/BorrarTransferenciaCaja',
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

  self.BuscarSaldoCajaTurnoOrigen = function (data,event,callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Caja/cTransferenciaCaja/BuscarSaldoCajaTurnoOrigen',
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

  self.BuscarSaldoCajaTurnoDestino = function (data,event,callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Caja/cTransferenciaCaja/BuscarSaldoCajaTurnoDestino',
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

ModeloOtroDocumentoIngreso = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showOtroDocumentoIngreso = ko.observable(false);
  self.EstadoCondicion = ko.observable();
  self.CajasDestino = ko.observable([]);
  self.IdMoneda = ko.observable('');

  self.InicializarModelo = function () {

  }
  self.DataCajasDestino = function(idMoneda){
    if (self.hasOwnProperty("CajasDestino")) {
      var nuevaLista = [],
      listaCajas = ko.mapping.toJS(self.Cajas);
      listaCajas.forEach(function(entry, key) {
        if (idMoneda == "") nuevaLista.push(Knockout.CopiarObjeto(entry));
        if (entry.IdMoneda == idMoneda) nuevaLista.push(Knockout.CopiarObjeto(entry));
      })
      return nuevaLista;
    } else {
      return [];
    }
  }

  self.NuevoOtroDocumentoIngreso = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.OtroDocumentoIngresoInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
    }
  }

  self.EditarOtroDocumentoIngreso = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.OtroDocumentoIngresoInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
    }
  }

  self.GuardarOtroDocumentoIngreso = function (event,callback) {
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

  self.Insertar = function(data,event,callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Caja/cDocumentoIngreso/InsertarDocumentoIngreso',
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
        url: SITE_URL+'/Caja/cDocumentoIngreso/ActualizarDocumentoIngreso',
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

  self.EliminarDocumentoIngreso = function(data,event,callback) {
    if (event) {
      var objeto = ko.mapping.toJS(Knockout.CopiarObjeto(data), mappingIgnore);
      var datajs = {Data: JSON.stringify(objeto)};
      self.opcionProceso(opcionProceso.Eliminacion);
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Caja/cDocumentoIngreso/BorrarDocumentoIngreso',
              success: function (data) {
                callback(data,event);
            },
            error : function (jqXHR, textStatus, errorThrown) {
              var data = {error:{msg:jqXHR.responseText}};
              callback(data,event);
          }
      });
    }
  }

  self.AnularDocumentoIngreso = function(data,event,callback) {
    if (event) {
      var objeto = ko.mapping.toJS(Knockout.CopiarObjeto(data), mappingIgnore);
      var datajs = {Data: JSON.stringify(objeto)};
      self.opcionProceso(opcionProceso.Eliminacion);
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Caja/cDocumentoIngreso/AnularDocumentoIngreso',
              success: function (data) {
                callback(data,event);
            },
            error : function (jqXHR, textStatus, errorThrown) {
              var data = {error:{msg:jqXHR.responseText}};
              callback(data,event);
          }
      });
    }
  }
}

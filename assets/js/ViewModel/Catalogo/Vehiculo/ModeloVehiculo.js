ModeloVehiculo = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showVehiculo = ko.observable(false);
  self.showDirecciones = ko.observable(false);
  self.showVehiculos = ko.observable(false);
  self.EstadoCondicion = ko.observable();
  self.copiatextofiltroguardar = ko.observable("");

  self.InicializarModelo = function () {

  }

  // self.ObtenerVehiculoPorIdVehiculo = function(data,event,callback) {
  //   if(event) {
  //     if(callback)  {
  //       var datajs = ko.toJS({"Data": {"IdVehiculo" : data.IdVehiculo} });
  //       $.ajax({
  //           method: 'GET',
  //           data : datajs,
  //           dataType: 'json',
  //           url: SITE_URL+'/Catalogo/cVehiculo/ObtenerVehiculoPorIdVehiculo',
  //           success: function(data) {
  //               callback(data,event);
  //           },
  //           error :  function (jqXHR, textStatus, errorThrown) {
  //             var data = {error:{msg:jqXHR.responseText}};
  //             callback(data,event);
  //           }
  //         });
  //       }
  //   }
  // }

  self.NuevoVehiculo = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.VehiculoInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
    }
  }

  self.EditarVehiculo = function (data, event) {
    if (event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia, {}, self);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.VehiculoInicial = ko.mapping.toJS(data, mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
    }
  }

  self.GuardarVehiculo = function (event, callback) {
    if (event) {
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      var datajs = ko.mapping.toJS(base, mappingfinal);

      if (self.opcionProceso() == opcionProceso.Nuevo) {
        datajs = { "Data": JSON.stringify(datajs), "Filtro": self.copiatextofiltroguardar() };
        self.Insertar(datajs, event, function ($data, $event) {
          if (!$data.error){
            ko.mapping.fromJS($data.resultado, MappingVehiculo, self);
            self.mensaje = "Se registró el vehiculo  " + self.NumeroPlaca() + " correctamente.";
          }
          callback($data, $event);
        });
      }
      else {
        datajs = { Data: JSON.stringify(datajs) };
        self.Actualizar(datajs, event, function ($data, $event) {
          if (!$data.error){
            self.mensaje = "Se actualizó el vehiculo " + self.NumeroPlaca() + " correctamente.";
          }
          callback($data, $event);
        });
      }
    }
  }

  self.EliminarVehiculo = function (data, event, callback) {
    if (event) {
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      var datajs = ko.mapping.toJS(base, mappingfinal);
      datajs = { "Data": JSON.stringify(datajs), "Filtro": data.filtro };
      self.Eliminar(datajs, event, function ($data, $event) {
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
        url: SITE_URL + '/Catalogo/cVehiculo/InsertarVehiculo',
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
        url: SITE_URL + '/Catalogo/cVehiculo/ActualizarVehiculo',
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

  self.Eliminar = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cVehiculo/BorrarVehiculo',
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

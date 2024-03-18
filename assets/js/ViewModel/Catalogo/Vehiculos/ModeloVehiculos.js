ModeloVehiculos = function (data) {
  var self = this;
  var base = data;

  self.copiatextofiltro = ko.observable("");

  self.ListarVehiculos = function (data, event, callback) {
    if (event) {
      self.ConsultarVehiculos(data, event, function ($data, $event) {
        base.data.Vehiculos([]);

        ko.utils.arrayForEach($data.resultado, function (item) {
          base.data.Vehiculos.push(new VistaModeloVehiculo(item));
        });

        callback($data, $event);
      });
    }
  }

  self.ListarVehiculosPorPagina = function (data, event, callback) {
    if (event) {
      self.ConsultarVehiculosPorPagina(data, event, function ($data, $event) {
        base.data.Vehiculos([]);

        ko.utils.arrayForEach($data, function (item) {
          base.data.Vehiculos.push(new VistaModeloVehiculo(item));
        });

        callback(data, event);
      });
    }
  }

  self.ConsultarVehiculos = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = ko.mapping.toJS({ "Data": data });
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: datajs,
        url: SITE_URL + '/Catalogo/cVehiculos/ConsultarVehiculos',
        success: function (data) {
          $("#loader").hide();
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert(jqXHR.responseText);
        }
      });
    }
  }

  self.ConsultarVehiculosPorPagina = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = ko.mapping.toJS({ "Data": data });
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: datajs,
        cache: false,
        url: SITE_URL + '/Catalogo/cVehiculos/ConsultarVehiculosPorPagina',
        success: function (data) {
          $("#loader").hide();
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert(jqXHR.responseText);
        }
      });
    }
  }

}

ModeloConsultaTransferenciaAlmacen = function (data) {
  var self = this;
  var base = data;

  self.ParseTransferenciasAlmacen = function (data, event) {
    if (event) {
      return new VistaModeloTransferenciaAlmacen(data);
    }
  }

  self.ListarTransferenciasAlmacen = function (data, event, callback) {
    if (event) {
      var objeto = ko.mapping.toJS(data);

      self.ConsultarTransferenciasAlmacen(objeto, event, function ($data, $event) {
        base.data.TransferenciasAlmacen([]);

        ko.utils.arrayForEach($data.resultado, function (item) {
          base.data.TransferenciasAlmacen.push(self.ParseTransferenciasAlmacen(item, event));
        });

        callback($data, $event);

      });
    }
  }

  self.ListarTransferenciasAlmacenPorPagina = function (data, event, callback) {
    if (event) {

      self.ConsultarTransferenciasAlmacenPorPagina(data, event, function ($data, $event) {
        base.data.TransferenciasAlmacen([]);

        ko.utils.arrayForEach($data, function (item) {
          base.data.TransferenciasAlmacen.push(self.ParseTransferenciasAlmacen(item, event));
        });

        callback(data, event);
      });
    }
  }

  self.ConsultarTransferenciasAlmacen = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) }
      $.ajax({
        type: 'POST',
        dataType: 'json',
        data: datajs,
        url: SITE_URL + '/Inventario/TransferenciaAlmacen/cConsultaTransferenciaAlmacen/ConsultarTransferenciasAlmacen',
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

  self.ConsultarTransferenciasAlmacenPorPagina = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore)) }
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: datajs,
        cache: false,
        url: SITE_URL + '/Inventario/TransferenciaAlmacen/cConsultaTransferenciaAlmacen/ConsultarTransferenciasAlmacenPorPagina',
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

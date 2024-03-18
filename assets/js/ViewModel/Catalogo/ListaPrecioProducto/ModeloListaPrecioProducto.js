ModeloListaPrecioProducto = function (data) {
  var self = this;
  var base = data;

  self.GuardarListaPrecio = function (event, callback) {
    if (event) {
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore, { include: "DetallesListaPrecio" });
      //var datajs = { Data: JSON.stringify(ko.mapping.toJS({ base,  mappingfinal })) };
      var nueva_data = ko.mapping.toJS(base, { ignore: ["FamiliasProducto", "SubFamiliasProducto", "Modelos", "Marcas", "LineasProducto"] });
      var datajs = ko.mapping.toJS({ "Data": nueva_data }, mappingfinal);
      datajs = { Data: JSON.stringify(datajs) };
      self.GuardarMercaderia(datajs, event, function ($data, $event) {
        callback($data, $event);
      });
    }
  }

  self.GuardarMercaderia = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cListaPreciosProducto/GuardarListaPreciosProducto',
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

  self.ConsultarPreciosMercaderia = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cListaPreciosProducto/ConsultarPreciosMercaderia',
        success: function (data) {
          callback(data, event);
        }
      });
    }
  }

}

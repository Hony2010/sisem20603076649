ModeloDetalleGuiaRemisionRemitente = function (data) {
  var self = this;
  var base = data;
  self._DataLotes = ko.observable([]);

  self.InsertarProducto = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cMercaderia/InsertarMercaderia',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  
  self.DataLotes = function (asignacionsede) {
    if (self.hasOwnProperty("ListaLotes")) {
      var nuevalista = [];
      var listalotes = ko.mapping.toJS(self.ListaLotes);
      if (listalotes) {
        listalotes.forEach(function (entry, key) {
          if (entry.IdAsignacionSede == asignacionsede) {
            nuevalista.push(entry);
          }
        })
      }
      self._DataLotes(nuevalista);
      return nuevalista;
    }
    else {
      return [];
    }
  }


}

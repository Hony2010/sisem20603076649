VistaModeloDetalleCanjeLetraCobrar = function (data, $parent) {
  var self = this;
  self.Cabecera = $parent;
  ko.mapping.fromJS(data, {}, self);

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.OnChangeMontoCobranza(self, event);
      self.InicializarValidator(event);
    }
  }

  self.OnFocus = function (data, event) {
    if (event) {
      self.Cabecera.OnFocus(data, event)
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      self.Cabecera.OnKeyEnter(data, event)
    }
  }

  self.ObtenerFechaVencimiento = function (data, event) {
    if (event) {
      var listafecha = self.FechaGiro().split("/");
      var fecha = new Date(`${listafecha[2]}-${parseInt(listafecha[1])} ${listafecha[0]}`);
      var dias = parseInt(self.DiasPlazo());
      fecha.setDate(fecha.getDate() + dias);
      fecha.getDate() + '/' + (fecha.getMonth() + 1) + '/' + fecha.getFullYear();

      self.FechaVencimiento(fecha.toLocaleDateString('es-ES', { year: 'numeric', month: '2-digit', day: '2-digit' }));
    }
  }

}

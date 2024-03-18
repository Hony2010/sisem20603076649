VistaModeloEmisionGuiaRemisionRemitente = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingGuiaRemisionRemitente, self);

  self.Inicializar = function () {
    self.Nuevo(self.data.GuiaRemisionRemitente, window);
  }

  self.Nuevo = function (data, event) {
    if (event) {
      self.data.GuiaRemisionRemitente.Nuevo(data, event, self.PostGuardar);
    }
  }

  self.PostGuardar = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (!data.error) {
        self.Nuevo(ko.mapping.toJS(self.data.GuiaRemisionRemitenteNuevo), event);
      } else {
        alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function () { });
      }
    }
  }

}

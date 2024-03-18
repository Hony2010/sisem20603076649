VistaModeloAnotacionProducto = function (data) {
  var self = this;

  ko.mapping.fromJS(data, {}, self);

  self.InicializarModelo = function (data,event) {
    if(event) {
    }
  }

  self.OnClickAnotacionMercaderia = function (data, event, root) {
    if (event) {
      root.Comanda.OnClickAgregarMercaderiaImagenComanda(data, event);
      $("#PreviewAnotacionesPlato").modal("hide");

    }
  }

}

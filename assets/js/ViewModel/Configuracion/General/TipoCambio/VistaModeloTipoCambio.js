VistaModeloTipoCambio = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
  ModeloTipoCambio.call(this,self);

  self.InicializarVistaModelo =function() {
    self.InicializarModelo();
  }


}

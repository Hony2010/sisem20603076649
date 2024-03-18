ModeloDetalleListaPrecio = function (data) {
    var self = this;
    var base = data;

    self.InicializarModelo =function(event,callback,callback2) {
      if(event) {
        if(callback)
          self.callback=callback;
        if(callback2)
          self.callback2=callback2;
      }
    }
}

VistaModeloRegistroListaPrecioProducto = function (data) {
    var self = this;

    ko.mapping.fromJS(data, MappingCatalogo, self);

    self.Inicializar = function () {
        ViewModels.data.ListaPrecioProducto.InicializarVistaModelo(window);
    }
}

VistaModeloDetalleCompraMasiva = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {} , self);

    self.Cantidad = ko.observable(accounting.formatNumber(data.Cantidad, CANTIDAD_DECIMALES_COMPRA.CANTIDAD));
    self.PrecioUnitario = ko.observable(accounting.formatNumber(data.PrecioUnitario, CANTIDAD_DECIMALES_COMPRA.PRECIO_UNITARIO));
    self.SubTotal = ko.observable(accounting.formatNumber(data.SubTotal, CANTIDAD_DECIMALES_COMPRA.SUB_TOTAL));

}

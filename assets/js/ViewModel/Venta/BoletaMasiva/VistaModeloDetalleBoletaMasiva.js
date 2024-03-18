VistaModeloDetalleBoletaMasiva = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {} , self);

    self.SerieDocumento = ko.observable(data.SerieDocumento + '-' +data.NumeroDocumento);
    self.Cliente = ko.observable(data.RUC + '-' + data.RazonSocial);
    self.Cantidad = ko.observable(accounting.formatNumber(data.Cantidad, CANTIDAD_DECIMALES_VENTA.CANTIDAD));
    self.PrecioUnitario = ko.observable(accounting.formatNumber(data.PrecioUnitario, CANTIDAD_DECIMALES_VENTA.PRECIO_UNITARIO));
    self.SubTotal = ko.observable(accounting.formatNumber(data.SubTotal, CANTIDAD_DECIMALES_VENTA.SUB_TOTAL));

    self.DetallesBoletaMasiva = ko.observable([]);


}

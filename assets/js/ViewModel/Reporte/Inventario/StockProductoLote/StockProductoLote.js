var Mapping_StockProductoLote = Object.assign(
  MappingStockProductoLote
  );


IndexReporteStockProductoLote = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingStockProductoLote, self);

}

var Mapping_StockProducto = Object.assign(
  MappingStockProducto
  );


IndexReporteStockProducto = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingStockProducto, self);

}

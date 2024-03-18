var Mapping_StockProductoMarca = Object.assign(
  MappingStockProductoMarca
  );


IndexReporteStockProductoMarca = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingStockProductoMarca, self);

}

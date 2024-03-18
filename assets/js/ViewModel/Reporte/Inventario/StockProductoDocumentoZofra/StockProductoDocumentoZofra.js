var Mapping_StockProductoDocumentoZofra = Object.assign(
  MappingStockProductoDocumentoZofra
  );


IndexReporteStockProductoDocumentoZofra = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingStockProductoDocumentoZofra, self);

}

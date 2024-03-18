var Mapping_StockNegativo = Object.assign(
  MappingStockNegativo
  );


IndexReporteStockNegativo = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingStockNegativo, self);

}

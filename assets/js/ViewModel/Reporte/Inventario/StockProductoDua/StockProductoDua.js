var Mapping_StockProductoDua = Object.assign(
  MappingStockProductoDua
  );


IndexReporteStockProductoDua = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingStockProductoDua, self);

}

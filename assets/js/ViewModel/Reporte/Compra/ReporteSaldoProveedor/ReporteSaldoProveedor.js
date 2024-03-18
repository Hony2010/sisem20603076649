var Mapping_SaldoProveedor = Object.assign(
  MappingReporteSaldoProveedor
  );


IndexReporteSaldoProveedor = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_SaldoProveedor, self);

}

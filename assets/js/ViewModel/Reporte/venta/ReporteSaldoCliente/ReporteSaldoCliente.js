var Mapping_SaldoCliente = Object.assign(
  MappingReporteSaldoCliente
  );


IndexReporteSaldoCliente = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_SaldoCliente, self);

}

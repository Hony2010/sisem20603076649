var Mapping_Vendedor = Object.assign(
  MappingVentasPorVendedor
  );


IndexReporteVentasPorVendedor = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_Vendedor, self);

}

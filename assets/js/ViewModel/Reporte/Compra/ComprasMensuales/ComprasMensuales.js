var Mapping_ComprasMensuales = Object.assign(
  MappingComprasMensuales
  );


IndexReporteComprasMensuales = function (data) {

    var self = this;

    ko.mapping.fromJS(data, MappingComprasMensuales, self);

}

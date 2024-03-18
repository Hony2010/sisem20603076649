var Mapping_D = Object.assign(
  MappingVentaDetallado
  );


IndexReporteVentaDetallado = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_D, self);

}

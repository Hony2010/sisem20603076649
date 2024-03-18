var Mapping_ResumenVentas = Object.assign(
  MappingResumenVentas
  );


IndexReporteResumenVentas = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_ResumenVentas, self);

}

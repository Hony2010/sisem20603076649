var Mapping_R = Object.assign(
  MappingVentaGeneral
  );


IndexReporteVentaGeneral = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_R, self);

}

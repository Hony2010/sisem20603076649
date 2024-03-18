var Mapping_Z = Object.assign(
  MappingClientesPorZona
  );


IndexReporteClientesPorZona = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_Z, self);

}
